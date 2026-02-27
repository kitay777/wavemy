<script setup>
import { useForm } from '@inertiajs/vue3'

const props = defineProps({
    categories: Array,
    posts: Array
})

const form = useForm({
    name: '',
    brand: '',
    price: '',
    category_id: '',
    post_id: '',
    image: null
})

const submit = () => {
    form.post(route('products.store'))
}
</script>

<template>
<div class="min-h-screen bg-gray-100 p-6">

    <h1 class="text-2xl font-bold mb-6">Create Product</h1>

    <form @submit.prevent="submit" class="space-y-6 bg-white p-6 rounded-3xl shadow text-gray-700 max-w-lg mx-auto">

        <!-- 商品名 -->
        <div>
            <label class="block font-semibold mb-1">Product Name</label>
            <input v-model="form.name"
                   type="text"
                   class="w-full border rounded-xl p-3"/>
        </div>

        <!-- ブランド -->
        <div>
            <label class="block font-semibold mb-1">Brand</label>
            <input v-model="form.brand"
                   type="text"
                   class="w-full border rounded-xl p-3"/>
        </div>

        <!-- 価格 -->
        <div>
            <label class="block font-semibold mb-1">Price (¥)</label>
            <input v-model="form.price"
                   type="number"
                   class="w-full border rounded-xl p-3"/>
        </div>

        <!-- カテゴリ -->
        <div>
            <label class="block font-semibold mb-1">Category</label>
            <select v-model="form.category_id"
                    class="w-full border rounded-xl p-3">
                <option value="">Select</option>
                <option v-for="cat in categories"
                        :key="cat.id"
                        :value="cat.id">
                    {{ cat.name }}
                </option>
            </select>
        </div>

        <!-- 紐付けWant -->
        <div>
            <label class="block font-semibold mb-1">Link Want (Optional)</label>
            <select v-model="form.post_id"
                    class="w-full border rounded-xl p-3">
                <option value="">None</option>
                <option v-for="post in posts"
                        :key="post.id"
                        :value="post.id">
                    {{ post.content.substring(0, 50) }}
                </option>
            </select>
        </div>

        <!-- 画像 -->
        <div>
            <label class="block font-semibold mb-1">Image</label>
            <input type="file"
                   @change="e => form.image = e.target.files[0]" />
        </div>

        <!-- 送信 -->
        <button type="submit"
                class="w-full py-4 rounded-full
                       bg-gradient-to-r from-pink-400 to-teal-400
                       text-white font-semibold">
            Save Product
        </button>

    </form>

</div>
</template>