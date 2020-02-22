<template>
	<div>
		<Modal :value="showModal" title="新增用户" @on-cancel="$emit('close')">
			<div slot="footer">
				<Button type="default" @click="$emit('close')">取消</Button>
				<Button type="primary" @click="success">确定</Button>
			</div>
			<div>
				<Form
					ref="insertForm"
					:model="insertForm"
					:rules="rules"
					:label-width="80"
				>
					<FormItem label="账号" prop="name">
						<Input
							v-model="insertForm.name"
							placeholder="请输入登陆账户名或手机号"
						/>
					</FormItem>
					<FormItem label="密码" prop="password">
						<Input
							v-model="insertForm.password"
							placeholder="请输入登陆密码"
							type="password"
						/>
					</FormItem>
					<FormItem label="电话" prop="phone">
						<Input
							v-model="insertForm.phone"
							placeholder="请输入当前登陆用户电话"
						/>
					</FormItem>
					<FormItem label="邮箱" prop="email">
						<Input
							v-model="insertForm.email"
							placeholder="请输入当前登陆用户邮箱"
						/>
					</FormItem>
					<FormItem label="用户类型" prop="type">
						<RadioGroup
							v-model="insertForm.type"
							placeholder="请输入新用户类型"
						>
							<Radio :label="1">普通用户</Radio>
							<Radio :label="2">代理商</Radio>
							<Radio :label="3">后台管理员</Radio>
						</RadioGroup>
					</FormItem>
				</Form>
			</div>
		</Modal>
	</div>
</template>

<script>
import { Modal, Button, Form, FormItem, Input, RadioGroup, Radio } from 'iview'
import http from '@/http'
export default {
	components: {
		Modal,
		Button,
		Form,
		FormItem,
		Input,
		RadioGroup,
		Radio
	},
	props: {
		showModal: {
			type: Boolean,
			default: false
		}
	},
	data() {
		return {
			insertForm: {
				name: '',
				password: '',
				type: 1,
				phone: '',
				email: ''
			},
			rules: {
				name: [
					{
						required: true,
						message: '登陆用户名不能为空'
					}
				],
				password: [
					{
						required: true,
						message: '登录密码不能为空'
					}
				],
				phone: [
					{
						required: true,
						message: '请输入当前登陆用户电话'
					}
				],
				email: [
					{
						required: true,
						message: '请输入当前登陆用户邮箱'
					}
				],
				type: [
					{
						required: true,
						message: '请选择新用户类型'
					}
				]
			}
		}
	},
	methods: {
		success() {
			this.$refs['insertForm'].validate(valid => {
				http.post('register', this.insertForm).then(() => {
					this.$Message.success('添加成功')
					this.$emit('close')
				})
			})
		}
	}
}
</script>

<style></style>
