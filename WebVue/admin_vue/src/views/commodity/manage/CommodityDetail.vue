<template>
	<div class="add-commodity">
		<Form :label-width="120" :model="detail" ref="eidtCommodityForm">
			<FormItem label="商品名称" prop="name">
				<div>{{ detail.name }}</div>
			</FormItem>
			<FormItem label="商品分类" prop="category_id">
				<div class="clearfix">
					<span class="pull-left">{{ detail.category_id }}</span>
				</div>
			</FormItem>
			<FormItem label="商品排序" prop="sort">
				<div>{{ detail.sort }}</div>
			</FormItem>
			<FormItem label="小程序标题" prop="description">
				<div>{{ detail.description }}</div>
			</FormItem>
			<FormItem label="小程序缩略图" props="front_image">
				<div v-if="detail.front_image" class="front_image">
					<img
						:src="detail.front_image"
						alt
						@click="handleView(detail.front_image)"
					/>
				</div>
			</FormItem>
			<FormItem label="封面图" prop="image_address">
				<div class="upload-list" v-for="item in uploadList" :key="item.id">
					<template v-if="item.status === 'finished'">
						<img :src="item.url" />
						<div class="upload-list-cover">
							<Icon
								type="ios-eye-outline"
								@click.native="handleView(item.url)"
							></Icon>
						</div>
					</template>
					<template v-else>
						<Progress
							v-if="item.showProgress"
							:percent="item.percentage"
							hide-info
						></Progress>
					</template>
				</div>
			</FormItem>
			<FormItem label="商品详情">
				<div class="desc-list">
					<template
						v-if="detail.descriptions && detail.descriptions.length > 0"
					>
						<div
							v-for="(item, index) in detail.descriptions"
							:key="index"
							class="list-item"
						>
							<div v-if="item.type === 1" class="desc-image">
								<img :src="item.content" alt />
							</div>
							<div v-if="item.type === 2" class="desc-text">
								<p>{{ item.content }}</p>
							</div>
						</div>
					</template>
					<div v-if="!detail.descriptions || detail.descriptions.length === 0">
						<div style="text-align: center;">暂无商品描述</div>
					</div>
				</div>
			</FormItem>
			<FormItem label="商品规格" prop="skus">
				<div class="skus-table">
					<Table :data="detail.skus" :columns="skusColumns" border>
						<template slot="sku_image" slot-scope="{ row }">
							<img
								:src="row.sku_image"
								alt
								style="width: 40px; height: auto; cursor: pointer;"
								@click="handleView(row.sku_image)"
							/>
						</template>
						<template slot="open_discount" slot-scope="{ row }">
							<span>{{ row.open_discount === 1 ? '是' : '否' }}</span>
						</template>
						<template
							slot="discount"
							slot-scope="{ row }"
							v-if="row.open_discount === 1"
						>
							<div v-for="item in row.ratios" :key="item.user_type">
								<span>{{ getAgentName(item.user_type) }}：</span>
								<span>{{ item.discount ? item.discount : '0.00' }}</span>
							</div>
						</template>
						<template slot="open_commission" slot-scope="{ row }">
							<span>{{ row.open_commission === 1 ? '是' : '否' }}</span>
						</template>
						<template slot="return_commission" slot-scope="{ row }">
							<!-- <div v-for="item in row.ratios" :key="item.user_type">
                <span>{{getAgentName(item.user_type)}}：</span>
                <span>{{item.return_commission}}</span>
              </div>-->
							<p v-if="row.open_commission === 1">
								<span>一级用户返佣比例：</span>
								<span>{{ row.one_commission || '0.00' }}</span>
							</p>
							<p v-if="row.open_commission === 1">
								<span>二级用户返佣比例：</span>
								<span>{{ row.two_commission || '0.00' }}</span>
							</p>
						</template>
					</Table>
				</div>
			</FormItem>
			<FormItem label="用户最大购买次数" prop="only_buy">
				<span>{{ detail.only_buy }}</span
				>次
			</FormItem>
			<FormItem label="单次购买最大数量" prop="only_count">
				<span>{{ detail.only_count }}</span
				>件
			</FormItem>
			<FormItem label="快递运费" prop="fare_type">
				<span>{{ detail.fare  }}元</span>
			</FormItem>
			<FormItem label="统一运费" v-if="detail.fare_type === 1">
				<span>{{ detail.fare }}元</span>
			</FormItem>
			<FormItem
				label="运费模板"
				v-if="detail.fare_type === 2"
				prop="template_id"
			>
				<Select
					class="form-item-component"
					v-model="detail.template_id"
					disabled
				>
					<Option
						:value="item.id"
						v-for="item in templateList"
						:key="item.id"
						>{{ item.name }}</Option
					>
				</Select>
			</FormItem>
			<FormItem label="发布配置" prop="fare_type">
				<span>{{ ['放入库存', '立即上架', '定时上架'][detail.on_sale] }}</span>
			</FormItem>
			<FormItem label="上架时间" prop="time_on" v-if="detail.on_sale === 2">
				<div class="clearfix">
					<span class="pull-left">{{ detail.time_on }}</span>
					<span class="pull-left">-</span>
					<span class="pull-left">{{ detail.time_off }}</span>
				</div>
			</FormItem>
			<FormItem label="商品评价" v-if="comments && comments.length > 0">
				<ul class="comments-list">
					<li class="comment-item" v-for="item in comments" :key="item.id">
						<p>
							<span class="item-label">购买用户：</span>
							<img
								v-if="item.order.user"
								:src="item.order.user.avatar_url"
								alt=""
								class="item-avatar"
							/>
							<span>{{ item.order.user_name }}</span>
						</p>
						<p>
							<span class="item-label">评价时间：</span>
							{{ item.reviewed_at }}
						</p>
						<p>
							<span class="item-label">商品规格：</span>
							{{ item.sku_title }}
						</p>
						<p>
							<span class="item-label">评分：</span>
							<Rate disabled :value="item.rating" />
						</p>
						<p>
							<span class="item-label">评价：</span>
							{{ item.review }}
						</p>
						<div class="clearfix" v-if="item.images && item.images.length > 0">
							<span class="pull-left item-label">评价图片：</span>
							<div class="pull-left attach-images">
								<img
									:src="subItem.image"
									alt
									v-for="subItem in item.images"
									:key="subItem.id"
									@click="handleView(subItem.image)"
								/>
							</div>
						</div>
					</li>
				</ul>
				<div style="margin-top: 20px; text-align: left;">
					<Page
						:current="commentsMeta.current_page || 1"
						:total="commentsMeta.total || 0"
						:page-size="commentsMeta.per_page || 20"
						@on-change="handlePageChange"
					/>
				</div>
			</FormItem>
		</Form>
		<Modal title="图片预览" v-model="visiblePreview" footer-hide>
			<img :src="previewImage" style="width: 100%" />
		</Modal>
	</div>
