<script setup>
import axios from "axios";
import { nextTick, onMounted, ref, watch } from "vue";

const props = defineProps({
    room: {
        type: Object,
        required: true,
    },
    currentUser: {
        type: Object,
        required: true,
    },
});

const messages = ref([]);
const newMessage = ref("");
const messagesContainer = ref(null);
const isSomeoneTyping = ref(false);
const typingUserName = ref("");
const typingTimer = ref(null);

watch(messages, () => {
    nextTick(() => {
        messagesContainer.value.scrollTo({
            top: messagesContainer.value.scrollHeight,
            behavior: "smooth",
        });
    });
}, { deep: true });

const sendMessage = () => {
    if (newMessage.value.trim() !== "") {
        axios.post(`/chat-rooms/${props.room.id}/messages`, {
            message: newMessage.value,
        }).then((response) => {
            messages.value.push(response.data);
            newMessage.value = "";
        });
    }
};

const sendTypingEvent = () => {
    Echo.private(`chat-room.${props.room.id}`).whisper("typing", {
        userID: props.currentUser.id,
        userName: props.currentUser.name,
    });
};

onMounted(() => {
    axios.get(`/chat-rooms/${props.room.id}/messages`).then((response) => {
        messages.value = response.data;
    });

    Echo.private(`chat-room.${props.room.id}`)
        .listen("MessageSent", (response) => {
            messages.value.push(response.message);
        })
        .listenForWhisper("typing", (payload) => {
            if (payload.userID !== props.currentUser.id) {
                isSomeoneTyping.value = true;
                typingUserName.value = payload.userName;

                if (typingTimer.value) {
                    clearTimeout(typingTimer.value);
                }

                typingTimer.value = setTimeout(() => {
                    isSomeoneTyping.value = false;
                    typingUserName.value = "";
                }, 1000);
            }
        });
});
</script>

<template>
  <div class="chat-container relative min-h-screen pb-24">
    <!-- メッセージ表示 -->
    <div ref="messagesContainer" class="p-4 overflow-y-auto max-h-[calc(100vh-6rem)]">
      <div
        v-for="message in messages"
        :key="message.id"
        class="flex items-center mb-2"
      >
        <div
          v-if="message.sender_id === currentUser.id"
          class="p-2 ml-auto text-white bg-blue-500 rounded-lg"
        >
          {{ message.message }}
        </div>
        <div v-else class="p-2 mr-auto bg-gray-200 rounded-lg">
          <strong class="block">{{ message.sender.name }}</strong>
          {{ message.message }}
        </div>
      </div>
    </div>

    <!-- 入力フォーム -->
    <div class="fixed bottom-0 left-0 w-full bg-white p-3 border-t border-gray-300">
    <div class="flex items-end gap-2">
        <textarea
        v-model="newMessage"
        @keydown.shift.enter.prevent="sendMessage"
        rows="2"
        class="flex-1 resize-none px-2 py-1 border rounded-lg"
        placeholder="Type a message..."
        ></textarea>
        <button
        @click="sendMessage"
        class="px-4 py-2 text-white bg-blue-500 rounded-lg whitespace-nowrap"
        >
        Send
        </button>
    </div>

    <small v-if="isSomeoneTyping" class="mt-1 block text-sm text-gray-600">
        {{ typingUserName }} is typing...
    </small>
    </div>

  </div>
</template>



<style scoped>
.chat-container {
  display: flex;
  flex-direction: column;
  height: 100vh;
  overflow: hidden;
}
</style>
