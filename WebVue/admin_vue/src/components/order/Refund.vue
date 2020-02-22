<template>
	<div>
		<Modal @on-cancel="$emit('refundClose')" :value="showModal">
			<p slot="footer">
				<Button @click="$emit('refundClose')">取消</Button>
				<Button @click="success" type="primary">确认</Button>
			</p>
			<div slot="header">
				<p v-if="type === 'refund'">{{ '仅退款' }}</p>
				<p v-if="type === 'refundProduct'">{{ '退货退款' }}</p>
				<p v-if="type === 'ChangeRefund'">{{ '退换货' }}</p>
			</div>
			<Form ref="formrefund" :model="formrefund" :label-width="100">
				<FormItem label="售后说明">
					<p>
						{{
							formrefund.refund_content ? formrefund.refund_content : '暂无原因'
						}}
					</p>
				</FormItem>
				<FormItem label="售后类型">
					<div v-if="formrefund.refund_type">
						<p v-if="formrefund.refund_type === 1">
							{{ formrefund.refund_type ? '退款' : '暂无数据' }}
						</p>
						<p v-if="formrefund.refund_type === 2">
							{{ formrefund.refund_type ? '退货退款' : '暂无数据' }}
						</p>
						<p v-if="formrefund.refund_type === 3">
							{{ formrefund.refund_type ? '退换货' : '暂无数据' }}
						</p>
					</div>
					<p v-else>暂无数据</p>
				</FormItem>
				<FormItem label="退款金额" v-if="formrefund.refund_type !== 3">
					<span class="mr10" v-if="defaultOrder">{{
						formrefund.refund_price ? formrefund.refund_price : '暂无金额'
					}}</span>
					<Input
						class="mr10"
						v-if="!defaultOrder"
						v-model="formrefund.refund_price"
						placeholder="退款金额"
					/>
					<Button
						size="small"
						type="primary"
						class="mr10"
						@click="defaultOrder = !defaultOrder"
						v-if="defaultOrder"
						>修改</Button
					>
					<Button
						size="small"
						v-if="!defaultOrder"
						@click="
							;(defaultOrder = true), (formrefund.refund_price = initOrder)
						"
						class="mr10"
						>取消</Button
					>
					<Button
						size="small"
						v-if="!defaultOrder"
						type="primary"
						@click="defaultOrder = true"
						>确定</Button
					>
				</FormItem>
			</Form>
		</Modal>
	</div>
</template>

<script>
import { Modal, Button, FormItem, Form, Input } from 'iview'
import http from '../../http'

export default {
	props: {
		showModal: Boolean,
		type: String,
		row: Object
	},
	components: {
		Modal,
		Button,
		FormItem,
		Form,
		Input
	},
	data() {
		return {
			// 订单的id
			orderId: null,
			// 记录初始查询订单金额
			initOrder: null,
			// 退款金额/修改
			defaultOrder: true,
			// 退款参数
			formrefund: {
				refund_content: null,
				refund_price: null,
				refund_type: null,
				ship_company: null,
				ship_no: null,
				ship_image: null
			}
		}
	},
	watch: {
		showModal(newvalue) {
			if (!newvalue) {
				this.$refs['formrefund'].resetFields()
			}
		},
		row(newvalue) {
			this.orderId = newvalue.id
			this.getOrderInfo()
		}
	},
	methods: {
		// 请求订单的详情
		getOrderInfo() {
			this.$Message.loading('查询中')
			http.get(`/order/${this.orderId}`).then(res => {
				let data = res.data
				if (data && data.order_refund) {
					// 售后内容说明
					this.formrefund.refund_content = data.order_refund.refund_content
					// 订单价格
					this.formrefund.refund_price = Number(data.order_refund.refund_price)
					// 退款 换货的类型
					this.formrefund.refund_type = data.order_refund.refund_type
					this.formrefund.ship_company = data.order_refund.ship_company
					this.formrefund.ship_no = data.order_refund.ship_no
					this.formrefund.ship_image = data.order_refund.ship_image
					// 原始订单价格
					this.initOrder = Number(data.order_refund.refund_price)
				}
			})
		},
		success() {
			let params = {
				type: 1 // 代表同意操作 部分类型
			}
			if (
				// 关于退款的操作
				this.formrefund.refund_type === 1 ||
				this.formrefund.refund_type === 2
			) {
				if (
					// 判断 仅退款和退货退款 和钱相关
					// 先判断退款 金额是否 大于 订单金额
					this.formrefund.refund_type === 1 ||
					this.formrefund.refund_type === 2
				) {
					if (this.formrefund.refund_price > this.initOrder) {
						this.$Message.error('退款金额不能大于订单金额')
						return
					}
					// 再判断售后的类型 修改产品逻辑 审核仅退款 状态变为3
					params.refund_price = this.formrefund.refund_price
				}
				this.$Message.loading('正在操作中')
				http.post(`/order/${this.orderId}/over`, params).then(res => {
					this.$Message.success('退款成功')
					this.$emit('refundClose', true)
				})
			} else if (this.formrefund.refund_type === 3) {
				// 换货的操作
				if (this.formrefund.refund_type === 3) {
					params.ship_company = this.formrefund.ship_company
					params.ship_no = this.formrefund.ship_no
					params.ship_image = this.formrefund.ship_image
					this.$Message.loading('正在操作中')
					http.post(`/order/${this.orderId}/over`, params).then(res => {
						this.$Message.success('确认换货')
						this.$emit('refundClose', true)
					})
				}
			}
		}
	}
}
</script>

<style></style>
