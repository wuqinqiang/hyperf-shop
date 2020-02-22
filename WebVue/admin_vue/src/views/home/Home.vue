<template>
  <div class="home">
    <div class="flex-box">
      <div class="trade-data data-container">
        <div class="data-header">
          <img src="/image/trade-data.png" alt />
          <span>交易数据</span>
        </div>
        <ul class="data-body">
          <li class="data-item">
            <p class="item-number">{{statsData ? statsData.order_all : 0}}</p>
            <p class="item-text">订单量</p>
            <p class="item-yet">昨日：{{statsData ? statsData.order_yesterday : 0}}</p>
          </li>
          <li class="data-item">
            <p class="item-number">{{statsData ? statsData.pay_amount : 0}}</p>
            <p class="item-text">支付金额</p>
            <p class="item-yet">昨日：{{statsData ? statsData.pay_amount_yesterday : 0}}</p>
          </li>
          <li class="data-item">
            <p class="item-number">{{statsData ? statsData.refund_amount : 0}}</p>
            <p class="item-text">退款</p>
            <p class="item-yet">昨日：{{statsData ? statsData.refund_amount_yesterday : 0}}</p>
          </li>
        </ul>
      </div>
      <div class="quick-entry data-container">
        <div class="data-header">
          <img src="/image/quick-entry.png" alt />
          <span>交易数据</span>
        </div>
        <ul class="data-body">
          <li class="data-item entry-item" @click="$router.push('/order/commodity')">
            <div class="item-image">
              <img src="/image/merchandise.png" alt />
              <img class="active-image" src="/image/merchandise-active.png" alt />
            </div>
            <p class="item-text">待处理商品订单</p>
            <p class="item-yet">{{statsData ? statsData.order : 0}}</p>
          </li>
        </ul>
      </div>
    </div>
<!--    <div class="user-data data-container">-->
<!--      <div class="data-header">-->
<!--        <img src="/image/user-data.png" alt />-->
<!--        <span>我的订单</span>-->
<!--      </div>-->
<!--      <ul class="data-body">-->
<!--        <li class="data-item">-->
<!--          <p class="item-number">{{statsData ? statsData.register_all : 0}}</p>-->
<!--          <p class="item-text">注册用户</p>-->
<!--          <p class="item-yet">昨日：{{statsData ? statsData.register : 0}}</p>-->
<!--        </li>-->
<!--        <li class="data-item">-->
<!--          <p class="item-number">{{statsData ? statsData.buy_all : 0}}</p>-->
<!--          <p class="item-text">购买用户</p>-->
<!--          <p class="item-yet">昨日：{{statsData ? statsData.buy : 0}}</p>-->
<!--        </li>-->
<!--      </ul>-->
<!--    </div>-->
<!--    <div class="rank data-container">-->
<!--      <Tabs v-model="currentType" @on-click="handleTabChange">-->
<!--        <TabPane label="畅销产品" name="1"></TabPane>-->
<!--        <TabPane label="滞销产品" name="2"></TabPane>-->
<!--        <TabPane label="高销售额产品" name="3"></TabPane>-->
<!--        <TabPane label="低销售额产品" name="4"></TabPane>-->
<!--      </Tabs>-->
<!--      <div class="pie-box">-->
<!--        <ve-pie :data="pieChartData"></ve-pie>-->
<!--      </div>-->
<!--    </div>-->
    <div class="chart-box data-container">
      <div class="chart-box-header clearfix">
        <div class="chart-box-selector pull-left">
          <Select v-model="timeRange" @on-change="handleTimeRangeChange">
            <Option :value="1">近一周</Option>
            <Option :value="2">近一月</Option>
            <Option :value="3">近半年</Option>
          </Select>
        </div>
        <!-- <div class="chart-box-selector pull-left">
          <DatePicker
            :clearable="false"
            :value="startTime"
            type="date"
            show-week-numbers
            placement="bottom-end"
            placeholder="自定义时间起始时间"
            style="width: 200px"
            @on-change="handleStartTimeChange"
          ></DatePicker>
        </div> -->
      </div>

      <ve-line
        :data="lineChartData"
        :settings="lineChartSettings"
        :data-empty="lineChartData.rows.length === 0"
      ></ve-line>
    </div>
  </div>
</template>

<script>
import { mapState, mapActions } from 'vuex'
import { Select, Option, Tabs, TabPane } from 'iview'
import http from '@/http'

