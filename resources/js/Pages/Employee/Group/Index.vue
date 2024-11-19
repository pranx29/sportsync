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
    FormControl,
    FormDescription,
    FormField,
    FormItem,
    FormLabel,
    FormMessage,
} from "@/Components/ui/form";
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from "@/Components/ui/dropdown-menu";
import { Input } from "@/Components/ui/input";
import AuthenticatedLayout from "@/Layouts/EmployeeLayout.vue";
import { ListFilter } from "lucide-vue-next";
import GroupList from "./GroupList.vue";
import JoinedGroupList from "./JoinedGroupList.vue";
</script>

<template>
    <Head title="Groups" />
    <AuthenticatedLayout>
        <div class="flex flex-col lg:flex-row min-h-screen p-4 lg:p-16 gap-8">
            <!-- Group List Section -->
            <GroupList :groups="$page.props.groups" />
            <!-- Joined Groups List Section -->
            <JoinedGroupList :joined-groups="$page.props.joinedGroups" />
        </div>
    </AuthenticatedLayout>
</template>
