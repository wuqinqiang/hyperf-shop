<template>
	<Modal
		:value="visible"
		:mask-closable="false"
		title="编辑规格"
		@on-cancel="$emit('close')"
		width="500"
	>
		<Form
			:model="formData"
			:rules="rules"
			ref="addSkuItemForm"
			:label-width="120"
		>
			<FormItem prop="title" label="规格">
				<Input v-model="formData.title" />
			</FormItem>
			<FormItem prop="sku_image" label="商品图片">
				<Upload
					ref="upload"
					:show-upload-list="false"
					:on-success="handleSuccess"
					accept="image/*"
					:max-size="1024"
					:on-exceeded-size="handleMaxSize"
					:action="`${server}/upload_file/image`"
					name="file"
					:headers="headers"
					style="display: inline-block;width:58px;"
				>
					<div
						class="uploader"
						style="width: 58px;height:58px;line-height: 58px;"
					>
						<img v-if="formData.sku_image" :src="formData.sku_image" alt />
						<Icon v-else type="ios-camera" size="20"></Icon>
					</div>
				</Upload>
				<p>建议尺寸：200*200</p>
			</FormItem>
			<FormItem prop="before_price" label="原价">
				<Input v-model="formData.before_price" />
			</FormItem>
			<FormItem prop="price" label="现价">
				<Input v-model="formData.price" />
			</FormItem>
			<FormItem prop="stock" label="库存数量">
				<Input v-model="formData.stock" placeholder="库存数量不能大于9999" />
			</FormItem>
		</Form>
		<div slot="footer">
			<Button @click="$emit('close')">取消</Button>
			<Button type="primary" @click="submit">确定</Button>
		</div>
	</Modal>
</template>

<script>
import {
	Modal,
	Form,
	FormItem,
	Input,
	Button,
	Upload,
	Icon,
	Switch
} from 'iview'

import { server } from '../../../config/server'
import { getCookie } from '../../../util/cookie'
import { mapState } from 'vuex'

export default {
	components: {
		Modal,
		Form,
		FormItem,
		Input,
		Button,
		Upload,
		Icon,
		'i-switch': Switch
	},
	props: {
		visible: {
			type: Boolean,
			default: false
		},
		defaultData: {
			type: Object,
			default: undefined
		}
	},
	data() {
		return {
			formData: {
				title: undefined,
				before_price: undefined,
				price: undefined,
				stock: undefined,
				sku_image: undefined,
			},
			discount: [],
			rules: {
				title: [
					{
						required: true,
						message: '规格名称不能为空'
					}
				],
				before_price: [
					{
						required: true,
						message: '原价不能为空'
					}
				],
				price: [
					{
						required: true,
						message: '现价不能为空'
					}
				],
				stock: [
					{
						required: true,
						message: '库存不能为空'
					}
				],
				sku_image: [
					{
						required: true,
						message: '商品图片不能为空'
					}
				],
			}
		}
	},
	computed: {
		...mapState({
			agentsList: state =>
				state.system.agentsList ? state.system.agentsList : undefined
		}),
		server() {
			return server
		},
		headers() {
			return {
				Authorization: 'Bearer ' + getCookie('access_token')
			}
		}
	},
	watch: {
		defaultData(val) {
			const {
				title,
				before_price,
				price,
				stock,
				sku_image,
				ratios
			} = val
			if (val) {
				this.formData = {
					title,
					before_price,
					price,
					stock,
					sku_image,
				}
				this.discount = (ratios && ratios.length > 0) ? ratios.map(item => item.discount) : []
			}
		}
	},
	methods: {
		handleTimeChange(timeRange) {
			if (timeRange && Array.isArray(timeRange)) {
				this.formData.start_at = timeRange[0]
				this.formData.end_at = timeRange[1]
			} else {
				this.formData.start_at = undefined
				this.formData.end_at = undefined
			}
		},
		submit() {
			this.$refs['addSkuItemForm'].validate(valid => {
				const ratios = []
				if (valid) {
					this.$emit(
						'editSku',
						{
							...this.formData,
							ratios
						},
						this.defaultData._index
					)
					this.$emit('close')
				}
			})
		},
		handleSuccess(res) {
			this.formData.sku_image = res.path
		},
		handleMaxSize() {
			this.$Message.error('所选图片尺寸过大')
		}
	}
}
</script>

<style lang="less">
.uploader {
	height: 60px;
	width: 60px;
	line-height: 60px;
	text-align: center;
	border-radius: 5px;
	border: 1px dashed #e5e5e5;
	cursor: pointer;
	&:hover {
		border-color: #c5c5c5;
	}
	img {
		width: 54px;
		height: 54px;
		margin: 1px;
		border-radius: 5px;
	}
}
</style>
