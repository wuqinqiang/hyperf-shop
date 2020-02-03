<?php

namespace App\Controller\Admin;

use App\Controller\AbstractController;
use App\Controller\CommonController;
use App\Exception\BusinessException;
use App\Model\Category;
use App\Model\FrontActivity;
use App\Model\Product;
use App\Model\ProductDescription;
use App\Model\ProductSkus;
use App\Request\Admin\ProductRequest;
use App\Service\QueueService;
use Hyperf\DbConnection\Db;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\HttpServer\Annotation\PatchMapping;
use Hyperf\HttpServer\Annotation\PostMapping;
use Hyperf\HttpServer\Annotation\RequestMapping;
use Hyperf\HttpServer\Contract\RequestInterface;
use Psr\Http\Message\ResponseInterface as Psr7ResponseInterface;
use App\Middleware\Admin\ProductMiddleware;
use Phper666\JwtAuth\Middleware\JwtAuthMiddleware;

/**
 * Class ProductController
 * @package App\Controller\Admin
 * @Controller()
 * @Middleware(JwtAuthMiddleware::class)
 */
class ProductController extends CommonController
{

    /**
     * @Inject
     * @var QueueService
     *
     */
    protected $service;

    public function getModel()
    {
        return 'App\Model\Product';
    }

    public function mergeQuery($query, $request)
    {
        return $query->when($type = $request->input('type'), function ($query) use ($type) {
            if ($type == Product::TIME_SALE) { //下架商品
                $query->whereIn('on_sale', [Product::UN_SALE, Product::TIME_SALE]);
            } else {
                $query->where('on_sale', $type);
            }
        })
            ->when($created_at = $request->input('created_at'), function ($query) use ($created_at) {
                $query->where('created_at', '>=', $created_at);
            })
            ->when($category_id = $request->input('category_id'), function ($query) use ($category_id) {
                $query->whereCategoryId($category_id);
            })
            ->when($name = $request->input('name'), function ($query) use ($name) {
                $query->where('name', 'like', '%' . $name . '%');
            })
            ->when($sold_sort = $request->input('sold_sort'), function ($query) use ($sold_sort) {
                $query->orderBy('sold_count', $sold_sort == 1 ? 'desc' : 'asc');
            });
    }

    public function isCanDelete(object $product)
    {
        if (Product::ON_SALE === $product->on_sale) {
            throw new BusinessException('当前是在售商品不能删除');
        }
        return true;
    }

    /**
     * @PostMapping(path="")
     * @Middleware(ProductMiddleware::class)
     */
    public function store(ProductRequest $request): Psr7ResponseInterface
    {
        $product = Db::transaction(function () use ($request) {
            //取sku最小值当做价格
            $minPrice = collect($request->input('skus'))->min('price');
            $product = Product::create([
                'name' => $request->input('name'),
                'category_id' => $request->input('category_id'),
                'category_name' => Category::find($request->input('category_id'))->name,
                'sort' => $request->input('sort') ?? 0,
                'price' => $minPrice,
                'description' => $request->input('description'),
                'front_image' => $request->input('front_image'),
                'on_sale' => $request->input('on_sale'),
                'time_on' => $request->input('on_sale') == Product::TIME_SALE ? $request->input('time_on') : null,
                'time_off' => $request->input('on_sale') == Product::TIME_SALE ? $request->input('time_off') : null,
                'fare' => $request->input('fare'),
                'only_buy' => $request->input('only_buy') > 0 ?? 9999,
                'only_count' => $request->input('only_count') > 0 ?? 9999,
                'images' => $request->input('image_address'),
            ]);

            //定时上架下架
            if ($request->input('on_sale') == Product::TIME_SALE) {
                $startTime = strtotime($request->input('time_on')) - time();
                $endTime = strtotime($request->input('time_off')) - time();

                $this->service->push(['product_id' => $product->id, 'type' => Product::ON_SALE], $startTime);
                $this->service->push(['product_id' => $product->id, 'type' => Product::UN_SALE], $endTime);
            }

            //商品简介
            $descriptionArray = ProductDescription::createProductDescription($request->input('descriptions'), $product->id);
            //商品sku
            foreach ($request->input('skus') as $index => $sku) {
                $productSku = ProductSkus::create([
                    'sku_image' => $sku['sku_image'], 'title' => $sku['title'], 'before_price' => $sku['before_price'] ?? '',
                    'price' => $sku['price'], 'stock' => $sku['stock'],
                    'product_id' => $product->id,
                ]);
            }

            return $product;
        });
        return $this->response->json($product);
    }