export default {
  components: {
    Select,
    Option,
    // DatePicker,
    Tabs,
    TabPane
  },
  data() {
    return {
      lineChartData: {
        columns: [
          'date',
          'order',
          'pay',
          'refund',
          'pv',
          'uv',
          'register',
          'agent'
        ],
        rows: []
      },
      lineChartSettings: {
        labelMap: {
          order: '订单量',
          pay: '支付金额',
          refund: '退款',
          pv: 'PV',
          uv: 'UV',
          register: '注册用户',
          agent: '代理商',
          buy: '购买用户'
        }
      },
      timeRange: 3,
      currentType: '1',
      startTime: this.getStartTime(3),
      pieChartData: {
        columns: ['name', 'sold_count'],
        rows: []
      }
    }
  },
  computed: {
    ...mapState({
      statsData: state => state.app.statsData
    })
  },
  methods: {
    ...mapActions(['getStatsData']),
    getLineChartData() {
      http
        .get('/home/history', {
          params: { type: this.currentType, start_time: this.startTime }
        })
        .then(res => {
          this.lineChartData = {
            columns: [
              'date',
              'order',
              'pay',
              'refund',
              'pv',
              'uv',
              'register',
              'agent'
            ],
            rows: res.data
          }
        })
    },
    handleTypeChange(name) {
      this.currentType = name
      this.getLineChartData()
    },
    getStartTime(type) {
      let timeStamp
      let startStamp
      let now = new Date().getTime()

      if (type === 1) {
        timeStamp = 7 * 24 * 60 * 60 * 1000
      } else if (type === 2) {
        timeStamp = 30 * 24 * 60 * 60 * 1000
      } else if (type === 3) {
        timeStamp = 180 * 24 * 60 * 60 * 1000
      }
      startStamp = now - timeStamp
      const startTime = new Date(startStamp)
      return `${startTime.getFullYear()}-${startTime.getMonth() +
        1}-${startTime.getDate()}`
    },
    handleTimeRangeChange(value) {
      this.startTime = this.getStartTime(value)
      this.getLineChartData()
    },
    handleStartTimeChange(time) {
      this.startTime = time
      this.getLineChartData()
    },
    handleTabChange(name) {
      this.pieChartData = this.getPieChartData(name)
    },
    getPieChartData(type) {
      if (!this.statsData || !type) {
        return {
          columns: ['name', 'sold_count'],
          rows: []
        }
      }
      if (type === '1') {
        return {
          columns: ['name', 'sold_count'],
          rows: this.statsData.product_number_desc
        }
      } else if (type === '2') {
        return {
          columns: ['name', 'sold_count'],
          rows: this.statsData.product_number_asc
        }
      } else if (type === '3') {
        return {
          columns: ['name', 'total_price'],
          rows: this.statsData.product_price_desc
        }
      } else if (type === '4') {
        return {
          columns: ['name', 'total_price'],
          rows: this.statsData.product_price_asc
        }
      }
    }
  },
  created() {
    this.getLineChartData()
    this.getStatsData().then(() => {
      this.pieChartData = this.getPieChartData('1')
    })
  }
}
</script>

<style lang="less">
.home {
  .flex-box {
    display: flex;
    justify-content: space-between;
  }
  .data-container {
    background: #fff;
    box-shadow: 0 2px 7px rgba(0, 0, 0, 0.15);
    border-radius: 2px;
    overflow: hidden;
    padding: 10px;
    position: relative;
  }
  .trade-data {
    height: 240px;
    width: 65%;
    margin-right: 30px;
  }
  .quick-entry {
    width: 35%;
    height: 240px;
  }
  .data-header {
    display: flex;
    align-items: center;
    padding-bottom: 10px;
    border-bottom: 1px solid #e5e5e5;
    img {
      margin-right: 10px;
    }
  }
  .data-body {
    display: flex;
    padding: 40px 20px;
  }
  .data-item {
    flex: 1;
    text-align: center;
    border-right: 1px solid #e5e5e5;
    padding-bottom: 10px;
    &:last-child {
      border-right: 0;
    }
  }
  .active-image {
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    right: 0;
    margin: auto;
    opacity: 0;
    transition: opacity 0.3s;
  }
  .item-image {
    height: 60px;
    display: flex;
    justify-content: center;
    align-items: center;
    position: relative;
  }
  .entry-item {
    cursor: pointer;
    &:hover {
      .active-image {
        opacity: 1;
      }
    }
  }
  .item-number {
    font-size: 40px;
    color: #ff9d4b;
    line-height: 60px;
  }
  .item-text {
    line-height: 30px;
    color: #666;
    font-weight: 700;
  }
  .item-yet {
    color: #999;
  }
  .user-data {
    height: 240px;
    .item-number {
      color: #54baff;
    }
  }
  .chart-box {
    padding: 10px 20px;
    padding-top: 40px;
  }
  .large-header {
    border-left: 4px solid #2d8cf0;
    text-indent: 10px;
    line-height: 24px;
    margin: 10px 0;
  }
}
</style>
