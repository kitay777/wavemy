<script setup>
import { ref } from 'vue'
import { Head, Link, router, usePage } from '@inertiajs/vue3'

const page = usePage()
const user = page.props.auth.user

const isOpen = ref(false)

const toggleMenu = () => {
  isOpen.value = !isOpen.value
}

const logout = () => {
  router.post(route('logout'))
}
</script>

<template>
  <div class="relative min-h-screen bg-gray-100 overflow-hidden">

    <!-- 🔹 HEADER -->
    <header class="flex justify-between items-center px-6 py-4 bg-white shadow-sm z-30 relative text-gray-700">
      <Link :href="route('dashboard')" class="text-xl font-bold">
        Wavemy
      </Link>

      <button @click="toggleMenu" class="text-2xl">
        ☰
      </button>
    </header>

    <!-- 🔹 PAGE CONTENT -->
    <main class="pb-20">
      <slot />
    </main>

    <!-- 🔹 OVERLAY -->
    <div
      v-if="isOpen"
      @click="toggleMenu"
      class="fixed inset-0 bg-black/40 z-40 transition"
    ></div>

    <!-- 🔹 SLIDE MENU -->
    <aside
      :class="[
        'fixed top-0 right-0 h-full w-72 bg-white shadow-2xl z-50 transform transition-transform duration-300',
        isOpen ? 'translate-x-0' : 'translate-x-full'
      ]"
    >
      <!-- User Header -->
      <div class="p-6 bg-gradient-to-r from-pink-400 to-teal-400 text-white">
        <div class="text-lg font-bold">
          {{ user.name }}
        </div>
        <div class="text-sm opacity-80">
          {{ user.email }}
        </div>
      </div>

      <!-- Menu Links -->
      <div class="p-6 space-y-4 text-gray-700">

        <Link href="/dashboard" class="block hover:text-teal-500">
          Dashboard
        </Link>

        <Link href="/explore" class="block hover:text-teal-500">
          Explore
        </Link>

        <Link href="/market" class="block hover:text-teal-500">
          Market
        </Link>

        <Link href="/posts/create" class="block hover:text-teal-500">
          Start a Wave
        </Link>

        <Link href="/products/create" class="block hover:text-teal-500">
          Add Product
        </Link>

        <hr>

        <button @click="logout"
                class="text-red-500">
          Logout
        </button>

      </div>
    </aside>

    <!-- 🔹 BOTTOM NAVIGATION -->
    <nav class="fixed bottom-0 left-0 w-full bg-white shadow-inner border-t z-30">
      <div class="flex justify-around items-center h-14">

        <Link :href="route('dashboard')" class="flex flex-col items-center text-xs text-gray-600">
          🏠
        </Link>

        <Link href="/explore" class="flex flex-col items-center text-xs text-gray-600">
          🔍
        </Link>

        <Link href="/posts/create" class="flex flex-col items-center text-xs text-gray-600">
          ➕
        </Link>

        <Link href="/market" class="flex flex-col items-center text-xs text-gray-600">
          🛒
        </Link>

        <Link href="/profile" class="flex flex-col items-center text-xs text-gray-600">
          👤
        </Link>

      </div>
    </nav>

  </div>
</template>