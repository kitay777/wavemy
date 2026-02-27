<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'
import { Link } from '@inertiajs/vue3'
import { router } from '@inertiajs/vue3'


const retweet = (text) => {
  const rtText = `RT: ${text}`
  if (confirm('このツイートをリツイートしますか？')) {
    router.post('/tweet', { text: rtText }, {
      onSuccess: () => {
        alert('リツイートしました！')
      },
      onError: () => {
        alert('リツイートに失敗しました')
      }
    })
  }
}


const follow = (userId) => {
  if (confirm('このユーザーをフォローしますか？')) {
    router.post(`/follow/${userId}`, {}, {
      onSuccess: () => alert('フォローしました！'),
      onError: () => alert('フォローに失敗しました'),
    })
  }
}

const props = defineProps({
  tweets: Array,
  followingIds: Array,
})


const page = usePage()

const highlightId = computed(() => {
  const url = page.url // 例: "/tweets?highlight=12"
  const query = new URLSearchParams(url.split('?')[1] || '')
  return Number(query.get('highlight')) || null
})

const selectedTweet = computed(() =>
  props.tweets.find(tweet => tweet.id === highlightId.value)
)


const tweetsBySameUser = computed(() => {
  if (!selectedTweet.value) return []
  return props.tweets
    .filter(t => t.user_name === selectedTweet.value.user_name && t.id !== selectedTweet.value.id)
    .sort((a, b) => new Date(b.created_at) - new Date(a.created_at))
})

const unfollow = (userId) => {
  if (confirm('フォローを解除しますか？')) {
    router.post(`/unfollow/${userId}`, {}, {
      onSuccess: () => {
        alert('フォローを解除しました！')
        // オプション：状態更新
        const index = props.followingIds.indexOf(userId)
        if (index !== -1) props.followingIds.splice(index, 1)
      },
      onError: () => alert('フォロー解除に失敗しました')
    })
  }
}

const react = (tweetId, type) => {
  router.post(`/tweets/${tweetId}/react`, { type }, {
    preserveScroll: true,
    onSuccess: () => {
      const tweet = props.tweets.find(t => t.id === tweetId)
      if (tweet) {
        const previous = tweet.my_reaction

        tweet.my_reaction = type
        if (type === 'like') {
          if (previous === 'dislike') tweet.dislike_count -= 1
          if (previous !== 'like') tweet.like_count += 1
        } else if (type === 'dislike') {
          if (previous === 'like') tweet.like_count -= 1
          if (previous !== 'dislike') tweet.dislike_count += 1
        }
      }
    }
  })
}




</script>

<template>
  <AppLayout title="ツイート一覧">
    <template #header>
      <div class="flex items-center gap-4">
        <Link href="/dashboard" class="text-white text-sm underline">← 戻る</Link>
        <h2 class="font-semibold text-xl text-white leading-tight">ツイート一覧</h2>
      </div>
    </template>

    <div class="py-6 px-4 bg-black min-h-screen">
      <!-- 選択されたツイート -->
      <div v-if="selectedTweet" class="max-w-2xl mx-auto p-4 mb-6 bg-gray-900 text-white rounded shadow">
        <h3 class="font-bold text-lg mb-2">選択されたツイート</h3>
        <div class="flex items-start gap-4">
          <img :src="selectedTweet.user_photo" class="w-12 h-12 rounded-full object-cover" />
          <div>
            <div class="font-semibold">{{ selectedTweet.user_name }}</div>
            <div class="whitespace-pre-line">{{ selectedTweet.text }}</div>
            <div class="text-xs text-gray-400 mt-1">{{ selectedTweet.created_at }}</div>
          </div>
        </div>
        <div class="mt-4 flex gap-4">
        <!-- フォロー状態で分岐 -->
        <div v-if="selectedTweet && selectedTweet.user_id !== $page.props.auth.user.id">
          <!-- フォローしていない -->
          <button
            v-if="!props.followingIds.includes(selectedTweet.user_id)"
            class="bg-blue-600 hover:bg-blue-700 text-white text-sm px-4 py-2 rounded"
            @click="follow(selectedTweet.user_id)"
          >
            フォロー
          </button>

          <!-- フォロー済み -->
          <button
            v-else
            class="bg-gray-500 hover:bg-gray-600 text-white text-sm px-4 py-2 rounded"
            @click="unfollow(selectedTweet.user_id)"
          >
            フォロー中（解除）
          </button>
          <div class="flex gap-2 mt-2">
            <button
              :class="['px-2 py-1 rounded', selectedTweet.my_reaction === 'like' ? 'bg-green-600' : 'bg-gray-600']"
              @click="react(selectedTweet.id, 'like')"
            >
              👍 {{ selectedTweet.like_count }}
            </button>

            <button
              :class="['px-2 py-1 rounded', selectedTweet.my_reaction === 'dislike' ? 'bg-red-600' : 'bg-gray-600']"
              @click="react(selectedTweet.id, 'dislike')"
            >
              👎 {{ selectedTweet.dislike_count }}
            </button>
          </div>


        </div>




        

        <button
          class="bg-green-600 hover:bg-green-700 text-white text-sm px-4 py-2 rounded"
          @click="retweet(selectedTweet.text)"
        >
          リツイート
        </button>
      </div>


      </div>

      <!-- 全ツイート一覧 -->
      <div class="max-w-2xl mx-auto space-y-1">
        <div
          v-for="tweet in tweets"
          :key="tweet.id"
          class="flex items-start gap-4 bg-gray-800 text-white p-4 rounded-lg shadow cursor-pointer hover:bg-gray-700 transition"
          @click="$inertia.replace(route('tweets.index', { highlight: tweet.id }))"
        >
          <img :src="tweet.user_photo" class="w-12 h-12 rounded-full object-cover" />
          <div class="flex-1">
            <div class="text-sm font-semibold">{{ tweet.user_name }}</div>
            <div class="text-sm whitespace-pre-line">{{ tweet.text }}</div>
            <div class="text-xs text-gray-400 mt-1">{{ tweet.created_at }}</div>
          </div>
        </div>
      </div>

      <!-- 他のツイート -->
      <div v-if="tweetsBySameUser.length" class="max-w-2xl mx-auto mt-10 space-y-1">
        <h3 class="text-lg font-bold text-white mb-2">{{ selectedTweet.user_name }}さんの他のツイート</h3>
        <div
          v-for="tweet in tweetsBySameUser"
          :key="tweet.id"
          class="flex items-start gap-4 bg-gray-700 text-white p-4 rounded-lg shadow"
        >
          <img :src="tweet.user_photo" class="w-10 h-10 rounded-full object-cover" />
          <div>
            <div class="text-sm">{{ tweet.text }}</div>
            <div class="text-xs text-gray-400 mt-1">{{ tweet.created_at }}</div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
