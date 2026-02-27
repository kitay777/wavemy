<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import CategoryCard from '@/Components/CategoryCard.vue'
import { Link } from '@inertiajs/vue3'

const props = defineProps({
  categories: Array,
  joinedCategoryIds: Array,
})
</script>

<template>
  <AppLayout title="Dashboard">
    <template #header>
      <h2 class="font-semibold text-xl text-white leading-tight">注目のカテゴリーテーマ</h2>
    </template>

    <div class="py-6 px-4 bg-black min-h-screen">
        <div class="flex space-x-4 overflow-x-auto scrollbar-hide pb-4 snap-x snap-mandatory">

        <!-- カテゴリカード横並び -->
        <div
        v-for="cat in categories"
        :key="cat.id"
        class="flex-shrink-0 w-full sm:w-[320px] snap-start"
        >

          <!-- カテゴリカード -->
          <CategoryCard
            :id="cat.id"
            :thumbnail="`/storage/${cat.thumbnail_path}`"
            :supporter="cat.supporter"
            :summary="cat.summary"
            :bonus="cat.bonus"
            :fee="cat.fee"
            :current="cat.current"
            :max="cat.max"
            :is-joined="joinedCategoryIds.includes(cat.id)"
            :creator="cat.creator_name"
            :tag="cat.tag"
          />

          <!-- ツイート一覧（縦並び） -->
          <div v-if="cat.tweets?.length" class="mt-4 space-y-2">
            <Link
              v-for="tweet in cat.tweets"
              :key="tweet.id"
              :href="`/tweets?highlight=${tweet.id}`"
              class="block bg-gray-800 text-white p-3 rounded shadow hover:bg-gray-700 transition"
            >
              <div class="flex gap-2 items-center mb-1">
                <img :src="tweet.user_photo" class="w-8 h-8 rounded-full object-cover" />
                <div class="text-sm font-semibold">{{ tweet.user_name }}</div>
              </div>
              <div class="text-sm whitespace-pre-line">{{ tweet.text }}</div>
              <div class="text-xs text-gray-400 mt-1">{{ tweet.created_at }}</div>
            </Link>
          </div>

          <div v-else class="text-gray-500 text-sm mt-2">ツイートはまだありません</div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<style scoped>
.scrollbar-hide::-webkit-scrollbar {
  display: none;
}
.scrollbar-hide {
  -ms-overflow-style: none;
  scrollbar-width: none;
}
</style>
