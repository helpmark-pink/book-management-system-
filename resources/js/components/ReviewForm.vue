<template>
  <div class="max-w-4xl mx-auto p-6">
    <div class="bg-white rounded-lg shadow-md p-6">
      <h2 class="text-2xl font-bold mb-4">レビューを書く</h2>

      <div class="space-y-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">
            評価 (1-5)
          </label>
          <div class="flex space-x-2">
            <button
              v-for="star in 5"
              :key="star"
              @click="rating = star"
              type="button"
              class="text-3xl focus:outline-none transition-colors"
              :class="star <= rating ? 'text-yellow-400' : 'text-gray-300'"
            >
              ★
            </button>
          </div>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">
            レビュー (最大1000文字)
          </label>
          <textarea
            v-model="reviewText"
            rows="6"
            maxlength="1000"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
            placeholder="この本の感想を書いてください..."
          ></textarea>
          <div class="text-sm text-gray-500 text-right mt-1">
            {{ reviewText.length }}/1000
          </div>
        </div>

        <button
          @click="submitReview"
          :disabled="loading || !rating || !reviewText"
          class="w-full bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 disabled:bg-gray-400 disabled:cursor-not-allowed"
        >
          {{ loading ? '送信中...' : 'レビューを投稿' }}
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
  name: 'ReviewForm',
  props: {
    readingRecordId: {
      type: Number,
      required: true,
    },
  },
  data() {
    return {
      rating: 0,
      reviewText: '',
      loading: false,
      error: null,
      success: null,
    };
  },
  methods: {
    async submitReview() {
      this.loading = true;
      this.error = null;
      this.success = null;

      try {
        const response = await fetch('/api/reviews', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${localStorage.getItem('token')}`,
          },
          body: JSON.stringify({
            reading_record_id: this.readingRecordId,
            rating: this.rating,
            review_text: this.reviewText,
          }),
        });

        if (!response.ok) {
          const errorData = await response.json();
          throw new Error(errorData.message || 'レビューの投稿に失敗しました');
        }

        this.success = 'レビューを投稿しました';
        this.rating = 0;
        this.reviewText = '';

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
