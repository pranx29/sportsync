<script setup>
import { Card, CardHeader, CardTitle } from "@/Components/ui/card";
import { Avatar, AvatarImage, AvatarFallback } from "@/Components/ui/avatar";
import { ScrollArea } from "@/Components/ui/scroll-area";
import Badge from "@/Components/ui/badge/Badge.vue";

const props = defineProps({
    leader: {
        type: Object,
        required: true,
    },
    members: {
        type: Array,
        required: true,
    },
});
</script>

<template>
    <!-- Members List -->
    <Card class="flex flex-col">
        <ScrollArea class="flex-1 overflow-y-auto p-4">
            <ul class="space-y-4">
                <li class="flex items-center space-x-3">
                    <Avatar>
                        <AvatarImage
                            :src="leader.profile?.profile_image || ''"
                        />
                        <AvatarFallback>
                            {{
                                leader.first_name.charAt(0) +
                                leader.last_name.charAt(0)
                            }}
                        </AvatarFallback>
                    </Avatar>
                    <div class="w-full">
                        <div class="flex justify-between">
                            <h3 class="font-medium text-sm">
                                {{ leader.first_name }}
                                {{ leader.last_name }}
                            </h3>
                            <Badge variant="outline">Leader</Badge>
                        </div>
                        <p class="text-xs text-muted-foreground">
                            {{ leader.email }}
                        </p>
                    </div>
                </li>
                <li
                    v-for="member in members"
                    :key="member.id"
                    class="flex items-center space-x-3"
                >
                    <Avatar>
                        <AvatarImage
                            :src="member.profile?.profile_image || ''"
                        />
                        <AvatarFallback>
                            {{
                                member.first_name.charAt(0) +
                                member.last_name.charAt(0)
                            }}
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
</template>
