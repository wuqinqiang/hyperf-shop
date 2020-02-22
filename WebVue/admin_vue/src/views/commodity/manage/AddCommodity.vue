<template>
	<div class="add-commodity">
		<Form
			:label-width="120"
			:model="formData"
			:rules="rules"
			ref="addCommodityForm"
		>
			<FormItem label="商品名称" prop="name">
				<Input
					v-model="formData.name"
					class="form-item-component"
					style="width: 500px;"
				/>
			</FormItem>
			<FormItem label="副标题" prop="description">
				<Input v-model="formData.description" class="form-item-component" />
			</FormItem>
			<FormItem label="商品分类" prop="category_id">
				<Select
					class="form-item-component"
					v-model="parentCategoryId"
					@on-change="handleParentCategorySelect"
				>
					<Option
						v-for="item in flatCategoryList"
						:key="item.id"
						:value="item.id"
						:label="item.name"
					>
						<span>{{ item.name }}</span>
					</Option>
				</Select>
				<Select
					style="width: 200px;"
					placeholder="商品二级分类"
					v-model="childCategoryId"
					v-if="childCategoryList && childCategoryList.length > 0"
					@on-change="handleChildCategorySelect"
				>
					<Option
						v-for="item in childCategoryList"
						:key="item.id"
						:value="item.id"
						:label="item.name"
					>
						<span>{{ item.name }}</span>
					</Option>
				</Select>
			</FormItem>
			<FormItem label="商品排序" prop="sort">
				<Input
					v-model="formData.sort"
					placeholder="商品排序编号，不输入则默认为最后一个"
					class="form-item-component"
				/>
			</FormItem>
			<FormItem prop="front_image" label="缩略图">
				<Upload
					ref="upload"
					:show-upload-list="false"
					:on-success="handleFrontImageSuccess"
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
						<img v-if="formData.front_image" :src="formData.front_image" alt />
						<Icon v-else type="ios-camera" size="20"></Icon>
					</div>
				</Upload>
				<p>首页新品上市产品缩略图，推荐尺寸：334*334</p>
			</FormItem>
			<FormItem label="封面图" prop="image_address">
				<Draggable
					v-model="uploadList"
					style="display: inline-block;"
					@change="handleDraggable"
				>
					<div class="upload-list" v-for="item in uploadList" :key="item.id">
						<template v-if="item.status === 'finished'">
							<img :src="item.url" />
							<div class="upload-list-cover">
								<Icon
									type="ios-eye-outline"
									@click.native="handleView(item.url)"
								></Icon>
								<Icon
									type="ios-trash-outline"
									@click.native="handleRemove(item)"
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
				</Draggable>
				<Upload
					class="big-uploader uploader"
					ref="upload"
					:show-upload-list="false"
					:on-success="handleSuccess"
					accept="image/*"
					:max-size="1024"
					:on-exceeded-size="handleMaxSize"
					:action="`${server}/upload_file/image`"
					name="file"
					:headers="headers"
					style="display: inline-block;width:100px;"
					v-show="uploadList.length <= 4"
				>
					<div style="width: 100px;height:100px;line-height: 100px;">
						<Icon type="ios-camera" size="32"></Icon>
					</div>
				</Upload>
				<p>封面图最少上传3张，最多上传5张，拖拽可进行排序，推荐尺寸 750*750</p>
			</FormItem>
			<FormItem label="商品详情">
				<div class="desc-list">
					<template
						v-if="formData.descriptions && formData.descriptions.length > 0"
					>
						<div
							v-for="(item, index) in formData.descriptions"
							:key="index"
							class="list-item"
						>
							<div v-if="item.type === 1" class="desc-image">
								<img :src="item.content" alt />
								<Icon
									type="md-close-circle"
									size="24"
									@click.native="handleDescRemove(index)"
								/>
							</div>
							<div v-if="item.type === 2" class="desc-text">
								<p>{{ item.content }}</p>
								<Icon
									type="md-close-circle"
									size="24"
									@click.native="handleDescRemove(index)"
								/>
							</div>
						</div>
					</template>
					<div
						v-if="!formData.descriptions || formData.descriptions.length === 0"
					>
						<div style="text-align: center;">暂无商品描述</div>
					</div>
				</div>
				<div class="desc-button">
					<Upload
						:show-upload-list="false"
						:on-success="handleDescImageAdd"
						accept="image/*"
						:max-size="1024"
						:on-exceeded-size="handleMaxSize"
						:action="`${server}/upload_file/image`"
						name="file"
						:headers="headers"
					>
						<Button type="primary">插入图片</Button>
						<span class="desc-image-limit"
							>图片大小不能超过1M，建议尺寸 宽750，高度不限</span
						>
					</Upload>
				</div>
				<div class="desc-button">
					<Button
						v-if="!visibleText"
						type="primary"
						style="margin-bottom: 20px; margin-right: 20px;"
						@click="
							visibleText = true
							text = ''
						"
						>插入文本</Button
					>
					<Button
						v-if="visibleText"
						type="primary"
						@click="handleDescTextAdd"
						style="margin-right: 20px;"
						>确认插入</Button
					>
					<Button v-if="visibleText" @click="visibleText = false">取消</Button>
				</div>
				<div>
					<Input
						v-if="visibleText"
						v-model="text"
						:rows="4"
						type="textarea"
						style="width: 600px;"
						placeholder="请输入文本内容"
					/>
				</div>
			</FormItem>
			<FormItem label="商品规格" prop="skus">
				<div class="skus-table">
					<Table :data="formData.skus" :columns="skusColumns" border>
						<template slot="sku_image" slot-scope="{ row }">
							<img
								:src="row.sku_image"
								alt
								style="width: 40px; height: auto; cursor: pointer;"
								@click="handleView(row.sku_image)"
							/>
						</template>
						<template slot="action" slot-scope="{ row }">
							<Button
								size="small"
								type="primary"
								@click="handleEditSku(row)"
								style="margin-right: 10px;"
								>编辑</Button
							>
							<Button size="small" type="error" @click="handleDelete(row)"
								>删除</Button
							>
						</template>
					</Table>
					<Button
						type="primary"
						icon="md-add"
						style="margin: 20px 0;"
						@click="visibleAddSkuItem = true"
						>添加规格</Button
					>
				</div>
			</FormItem>
			<FormItem label="用户最大购买次数" prop="only_buy">
				<Input
					v-model="formData.only_buy"
					class="form-item-component"
					placeholder="为0或空即默认值，默认9999"
				/>次
			</FormItem>
			<FormItem label="单次购买最大数量" prop="only_count">
				<Input
					v-model="formData.only_count"
					class="form-item-component"
					placeholder="为0或空即默认值，默认9999"
				/>件
			</FormItem>
			<FormItem label="统一运费" v-if="formData.fare_type === 1" prop="fare">
				<InputNumber v-model="formData.fare" class="form-item-component" />元
			</FormItem>
			<FormItem label="发布配置" prop="fare_type">
				<RadioGroup v-model="formData.on_sale" @on-change="saleStatusChange">
					<Radio :label="1">立即上架</Radio>
					<Radio :label="2">定时上架</Radio>
					<Radio :label="0">放入库存</Radio>
				</RadioGroup>
			</FormItem>
			<FormItem label="上架时间" prop="time_on" v-if="formData.on_sale === 2">
				<DatePicker
					@on-change="handleTimeChange"
					type="datetimerange"
					placeholder="创建时间"
					style="width: 300px"
				></DatePicker>
			</FormItem>
			<FormItem>
				<Button @click="handleSubmit" type="primary" style="margin-right: 20px;"
					>添加商品</Button
				>
				<Button @click="$router.back()" type="info">取消</Button>
			</FormItem>
		</Form>
		<Modal title="图片预览" v-model="visiblePreview" footer-hide>
			<img :src="previewImage" style="width: 100%" />
		</Modal>
		<AddSkuItem
			:visible="visibleAddSkuItem"
			@close="visibleAddSkuItem = false"
			@addSkuItem="handleAddSkuItem"
		/>
		<EditSkuItem
			:visible="visibleEditSkuItem"
			@close="visibleEditSkuItem = false"
			:defaultData="editSkuData"
			@editSku="handleEditSkuItem"
		/>
	</div>