</template>

<script>
import {
	Form,
	FormItem,
	Select,
	Option,
	Icon,
	Progress,
	Modal,
	Table,
	Rate,
	Page
} from 'iview'

import { mapState, mapActions } from 'vuex'

import http from '../../../http'

export default {
	components: {
		Form,
		FormItem,
		Select,
		Option,
		Icon,
		Progress,
		Modal,
		Table,
		Rate,
		Page
	},
	props: {
		id: {
			type: String,
			default: undefined
		}
	},
	data() {
		return {
			category_pid: undefined,
			detail: {},
			comments: undefined,
			commentsMeta: {},
			skusColumns: [
				{
					title: '商品图片',
					slot: 'sku_image'
				},
				{
					title: '规格名称',
					key: 'title'
				},
				{
					title: '原价（元）',
					key: 'before_price'
				},
				{
					title: '现价（元）',
					key: 'price'
				},
				{
					title: '库存',
					key: 'stock'
				}
			],
			visiblePreview: false,
			previewImage: '',
			uploadList: []
		}
	},
	computed: {
		...mapState({
			categoryList: state => state.commodity.categoryList,
			agentsList: state =>
				state.system.agentsList ? state.system.agentsList : undefined,
			templateList: state => state.system.allTemplates
		}),
		flatCategoryList() {
			const result = []
			if (this.categoryList) {
				this.categoryList.forEach(item => {
					result.push({
						id: item.id,
						name: item.name
					})
					if (item.children && item.children.length > 0) {
						item.children.forEach(subItem => {
							result.push({
								id: subItem.id,
								name: subItem.name
							})
						})
					}
				})
			}
			return result
		}
	},
	methods: {
		...mapActions(['getCategoryList']),
		getAgentName(id) {
			let name = ''
			if (this.agentsList) {
				this.agentsList.some(item => {
					if (item.id === id) {
						name = item.name
						return true
					}
				})
			}
			return name
		},
		handleView(image) {
			this.previewImage = image
			this.visiblePreview = true
		},
		getCommodityDetail() {
			http.get(`/product/${this.id}`).then(res => {
				this.detail = { ...res.data }
				this.detail.image_address = res.data.images.map(item => {
					this.uploadList.push({
						name: item,
						url: item,
						uid: new Date().getTime(),
						status: 'finished',
						percentage: 100
					})
					return item
				})
				this.detail.items = res.data.items

				if (this.categoryList) {
					this.categoryList.forEach(item => {
						if (item.id === res.data.category_id) {
							this.detail.category_id = item.name
						} else if (item.children && item.children.length > 0) {
							item.children.forEach(subItem => {
								if (subItem.id === res.data.category_id) {
									this.detail.category_id = item.name + ' - ' + subItem.name
								}
							})
						}
					})
				}
			})
		}
	},
	created() {
		!this.categoryList && this.getCategoryList()
		if (this.id) {
			this.getCommodityDetail()
		}
	}
}
</script>
