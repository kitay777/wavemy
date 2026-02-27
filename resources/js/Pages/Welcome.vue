<script setup>
import { Link } from '@inertiajs/vue3'
</script>

<template>
  <div class="min-h-screen flex flex-col items-center justify-between
              bg-gradient-to-br from-gray-100 via-gray-200 to-gray-300
              relative overflow-hidden">

    <!-- 背景ぼかしサークル -->
    <div class="absolute w-96 h-96 rounded-full 
                bg-gradient-to-br from-pink-300 via-purple-300 to-teal-300
                opacity-30 blur-3xl top-10"></div>

    <!-- メイン -->
    <div class="flex flex-col items-center mt-24 z-10">

      <!-- ロゴ -->
      <div class="w-40 h-40 rounded-full
                  bg-gradient-to-br from-pink-400 via-purple-400 to-teal-400
                  flex items-center justify-center text-4xl font-bold text-black shadow-lg">
        Logo
      </div>

      <!-- タイトル -->
      <h1 class="text-6xl font-bold mt-10 text-gray-700">
        Wavemy
      </h1>

      <!-- サブテキスト -->
      <p class="mt-6 text-xl text-gray-500 text-center leading-relaxed">
        Wants become waves.<br>
        Waves reshape the world.
      </p>
    </div>

    <!-- 下部ボタンエリア -->
    <div class="w-full max-w-md px-6 mb-20 z-10">

      <!-- Emailボタン -->
      <Link href="/register"
        class="w-full flex items-center justify-center
               bg-gradient-to-r from-pink-400 via-purple-400 to-teal-400
               text-white font-semibold text-lg py-4 rounded-full
               shadow-md hover:opacity-90 transition">

        <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" stroke-width="2"
             viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round"
                d="M16 12H8m8 4H8m8-8H8m-2 12h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
        </svg>

        Sign Up with Email
      </Link>

      <!-- 区切り -->
      <div class="flex items-center my-6">
        <div class="flex-grow border-t border-gray-300"></div>
        <span class="px-4 text-gray-400">or continue with</span>
        <div class="flex-grow border-t border-gray-300"></div>
      </div>

      <!-- Apple -->
      <Link href="/auth/apple"
        class="w-full bg-gray-200 text-gray-700
               py-4 rounded-full text-lg font-semibold
               text-center mb-4 hover:bg-gray-300 transition">
        Continue with Apple
      </Link>

      <!-- Google -->
      <Link href="/auth/google"
        class="w-full bg-gray-200 text-gray-700
               py-4 rounded-full text-lg font-semibold
               text-center hover:bg-gray-300 transition">
        Continue with Google
      </Link>

      <!-- ログイン -->
      <div class="text-center mt-6 text-gray-500">
        Already have an account?
        <Link href="/login" class="text-teal-500 font-semibold ml-1">
          Log In
        </Link>
      </div>

      <!-- フッター -->
      <div class="text-center text-sm text-gray-400 mt-6">
        Terms of Service ・ Privacy Policy
      </div>

    </div>
  </div>
</template>