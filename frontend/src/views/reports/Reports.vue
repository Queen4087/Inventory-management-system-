<template>
  <div class="space-y-8 pb-12">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
      <div>
        <h2 class="text-3xl font-black text-gray-900 tracking-tight">Reports & Analytics</h2>
        <p class="text-gray-500 font-medium">Business insights and performance metrics</p>
      </div>
      <div class="flex items-center bg-white p-1 rounded-2xl shadow-sm border border-gray-100">
          <button 
            @click="timeRange = '7'" 
            :class="['px-4 py-2 text-sm font-bold rounded-xl transition-all', timeRange === '7' ? 'bg-blue-600 text-white shadow-lg shadow-blue-200' : 'text-gray-500 hover:bg-gray-50']"
          >7 Days</button>
          <button 
            @click="timeRange = '30'" 
            :class="['px-4 py-2 text-sm font-bold rounded-xl transition-all', timeRange === '30' ? 'bg-blue-600 text-white shadow-lg shadow-blue-200' : 'text-gray-500 hover:bg-gray-50']"
          >30 Days</button>
      </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
      <div class="bg-blue-600 rounded-3xl p-8 text-white shadow-xl shadow-blue-100 relative overflow-hidden group">
        <div class="absolute -right-4 -top-4 w-24 h-24 bg-white/10 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
        <div class="flex items-center justify-between mb-4">
          <span class="text-sm font-bold uppercase tracking-wider text-blue-100">Total Revenue</span>
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m.599-1H11" />
          </svg>
        </div>
        <div class="text-3xl font-black mb-1">${{ formatNumber(stats.total_revenue) }}</div>
        <div class="text-xs font-medium text-blue-100 italic">All time</div>
      </div>

      <div class="bg-green-500 rounded-3xl p-8 text-white shadow-xl shadow-green-100 relative overflow-hidden group">
        <div class="absolute -right-4 -top-4 w-24 h-24 bg-white/10 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
        <div class="flex items-center justify-between mb-4">
          <span class="text-sm font-bold uppercase tracking-wider text-green-100">Total Sales</span>
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
          </svg>
        </div>
        <div class="text-3xl font-black mb-1">{{ stats.total_sales }}</div>
        <div class="text-xs font-medium text-green-100 italic">Transactions</div>
      </div>

      <div class="bg-purple-600 rounded-3xl p-8 text-white shadow-xl shadow-purple-100 relative overflow-hidden group">
        <div class="absolute -right-4 -top-4 w-24 h-24 bg-white/10 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
        <div class="flex items-center justify-between mb-4">
          <span class="text-sm font-bold uppercase tracking-wider text-purple-100">Inventory Value</span>
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
          </svg>
        </div>
        <div class="text-3xl font-black mb-1">${{ formatNumber(stats.inventory_value) }}</div>
        <div class="text-xs font-medium text-purple-100 italic">{{ stats.total_products }} products</div>
      </div>

      <div class="bg-orange-600 rounded-3xl p-8 text-white shadow-xl shadow-orange-100 relative overflow-hidden group">
        <div class="absolute -right-4 -top-4 w-24 h-24 bg-white/10 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
        <div class="flex items-center justify-between mb-4">
          <span class="text-sm font-bold uppercase tracking-wider text-orange-100">Low Stock Items</span>
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-orange-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
          </svg>
        </div>
        <div class="text-3xl font-black mb-1">{{ stats.low_stock_items }}</div>
        <div class="text-xs font-medium text-orange-100 italic">Needs restock</div>
      </div>
    </div>

    <!-- Main Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
      <!-- Monthly Revenue Line Chart -->
      <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
        <h3 class="text-xl font-bold text-gray-900 mb-8">Monthly Revenue (2026)</h3>
        <div class="h-80 relative">
          <Line v-if="monthlyData.labels" :data="monthlyData" :options="lineOptions" />
          <div v-else class="h-full flex items-center justify-center text-gray-400 font-medium italic">Loading charts...</div>
        </div>
      </div>

      <!-- Inventory Value by Category Donut -->
      <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
        <h3 class="text-xl font-bold text-gray-900 mb-8">Inventory Value by Category</h3>
        <div class="h-80 relative flex items-center justify-center">
          <Doughnut v-if="categoryData.labels" :data="categoryData" :options="doughnutOptions" />
          <div v-else class="h-full flex items-center justify-center text-gray-400 font-medium italic">Loading charts...</div>
        </div>
      </div>

      <!-- Top 5 Products Bar Chart -->
      <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 lg:col-span-2">
        <h3 class="text-xl font-bold text-gray-900 mb-8">Top 5 Products by Revenue</h3>
        <div class="h-80 relative">
          <Bar v-if="topProductChartData.labels" :data="topProductChartData" :options="barOptions" />
          <div v-else class="h-full flex items-center justify-center text-gray-400 font-medium italic">Loading charts...</div>
        </div>
      </div>
    </div>

    <!-- Lists Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
      <!-- Top Selling Products -->
      <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-8 border-b border-gray-50 bg-gray-50/30">
          <h3 class="text-xl font-bold text-gray-900">Top Selling Products</h3>
        </div>
        <div class="divide-y divide-gray-50 px-4">
          <div v-for="(item, index) in topSellingProducts" :key="item.product_id" class="p-4 flex items-center justify-between hover:bg-gray-50/50 rounded-2xl transition-all group">
            <div class="flex items-center space-x-4">
              <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center font-black text-xs uppercase tracking-tighter">
                #{{ index + 1 }}
              </div>
              <div>
                <div class="font-bold text-gray-900 group-hover:text-blue-600 transition-colors">{{ item.product?.name }}</div>
                <div class="text-xs font-medium text-gray-500 uppercase tracking-widest">{{ item.total_sold }} units sold</div>
              </div>
            </div>
            <div class="text-right">
              <div class="text-green-600 font-black tracking-tight">${{ formatNumber(item.total_revenue) }}</div>
            </div>
          </div>
          <div v-if="topSellingProducts.length === 0" class="p-12 text-center text-gray-400 italic">No sales data recorded yet.</div>
        </div>
      </div>

      <!-- Low Stock Alerts -->
      <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-8 border-b border-gray-50 bg-gray-50/30">
          <h3 class="text-xl font-bold text-gray-900">Low Stock Alert</h3>
        </div>
        <div class="divide-y divide-gray-50 px-4">
          <div v-for="item in lowStockItems" :key="item.id" class="p-4 flex items-center justify-between hover:bg-orange-50/30 rounded-2xl transition-all group">
            <div class="flex items-center space-x-4">
              <div class="w-10 h-10 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
              </div>
              <div>
                <div class="font-bold text-gray-900 group-hover:text-orange-700 transition-colors">{{ item.name }}</div>
                <div class="text-xs font-medium font-mono text-gray-500 uppercase tracking-widest">SKU: {{ item.sku }}</div>
              </div>
            </div>
            <div class="text-right">
              <div class="text-orange-600 font-black text-xl">{{ item.quantity }}</div>
              <div class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Min: {{ item.minimum_stock_level }}</div>
            </div>
          </div>
          <div v-if="lowStockItems.length === 0" class="p-12 text-center text-gray-400 italic flex flex-col items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-300 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
            </svg>
            <p>Inventory level looks healthy!</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue'
