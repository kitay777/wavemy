<script setup>
import { ref, computed } from "vue";
import axios from "axios";
import AppLayout from "@/Layouts/AppLayout.vue";
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    categories: Array,
    posts: Array,
});

const activeCategory = ref("All");

const filteredPosts = computed(() => {
    if (activeCategory.value === "All") return props.posts;
    return props.posts.filter((p) => p.category_id === activeCategory.value);
});

const echoPost = async (post) => {
    const res = await axios.post(`/posts/${post.id}/echo`);
    post.echo_count = res.data.echo_count;
    post.is_echoed = res.data.is_echoed;
};
</script>

<template>
    <AppLayout>
        <div class="pb-20">
            <!-- Category Slider -->
            <div class="flex overflow-x-auto space-x-4 px-4 pb-4 pt-4">
                <button
                    @click="activeCategory = 'All'"
                    :class="[
                        'px-6 py-2 rounded-full whitespace-nowrap transition',
                        activeCategory === 'All'
                            ? 'bg-gradient-to-r from-pink-400 to-teal-400 text-white'
                            : 'bg-gray-200 text-gray-600',
                    ]"
                >
                    All
                </button>

                <button
                    v-for="cat in categories"
                    :key="cat.id"
                    @click="activeCategory = cat.id"
                    :class="[
                        'px-6 py-2 rounded-full whitespace-nowrap transition',
                        activeCategory === cat.id
                            ? 'bg-gradient-to-r from-pink-400 to-teal-400 text-white'
                            : 'bg-gray-200 text-gray-600',
                    ]"
                >
                    {{ cat.name }}
                </button>
            </div>

            <!-- Posts -->
            <div class="space-y-6 px-4">
                <div
                    v-for="post in filteredPosts"
                    :key="post.id"
                    class="bg-white rounded-3xl p-6 shadow-md text-gray-700"
                >
                    <div class="font-semibold text-lg">
                      <a :href="`/profile/${post.user_id}`" class="text-teal-500">
                        {{ post.user.name }}
                        </a>
                    </div>

                    <p class="mt-3 text-gray-600">
                        {{ post.content }}
                    </p>
                    <img
                        v-if="post.image"
                        :src="`${post.image}`"
                        class="w-full h-40 object-cover rounded-xl"
                    />
                    <div class="mt-4 text-teal-500 font-semibold">
                        ~ {{ post.echo_count }} Echoes
                    </div>

                    <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                        <div
                            class="bg-gradient-to-r from-pink-400 to-purple-400 h-2 rounded-full"
                            :style="{
                                width:
                                    (post.echo_count / post.goal) * 100 + '%',
                            }"
                        ></div>
                    </div>

                    <button
                        @click="echoPost(post)"
                        :class="[
                            'mt-6 w-full py-4 rounded-full text-lg font-semibold transition',
                            post.is_echoed
                                ? 'border-2 border-purple-400 text-purple-500 bg-white'
                                : 'bg-gradient-to-r from-pink-400 to-teal-400 text-white',
                        ]"
                    >
                        {{ post.is_echoed ? "Echoed" : "Echo" }}
                    </button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
