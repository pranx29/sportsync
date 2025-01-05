<script setup>
import { Button } from "@/Components/ui/button";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from "@/Components/ui/dropdown-menu";
import {
    Pagination,
    PaginationEllipsis,
    PaginationFirst,
    PaginationLast,
    PaginationList,
    PaginationListItem,
    PaginationNext,
    PaginationPrev,
} from "@/Components/ui/pagination";
import { toast } from "@/Components/ui/toast";
import { File, ListFilter, CirclePlus } from "lucide-vue-next";
import EventCreationForm from "./Create.vue";
import EventTable from "./EventTable.vue";
import { usePage } from "@inertiajs/vue3";
import { watch, ref } from "vue";

const events = ref(usePage().props.events);

const filterEvents = (status) => {
    if (status === "all") {
        events.value = usePage().props.events;
    } else {
        events.value = usePage().props.events.filter(
            (event) => event.status === status
        );
    }
};
</script>

<template>
    <Head title="Events" />
    <AdminLayout>
        <template #title> Event </template>
        <main
            class="grid items-start gap-4 p-4 sm:px-6 sm:py-0 md:gap-8 flex-1 min-h-screen"
        >
            <div class="ml-auto flex items-center gap-2 flex-wrap">
                <DropdownMenu>
                    <DropdownMenuTrigger as-child>
                        <Button variant="outline" size="sm" class="h-7 gap-1">
                            <ListFilter class="h-3.5 w-3.5" />
                            <span
                                class="sr-only sm:not-sr-only sm:whitespace-nowrap"
                            >
                                Filter
                            </span>
                        </Button>
                    </DropdownMenuTrigger>
                    <DropdownMenuContent align="end">
                        <DropdownMenuLabel>Filter by</DropdownMenuLabel>
                        <DropdownMenuSeparator />
                        <DropdownMenuItem @click="filterEvents('upcoming')">
                            Upcoming
                        </DropdownMenuItem>
                        <DropdownMenuItem @click="filterEvents('completed')">
                            Completed
                        </DropdownMenuItem>
                        <DropdownMenuItem @click="filterEvents('canceled')">
                            Canceled
                        </DropdownMenuItem>
                        <DropdownMenuItem @click="filterEvents('all')">
                            All
                        </DropdownMenuItem>
                    </DropdownMenuContent>
                </DropdownMenu>
                <Link :href="route('admin.events.create')">
                    <Button size="sm" class="h-7 gap-1">
                        <CirclePlus class="h-3.5 w-3.5" />
                        <span
                            class="sr-only sm:not-sr-only sm:whitespace-nowrap"
                        >
                            Create Event
                        </span>
                    </Button>
                </Link>
            </div>
            <div class="min-h-screen overflow-x-auto">
                <EventTable :events="events" />
            </div>
        </main>
    </AdminLayout>
</template>
