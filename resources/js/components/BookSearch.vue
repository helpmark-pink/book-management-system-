<template>
  <div class="max-w-4xl mx-auto p-6">
    <div class="bg-white rounded-lg shadow-md p-6">
      <h2 class="text-2xl font-bold mb-4">書籍検索</h2>

      <div class="mb-4">
        <input
          v-model="searchQuery"
          type="text"
          placeholder="書籍名またはISBNを入力"
          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
          @keyup.enter="searchBooks"
        />
      </div>

      <button
        @click="searchBooks"
        :disabled="loading || !searchQuery"
        class="w-full bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 disabled:bg-gray-400 disabled:cursor-not-allowed"
      >
        {{ loading ? '検索中...' : '検索' }}
      </button>

      <div v-if="error" class="mt-4 p-4 bg-red-100 text-red-700 rounded-lg">
        {{ error }}
      </div>

      <div v-if="books.length > 0" class="mt-6">
        <h3 class="text-xl font-semibold mb-4">検索結果</h3>
        <div class="space-y-4">
          <div
            v-for="book in books"
            :key="book.isbn"
            class="flex border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow"
          >
            <img
              v-if="book.cover_image_url"
              :src="book.cover_image_url"
              :alt="book.title"
              class="w-24 h-32 object-cover rounded mr-4"
            />
            <div class="flex-1">
              <h4 class="font-semibold text-lg">{{ book.title }}</h4>
              <p class="text-gray-600">{{ book.author }}</p>
              <p class="text-sm text-gray-500 mt-1">ISBN: {{ book.isbn || 'N/A' }}</p>
              <p v-if="book.publisher" class="text-sm text-gray-500">出版社: {{ book.publisher }}</p>
              <button
                @click="addBook(book)"
                class="mt-2 bg-green-500 text-white px-4 py-1 rounded hover:bg-green-600"
              >
                追加
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'BookSearch',
  data() {
    return {
      searchQuery: '',
      books: [],
      loading: false,
      error: null,
    };
  },
  methods: {
    async searchBooks() {
      this.loading = true;
      this.error = null;

      try {
        const response = await fetch('/api/books/search', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${localStorage.getItem('token')}`,
          },
          body: JSON.stringify({
            query: this.searchQuery,
          }),
        });

        if (!response.ok) {
          throw new Error('検索に失敗しました');
        }

        const data = await response.json();
        this.books = data.items || [];
      } catch (err) {
        this.error = err.message;
      } finally {
        this.loading = false;
      }
    },
    async addBook(book) {
      try {
        const response = await fetch('/api/books', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${localStorage.getItem('token')}`,
          },
          body: JSON.stringify(book),
        });

        if (!response.ok) {
          const errorData = await response.json();
          if (errorData.errors?.isbn?.[0]?.includes('unique')) {
            alert('この本は既に登録されています');
          } else {
            throw new Error('本の追加に失敗しました');
          }
          return;
        }

        alert('本を追加しました');
      } catch (err) {
        alert(err.message);
      }
    },
  },
};
</script>