import api from '../../utils/axios'
import {
  Chart as ChartJS,
  Title,
  Tooltip,
  Legend,
  BarElement,
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  ArcElement,
  Filler
} from 'chart.js'
import { Bar, Line, Doughnut } from 'vue-chartjs'

// Register ChartJS components
ChartJS.register(
  Title,
  Tooltip,
  Legend,
  BarElement,
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  ArcElement,
  Filler
)

const timeRange = ref('7') // '7' or '30'
const stats = ref({
  total_revenue: 0,
  total_sales: 0,
  inventory_value: 0,
  low_stock_items: 0,
  total_products: 0
})

const monthlyData = ref({})
const categoryData = ref({})
const topProductChartData = ref({})
const topSellingProducts = ref([])
const lowStockItems = ref([])

const formatNumber = (num) => {
  return Number(num).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })
}

const fetchAllReportData = async () => {
  try {
    // Parallel fetching for performance
    const [statsRes, monthlyRes, categoryRes, topProductsRevenueRes, topProductsSoldRes, lowStockRes] = await Promise.all([
      api.get('/reports/dashboard'),
      api.get('/reports/monthly-revenue'),
      api.get('/reports/inventory-by-category'),
      api.get('/reports/top-products?type=revenue'),
      api.get('/reports/top-products?type=quantity'),
      api.get('/reports/low-stock-alerts')
    ])

    stats.value = statsRes.data

    // Monthly Revenue Data Processing
    const months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"]
    monthlyData.value = {
      labels: monthlyRes.data.map(item => months[item.month - 1]),
      datasets: [{
        label: 'Revenue ($)',
        backgroundColor: 'rgba(59, 130, 246, 0.1)',
        borderColor: '#3b82f6',
        pointBackgroundColor: '#fff',
        pointBorderColor: '#3b82f6',
        pointBorderWidth: 2,
        pointRadius: 5,
        borderWidth: 4,
        tension: 0.4,
        fill: true,
        data: monthlyRes.data.map(item => item.total)
      }]
    }

    // Category Distribution Processing
    categoryData.value = {
      labels: categoryRes.data.map(item => item.name),
      datasets: [{
        backgroundColor: ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#ec4899'],
        borderWidth: 0,
        hoverOffset: 15,
        data: categoryRes.data.map(item => item.value)
      }]
    }

    // Top Products Chart Data
    topProductChartData.value = {
      labels: topProductsRevenueRes.data.map(item => item.product?.name || 'Unknown'),
      datasets: [{
        label: 'Revenue ($)',
        backgroundColor: '#8b5cf6',
        borderRadius: 12,
        data: topProductsRevenueRes.data.map(item => item.total_revenue)
      }]
    }

    topSellingProducts.value = topProductsSoldRes.data
    lowStockItems.value = lowStockRes.data

  } catch (error) {
    console.error("Report Fetch Error:", error)
  }
}

