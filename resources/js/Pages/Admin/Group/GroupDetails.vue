<script setup>
import { Button } from "@/Components/ui/button";
import {
    Card,
    CardContent,
    CardDescription,
    CardFooter,
    CardHeader,
    CardTitle,
} from "@/Components/ui/card";

import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from "@/Components/ui/dropdown-menu";
import { Avatar, AvatarFallback, AvatarImage } from "@/Components/ui/avatar";
import { Badge } from "@/Components/ui/badge";
import { Separator } from "@/Components/ui/separator";
import {
    CircleUser,
    Copy,
    CreditCard,
    File,
    Home,
    LineChart,
    ListFilter,
    MoreVertical,
    Package,
    Package2,
    PanelLeft,
    Search,
    Settings,
    ShoppingCart,
    Truck,
    Users2,
} from "lucide-vue-next";
import { ref } from "vue";
import EditGroupForm from "./EditGroupForm.vue";

defineProps({
    group: {
        type: Object,
        required: true,
    },
    leader: {
        type: Object,
        required: true,
    },
});
</script>

<template>
    <div v-if="group">
        <Card class="overflow-hidden">
            <CardHeader class="flex flex-row items-start bg-muted/50">
                <div class="grid gap-0.5">
                    <CardTitle class="group flex items-center gap-2 text-lg">
                        {{ group.name }}
                        <Button
                            size="icon"
                            variant="outline"
                            class="h-6 w-6 opacity-0 transition-opacity group-hover:opacity-100"
                        >
                        </Button>
                    </CardTitle>
                    <CardDescription
                        >Created on
                        {{
                            new Date(group.created_at).toLocaleString()
                        }}</CardDescription
                    >
                </div>
                <div class="ml-auto flex items-center gap-1">
                    <DropdownMenu>
                        <DropdownMenuTrigger as-child>
                            <Button
                                size="icon"
                                variant="outline"
                                class="h-8 w-8"
                            >
                                <MoreVertical class="h-3.5 w-3.5" />
                                <span class="sr-only">More</span>
                            </Button>
                        </DropdownMenuTrigger>
                        <DropdownMenuContent align="end">
                            <EditGroupForm>
                                <DropdownMenuItem> Edit </DropdownMenuItem>
                            </EditGroupForm>
                            <DropdownMenuItem>Export</DropdownMenuItem>
                            <DropdownMenuSeparator />
                            <DropdownMenuItem>Trash</DropdownMenuItem>
                        </DropdownMenuContent>
                    </DropdownMenu>
                </div>
            </CardHeader>
            <CardContent class="p-6 text-sm">
                <div class="grid gap-3">
                    <div class="font-semibold">Description</div>
                    <p class="ttext-muted-foreground">
                        {{ group.description }}
                    </p>
                </div>
                <Separator class="my-4" />
                <div class="grid grid-cols-1 gap-4">
                    <div class="grid gap-3">
                        <div class="font-semibold">Group members</div>
                        <!-- Members List -->
                        <ScrollArea class="flex-1 overflow-y-auto p-4">
                            <ul class="space-y-4">
                                <li
                                    v-for="member in group.users"
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
                                    <div class="w-full">
                                        <div class="flex justify-between">
                                            <h3 class="font-medium text-sm">
                                                {{ member.first_name }}
                                                {{ member.last_name }}
                                            </h3>
                                            <Badge
                                                v-if="
                                                    member.id ===
                                                    group.leader.id
                                                "
                                                variant="outline"
                                                >Leader</Badge
                                            >
                                        </div>
                                        <p
                                            class="text-xs text-muted-foreground"
                                        >
                                            {{ member.email }}
                                        </p>
                                    </div>
                                </li>
                            </ul>
                        </ScrollArea>
                    </div>
                </div>
            </CardContent>
            <CardFooter
                class="flex flex-row items-center border-t bg-muted/50 px-6 py-3"
            >
                <div class="text-xs text-muted-foreground">
                    Updated
                    <time dateTime="2023-11-23">November 23, 2023</time>
                </div>
                <Pagination class="ml-auto mr-0 w-auto">
                    <PaginationList class="gap-1">
                        <PaginationPrev variant="outline" class="h-6 w-6" />
                        <PaginationNext variant="outline" class="h-6 w-6" />
                    </PaginationList>
                </Pagination>
            </CardFooter>
        </Card>
    </div>
</template>
