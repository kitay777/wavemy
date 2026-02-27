<template>
  <div class="max-w-xl mx-auto p-6">
    <h1 class="text-xl font-bold mb-4">カテゴリーテーマ登録</h1>
    <form @submit.prevent="submit" class="space-y-4">
      <div>
        <label class="block mb-1 font-semibold">サムネイル画像</label>
        <input type="file" @change="handleFile" class="w-full" />
      </div>

      <div>
        <label class="block mb-1 font-semibold">概要 (128文字まで)</label>
        <input v-model="form.summary" maxlength="128" class="w-full border rounded px-3 py-2" />
      </div>

      <div>
        <label class="block mb-1 font-semibold">参加特典 (256文字まで)</label>
        <textarea v-model="form.bonus" maxlength="256" class="w-full border rounded px-3 py-2" rows="3" />
      </div>

      <div>
        <label class="block mb-1 font-semibold">参加費 (128文字まで)</label>
        <input v-model="form.fee" maxlength="128" class="w-full border rounded px-3 py-2" />
      </div>

      <div>
        <label class="block mb-1 font-semibold">最大人数</label>
        <input v-model.number="form.max_participants" type="number" min="1" class="w-full border rounded px-3 py-2" />
      </div>

      <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">登録</button>
    </form>
  </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'

const form = useForm({
  thumbnail: null,
  summary: '',
  bonus: '',
  fee: '',
  max_participants: null,
})

const handleFile = (e) => {
  form.thumbnail = e.target.files[0]
}

const submit = () => {
  form.post('/admin/category-themes')
}
</script>
