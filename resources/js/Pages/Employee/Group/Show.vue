<template>
    <AuthenticatedLayout>
        <div class="container mx-auto p-4 space-y-4">
            <h1 class="text-2xl font-bold text-foreground">
                {{ $page.props.group.name }}
            </h1>
            <div class="grid lg:grid-cols-[300px_1fr] gap-4">
                <!-- Members List -->
                <Card class="h-[calc(100vh-12rem)] flex flex-col">
                    <CardHeader>
                        <CardTitle>Members</CardTitle>
                    </CardHeader>
                    <ScrollArea class="flex-1 overflow-y-auto p-4">
                        <ul class="space-y-4">
                            <li class="flex items-center space-x-3">
                                <Avatar>
                                    <AvatarImage
                                        :src="`https://api.dicebear.com/6.x/initials/svg?seed=${$page.props.leader.first_name}+${$page.props.leader.last_name}&fontSize=32`"
                                    />
                                    <AvatarFallback>
                                        <User class="w-4 h-4" />
                                    </AvatarFallback>
                                </Avatar>
                                <div class="w-full">
                                    <div class="flex justify-between">
                                        <h3 class="font-medium text-sm">
                                            {{ $page.props.leader.first_name }}
                                            {{ $page.props.leader.last_name }}
                                        </h3>
                                        <Badge variant="outline">Leader</Badge>
                                    </div>
                                    <p class="text-xs text-muted-foreground">
                                        {{ $page.props.leader.email }}
                                    </p>
                                </div>
                            </li>
                            <li
                                v-for="member in $page.props.members"
                                :key="member.id"
                                class="flex items-center space-x-3"
                            >
                                <Avatar>
                                    <AvatarImage
                                        :src="`https://api.dicebear.com/6.x/initials/svg?seed=${member.first_name}+${member.last_name}&fontSize=32`"
                                    />
                                    <AvatarFallback>
                                        <User class="w-4 h-4" />
                                    </AvatarFallback>
                                </Avatar>
                                <div>
                                    <h3 class="font-medium text-sm">
                                        {{ member.first_name }}
                                        {{ member.last_name }}
                                    </h3>
                                    <p class="text-xs text-muted-foreground">
                                        {{ member.email }}
                                    </p>
                                </div>
                            </li>
                        </ul>
                    </ScrollArea>
                </Card>

                <!-- Chat Area -->
                <Card class="h-[calc(100vh-12rem)] flex flex-col">
                    <CardHeader>
                        <CardTitle>Chat</CardTitle>
                    </CardHeader>
                    <div class="flex-1 overflow-y-auto p-4 space-y-4">
                        <div
                            v-for="message in messages"
                            :key="message.id"
                            class="flex"
                            :class="{ 'justify-end': message.sender === 'You' }"
                        >
                            <div
                                class="max-w-[80%] px-4 py-2 rounded-lg"
                                :class="
                                    message.sender === 'You'
                                        ? 'bg-primary text-primary-foreground'
                                        : 'bg-muted'
                                "
                            >
                                <p class="text-sm">{{ message.text }}</p>
                                <span class="text-xs mt-1 opacity-75 block">{{
                                    message.sender
                                }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="p-4 border-t border-border">
                        <form
                            @submit.prevent="sendMessage"
                            class="flex space-x-2"
                        >
                            <Input
                                v-model="newMessage"
                                type="text"
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
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref } from "vue";
import { User, Send } from "lucide-vue-next";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Card, CardHeader, CardTitle } from "@/Components/ui/card";
import { Avatar, AvatarImage, AvatarFallback } from "@/Components/ui/avatar";
import { Input } from "@/Components/ui/input";
import { Button } from "@/Components/ui/button";
import { ScrollArea } from "@/Components/ui/scroll-area";
import Badge from "@/Components/ui/badge/Badge.vue";

const members = ref([
    { id: 1, name: "Alice Johnson", email: "alice@example.com" },
    { id: 2, name: "Bob Smith", email: "bob@example.com" },
    { id: 3, name: "Charlie Brown", email: "charlie@example.com" },
    { id: 4, name: "Diana Prince", email: "diana@example.com" },
    { id: 5, name: "Ethan Hunt", email: "ethan@example.com" },
]);

const messages = ref([
    {
        id: 1,
        sender: "Alice Johnson",
        text: "Hey team, how's everyone doing today?",
    },
    { id: 2, sender: "Bob Smith", text: "I'm good! Just finished the report." },
    {
        id: 3,
        sender: "You",
        text: "Great job, Bob! Can you share it with the team?",
    },
    {
        id: 4,
        sender: "Charlie Brown",
        text: "Looking forward to reviewing it!",
    },
]);

const newMessage = ref("");

const sendMessage = () => {
    if (newMessage.value.trim()) {
        messages.value.push({
            id: messages.value.length + 1,
            sender: "You",
            text: newMessage.value.trim(),
        });
        newMessage.value = "";
    }
};
</script>
