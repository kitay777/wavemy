<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { useForm } from '@inertiajs/vue3'

const form = useForm({
  text: ''
})

const submit = () => {
  form.post('/tweet', {
    onSuccess: () => {
      form.reset()
    }
  })
}
</script>

<template>
  <AppLayout title="ツイート投稿">
    <template #header>
      <h2 class="font-semibold text-xl text-white leading-tight">ツイート投稿</h2>
    </template>

    <div class="py-12 bgーblack">
      <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-black text-white overflow-hidden shadow-xl sm:rounded-lg p-6">
          <form @submit.prevent="submit" class="space-y-4">
            <textarea
              v-model="form.text"
              class="bg-black text-white w-full h-32 border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
              placeholder="いま何してる？（@タグでカテゴリ紐付け）"
            ></textarea>

            <div v-if="form.errors.text" class="text-red-600 text-sm">
              {{ form.errors.text }}
            </div>

            <div class="flex justify-end">
              <button
                type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
              >
                投稿する
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
