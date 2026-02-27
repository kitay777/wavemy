<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    categories: Array,
    trending: Array,
    creators: Array
})
</script>

<template>
<AppLayout>
<div class="min-h-screen bg-gray-100 pb-24 text-gray-700">

    <!-- Header -->
    <div class="p-6">
        <h1 class="text-3xl font-bold">Explore</h1>

        <div class="mt-4 bg-white rounded-full px-4 py-3 flex items-center shadow">
            <input
                type="text"
                placeholder="Search Wants, Products, Users..."
                class="flex-1 bg-transparent outline-none text-gray-700"
            />
            <span class="text-purple-400">⚙</span>
        </div>
    </div>

    <!-- Browse Categories -->
    <div class="px-6 mt-6">
        <h2 class="text-xl font-semibold mb-4">Browse Categories</h2>

        <div class="grid grid-cols-2 gap-4">
            <div v-for="cat in categories"
                 :key="cat.id"
                 class="rounded-3xl p-6 text-center shadow
                        bg-gradient-to-br from-pink-200 to-teal-200">

                <div class="text-4xl mb-3">📦</div>
                <div class="font-semibold text-gray-700">
                    {{ cat.name }}
                </div>
            </div>
        </div>
    </div>

    <!-- Trending Waves -->
    <div class="px-6 mt-10">
        <h2 class="text-xl font-semibold mb-4">
            Trending Waves 🔥
        </h2>

<div class="flex space-x-4 overflow-x-auto">

  <div v-for="post in trending"
       :key="post.id"
       class="min-w-[260px] bg-white rounded-3xl shadow p-4">

    <div class="h-32 rounded-xl
                bg-gradient-to-br from-pink-200 to-purple-200 mb-3">
    </div>

    <div class="font-semibold">
      {{ post.content.substring(0, 40) }}
    </div>

    <div class="mt-2 text-teal-500 text-sm">
      ~ {{ post.echo_count }} Echoes
    </div>

    <button
      class="mt-3 px-4 py-2 rounded-full text-white text-sm
             bg-gradient-to-r from-pink-400 to-teal-400">
      Echo
    </button>

  </div>

</div>
    </div>

    <!-- Suggested Creators -->
    <div class="px-6 mt-10">
        <h2 class="text-xl font-semibold mb-4">
            Suggested Creators
        </h2>

        <div v-for="creator in creators"
             :key="creator.id"
             class="flex items-center justify-between bg-white rounded-3xl p-4 shadow mb-4">

            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 rounded-full
                            bg-gradient-to-br from-pink-300 to-purple-300
                            flex items-center justify-center text-white">
                    {{ creator.name.charAt(0) }}
                </div>

                <div>
                    <div class="font-semibold">{{ creator.name }}</div>
                    <div class="text-sm text-gray-500">
                        {{ creator.posts_count }} Waves Started
                    </div>
                </div>
            </div>

            <button
                class="px-4 py-2 rounded-full text-white
                       bg-gradient-to-r from-pink-400 to-teal-400 text-sm">
                Follow
            </button>

        </div>
    </div>

</div>
</AppLayout>
</template>