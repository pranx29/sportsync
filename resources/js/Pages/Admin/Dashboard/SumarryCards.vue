<template>
    <Card v-for="item in summaryData" :key="item.title">
        <CardHeader
            class="flex flex-row items-center justify-between space-y-0 pb-2"
        >
            <CardTitle class="text-base font-medium">
                {{ item.title }}
            </CardTitle>
            <component :is="item.icon" class="h-4 w-4 text-primary" />
        </CardHeader>
        <CardContent>
            <div class="text-2xl font-bold">{{ item.value }}</div>
            <p class="text-xs text-muted-foreground">{{ item.description }}</p>
        </CardContent>
    </Card>
</template>

<script setup>
import { ref } from "vue";
import { Card, CardContent, CardHeader, CardTitle } from "@/Components/ui/card";
import {
    Users,
    UserPlus,
    GroupIcon as Groups,
    Calendar,
} from "lucide-vue-next";
import { usePage } from "@inertiajs/vue3";

const data = usePage().props.summaryData;
console.log(data);

const summaryData = ref([
    {
        title: "Total Employees",
        value: data.totalEmployees,
        icon: Users,
        description: data.employeesJoinedThisMonth + " joined this month",
    },
    {
        title: "Total Group Sessions",
        value: data.totalGroupSessions,
        icon: Groups,
        description: data.groupSessionsThisWeek + " created this month",
    },
    {
        title: "Total Events",
        value: data.totalEvents,
        icon: Calendar,
        description: data.eventsThisMonth + " events this month",
    },
    {
        title: "Profile Creations",
        value: data.totalProfiles,
        icon: UserPlus,
        description: data.profilesLastSevenDays + " in the last 7 days",
    },
]);
</script>
