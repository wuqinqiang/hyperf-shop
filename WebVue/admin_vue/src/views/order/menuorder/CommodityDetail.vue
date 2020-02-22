<template>
	<div class="order-info-wrap">
		<div class="order-info">
			<Form>
				<FormItem>
					<!-- 基本信息 -->
					<h3 class="mb20">基本信息</h3>
					<div class="flex-row bd1 mb20">
						<div class="ml10 w500">
							<span class="pull-left ft-w600">订单号:</span>
							<p class="pl50">{{ orderInfo.no ? orderInfo.no : '暂无数据' }}</p>
						</div>
						<div class="ml10 w500">
							<span class="pull-left ft-w600">用户ID:</span>
							<p class="pl50">
								{{ orderInfo.user_id ? orderInfo.user_id : '暂无数据' }}
							</p>
						</div>
					</div>



					<div class="flex-row bd1 mb20">
						<div class="ml10 w500">
							<span class="pull-left ft-w600">用户类型:</span>
							<p class="pl50" v-if="orderInfo.user_type === 1">普通用户</p>
							<p class="pl50" v-if="orderInfo.user_type === 2">县级代理</p>
							<p class="pl50" v-if="orderInfo.user_type === 3">市级代理</p>
							<p class="pl50" v-if="orderInfo.user_type === 4">省级代理</p>
						</div>
						<div class="ml10 w500">
							<span class="pull-left ft-w600" style="color: red; ">订单状态:</span>
							<!-- 订单状态 -->
              <font color="red">
							<p class="pl50" v-if="closed(orderInfo)">交易关闭</p>
							<p class="pl50" v-if="orderInfo.no && !orderInfo.paid_at">
								待付款
							</p>
							<p class="pl50" v-if="sendGoods(orderInfo)">待发货</p>
							<p class="pl50" v-if="waitGoods(orderInfo)">待收货</p>
							<!-- <p class="pl50" v-if="takeGoods(orderInfo)">已收货</p> -->
							<p class="pl50" v-if="waitAgree(orderInfo)">待审核</p>
							<p class="pl50" v-if="AfterComplete(orderInfo)">售后完成</p>
							<p class="pl50" v-if="errorRefused(orderInfo)">拒绝售后</p>
							<p class="pl50" v-if="OnlyaRefund(orderInfo)">待退款</p>
							<p class="pl50" v-if="ReturnsORefunds(orderInfo)">待退货</p>
							<p class="pl50" v-if="ReturnsReplacement(orderInfo)">待换货</p>
							<p class="pl50" v-if="unfinished(orderInfo)">已完成 - 未评价</p>
							<p class="pl50" v-if="orderComplete(orderInfo)">
								已完成 - 已评价
							</p>
              </font>
						</div>
					</div>

					<div class="flex-row bd1 mb20">
						<div class="ml10 w500">
							<span class="pull-left ft-w600" style="color: red">付款金额:</span>
							<p class="pl50">
                <font color="red">{{ orderInfo.pay_amount +' 元' }}</font>
							</p>
						</div>
						<div class="ml10 w500">
							<span class="pull-left ft-w600">下单时间:</span>
							<p class="pl50">
								{{ orderInfo.created_at ? orderInfo.created_at : '暂无数据' }}
							</p>
						</div>
					</div>

					<div class="flex-row bd1 mb20">
						<div class="ml10 w500">
							<span class="pull-left ft-w600">支付时间:</span>
							<p class="pl50">
								{{ orderInfo.paid_at ? orderInfo.paid_at : '暂无数据' }}
							</p>
						</div>
						<div class="ml10 w500">
							<span class="pull-left ft-w600">支付方式:</span>
							<p class="pl50">微信</p>
						</div>
					</div>

					<div class="flex-row bd1 mb20">
						<div class="ml10 w500">
							<span class="pull-left ft-w600">所属上级:</span>
							<p class="pl50">
								{{ orderInfo.pid ? orderInfo.pid : '暂无数据' }}
							</p>
						</div>
					</div>
				</FormItem>

				<!-- 收货信息 -->
				<FormItem>
					<h3 class="mb20">收货信息</h3>
					<div class="flex-row bd1 mb20">
						<div class="ml10 w500">
							<span class="pull-left ft-w600">收货人:</span>
							<p class="pl50">
								{{ orderInfo.user_name ? orderInfo.user_name : '暂无数据' }}
							</p>
						</div>
						<div class="ml10 w500">
							<span class="pull-left ft-w600">联系方式:</span>
							<p class="pl50">
								{{ orderInfo.user_phone ? orderInfo.user_phone : '暂无数据' }}
							</p>
						</div>
					</div>

					<div class="flex-row bd1 mb20">
						<div class="ml10 w500">
							<span class="pull-left ft-w600">地址:</span>
							<p class="pl50">
								{{                orderInfo.address }}
							</p>
						</div>
						<div class="ml10 w500">
							<span class="pull-left ft-w600">买家留言:</span>
							<p class="pl50">
								{{ orderInfo.remark ? orderInfo.remark : '暂无数据' }}
							</p>
						</div>
					</div>
				</FormItem>

				<FormItem>
					<h3 class="mb20" style="background-color: mintcream">商品信息</h3>
					<div
						class="products"
						v-for="item of orderInfo.items"
						:key="item.id"
					>
						<div class="flex-row bd1 mb20">
							<div class="w500">
								<span class="pull-left ft-w600">商品ID:</span>
								<p class="pl50">{{ item.product_id ? item.product_id : '暂无数据' }}</p>
							</div>
							<div class="w500">
								<span class="pull-left ft-w600">商品名称:</span>
								<p class="pl50">
									{{ item.product_name ? item.product_name : '暂无数据' }}
								</p>
							</div>
						</div>

						<div class="flex-row bd1 mb20">
							<div class="w500">
								<span class="pull-left ft-w600">商品规格型号:</span>
								<p class="pl50">
									{{ item.sku_title ? item.sku_title : '暂无数据' }}
								</p>
							</div>
							<div class="w500">
								<span class="pull-left ft-w600">数量:</span>
								<p class="pl50">{{ item.amount ? item.amount : '暂无数据' }}</p>
							</div>
						</div>

						<div class="flex-row bd1 mb20">
							<div class="w500">
								<span class="pull-left ft-w600">价格:</span>
                <p class="pl50"><font color="red">{{ item.price +' 元' }}</font></p>
							</div>
							<div class="w500" v-if="item.user_type === 2">
								<span class="pull-left ft-w600">代理折扣:</span>
								<p class="pl50">
									{{ item.buy_discount ? item.buy_discount + '%' : '暂无数据' }}
								</p>
							</div>
						</div>

						<div class="flex-row bd1 mb20">
							<div class="w500">
								<span class="pull-left ft-w600">实付金额:</span>
								<p class="pl50">
                  <font color="red">{{ item.pay_amount+' 元' }}</font>
								</p>
							</div>
						</div>
					</div>

					<div class="flex-row bd1 mb20">
						<div class="ml10 w500">
							<span class="pull-left ft-w600" style="color: red;">订单总价:</span>
							<p class="pl50" v-if="query !== '1'">
                <font color="red">	{{ orderInfo.pay_amount+' 元' }}</font>
							</p>
							<!-- 待付款状态可以修改 -->
							<p class="pl50" v-if="query === '1'">
								<Input
									:disabled="paydisabled"
									style="width: 100px"
									v-model="orderInfo.pay_ship"
								/>
								<span
									v-if="paydisabled"
									class="edit-pay-ship"
									@click="paydisabled = false"
									>修改</span
								>
								<span v-else class="edit-pay-ship" @click="savePayship"
									>保存</span
								>
							</p>
						</div>
						<div class="ml10 w500">
							<!-- <span class="pull-left ft-w600">所属上级:</span>
							<p class="pl50">{{ orderInfo.pid ? orderInfo.pid : '暂无数据' }}</p> -->
						</div>
					</div>
				</FormItem>

				<!-- 发货信息 v-if="query === '3'" -->
				<FormItem v-if="query === '3'">
					<h3 class="mb20">发货信息</h3>
					<div class="flex-row bd1 mb20">
						<div class="ml10 w500">
							<span class="pull-left ft-w600">快递公司:</span>
							<p class="pl50">
								{{
									orderInfo.ship_company ? orderInfo.ship_company : '暂无数据'
								}}
							</p>
						</div>
					</div>

					<div class="flex-row bd1 mb20">
						<div class="ml10 w500">
							<span class="pull-left ft-w600">快递单号:</span>
							<p class="pl50">
								{{ orderInfo.ship_no ? orderInfo.ship_no : '暂无数据' }}
							</p>
						</div>
					</div>
					<!-- :src="orderInfo.ship_image" -->
					<div class="flex-row bd1 mb20" style="padding-top: 10px">
						<div class="ml10 w500">
							<span class="pull-left ft-w600">快递单:</span>
							<div class="pl50">
								<img
									@click="showModal(orderInfo.ship_image)"
									style="width: 100px"
									:src="orderInfo.ship_image"
									alt="快递凭证"
								/>
							</div>
						</div>
					</div>
				</FormItem>

				<!-- 售后申请 v-if="query === '4'" -->
				<FormItem v-if="query === '4'">
					<h3 class="mb20">售后申请</h3>
					<div class="flex-row bd1 mb20">
						<div class="ml10 w500">
							<span class="pull-left ft-w600">申请原因:</span>
							<div
								class="pl50"
								v-if="
									orderInfo.order_refund &&
										orderInfo.order_refund.refund_content !== ''
								"
							>
								<p>{{ orderInfo.order_refund.refund_content }}</p>
							</div>
							<div class="pl50" v-else>{{ '暂无数据' }}</div>
						</div>
					</div>

					<div class="flex-row bd1 mb20">
						<div class="ml10 w500">
							<span class="pull-left ft-w600">售后类型:</span>
							<div class="pl50" v-if="orderInfo.order_refund">
								<p v-if="orderInfo.order_refund.refund_type === 1">退款</p>
								<p v-if="orderInfo.order_refund.refund_type === 2">退货退款</p>
								<p v-if="orderInfo.order_refund.refund_type === 3">退换货</p>
							</div>
							<div class="pl50" v-else>{{ '暂无数据' }}</div>
						</div>
					</div>
					<!-- :src="orderInfo.ship_image" -->
					<div class="flex-row bd1 mb20">
						<div class="ml10 w500">
							<span class="pull-left ft-w600">退款金额:</span>
							<div v-if="orderInfo.order_refund">
                <p class="pl50"><font color="red">{{ orderInfo.order_refund.refund_price }}</font></p>
							</div>
						</div>
					</div>
					<div class="flex-row bd1 mb20">
						<div class="ml10 w500">
							<span class="pull-left ft-w600">售后状态:</span>
							<p class="pl50" v-if="orderInfo.refund_status === 1">售后完成</p>
							<p class="pl50" v-if="orderInfo.refund_status === 2">正在售后</p>
							<p class="pl50" v-if="orderInfo.refund_status === 3">
								售后处理中
							</p>
							<p class="pl50" v-if="orderInfo.refund_status === 4">拒绝售后</p>
							<p class="pl50" v-if="orderInfo.refund_status === 0">暂无售后</p>
						</div>
					</div>
					<!-- 这个凭证图片要注意  参数于文档不一致 ！！！！！-->
					<div class="flex-row bd1 mb20">
						<div class="ml10">
							<span class="pull-left ft-w600">凭证（图片）:</span>
							<div class="pl50" v-if="orderInfo.order_refund">
								<div v-if="orderInfo.order_refund.images">
									<p v-if="orderInfo.order_refund.images.length == 0">
										暂无数据
									</p>
									<img
										style="width: 100px"
										v-for="(item, index) of orderInfo.order_refund.images"
										:key="index"
										:src="item.image"
										alt
										@click="order_refund_img(item.image)"
									/>
								</div>
							</div>
						</div>
					</div>
				</FormItem>

				<!-- 用户评价 v-if="query === '5'" -->
				<FormItem v-if="query === '0'">
					<h3 class="mb20">用户评价</h3>
					<div
						class="user_ping"
						v-for="item of orderInfo.order_items"
						:key="item.id"
					>
						<div class="flex-row bd1 mb20">
							<div class="ml10 w500">
								<span class="pull-left ft-w600">商品图片:</span>
								<div class="pl50">
									<img
										style="width: 100px"
										:src="item.sku_image"
										alt="商品图片"
									/>
								</div>
							</div>
						</div>

						<div class="flex-row bd1 mb20">
							<div class="ml10 w500">
								<span class="pull-left ft-w600">商品规格:</span>
								<p class="pl50">
									{{ item.sku_title ? item.sku_title : '暂无规格' }}
								</p>
							</div>
						</div>
						<!-- :src="orderInfo.ship_image"  每个商品的评价  评星 "rating": 3, ---------------------- -->
						<div class="flex-row bd1 mb20">
							<div class="ml10 w500">
								<span class="pull-left ft-w600">评星:</span>
								<p class="pl50">{{ '暂无评价' }}</p>
								<div class="pl50">
									<span
										v-for="(rating, index) of star(item.rating)"
										:key="index"
										>{{ rating }}</span
									>
								</div>
							</div>
						</div>
						<div class="flex-row bd1 mb20">
							<div class="ml10 w500">
								<span class="pull-left ft-w600">评语:</span>
								<p class="pl50">{{ item.review ? item.review : '暂无评价' }}</p>
							</div>
						</div>

						<div class="flex-row bd1 mb20">
							<div class="ml10 w500">
								<span class="pull-left ft-w600">评价图片:</span>
								<p class="pl50" v-if="item.review_image">
									<img
										style="width: 100px"
										v-for="(img, index) of item.review_image"
										:key="index"
										:src="img"
										alt="评价图片"
									/>
								</p>
								<p v-else class="pl50">暂无图片</p>
							</div>
						</div>
					</div>
				</FormItem>

				<Button @click="$router.push('/order')">返回</Button>
			</Form>
			<!-- <h1>{{ $route }}</h1> -->
			<Modal
				:value="modalHidden.showModal"
				title="快递单"
				@on-cancel="modalHidden.showModal = false"
			>
				<img width="100%" :src="modalHidden.imgUrl" alt />
				<p slot="footer">
					<Button type="primary" @click="modalHidden.showModal = false"
						>确定</Button
					>
				</p>
			</Modal>
		</div>
		<PriviewModal
			@close="priviewClose"
			:visible="priview.visible"
			:imgUrl="priview.imgUrl"
		></PriviewModal>
	</div>
