<script setup>
import { ref, computed } from 'vue'

const props = defineProps({
    categories: Array,
    products: Array
})

const activeCategory = ref('All')

const filteredProducts = computed(() => {
    if (activeCategory.value === 'All') return props.products
    return props.products.filter(
        p => p.category_id === activeCategory.value
    )
})
</script>

<template>
<div class="min-h-screen bg-gray-100 pb-20 text-gray-700">

    <!-- Header -->
    <div class="flex justify-between items-center p-6">
        <h1 class="text-2xl font-bold">Market</h1>
    </div>

    <!-- Category slider -->
<div class="flex overflow-x-auto space-x-4 px-4 pb-6">

    <!-- All -->
    <button
        @click="activeCategory = 'All'"
        :class="[
            'px-6 py-2 rounded-full whitespace-nowrap transition',
            activeCategory === 'All'
                ? 'bg-gradient-to-r from-pink-400 to-teal-400 text-white'
                : 'bg-gray-200 text-gray-600'
        ]">
        All
    </button>

    <!-- Category -->
    <button
        v-for="cat in categories"
        :key="cat.id"
        @click="activeCategory = cat.id"
        :class="[
            'px-6 py-2 rounded-full whitespace-nowrap transition',
            activeCategory === cat.id
                ? 'bg-gradient-to-r from-pink-400 to-teal-400 text-white'
                : 'bg-gray-200 text-gray-600'
        ]">
        {{ cat.name }}
    </button>

</div>

    <!-- Products grid -->
    <div class="grid grid-cols-2 gap-6 px-4">

        <div v-for="product in filteredProducts"
             :key="product.id"
             class="bg-white rounded-3xl shadow-md overflow-hidden">

<div class="h-40 bg-gray-100 overflow-hidden">

    <img
        v-if="product.image"
        :src="`/storage/${product.image}`"
        class="w-full h-full object-cover"
    />

    <div v-else
         class="h-full flex items-center justify-center
                bg-gradient-to-br from-pink-200 to-purple-200 text-5xl">
        📦
    </div>

</div>

            <div class="p-4">
                <div class="font-semibold text-lg">
                    {{ product.name }}
                </div>

                <div class="text-gray-500 text-sm">
                    by {{ product.brand }}
                </div>

                <div class="flex justify-between items-center mt-3">
                    <div class="text-xl font-bold">
                        ¥{{ product.price.toLocaleString() }}
                    </div>

                    <button class="px-4 py-2 rounded-full
                                   bg-gradient-to-r from-pink-400 to-teal-400
                                   text-white text-sm">
                        Buy
                    </button>
                </div>
            </div>
        </div>

    </div>

</div>
</template>