    /**
     * @PatchMapping(path="{id:\d+}")
     * @Middleware(ProductMiddleware::class)
     */
    public function update(ProductRequest $request, int $id): Psr7ResponseInterface
    {
        $productInfo = Product::find($id);
        //取sku最小值当做价格
        $product = Db::transaction(function () use ($request, $productInfo) {
            $data = $request->all();
            $data['price'] = collect($request->input('skus'))->min('price');
            $data['category_name'] = Category::find($data['category_id'])->name;
            $data['only_buy'] = $request->input('only_buy') > 0 ?? 9999;
            $data['only_count'] = $request->input('only_count') > 0 ?? 9999;
            $productInfo->fill($data);
            $productInfo->save();

            //定时上架下架
            if ($request->input('on_sale') == Product::TIME_SALE) {
                $startTime = strtotime($request->input('time_on')) - time();
                $endTime = strtotime($request->input('time_off')) - time();
                $this->service->push(['product_id' => $productInfo->id, 'type' => Product::ON_SALE], $startTime);
                $this->service->push(['product_id' => $productInfo->id, 'type' => Product::UN_SALE], $endTime);
            }

            //商品简介
            ProductDescription::query()->where('product_id', $productInfo->id)->delete();
            $descriptionArray = ProductDescription::createProductDescription($request->input('descriptions'), $productInfo->id);
            //商品sku
            ProductSkus::query()->where('product_id', $productInfo->id)->delete();
            foreach ($request->input('skus') as $index => $sku) {
                $productSku = ProductSkus::create([
                    'sku_image' => $sku['sku_image'], 'title' => $sku['title'], 'before_price' => $sku['before_price'] ?? '',
                    'price' => $sku['price'], 'stock' => $sku['stock'],
                    'product_id' => $productInfo->id,
                ]);
            }
            return $productInfo;
        });
        return $this->response->json($product);
    }

    /**
     * @GetMapping(path="{id:\d+}")
     */
    public function show(int $id)
    {
        $product = Product::query()->where('id', $id)
            ->with([
//                'items' => function ($query) {
//                $query->whereNotNull('reviewed_at')->orderBy('reviewed_at', 'desc');
//            }, 'items.images', 'images' => function ($query) {
//                $query->select('id', 'product_id', 'image_address');
//            },
                'descriptions' => function ($query) {
                    $query->select('id', 'product_id', 'type', 'content');
                }, 'skus'])
            ->withCount(['skus as stock' => function ($query) {
                $query->select(Db::raw("SUM(stock) as stock"));
            }])
            ->first()->toArray();

        return $this->response->json(['data' => $product]);
    }


    /**
     * //给其他选择商品使用的商品列表
     * @return Psr7ResponseInterface
     * @GetMapping(path="product_list")
     */
    public function productList()
    {
        $product = Product::query()
            ->select('id', 'name')
            ->where('on_sale', Product::ON_SALE)
            ->get()
            ->toArray();
        return $this->response->json(['data' => $product]);
    }

    /**
     * 上架下架
     * @PostMapping(path="sale/{id:\d+}")
     */
    public function sale(int $id)
    {
        $product = Product::find($id);
        $product->on_sale = !$product->on_sale;
        if ($product->on_sale == 0) {
            $activity = FrontActivity::query()->where('product_id', $product->id)->update(['status' => 0]);
        }
        $product->save();
        return $this->response->json(['data' => $product]);
    }


}