// Chart Options
const lineOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: { display: false },
        tooltip: {
            backgroundColor: '#1f2937',
            titleFont: { size: 14, weight: 'bold' },
            bodyFont: { size: 14 },
            padding: 12,
            cornerRadius: 12,
            displayColors: false
        }
    },
    scales: {
        x: { grid: { display: false }, ticks: { font: { weight: '600' } } },
        y: { grid: { borderDash: [5, 5], color: '#f3f4f6' }, border: { display: false } }
    }
}

const doughnutOptions = {
    responsive: true,
    maintainAspectRatio: false,
    cutout: '70%',
    plugins: {
        legend: { position: 'bottom', labels: { usePointStyle: true, padding: 25, font: { weight: '600', size: 12 } } }
    }
}

const barOptions = {
    responsive: true,
    maintainAspectRatio: false,
    indexAxis: 'y', // Horizontal bars
    plugins: {
        legend: { display: false },
    },
    scales: {
        x: { grid: { borderDash: [5, 5], color: '#f3f4f6' }, border: { display: false } },
        y: { grid: { display: false }, ticks: { font: { weight: '600' } } }
    }
}

onMounted(fetchAllReportData)
watch(timeRange, fetchAllReportData)
</script>

<style scoped>
/* Smooth transition for any hovered items */
.group:hover .group-hover\:scale-150 {
  transform: scale(1.5);
}
</style>
