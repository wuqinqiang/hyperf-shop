<template>
	<div>
		<Upload
			ref="upload"
			:action="server + '/image'"
			:on-success="handleSuccess"
			:headers="{ Authorization: 'Bearer ' + getToken()}"
			:format="['jpg','jpeg','png']"
			:max-size="10240"
			:before-upload="handleBeforeUpload"
		>
			<Button icon="ios-cloud-upload-outline">点击上传</Button>
		</Upload>

		<Modal v-if="type === 'orderload'" title="打款凭证" v-model="visible">
			<img :src="imgUrl" v-if="visible" style="width: 100%" />
		</Modal>
	</div>
</template>

<script>
import { Upload, Button, Modal } from 'iview'
import { getCookie } from '../../util/cookie'
import { server } from '../../config/server'
export default {
	props: {
		type: String
	},
	components: {
		Upload,
		Button,
		Modal
	},
	data() {
		return {
			server,
			imgUrl: '',
			visible: false
		}
	},
	methods: {
		getToken() {
			// server + '/image'
			const token = getCookie('access_token')
			return token
		},
		handleView(file) {
			this.imgUrl = file.url
			this.visible = true
		}, // 上传之前
		handleBeforeUpload() {
			let uploadLen = this.$refs.upload.fileList.length
			if (uploadLen >= 1) {
				this.$Message.error('最多上传一张')
				return false
			}
		}, // 上传成功的钩子
		handleSuccess(res, file) {
			file.url = res.path
			file.name = file.name + ''
			this.$emit('notify', res.path)
			// 提现订单上传不清空
			if (this.type !== 'orderload') {
				this.$refs.upload.fileList = []
			}
			this.$Message.success('上传成功')
		}, // 文件格式校验失败
		handleFormatError(file) {
			this.$Message.error({
				title: '文件的格式不正确'
			})
		}
	}
}
</script>
<style>
</style>
