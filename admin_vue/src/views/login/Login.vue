
<template>
	<div class="login">
		<div class="login-container">
			<Card :bordered="false" :shadow="true">
				<Form ref="loginForm" :model="loginForm" :rules="rules">
					<FormItem prop="username">
						<Input
							v-model="loginForm.username"
							prefix="ios-contact"
							placeholder="请输入账户名或手机号"
							style="width: 100%;"
						/>
					</FormItem>
					<FormItem prop="password">
						<Input
							v-model="loginForm.password"
							prefix="ios-lock"
							placeholder="请输入密码"
							style="width: 100%;"
							type="password"
							@keyup.enter.native="handleLogin"
						/>
					</FormItem>
					<div>
						<Button type="primary" style="width: 100%;" @click="handleLogin">登录</Button>
					</div>
				</Form>
			</Card>
		</div>
	</div>
</template>

<script>
import { Card, Input, Form, FormItem, Button } from 'iview'
import { clientSecret } from '../../config/server.js'
import { mapActions } from 'vuex'
import http from '../../http'

export default {
	components: {
		Card,
		Form,
		FormItem,
		Input,
		Button
	},
	data() {
		return {
			loginForm: {
				username: '',
				password: ''
			},
			rules: {
				username: [
					{
						required: true,
						message: '用户名不能为空'
					}
				],
				password: [
					{
						required: true,
						message: '登录密码不能为空'
					}
				]
			}
		}
	},
	methods: {
		...mapActions(['login']),
		handleLogin() {
			this.$refs['loginForm'].validate(valid => {
				if (valid) {
					this.$Message.loading('登录中')
					http
						.post('/login/login', {
							name: this.loginForm.username,
							password: this.loginForm.password
						})
						.then(
							res => {
								this.$Message.destroy()
								this.$Message.success('登录成功')
								this.login(res)
								this.$router.push('/')
								localStorage.setItem('userInfo', JSON.stringify(res))
							},
							err => {
								console.log(err)
							}
						)
				}
			})
		}
	}
}
</script>

<style>
</style>
