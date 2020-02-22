<template>
	<div class="order mt10">
		<div class="order-header mb20 clearfix">
			<div class="pull-left mr20 mb20">
				<DatePicker
					:value="search.created_at"
					@on-change="createChange"
					placeholder="交易时间"
					style="width: 180px"
					type="datetime"
					format="yyyy-MM-dd HH:mm:ss"
				></DatePicker>
			</div>
			<div class="pull-left mr20 mb20">
				<Input
					v-model="search.user_name"
					placeholder="用户名"
					style="width: 180px"
				/>
			</div>
			<div class="pull-left mr20 mb20">
				<Input v-model="search.no" placeholder="订单号" style="width: 180px" />
			</div>
			<div class="pull-left mr20 mb20">
				<Input
					v-model="search.user_phone"
					placeholder="手机号"
					style="width: 180px"
				/>
			</div>
			<div class="pull-left mr20 mb20">
				<Select
					v-model="search.refund_status"
					style="width:180px"
					placeholder="售后状态"
				>
					<Option :value="0">{{ '全部' }}</Option>
					<Option :value="1">{{ '售后成功' }}</Option>
					<Option :value="2">{{ '售后未处理' }}</Option>
					<Option :value="3">{{ '同意售后' }}</Option>
					<Option :value="4">{{ '售后被拒绝' }}</Option>
				</Select>
			</div>
			<Button type="primary" @click="searchOrder">搜索</Button>
		</div>
		<div class="order-body">
			<div class="tabs">
				<Tabs type="card" :animated="false" @on-click="currentTab">
					<TabPane label="全部订单"></TabPane>
					<TabPane label="待付款"></TabPane>
					<TabPane label="待发货"></TabPane>
					<TabPane label="待收货"></TabPane>
					<TabPane label="售后"></TabPane>
					<TabPane label="已完成"></TabPane>
				</Tabs>
				<div class="mt10">
					<Table
						:columns="columns1"
						:data="orderList"
						:loading="loading"
						border
						class="cursor"
					>
						<template slot="id" slot-scope="{ row, index }">
							<span>{{
								computPage(ordermeta.current_page, ordermeta.per_page, index)
							}}</span>
						</template>
						<!-- 商品名称 -->
						<!-- 单价 -->
						<template  slot-scope="{ row }">
							<p>{{ row.item.price }}</p>
							<div class="pd5">
								<ul>
									<li class="pd10 item" v-for="item of row.item" :key="item.id">
										{{ item.price }}
									</li>
								</ul>
							</div>
						</template>
						<!-- 数量 -->
						<template  slot-scope="{ row }">
							<p>{{ row.item.amount }}</p>
							<div class="pd5">
								<ul>
									<li class="pd10 item" v-for="item of row.item" :key="item.id">
										{{ item.amount }}
									</li>
								</ul>
							</div>
						</template>
						<!-- 一个商品的付款总额 -->

						<template slot="pay_type">
							<span>微信</span>
						</template>
						<template slot="operation" slot-scope="{ row }">
							<!-- paid_at 确定已经下过单了 这是支付时间  发货状态 0 未发货 1 已收货  2 已经发货 order_refund 订单售后申请的信息 -->
							<Button
								v-if="
									(statusTab === 0 || statusTab === 2) &&
										row.no &&
										row.paid_at &&
										row.ship_status === 0 &&
										row.refund_status === 0
								"
								class="mr10"
								type="error"
								size="small"
								@click="deliverGoods(row)"
								>发货</Button
							>
							<Button
								v-if="
									(statusTab === 0 || statusTab === 3) &&
										row.no &&
										row.paid_at &&
										row.ship_status === 2
								"
								class="mr10"
								type="primary"
								size="small"
								@click="update(row)"
								>修改</Button
							>
							<Button
								class="mr10"
								type="info"
								size="small"
								@click="
									$router.push(`/order/commodity/${row.id}?tabs=${statusTab}`)
								"
								>详情</Button
							>
							<!-- refund_status !== 0 一定有售后 取审核的信息 -->
							<Button
								v-if="checkRefund(row)"
								type="primary"
								size="small"
								class="mr10"
								@click="handlecheck(row, 'refund')"
								>审核</Button
							>
							<Button
								v-if="refundProduct(row)"
								type="primary"
								size="small"
								class="mr10"
								@click="handlecheck(row, 'allRefund')"
								>审核</Button
							>
							<Button
								v-if="exchangeGoods(row)"
								type="primary"
								size="small"
								class="mr10"
								@click="handlecheck(row, 'exchange')"
								>审核</Button
							>
							<Button
								v-if="refund(row)"
								:disabled="disabledbtn(row)"
								type="error"
								size="small"
								@click="handlerefund(row)"
								>仅退款</Button
							>
							<Button
								v-if="changeProduct(row)"
								type="error"
								size="small"
								@click="handleProduct(row)"
								>退货款</Button
							>
							<Button
								v-if="ChangeRefund(row)"
								type="error"
								size="small"
								@click="handleChange(row)"
								>退换货</Button
							>
						</template>

						<!-- 表格上的状态 -->
						<template slot="user_type" slot-scope="{ row }">
							<p v-if="row.user_type === 1">普通用户</p>
							<p v-if="row.user_type === 2">代理商</p>
						</template>

						<template  slot="status" slot-scope="{ row }">
							<!-- table上table表格的订单状态 -->
							<p v-if="closed(row)">交易关闭</p>
							<p v-if="wait(row)">待付款</p>
							<p v-if="sendGoods(row)">待发货</p>
							<p v-if="waitGoods(row)">待收货</p>
							<p v-if="ReturnsReplacement(row)">待换货</p>
							<p v-if="waitAgree(row)">待审核</p>
							<p v-if="errorRefused(row)">拒绝售后</p>
							<p v-if="OnlyaRefund(row)">待退款</p>
							<p v-if="ReturnsORefunds(row)">待退货</p>
							<p v-if="aftercomplete(row)">售后完成</p>
							<p v-if="unfinished(row)">已完成 - 未评价</p>
							<p v-if="orderComplete(row)">已完成 - 已评价</p>
						</template>
					</Table>
				</div>
			</div>
		</div>
		<div class="order-footer clearfix mt20">
			<div class="pull-right">
				<Page
					:current="ordermeta.current_page || 1"
					:total="ordermeta.total || 20"
					:page-size="ordermeta.per_page || 10"
					@on-change="changeSize"
				/>
			</div>
		</div>
		<Deliver
			@goodsClose="goodsHidden"
			:showModal="deliverModal.showModal"
			:type="deliverModal.type"
			:row="deliverModal.row"
		></Deliver>
		<Check
			@checkClose="checkHidden"
			:showModal="checkModal.showModal"
			:type="checkModal.type"
			:row="checkModal.row"
		></Check>
		<Refund
			@refundClose="refundHidden"
			:row="refundModal.row"
			:showModal="refundModal.showModal"
			:type="refundModal.type"
		></Refund>
	</div>
