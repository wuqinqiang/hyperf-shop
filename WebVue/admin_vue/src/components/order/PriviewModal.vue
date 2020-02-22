<template>
	<!-- 公共预览图片组件 -->
	<div>
		<Modal title="预览上传" :value="visible" @on-cancel="$emit('close')">
			<img :src="imgUrl" v-if="visible" style="width: 100%" />
			<p slot="footer">
				<Button @click="$emit('close')">取消</Button>
				<Button v-if="type === 'updateorder'" type="error" @click="deleteImg">删除</Button>
			</p>
		</Modal>
	</div>
</template>

<script>
import { Modal, Button } from 'iview'
export default {
	components: {
		Modal,
		Button
	},
	props: {
		visible: Boolean,
		imgUrl: String,
		type: String
	},
	methods: {
		deleteImg() {
			this.$emit('close', true)
		}
	}
}
</script>

<style>
</style>
