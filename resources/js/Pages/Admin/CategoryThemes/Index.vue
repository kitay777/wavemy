<template>
  <div class="p-6">
    <h1 class="text-2xl font-bold mb-6">カテゴリーテーマ一覧</h1>
    <Link href="/admin/category-themes/create" class="bg-blue-500 text-white px-4 py-2 rounded mb-4 inline-block">
      ＋新規作成
    </Link>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <div v-for="theme in themes" :key="theme.id" class="bg-white shadow rounded-lg p-4">
        <img :src="'/storage/' + theme.thumbnail_path" alt="サムネイル" class="w-full h-40 object-cover rounded" />
        <h2 class="text-lg font-semibold mt-2">概要</h2>
        <p class="text-sm text-gray-600">{{ theme.summary }}</p>

        <h3 class="text-sm font-semibold mt-2">参加特典</h3>
        <p class="text-sm text-gray-600">{{ theme.bonus }}</p>

        <p class="mt-2 text-sm">参加費: {{ theme.fee }}</p>
        <p class="text-sm">最大人数: {{ theme.max_participants }}</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'

const props = defineProps({
  themes: Array,
})
</script>
