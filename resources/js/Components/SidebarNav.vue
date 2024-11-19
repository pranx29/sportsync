<script setup>
import { Button } from "@/Components/ui/button";
import { cn } from "@/lib/utils";

const sidebarNavItems = [
    {
        title: "Profile",
        name: "profile.edit",
    },
    {
        title: "Availability",
        name: "availability.edit",
    },
    {
        title: "Change Password",
        name: "password.edit",
    },];
</script>

<template>
    <nav class="flex space-x-2 lg:flex-col lg:space-x-0 lg:space-y-1">
        <Button
            v-for="item in sidebarNavItems"
            :key="item.title"
            as="a"
            :href="route(item.name)"
            variant="ghost"
            :class="
                cn('w-full text-left justify-start', {
                    'bg-muted hover:bg-muted': route().current(item.name),
                })
            "
        >
            {{ item.title }}
        </Button>
    </nav>
</template>
