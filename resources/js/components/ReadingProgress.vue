<template>
  <div class="max-w-4xl mx-auto p-6">
    <div class="bg-white rounded-lg shadow-md p-6">
      <h2 class="text-2xl font-bold mb-4">読書進捗</h2>

      <div class="space-y-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">
            現在のページ
          </label>
          <input
            v-model.number="currentPage"
            type="number"
            min="0"
            :max="totalPages"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
            @input="calculateProgress"
          />
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">
            総ページ数
          </label>
          <input
            v-model.number="totalPages"
            type="number"
            min="1"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
            @input="calculateProgress"
          />
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">
            ステータス
          </label>
          <select
            v-model="status"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
          >
            <option value="未読">未読</option>
            <option value="読中">読中</option>
            <option value="完読">完読</option>
          </select>
        </div>

        <div class="mt-4">
          <div class="flex justify-between text-sm font-medium text-gray-700 mb-2">
            <span>進捗率</span>
            <span>{{ progressPercentage.toFixed(2) }}%</span>
          </div>
          <div class="w-full bg-gray-200 rounded-full h-4 overflow-hidden">
            <div
              class="bg-blue-500 h-full transition-all duration-300"
              :style="{ width: progressPercentage + '%' }"
            ></div>
          </div>
        </div>

        <button
          @click="updateProgress"
          :disabled="loading"
          class="w-full bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 disabled:bg-gray-400 disabled:cursor-not-allowed mt-4"
        >
          {{ loading ? '更新中...' : '進捗を更新' }}
        </button>

        <div v-if="error" class="mt-4 p-4 bg-red-100 text-red-700 rounded-lg">
          {{ error }}
        </div>

        <div v-if="success" class="mt-4 p-4 bg-green-100 text-green-700 rounded-lg">
          {{ success }}
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'ReadingProgress',
  props: {
    recordId: {
      type: Number,
      required: true,
    },
    initialCurrentPage: {
      type: Number,
      default: 0,
    },
    initialTotalPages: {
      type: Number,
      required: true,
    },
    initialStatus: {
      type: String,
      default: '未読',
    },
  },
  data() {
    return {
      currentPage: this.initialCurrentPage,
      totalPages: this.initialTotalPages,
      status: this.initialStatus,
      progressPercentage: 0,
      loading: false,
      error: null,
      success: null,
    };
  },
  mounted() {
    this.calculateProgress();
  },
  methods: {
    calculateProgress() {
      if (this.totalPages > 0) {
        this.progressPercentage = (this.currentPage / this.totalPages) * 100;
      } else {
        this.progressPercentage = 0;
      }
    },
    async updateProgress() {
      this.loading = true;
      this.error = null;
      this.success = null;

      try {
        const response = await fetch(`/api/reading-records/${this.recordId}`, {
          method: 'PUT',
          headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${localStorage.getItem('token')}`,
          },
          body: JSON.stringify({
            current_page: this.currentPage,
            total_pages: this.totalPages,
            status: this.status,
          }),
        });

        if (!response.ok) {
          throw new Error('進捗の更新に失敗しました');
        }

        this.success = '進捗を更新しました';
        setTimeout(() => {
          this.success = null;
        }, 3000);
      } catch (err) {
        this.error = err.message;
      } finally {
        this.loading = false;
      }
    },
  },
};
</script>
