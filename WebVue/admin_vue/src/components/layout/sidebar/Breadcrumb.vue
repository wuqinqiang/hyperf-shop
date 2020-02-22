<template>
	<div v-show="$route.path !== '/home'">
		<Breadcrumb>
			<BreadcrumbItem v-for="item of renderArr" :key="item.path" :to="item.path">{{ item.title }}</BreadcrumbItem>
		</Breadcrumb>
	</div>
</template>

<script>
import { Breadcrumb, BreadcrumbItem } from 'iview'
import breadcrumb from '@/config/breadcrumbitem'
export default {
	components: {
		Breadcrumb,
		BreadcrumbItem
	},
	computed: {
		renderArr () {
			const arr = []
			this.$route.matched.forEach((item, index) => {
				if (index > 0) {
					let routePath = {
						path: item.path,
						title: breadcrumb[item.path] || index
					}
					const pathArr = item.path.split('/')

					if (pathArr[pathArr.length - 1].indexOf(':') > -1) {
						pathArr.pop()
						routePath.path = pathArr.join('/') + '/' + this.$route.params.id
						routePath.title = breadcrumb[pathArr.join('/')]
					}
					arr.push(routePath)
				}
			})
			return arr
		}
	}
}
</script>

<style>
</style>
