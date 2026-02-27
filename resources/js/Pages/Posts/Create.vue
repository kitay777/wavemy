<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { useForm } from '@inertiajs/vue3'

const props = defineProps({
    categories: Array
})

const form = useForm({
    content: '',
    category_id: '',
    goal: 1000,
    image: null
})

const submit = () => {
    form.post(route('posts.store'))
}
</script>

<template>
<AppLayout>
<div class="min-h-screen bg-gray-100 p-6">

    <h1 class="text-2xl font-bold mb-6">Start a Wave 🌊</h1>

    <form @submit.prevent="submit"
          class="space-y-6 bg-white p-6 rounded-3xl shadow text-gray-800">

        <!-- Content -->
        <div>
            <label class="block font-semibold mb-1">What do you want?</label>
            <textarea v-model="form.content"
                      rows="4"
                      class="w-full border rounded-xl p-3 bg-white"></textarea>
        </div>

        <!-- Category -->
        <div>
            <label class="block font-semibold mb-1">Category</label>
            <select v-model="form.category_id"
                    class="w-full border rounded-xl p-3 bg-white">
                <option value="">Select</option>
                <option v-for="cat in categories"
                        :key="cat.id"
                        :value="cat.id">
                    {{ cat.name }}
                </option>
            </select>
        </div>

        <!-- Goal -->
        <div>
            <label class="block font-semibold mb-1">Goal Echoes</label>
            <input v-model="form.goal"
                   type="number"
                   class="w-full border rounded-xl p-3 bg-white"/>
        </div>

        <!-- Image -->
        <div>
            <label class="block font-semibold mb-1">Image</label>
            <input type="file"
                   @change="e => form.image = e.target.files[0]" />
        </div>

        <!-- Submit -->
        <button type="submit"
                class="w-full py-4 rounded-full
                       bg-gradient-to-r from-pink-400 to-teal-400
                       text-white font-semibold">
            Launch Wave
        </button>

    </form>

</div>
</AppLayout>
</template>