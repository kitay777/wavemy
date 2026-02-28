<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { ref, computed } from "vue";

const props = defineProps({
    user: Object,
    posts: Array,
    echoedPosts: Array,
    waves_started: Number,
    echoes_given: Number,
    karma_points: Number,
});

const activeTab = ref("posts");

const displayedPosts = computed(() => {
    if (activeTab.value === "posts") return props.posts;
    if (activeTab.value === "echoed") return props.echoedPosts;
    return [];
});
</script>

<template>
    <appLayout>
    <div class="min-h-screen bg-gray-100 pb-20 text-gray-700">
        <!-- Header -->
        <div class="text-center py-8">
            <!-- Avatar -->
            <div
                class="w-32 h-32 mx-auto rounded-full bg-gradient-to-br from-pink-300 to-teal-300 flex items-center justify-center text-white text-5xl font-bold shadow"
            >
                {{ user.name.charAt(0) }}
            </div>

            <!-- Name -->
            <div class="mt-4 text-2xl font-semibold">
                {{ user.name }}
            </div>

            <!-- Stats -->
            <div class="flex justify-center items-end mt-6 space-x-10">
                <div class="text-center">
                    <div class="text-2xl font-bold">
                        {{ waves_started }}
                    </div>
                    <div class="text-gray-500 text-sm">Waves Started</div>
                </div>

                <div class="text-center">
                    <div class="text-5xl font-bold leading-none">
                        {{ karma_points }}
                    </div>
                    <div class="text-gray-500 text-sm">Karma Points</div>
                </div>

                <div class="text-center">
                    <div class="text-2xl font-bold">
                        {{ echoes_given }}
                    </div>
                    <div class="text-gray-500 text-sm">Echoes Given</div>
                </div>
            </div>
        </div>

        <!-- Tabs -->
        <div class="flex justify-around border-b bg-white py-3">
            <button
                @click="activeTab = 'posts'"
                :class="[
                    'font-semibold pb-2',
                    activeTab === 'posts'
                        ? 'border-b-4 border-gradient-to-r from-pink-400 to-teal-400'
                        : 'text-gray-400',
                ]"
            >
                My Posts
            </button>

            <button
                @click="activeTab = 'echoed'"
                :class="[
                    'font-semibold pb-2',
                    activeTab === 'echoed'
                        ? 'border-b-4 border-pink-400'
                        : 'text-gray-400',
                ]"
            >
                Echoed
            </button>

            <button class="text-gray-400 font-semibold">Purchased</button>
        </div>

        <!-- Posts -->
        <div class="space-y-6 px-4 mt-6">
            <div
                v-for="post in displayedPosts"
                :key="post.id"
                class="bg-white rounded-3xl p-6 shadow"
            >
                <p class="text-gray-700 mb-3">
                    {{ post.content }}
                </p>

                <img
                    v-if="post.image"
                    :src="`/storage/${post.image}`"
                    class="w-full h-40 object-cover rounded-xl mb-3"
                />

                <div class="flex justify-between text-sm text-gray-500">
                    <span>~ {{ post.echo_count ?? 0 }} Echoes</span>
                    <span>{{ post.status }}</span>
                </div>
            </div>
        </div>
    </div>
    </appLayout>
</template>