</template>

<script>
import { Form, FormItem, Modal, Button, Input } from 'iview'
import http from '@/http'
import PriviewModal from '@/components/order/PriviewModal.vue'
export default {
	components: {
		Form,
		FormItem,
		Modal,
		Button,
		PriviewModal,
		Input
	},
	data() {
		return {
			priview: {
				visible: false,
				imgUrl: null
			},
			// 获取查询参数
			query: this.$route.query.tabs,
			// 快递单图片
			modalHidden: {
				imgUrl: null,
				showModal: false
			},
			// 订单的id
			orderId: this.$route.params.id,
			// 详情数据
			orderInfo: {
				order_refund: {
					images: []
				},
				user: {},
				// 修改快遞費
				pay_ship: null
			},
			// 運費禁用
			paydisabled: true
		}
	},
	created() {
		this.getOrderInfo()
	},
	methods: {
		// 展示星星
		star(count = 5) {
			let starCount = []
			for (let i = 0; i < count; i++) {
				starCount.push('⭐')
			}
			return starCount
		},
		getOrderInfo() {
			this.$Message.loading('加载中')
			http.get(`/order/${this.orderId}`).then(res => {
				this.orderInfo = res.data
			})
		}, // 预览快递单号图片
		showModal(imgUrl) {
			this.modalHidden.showModal = true
			this.modalHidden.imgUrl = imgUrl
		}, // 预览售后凭证
		order_refund_img(img) {
			this.priview.imgUrl = img
			this.priview.visible = true
		}, // 关闭售后凭证
		priviewClose(img) {
			this.priview.visible = false
		}, // 订单状态
		// 交易关闭
		closed(row) {
			return row.closed === 1
		},
		// 待发货
		sendGoods(row) {
			return row.no && row.paid_at && row.ship_status === 0 && !row.order_refund
		},
		// 待收货
		waitGoods(row) {
			return row.no && row.paid_at && row.ship_status === 2 && !row.order_refund
		},
		// 订单已完成 未评价
		unfinished(row) {
			return (
				row.paid_at && // 创建订单
				row.ship_status === 1 &&
				row.reviewed === 0 && // 未评价
				(row.refund_status === 1 ||
					row.refund_status === 0 ||
					row.refund_status === 4) // 已收货
			)
		},
		// 订单已完成 已评价
		orderComplete(row) {
			return (
				row.paid_at && // 创建订单
				row.ship_status === 1 &&
				row.reviewed === 1 && // 已评价
				(row.refund_status === 1 ||
					row.refund_status === 0 ||
					row.refund_status === 4) // 拒绝售后
			)
		},
		// 待同意
		waitAgree(row) {
			return row.paid_at && row.refund_status === 2
		},
		// 售后完成
		AfterComplete(row) {
			return (
				row.paid_at &&
				row.refund_status === 1 &&
				!this.unfinished(row) &&
				!this.orderComplete(row) &&
				row.ship_status !== 2
			)
		},
		// 拒绝售后
		errorRefused(row) {
			return row.paid_at && row.refund_status === 4
		},
		// 待退款
		OnlyaRefund(row) {
			return (
				row.paid_at &&
				(row.refund_status === 3 || row.refund_status === 5) &&
				row.order_refund &&
				row.order_refund.refund_type === 1
			)
		},
		// 退货退款
		ReturnsORefunds(row) {
			return (
				row.paid_at &&
				row.refund_status === 3 &&
				row.order_refund &&
				row.order_refund.refund_type === 2
			)
		},
		// 退货换货
		ReturnsReplacement(row) {
			return (
				row.paid_at &&
				row.refund_status === 3 &&
				row.order_refund &&
				row.order_refund.refund_type === 3
			)
		},
		// 待付款修改運費
		savePayship() {
			if (Number(this.orderInfo.pay_ship) < 0) {
				this.$Message.error('运费不能小于0')
				return
			}

			// 123456.13
			let reg = /\./
			let numberSum = null
			if (reg.test(this.orderInfo.pay_ship)) {
				numberSum = this.orderInfo.pay_ship.split('.')[0]
				if (numberSum.length > 6) {
					this.$Message.error('运费不能大于6位数')
					return
				}
			} else {
				if (this.orderInfo.pay_ship.length >= 6) {
					this.$Message.error('运费不能大于6位数')
					return
				}
			}

			let params = { id: this.orderId, pay_ship: this.orderInfo.pay_ship }
			http
				.post(`/order/${this.orderId}/edit`, params)
				.then(res => {
					this.$Message.success('修改成功')
					this.paydisabled = true
					this.getOrderInfo()
				})
				.catch(() => {
					this.$Message.error('修改失败')
				})
		}
	}
}
</script>

<style lang="less" scoped>
@import '~@/styles/order.less';
</style>
