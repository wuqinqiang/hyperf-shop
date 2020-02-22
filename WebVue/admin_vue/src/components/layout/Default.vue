<template>
	<div class="layout">
		<Layout>
			<!-- 侧边栏 -->
			<Sidebar></Sidebar>
			<Layout>
				<!-- 头部 -->
				<Header class="header">
					<Button v-if="isTest" @click="insetUesr" class="mr20"
						>新增用户</Button
					>
					<Button @click="logout">退出登录</Button>
				</Header>
				<!-- 内容 -->
				<Content>
					<Breadcrumb></Breadcrumb>
					<div class="view">
						<router-view></router-view>
					</div>
				</Content>
			</Layout>
		</Layout>

		<!-- 新增用户模态框 -->
		<InsertUser :showModal="showModal" @close="close"></InsertUser>
	</div>
</template>

<script>
import { Layout, Header, Content, Button } from 'iview'
import Breadcrumb from '@/components/layout/sidebar/Breadcrumb'
import Sidebar from './sidebar/Sidebar'
import { delCookie } from '../../util/cookie'
import InsertUser from '@/components/user/InsertUser'
export default {
	components: {
		Layout,
		Header,
		Content,
		Sidebar,
		Breadcrumb,
		Button,
		InsertUser
	},
	data() {
		return {
			showModal: false
		}
	},
	computed: {
		// 是否为测试账号
		isTest() {
			let userInfo = localStorage.getItem('userInfo')
				? JSON.parse(localStorage.getItem('userInfo'))
				: undefined
			if (!userInfo) {
				return false
			}
			if (!userInfo.user) {
				return false
			}
			return userInfo.user.name === 'ceshi001'
		}
	},
	methods: {
		logout() {
			this.$Message.success('注销成功')
			delCookie('access_token')
			this.$router.push('/login')
		},
		insetUesr() {
			this.showModal = true
		},
		close() {
			this.showModal = false
		}
	}
}
</script>

<style></style>
