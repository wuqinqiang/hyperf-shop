<template>
	<div>
		<Modal @on-cancel="$emit('checkClose')" :value="showModal">
			<p slot="footer">
				<Button @click="$emit('checkClose')">取消</Button>
				<Button @click="errorhandle" type="error">拒绝</Button>
				<Button @click="success" type="primary">同意</Button>
			</p>
			<div slot="header">
				<p v-if="type === 'refund'">审核仅退款</p>
				<p v-if="type === 'allRefund'">审核退货退款</p>
				<p v-if="type === 'exchange'">审核换货</p>
			</div>
			<Form ref="formcheck" :model="formValidate" :label-width="100">
				<FormItem label="售后说明">
					<p>{{ formValidate.order_refund.refund_content ? formValidate.order_refund.refund_content : '暂无原因' }}</p>
				</FormItem>
				<FormItem label="售后类型">
					<div v-if="formValidate.order_refund.refund_type">
						<p v-if="formValidate.order_refund.refund_type === 1">仅退款</p>
						<p v-if="formValidate.order_refund.refund_type === 2">退货退款</p>
						<p v-if="formValidate.order_refund.refund_type === 3">退货换货</p>
					</div>
					<div v-else>暂无类型</div>
				</FormItem>
				<FormItem label="退款金额" v-if="formValidate.order_refund.refund_type !== 3">
					<span
						class="mr10"
						v-if="defaultOrder"
					>{{ formValidate.order_refund.refund_price ? formValidate.order_refund.refund_price : '暂无金额' }}</span>
					<Input
						class="mr10"
						v-if="!defaultOrder"
						v-model="formValidate.order_refund.refund_price"
						placeholder="退款金额"
					/>
					<Button
						size="small"
						type="primary"
						class="mr10"
						@click="defaultOrder = !defaultOrder"
						v-if="defaultOrder"
					>修改</Button>
					<Button
						size="small"
						v-if="!defaultOrder"
						@click="defaultOrder = true,formValidate.order_refund.refund_price = initOrder"
						class="mr10"
					>取消</Button>
					<Button size="small" v-if="!defaultOrder" type="primary" @click="defaultOrder = true">确定</Button>
				</FormItem>
			</Form>
		</Modal>
		<Error
			@errorClose="errorHidden"
			:showModal="errorModal.showModal"
			:row="errorModal.row"
			:type="errorModal.type"
		></Error>
	</div>
</template>

<script>
import { Modal, Button, FormItem, Form, Input } from 'iview'
import Error from '@/components/order/Error'
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
		Input,
		Error
	},
	data() {
		return {
			// 退款金额/修改
			defaultOrder: true,
			// 保留初始金额
			initOrder: null,
			orderId: null,
			// 拒绝模态框
			errorModal: {
				showModal: null,
				type: null,
				row: null
			}, // 审核参数
			formValidate: {
				order_refund: {
					// 售后说明
					refund_content: null,
					// 拒绝说明
					refuse_content: null,
					refund_price: null,
					refund_type: null,
					ship_company: null,
					ship_no: null,
					ship_image: null
				}
			}
		}
	},
	watch: {
		showModal(newvalue) {
			if (!newvalue) {
				this.$refs['formcheck'].resetFields()
			}
		},
		row(newvalue) {
			this.orderId = newvalue.id
			this.errorModal.row = newvalue
			this.formValidate = newvalue
			// 模态框显示
			// this.getCheckInfo()
		}
	},
	methods: {
		success() {
			let params = {
				type: 1
			}
			if (
				// 审核退款操作
				this.formValidate.order_refund.refund_type === 1 ||
				this.formValidate.order_refund.refund_type === 2
			) {
				// this.$refs['formcheck'].validate(vaild => {
				// 	if (vaild) {

				// 	}
				// })
				// 先判断退款 金额是否 大于 订单金额
				if (this.initOrder < this.formValidate.refund_price) {
					this.$Message.error('退款金额大于订单金额')
					return
				}
				// 如果售后类型为 1 || 2 审核售后
				// 如果是换货
				if (
					this.formValidate.order_refund.refund_type === 1 ||
					this.formValidate.order_refund.refund_type === 2
				) {
					params.refund_price = this.formValidate.refund_price
				}
				this.$Message.loading('正在操作中')
				http.post(`/order/${this.orderId}/handle`, params).then(res => {
					// 判断售后的状态
					this.$Message.success('审核待处理')
					this.$emit('checkClose', true)
				})
			} else if (this.formValidate.order_refund.refund_type === 3) {
				// 审核换货接口
				params.ship_company = this.formValidate.ship_company
				params.ship_no = this.formValidate.ship_no
				params.ship_image = this.formValidate.ship_image

				this.$Message.loading('正在操作中')
				http.post(`/order/${this.orderId}/handle`, params).then(res => {
					// 判断售后的状态
					this.$Message.success('审核待处理')
					this.$emit('checkClose', true)
				})
			}
		},
		// 拒绝审核
		errorhandle() {
			this.$emit('checkClose')
			this.errorModal.showModal = true
			this.errorModal.type = 'refund'
		}, // 隐藏拒绝模态框
		errorHidden(status) {
			if (status) {
				this.$emit('checkClose', true)
			}
			this.errorModal.showModal = false
		}
	}
}
</script>

<style>
</style>
