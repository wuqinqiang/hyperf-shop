<template>
	<div>
		<Modal @on-cancel="$emit('errorClose')" :value="showModal">
			<div slot="header">
				<p v-if="type === 'refund'">退款拒绝理由</p>
				<p v-if="type === 'withdraw'">拒绝提现订单</p>
			</div>
			<p slot="footer">
				<Button @click="$emit('errorClose')">取消</Button>
				<Button @click="success" type="primary">确定</Button>
			</p>
			<Form ref="formerror" :model="formerror" :rules="ruleerror" :label-width="100">
				<FormItem label="拒绝原因" prop="refuse_content">
					<Input v-model="formerror.refuse_content" type="textarea" placeholder="请输入拒绝理由" />
				</FormItem>
			</Form>
		</Modal>
	</div>
</template>

<script>
import { Modal, Button, FormItem, Input, Form } from 'iview'
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
			// 保存订单的id
			orderId: null,
			formerror: {
				refuse_content: null
			}, // 表单规则
			ruleerror: {
				refuse_content: [
					{
						required: true,
						message: '请输入拒绝理由',
						trigger: 'change'
					}
				]
			}
		}
	},
	watch: {
		showModal(newvalue) {
			if (!newvalue) {
				this.$refs['formerror'].resetFields()
			}
		},
		row(newvalue) {
			this.orderId = newvalue.id
		}
	},
	methods: {
		success() {
			this.$refs['formerror'].validate(vaild => {
				if (vaild) {
					this.$Message.loading('加载中')

					if (this.type === 'refund') {
						let params = {
							type: 2,
							refuse_content: this.formerror.refuse_content
						}
						http
							.post(`/order/${this.orderId}/handle`, params)
							.then(res => {
								this.$Message.success('操作成功')
								this.$emit('errorClose', true)
							})
							.catch(() => {
								this.$Message.error('操作失败')
							})
					} else if (this.type === 'withdraw') {
						let params = {
							status: this.row.status,
							withdraw_status: this.row.withdraw_status,
							result: 2,
							reason: this.formerror.refuse_content
						}

						if (this.row.withdraw_status === '2') {
							params.no = this.row.withdraw_status
							params.evidence = this.row.evidence
						}

						http
							.patch(`withdraw/${this.row.id}`, params)
							.then(res => {
								this.$Message.success('拒绝提现订单')
								this.$emit('errorClose', true)
							})
							.catch(() => {
								this.$Message.error('操作失败')
							})
					}
				}
			})
		}
	}
}
</script>

<style>
</style>
