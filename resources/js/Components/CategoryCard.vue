<script setup>
import { ref, computed } from 'vue'
import { router } from '@inertiajs/vue3'
import { useToast } from 'vue-toastification' // ← インストール済み前提

const toast = useToast()

const props = defineProps({
  id: Number,
  thumbnail: String,
  supporter: String,
  summary: String,
  bonus: String,
  fee: String,
  current: Number,
  max: Number,
  isJoined: Boolean,
  creator: String,
  tag: String,
})

const joined = ref(props.isJoined)
const participantCount = ref(props.current)

const progressPercent = computed(() => {
  return Math.min(100, Math.round((participantCount.value / props.max) * 100));
})

const join = () => {
  router.post(`/category-themes/${props.id}/join`, {}, {
    onSuccess: () => {
      joined.value = true
      participantCount.value += 1
      toast.success('参加が完了しました！')
    },
    onError: () => {
      toast.error('参加に失敗しました')
    }
  })
}
</script>

<template>
  <div class="bg-gray-900 text-white p-4 rounded-xl shadow-lg w-full sm:w-[300px] shrink-0 transition-transform duration-300 hover:scale-105">
    <p class="text-sm text-gray-300">Supported by {{ creator }} {{ tag }}</p>
    <div class="flex items-start mt-2">
      <img :src="thumbnail" alt="Thumbnail" class="w-20 h-20 rounded bg-gray-200 object-contain" />
      <div class="ml-4 flex-1">
        <h2 class="text-lg font-semibold text-white">概要</h2>
        <p class="text-sm text-white whitespace-pre-line">{{ summary }}</p>
        <h3 class="mt-2 text-sm font-semibold text-white">参加特典</h3>
        <p class="text-sm text-gray-100 whitespace-pre-line">{{ bonus }}</p>
        <h3 class="mt-2 text-sm font-semibold text-white">参加費</h3>
        <p class="text-sm">{{ fee }}</p>
      </div>
    </div>

    <template v-if="joined">
      <p class="mt-3 text-center text-green-400 font-bold">参加済み</p>
    </template>
    <template v-else>
      <button @click="join" class="mt-3 w-full bg-sky-500 hover:bg-sky-600 text-white font-semibold py-2 rounded">
        コミュニティーに参加する！
      </button>
    </template>

    <div class="mt-2 bg-gray-700 rounded-full h-4 relative">
      <div class="bg-orange-400 h-4 rounded-full absolute top-0 left-0" :style="{ width: progressPercent + '%' }"></div>
      <div class="absolute inset-0 flex justify-center items-center text-xs text-white font-bold">
        {{ participantCount }}/{{ max }}
      </div>
    </div>
  </div>
</template>
