<script setup>
import Chat from '@/Components/Chat.vue'

const props = defineProps({
    room: Object,
    currentUser: Object,
});
</script>

<template>
    <div class="p-6">
        <h1 class="text-xl font-bold mb-4">Chat Room: {{ room.name }}</h1>
        <Chat :room="room" :currentUser="currentUser" />
    </div>
</template>
