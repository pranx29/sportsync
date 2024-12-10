<script setup>
import { nextTick, ref, watch } from "vue";
import { User, Send } from "lucide-vue-next";
import { Input } from "@/Components/ui/input";
import { Button } from "@/Components/ui/button";
import { Card, CardHeader, CardTitle } from "@/Components/ui/card";
import { router, usePage } from "@inertiajs/vue3";
import { defineProps, onMounted } from "vue";
import Echo from "laravel-echo";

const props = defineProps({
    messages: {
        type: Array,
        required: true,
    },
    group_id: {
        type: Number,
        required: true,
    },
});

const newMessage = ref("");
const messageContainer = ref(null);

watch(
    props.messages,
    () => {
        nextTick(() => {
            messageContainer.value.scrollTo({
                top: messageContainer.value.scrollHeight,
                behavior: "smooth",
            });
        });
    },
    {
        deep: true,
        immediate: true,
    }
);

onMounted(() => {
    window.Echo.channel(`chat.${props.group_id}`).listen(
        "MessageSent",
        (event) => {
            props.messages.push(event.message);
        }
    );
});

const sendMessage = () => {
    // Send the message to the server
    router.post(
        route("groups.messages.store", {
            group: props.group_id,
        }),
        {
            message: newMessage.value.trim(),
        },
        {
            preserveScroll: true,
        }
    );

    newMessage.value = "";
};
</script>

<template>
    <!-- Chat Area -->
    <Card class="flex flex-col h-[calc(100vh-28vh)]">
        <CardHeader>
            <CardTitle>Chat</CardTitle>
        </CardHeader>
        <div
            ref="messageContainer"
            class="flex-1 overflow-y-auto p-4 space-y-4"
        >
            <div
                v-for="message in props.messages"
                :key="message.id"
                class="flex"
                :class="{
                    'justify-end':
                        message.user.email === $page.props.auth.user.email,
                }"
            >
                <div
                    class="max-w-[80%] px-4 py-2 rounded-lg"
                    :class="
                        message.user.email === $page.props.auth.user.email
                            ? 'bg-primary text-primary-foreground'
                            : 'bg-muted'
                    "
                >
                    <p class="text-sm">{{ message.message }}</p>
                    <div class="flex items-center justify-between gap-4">
                        <span class="text-xs opacity-75 block">
                            {{
                                message.user.email ===
                                $page.props.auth.user.email
                                    ? "You"
                                    : `${message.user.first_name} ${message.user.last_name}`
                            }}
                        </span>
                        <span class="text-xs opacity-50">
                            {{
                                new Date(message.created_at).toLocaleTimeString(
                                    [],
                                    {
                                        hour: "2-digit",
                                        minute: "2-digit",
                                        hour12: false,
                                    }
                                )
                            }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="p-4 border-t border-border">
            <form @submit.prevent="sendMessage" class="flex space-x-2">
                <Input
                    v-model="newMessage"
                    type="message"
                    placeholder="Type a message..."
                    class="flex-1"
                />
                <Button type="submit">
                    Send
                    <Send class="ml-2 h-4 w-4" />
                </Button>
            </form>
        </div>
    </Card>
</template>