</template>

<script>
import Deliver from '@/components/order/Deliver'
import Check from '@/components/order/Check'
import Refund from '@/components/order/Refund'
import { computPage } from '@/util/common.js'

import {
	DatePicker,
	Input,
	Select,
	Option,
	Button,
	Tabs,
	TabPane,
	Table,
	Page
} from 'iview'
import { mapActions, mapState } from 'vuex'
// import http from '../../../http'

export default {
	components: {
		DatePicker,
		Input,
		Select,
		Option,
		Button,
		Tabs,
		TabPane,
		Table,
		Page,
		Deliver,
		Check,
		Refund
	},
	data() {
		return {
			loading: false,
			// tab栏切换隐藏控制按钮隐藏
			statusTab: 0,
			// 发货模态框
			deliverModal: {
				showModal: null,
				type: null,
				row: null
			}, // 审核模态框
			checkModal: {
				showModal: null,
				type: null,
				row: null
			}, // 退款模态框
			refundModal: {
				showModal: null,
				type: null,
				row: null
			}, // 筛选条件
			search: {
				created_at: null,
				name: null,
				no: null,
				userType: null,
				refund_status: null,
				order_status: null,
				page: 1 // 默认第一页
			},
			columns1: [
				{
					title: '序号',
					slot: 'id',
					width: 60
				},
				{
					title: '订单号',
					key: 'no',
					width: 150
				},
				{
					title: '创建时间',
					key: 'created_at',
					width: 150
				},
				{
					title: '商品付款总额',
					key: 'total_amount', // 一个商品的付款总额
					width: 150
				},
				{
					title: '订单付款总额',
					key: 'pay_amount', // 一个订单的付款总额
					width: 150
				},
				{
					title: '支付方式',
					slot: 'pay_type',
					width: 150
				},
				{
					title: '收货人', // 收货人的信息
					key: 'user_name',
					width: 150
				},
				{
					title: '手机号',
					key: 'user_phone',
					width: 150
				},
				{
					title: '地址',
					key: 'address',
					width: 150
				},
				{
					title: '用户类型',
					slot: 'user_type',
					width: 150
				},
				{
					title: '订单状态',
					slot: 'status',
          fixed: 'right',
          width: 150
				},
				{
					title: '操作',
					slot: 'operation',
					fixed: 'right',
					width: 220
				}
			]
		}
	},
	created() {
		this.disOrder()
	},
	filters: {
		reversal(index) {}
	},
	computed: {
		...mapState({
			orderList: state => state.order.order.list,
			ordermeta: state => state.order.order.meta
		})
	},
	methods: {
		// 分页
		computPage,
		// 派发订单列表
		...mapActions(['getOrderList']),
		disOrder() {
			this.loading = true
			this.getOrderList(this.search).finally(() => {
				this.loading = false
			})
		},
		// 搜索订单
		searchOrder() {
			this.search.page = 1
			this.disOrder()
		},
		// tab页切换
		currentTab(current) {
			// 0 1 2 3 4 5
			this.statusTab = current
			this.search = {}
			this.search.page = 1
			this.search.order_status = current
			this.disOrder()
		},
		// 页码改变
		changeSize(pageIndex) {
			this.search.page = pageIndex
			this.disOrder()
		},
		deliverGoods(row) {
			// 发货
			this.deliverModal.showModal = true
			this.deliverModal.type = 'deliver'
			this.deliverModal.row = row
		}, // 隐藏发货
		goodsHidden(status) {
			if (status) {
				// 点击了确定
				setTimeout(() => {
					this.disOrder()
				}, 500)
			}
			this.deliverModal.showModal = false
		}, // 修改发货
		update(row) {
			this.deliverModal.showModal = true
			this.deliverModal.type = 'update'
			this.deliverModal.row = row
		}, // 审核
		handlecheck(row, type) {
			this.checkModal.showModal = true
			this.checkModal.type = type
			this.checkModal.row = row
		}, // 隐藏审核
		checkHidden(status) {
			if (status) {
				this.disOrder()
			}
			this.checkModal.showModal = false
		}, // 退款
		handlerefund(row) {
			this.refundModal.showModal = true
			this.refundModal.row = row
			this.refundModal.type = 'refund'
		}, // 退货退款
		handleProduct(row) {
			this.refundModal.showModal = true
			this.refundModal.row = row
			this.refundModal.type = 'refundProduct'
		},
		// 退换货
		handleChange(row) {
			this.refundModal.showModal = true
			this.refundModal.row = row
			this.refundModal.type = 'ChangeRefund'
		},

		// 隐藏退款模态框
		refundHidden(status) {
			if (status) {
				this.disOrder()
			}
			this.refundModal.showModal = false
		}, // 时间搜索
		createChange(currenTime) {
			this.search.created_at = currenTime
		},
		/**
		 * table表格上操作的按钮的状态判断
		 */
		checkRefund(row) {
			// 判断审核退款 审核售后接口调试
			return (
				(this.statusTab === 0 || this.statusTab === 4) && // tab栏筛选显示
				row.paid_at && // 根据订单时间
				row.refund_status !== 0 && // 判断售后的状态： 0没有售后 2是当前订单有售后
				row.refund_status === 2 &&
				row.order_refund &&
				row.order_refund.refund_type === 1 // 判断审核类型
			)
		}, // 判断审核退换退款
		refundProduct(row) {
			return (
				(this.statusTab === 0 || this.statusTab === 4) &&
				row.paid_at &&
				row.refund_status !== 0 &&
				row.refund_status === 2 &&
				row.order_refund &&
				row.order_refund.refund_type === 2
			)
		}, // 判断审核换货 refund_status == 3 售后的状态 已经同意了审核 售后为进行中
		exchangeGoods(row) {
			return (
				(this.statusTab === 0 || this.statusTab === 4) &&
				row.paid_at &&
				row.refund_status !== 0 &&
				row.refund_status === 2 &&
				row.order_refund &&
				row.order_refund.refund_type === 3
			)
		}, // 退款接口调试 refund_type： 1: 仅退款 2: 退货退款 3 退货换货

		refund(row) {
			return (
				(this.statusTab === 0 || this.statusTab === 4) &&
				row.paid_at &&
				row.refund_status !== 0 &&
				(row.refund_status === 3 || row.refund_status === 5) &&
				row.order_refund &&
				row.order_refund.refund_type === 1
			)
		}, // 禁用退款
		disabledbtn(row) {
			// refund_status =5 表示正在退款中     不让他再点击退款
			return row.refund_status === 5
		},

		// 退款退货
		changeProduct(row) {
			return (
				(this.statusTab === 0 || this.statusTab === 4) &&
				row.paid_at &&
				row.refund_status !== 0 &&
				row.refund_status === 3 &&
				row.order_refund &&
				row.order_refund.refund_type === 2
			)
		},
		// 退换货
		ChangeRefund(row) {
			return (
				(this.statusTab === 0 || this.statusTab === 4) &&
				row.paid_at &&
				row.refund_status !== 0 &&
				row.refund_status === 3 &&
				row.order_refund &&
				row.order_refund.refund_type === 3
			)
		},
		/**
		 * table表头上订单状态的条件判断
		 *
		 */
		// 交易关闭
		closed(row) {
			return this.statusTab === 0 && row.closed === 1
		},
		// 待付款
		wait(row) {
			return row.no && !row.paid_at && !row.closed
		},
		// 待发货 用户付款,等待商家发货。
		sendGoods(row) {
			return (
				(this.statusTab === 0 || this.statusTab === 2) &&
				row.no &&
				row.paid_at &&
				row.ship_status === 0 &&
				row.refund_status === 0
			)
		},
		// 待收货
		waitGoods(row) {
			return (
				(this.statusTab === 0 || this.statusTab === 3) &&
				row.no &&
				row.paid_at &&
				row.ship_status === 2
				// 待收货和售后完成同时存在
				/*
					买家已付款了 此时提出换货  买家同意 收到货以后 去发货
					此时订单状态为待收货 给售后完成添加状态
				*/
			)
		},
		// 待换货
		ReturnsReplacement(row) {
			return (
				(this.statusTab === 0 || this.statusTab === 4) &&
				row.paid_at &&
				row.refund_status === 3 &&
				row.order_refund &&
				row.order_refund.refund_type === 3 &&
				row.ship_status !== 2 // 去除不要和待收货并存
			)
		},
		// 订单已完成 未评价
		unfinished(row) {
			return (
				(this.statusTab === 0 || this.statusTab === 5) &&
				row.paid_at && // 创建订单
				row.ship_status === 1 &&
				row.reviewed === 0 && // 未评价
				(row.refund_status === 1 ||
					row.refund_status === 0 ||
					row.refund_status === 4)
			)
		},
		// 订单已完成 已评价
		orderComplete(row) {
			return (
				(this.statusTab === 0 || this.statusTab === 5) &&
				row.paid_at && // 创建订单
				row.ship_status === 1 &&
				row.reviewed === 1 && // 未评价
				(row.refund_status === 1 ||
					row.refund_status === 0 ||
					row.refund_status === 4)
			)
		},
		// 待审核状态
		waitAgree(row) {
			// 已完成
			// 1: 未发货 申请退款 -- 审核
			// 2: 待收货 申请退款 -- 审核
			// 3: 已收货 申请退款退款 -- 审核
			return (
				(this.statusTab === 0 || this.statusTab === 4) &&
				row.paid_at &&
				row.refund_status === 2
			)
		},
		// 拒绝售后
		errorRefused(row) {
			return (
				(this.statusTab === 0 || this.statusTab === 4) &&
				row.paid_at &&
				row.refund_status === 4
			)
		},
		// 待退款
		OnlyaRefund(row) {
			return (
				(this.statusTab === 0 || this.statusTab === 4) &&
				row.paid_at &&
				(row.refund_status === 3 || row.refund_status === 5) &&
				row.order_refund &&
				row.order_refund.refund_type === 1
			)
		},
		// 退货退款
		ReturnsORefunds(row) {
			return (
				(this.statusTab === 0 || this.statusTab === 4) &&
				row.paid_at &&
				row.refund_status === 3 &&
				row.order_refund &&
				row.order_refund.refund_type === 2
			)
		}, // 售后完成
		aftercomplete(row) {
			return (
				(this.statusTab === 0 || this.statusTab === 4) &&
				row.order_refund &&
				row.refund_status === 1 &&
				!this.unfinished(row) &&
				!this.orderComplete(row) &&
				row.ship_status !== 2
				// 售后完成 排除已完成的订单
			)
		}
	}
}
</script>
<style scoped lang="less">
@import '~@/styles/order.less';
</style>
