<!--
 * @Author: your name
 * @Date: 2019-10-28 16:56:17
 * @LastEditTime: 2019-11-01 15:10:05
 * @LastEditors: Please set LastEditors
 * @Description: In User Settings Edit
 * @FilePath: \tcy-system-manage\src\components\upload\Upload.vue
 -->
<template>
	<div>
		<Upload
			ref="upload"
			:show-upload-list="false"
			:action="server + '/image'"
			:headers="{ Authorization: 'Bearer ' + token}"
			:on-success="handleSuccess"
			:format="['jpg','jpeg','png']"
			:max-size="2048"
			:on-format-error="handleFormatError"
			:on-exceeded-size="handleMaxSize"
			type="drag"
			style="display: inline-block;width:58px;"
		>
			<div style="width: 58px;height:58px;line-height: 58px;">
				<Icon type="ios-camera" size="20"></Icon>
			</div>
		</Upload>
	</div>
</template>
<script>
import { Upload, Icon } from 'iview'
import { server } from '@/config/server.js'
import { getCookie } from '@/util/cookie.js'
export default {
	components: {
		Upload,
		Icon
	},
	data() {
		return {
			server,
			getCookie
		}
	},
	computed: {
		token() {
			return getCookie('access_token')
		}
	},
	methods: {
		handleSuccess(res, file) {
			file.url = res.path
			this.$emit('upload', res)
		},
		handleFormatError(file) {
			this.$Notice.warning({
				title: '图片格式',
				desc: '图片' + file.name + '格式应为jpg or png.'
			})
		},
		handleMaxSize(file) {
			this.$Notice.warning({
				title: '图片过大',
				desc: '图片  ' + file.name + '图片大小超出了2M'
			})
		}
	}
}
</script>
<style>
</style>
