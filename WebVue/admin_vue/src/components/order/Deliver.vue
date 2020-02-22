<template>
	<div>
		<Modal
			@on-cancel="$emit('goodsClose')"
			:value="showModal"
			:title="type === 'deliver' ? '快递发货' : '修改发货'"
		>
			<p slot="footer">
				<Button @click="$emit('goodsClose')">取消</Button>
				<Button @click="success" type="primary">确定</Button>
			</p>
			<Form ref="formValidate" :model="formValidate" :rules="ruleValidate" :label-width="80">
				<FormItem label="快递公司" prop="ship_company">
					<Select v-model="formValidate.ship_company" placeholder="请选择快递公司">
						<Option value="圆通">圆通</Option>
						<Option value="申通">申通</Option>
						<Option value="中通">中通</Option>
						<Option value="百世">百世</Option>
						<Option value="韵达">韵达</Option>
						<Option value="顺丰">顺丰</Option>
						<Option value="天天">天天</Option>
						<Option value="邮政">邮政</Option>
						<Option value="宅急送">宅急送</Option>
						<Option value="德邦">德邦</Option>
						<Option value="跨越速运">跨越速运</Option>
						<Option value="其他">其他</Option>
					</Select>
				</FormItem>
				<FormItem label="快递单号" prop="ship_no">
					<Input v-model="formValidate.ship_no" placeholder="请输入快递单号" />
				</FormItem>
				<FormItem label="快递凭证">
					<Upload @notify="notify"></Upload>
					<img
						v-if="type === 'update' || 'deliver'"
						:src="formValidate.ship_image"
						@click="priviewImgModal(formValidate.ship_image)"
						style="width: 100px"
					/>
				</FormItem>
			</Form>
		</Modal>
		<!-- 预览图片 -->
		<priviewModal
			:visible="priviewImg.visible"
			:imgUrl="priviewImg.imgUrl"
			:type="priviewImg.type"
			@close="closePriview"
		></priviewModal>
	</div>
</template>

<script>
import { Modal, Button, FormItem, Form, Input, Select, Option } from 'iview'
import priviewModal from './PriviewModal'
import Upload from './Upload'
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
		Select,
		Option,
		Upload,
		priviewModal
	},
	data() {
		return {
			// 列表的订单id
			// 图片预览
			priviewImg: {
				imgUrl: null,
				visible: false,
				type: 'updateorder'
			},
			orderId: null,
			formValidate: {
				ship_company: null,
				ship_no: null,
				ship_image: null
			},
			// 表单规则
			ruleValidate: {
				ship_company: [
					{
						required: true,
						message: '请选择快递公司',
						trigger: 'blur'
					}
				],
				ship_no: [
					{
						required: true,
						message: '请填写快递单号',
						trigger: 'blur'
					}
				]
			}
		}
	},
	watch: {
		showModal(newvalue) {
			// 新增去除输入框
			if (this.type === 'deliver') {
				this.$refs['formValidate'].resetFields()
				this.formValidate.ship_image = null
			}
		},
		row(newvalue) {
			this.orderId = newvalue.id
			// 修改发货
			if (this.type === 'update') {
				this.$nextTick(() => {
					this.formValidate.ship_company = newvalue.ship_company
					this.formValidate.ship_no = newvalue.ship_no
					this.formValidate.ship_image = newvalue.ship_image
				})
			}
		}
	},
	methods: {
		success() {
			this.$refs['formValidate'].validate(vaild => {
				if (vaild) {
					let params = {
						ship_no: this.formValidate.ship_no,
						ship_company: this.formValidate.ship_company,
						ship_image: this.formValidate.ship_image
					}
					this.$Message.loading('操作中')
					if (this.type === 'deliver') {
						http.post(`/order/ship/${this.orderId}`, params).then(res => {
							this.$Message.loading('等待发货')
							this.$emit('goodsClose', true)
						})
					} else if (this.type === 'update') {
						http.post(`/order/${this.orderId}/change`, params)
						this.$Message.success('快递修改成功')
						this.$emit('goodsClose', true)
					}
				}
			})
		}, // 图片上传通知
		notify(filepath) {
			this.formValidate.ship_image = null
			if (filepath) {
				this.formValidate.ship_image = filepath
			}
		}, // 图片预览
		priviewImgModal(imgUrl) {
			this.priviewImg.imgUrl = imgUrl
			this.priviewImg.visible = true
		}, // 关闭图片预览
		closePriview(status) {
			if (status) {
				// 确认删除图片
				this.formValidate.ship_image = null
			}
			this.priviewImg.visible = false
		}
	}
}
</script>

<style>
</style>
