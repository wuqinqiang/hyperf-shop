<template>
	<div>
		<div class="user mt10">

			<div class="uesr-header clearfix mb20">
				<div class="pull-left mr20 mb10">
				</div>
				<div class="pull-left mr20 mb10">
          <Button
            type="primary"
            @click="handleoffline()"
          >增加后台用户</Button
          >
				</div>
			</div>
			<div class="user-body">
				<Table :columns="columns1" :data="userList" :loading="loading" border>

					<template slot="id" slot-scope="{ row, index }">
						<span>{{
							computPage(usermete.current_page, usermete.per_page, index)
						}}</span>
					</template>
					<template slot="operation" slot-scope="{ row }">
						<Button
							class="mr10"
							type="error"
							size="small"
							@click="handleagent(row)"
              v-if="row.status===1"
							>禁用</Button
						>
            <Button
              class="mr10"
              type="info"
              size="small"
              @click="handleagent(row)"
              v-if="row.status===2"
            >启用</Button
            >
					</template>
				</Table>
			</div>
			<div class="user-footer clearfix mt20">
				<div class="pull-right">
					<Page
						:current="usermete.current_page || 1"
						:total="usermete.total || 10"
						:page-size="usermete.per_page || 10"
						@on-change="changeSize"
					/>
				</div>
			</div>
		</div>
		<!-- 添加下线模态框 -->
		<AddOffline
			:showModal="showModal"
			:row="userrow"
			@offlineclose="offlineclose"
		></AddOffline>
	</div>
</template>

<script>
import { DatePicker, Input, Button, Table, Page } from 'iview'
import { mapActions, mapState } from 'vuex'
import AddOffline from '@/components/user/AddOffline.vue'
import { computPage } from '@/util/common.js'
import http from '@/http'
export default {
	components: {
		DatePicker,
		Input,
		Button,
		Table,
		Page,
		AddOffline
	},
	data() {
		const columns1 = [
			{
				title: '用户ID',
				key: 'id',
				width: 280
			},
			{
				title: '昵称',
				key: 'name',
				width: 280
			},
      {
        title: '角色',
        key: 'roles',
        width: 480
      },
      {
        title: '创建时间',
        key: 'created_at',
        width: 280
      },
			{
				title: '操作',
				slot: 'operation',
				fixed: 'right',
				width: 327,
				align: 'center'
			}
		]
		return {
			loading: false,
			search: {
				start_time: null,
				end_time: null,
				page: 1,
				phone: null,
				name: null,
				type: 1,
				id: null
			},
			// 普通用户数据
			columns1,
			// 添加下线模态框
			showModal: false,
			userrow: null
		}
	},
	created() {
		this.disUserList()
	},
	computed: {
		...mapState({
			userList: state => state.user.user.list,
			usermete: state => state.user.user.meta
		})
	},
	methods: {
		computPage,
		...mapActions(['getUserList']),
		disUserList() {
			this.loading = true
			this.getUserList({ ...this.search }).finally(() => {
				this.loading = false
			})
		}, // 搜索
		searchuser() {
			this.search.page = 1
			this.disUserList()
		}, // 切换页码
		changeSize(page) {
			this.search.page = page
			this.disUserList()
		}, // 时间改变
		startChange(startTime) {
			this.search.start_time = startTime
		},
		endChange(endTime) {
			this.search.end_time = endTime
		}, // 下线
		handleoffline(rowdata) {
			this.showModal = true
			this.userrow = rowdata
		},
		// 关闭模态框
		offlineclose(status) {
			if (status) {
				this.disUserList()
			}
			this.showModal = false
		},
		handleagent(row) {
		  let content=row.status===1?'确定要禁用'+row.name+'吗?':'确定解禁'+row.name+'吗?'
			this.$Modal.confirm({
				title: '提示',
				content: content,
				onOk: () => {
					http.post(`/user/status/${row.id}`).then(res => {
						this.$Message.success('操作成功')
						this.disUserList()
					})
				}
			})
		}
	}
}
</script>

<style scoped lang="less">
@import '~@/styles/user.less';
</style>