</template>

<script>
import {
	Form,
	FormItem,
	Input,
	Select,
	Option,
	Upload,
	Icon,
	Progress,
	Modal,
	Table,
	Button,
	RadioGroup,
	Radio,
	DatePicker,
	InputNumber
} from 'iview'

import Draggable from 'vuedraggable'
import AddSkuItem from './AddSkuItem'
import EditSkuItem from './EditSkuItem'
import { mapState, mapActions } from 'vuex'
import { server } from '../../../config/server'
import { getCookie } from '../../../util/cookie'

import http from '../../../http'

export default {
	components: {
		Form,
		FormItem,
		Input,
		Select,
		Option,
		Upload,
		Icon,
		Progress,
		Modal,
		Table,
		AddSkuItem,
		Button,
		RadioGroup,
		Radio,
		DatePicker,
		InputNumber,
		Draggable,
		EditSkuItem
	},
	data() {
		return {
			formData: {
				name: '',
				category_id: '',
				sort: '',
				descriptions: undefined,
				skus: undefined,
				fare_type: 1,
				fare: null,
				template_id: undefined,
				on_sale: 1,
				time_on: undefined,
				time_off: undefined,
				image_address: undefined,
				description: '',
				front_image: '',
				only_buy: '',
				only_count: ''
			},
			parentCategoryId: '',
			childCategoryId: '',
			rules: {
				name: [
					{
						required: true,
						message: '商品名称不能为空'
					}
				],
				category_id: [
					{
						required: true,
						message: '商品分类不能为空'
					}
				],
				descriptions: [
					{
						required: true,
						message: '商品描述不能为空'
					}
				],
				time_on: [
					{
						required: true,
						message: '上架时间不能为空'
					}
				],
				fare: [
					{
						required: true,
						message: '运费不能为空'
					}
				],

				image_address: [
					{
						required: true,
						message: '封面图片不能为空'
					}
				],
				skus: [
					{
						required: true,
						message: '商品规格不能为空'
					}
				],
				description: [
					{
						required: true,
						message: '小程序标题不能为空'
					}
				],
				front_image: [
					{
						required: true,
						message: '小程序缩略图不能为空'
					}
				]
			},
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
					title: '成本价（元）',
					key: 'prime'
				},
				{
					title: '库存',
					key: 'stock'
				},
				{
					title: '操作',
					slot: 'action'
				}
			],
			visibleAddSkuItem: false,
			visibleEditSkuItem: false,
			secondaryCategory: undefined,
			visiblePreview: false,
			visibleText: false,
			text: '',
			previewImage: '',
			uploadList: [],
			editSkuData: undefined
		}
	},
	watch: {
		parentCategoryId(id) {
			this.formData.category_id = id
			this.childCategoryId = ''
		},
		childCategoryId(id) {
			if (id) {
				this.query.category_id = id
			} else {
				this.query.category_id = this.parentCategoryId
			}
		},
	},
	computed: {
		...mapState({
			categoryList: state => state.commodity.categoryList,
		}),
		flatCategoryList() {
			const result = []
			if (this.categoryList) {
				this.categoryList.forEach(item => {
					result.push({
						id: item.id,
						name: item.name,
						pid: 0
					})
				})
			}
			return result
		},
		childCategoryList() {
			let result
			if (this.categoryList) {
				this.categoryList.some(item => {
					if (item.id === this.parentCategoryId) {
						result = item.children
					}
				})
			}
			return result
		},
		server() {
			return server
		},
		headers() {
			return {
				Authorization: 'Bearer ' + getCookie('access_token')
			}
		}
	},
	methods: {
		...mapActions(['getCategoryList']),
		getAgentName(id) {
			let name = ''
			return name
		},
		handleParentCategorySelect(id) {
			this.formData.category_id = id
			this.childCategoryId = ''
		},
		handleChildCategorySelect(id) {
			if (id) {
				this.formData.category_id = id
			} else {
				this.formData.category_id = this.parentCategoryId
			}
		},
		handleDraggable() {
			this.formData.image_address = this.uploadList.map(item => {
				return item.url
			})
			this.$refs.upload.fileList = this.uploadList
		},
		handlePrimaryCategoryChange(val) {
			this.formData.category_id = undefined
			this.category.forEach(item => {
				if (item.id === val) {
					this.secondaryCategory = item.children
				}
			})
		},
		handleSuccess(res, file) {
			file.url = res.path

			if (!this.formData.image_address) {
				this.formData.image_address = []
			}
			this.formData.image_address.push(res.path)
			this.uploadList = this.$refs.upload.fileList
		},
		handleFrontImageSuccess(res) {
			this.formData.front_image = res.path
		},
		handleDescImageAdd(res) {
			if (!this.formData.descriptions) {
				this.formData.descriptions = []
			}
			this.formData.descriptions.push({
				type: 1,
				content: res.path
			})
		},
		handleDescTextAdd() {
			if (!this.text) {
				this.$Message.error('不能添加空文本')
				return
			}
			if (!this.formData.descriptions) {
				this.formData.descriptions = []
			}
			this.formData.descriptions.push({
				type: 2,
				content: this.text
			})
			this.visibleText = false
		},
		handleDescRemove(index) {
			this.formData.descriptions.splice(index, 1)
		},
		handleMaxSize() {
			this.$Message.error('所选图片尺寸过大')
		},
		handleRemove(file) {
			const fileList = this.$refs.upload.fileList
			const imageList = this.formData.image_address

			this.$refs.upload.fileList.splice(fileList.indexOf(file), 1)
			this.formData.image_address.splice(imageList.indexOf(file.url), 1)
			this.uploadList = this.$refs.upload.fileList
		},
		handleView(image) {
			this.previewImage = image
			this.visiblePreview = true
		},
		handleAddSkuItem(item) {
			if (!this.formData.skus) {
				this.formData.skus = []
			}
			this.formData.skus.push(item)
		},
		handleDelete(row) {
			this.formData.skus.splice(row._index, 1)
		},
		handleEditSku(row) {
			this.editSkuData = row
			this.visibleEditSkuItem = true
		},
		handleEditSkuItem(item, index) {
			this.formData.skus.splice(index, 1, item)
		},

		saleStatusChange(status) {
			if (status !== 2) {
				this.formData.time_on = undefined
				this.formData.time_off = undefined
			}
		},
		handleTimeChange(time) {
			if (time && Array.isArray(time)) {
				this.formData.time_on = time[0]
				this.formData.time_off = time[1]
			}
		},
		handleSubmit() {
			this.$refs['addCommodityForm'].validate(valid => {
				if (valid) {
					if (
						!this.formData.image_address ||
						this.formData.image_address.length < 3
					) {
						this.$Message.error('请上传至少3张封面图')
						return
					}
					if (!this.formData.skus || this.formData.skus === 0) {
						this.$Message.error('请至少添加一种商品规格')
						return
					}
					this.$Message.loading('添加中')
					http
						.post('/product', {
							...this.formData
						})
						.then(
							res => {
								this.$Message.success('添加成功')
								this.$router.push('/commodity')
							},
							err => {
								console.log(err)
							}
						)
				}
			})
		}
	},
	created() {
		!this.categoryList && this.getCategoryList()
	}
}
</script>
