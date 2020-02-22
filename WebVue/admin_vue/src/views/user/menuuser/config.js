// TA的佣金明细 和下线的佣金名字表头字段
export const columns = [
	{
		title: '序号',
		slot: 'id',
		fixed: 'left',
		width: 140,
		align: 'center'
	},
	{
		title: '用户ID',
		key: 'user_id',
		width: 150
	},
	{
		title: '用户名',
		key: 'name',
		width: 150
	},
	{
		title: '商品ID',
		slot: 'goodsId',
		width: 150
	},
	{
		title: '商品名称',
		slot: 'product_name',
		width: 150
	},
	{
		title: '数量',
		slot: 'amount',
		width: 150
	},
	{
		title: '单价',
		slot: 'unitprice',
		width: 150
	},
	{
		title: '付款金额',
		slot: 'pay_amount',
		width: 150
	},
	{
		title: '付款方式',
		slot: 'pay_type',
		width: 150
	},
	{
		title: '交易时间',
		slot: 'paid_at',
		width: 150
	},
	{
		title: '贡献佣金',
		key: 'price',
		width: 150
	}
]
// TA的下线
export const columns1 = [
	{
		title: '用户ID',
		key: 'id'
	},
	{
		title: '用户名',
		key: 'name'
	},
	{
		title: '昵称',
		key: 'name'
	},
	{
		title: '手机号',
		key: 'phone'
	},
	{
		title: '性别',
		slot: 'gender'
	},
	{
		title: '交易时间',
		key: 'updated_at'
	},
	{
		title: '佣金',
		key: 'commission'
	},
	{
		title: '操作',
		slot: 'operation'
	}
]

// TA的提现明细
export const columns2 = [
	{
		title: '序号',
		key: 'id',
		fixed: 'left',
		align: 'center'
	},
	{
		title: '提现时间',
		key: 'created_at'
	},
	{
		title: '本次提现金额',
		key: 'withdraw_amount'
	},
	{
		title: '剩余佣金',
		key: 'charge_balance'
	},
	{
		title: '审核人',
		key: 'audit'
	}
]
export const columns3 = [
	{
		title: '序号',
		slot: 'id',
		fixed: 'left',
		align: 'center',
		width: 120
	},
	{
		title: '订单号',
		key: 'no'
	},
	{
		title: '商品ID', // 订单里面的每一个商品
		slot: 'product_id'
	},
	{
		title: '商品名称', // 订单里面的每一个名称
		slot: 'product_name'
	},
	{
		title: '单价', // 每个商户的单价
		slot: 'price'
	},
	{
		title: '数量', // 一个订单中商品的数量
		slot: 'amount'
	},
	{
		title: '实付金额', // 每一个商品的实付金额
		slot: 'sub_pay'
	},
	{
		title: '付款方式',
		slot: 'pay_type'
	},
	{
		title: '付款金额',
		key: 'pay_amount'
	},
	{
		title: '会员折扣%', // 每个商品的会员折扣
		slot: 'sub_discount'
	},
	{
		title: '佣金', // 每个商品的佣金
		slot: 'sub_bonus'
	},
	{
		title: '佣金总额',
		key: 'bonus'
	},
	{
		title: '快递费',
		key: 'pay_ship'
	},
	{
		title: '交易时间',
		key: 'paid_at'
	}
]

export const columns4 = [
	{
		title: '序号',
		slot: 'id',
		fixed: 'left',
		align: 'center',
		width: 120
	},
	{
		title: '来源',
		slot: 'type'
	},
	{
		title: '时间',
		key: 'updated_at'
	},
	{
		title: '积分',
		slot: 'amount'
	}
]
