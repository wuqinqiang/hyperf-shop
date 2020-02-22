// 计算table排序字段
export const computPage = (currentPage, pageSize, index) => {
	let limit = (currentPage - 1) * pageSize
	return limit + index + 1
}
