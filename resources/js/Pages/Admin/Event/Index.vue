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
                        <Link
                            :href="
                                route('admin.groups.index', {
                                    active: true,
                                })
                            "
                        >
                            <DropdownMenuItem checked>
                                Active
                            </DropdownMenuItem>
                        </Link>

                        <Link
                            :href="
                                route('admin.groups.index', {
                                    active: false,
                                })
                            "
                        >
                            <DropdownMenuItem checked>
                                Inactive
                            </DropdownMenuItem>
                        </Link>

                        <Link :href="route('admin.groups.index')">
                            <DropdownMenuItem checked>All</DropdownMenuItem>
                        </Link>
                    </DropdownMenuContent>
                </DropdownMenu>
                <!-- <Button size="sm" variant="outline" class="h-7 gap-1">
                    <File class="h-3.5 w-3.5" />
                    <span class="sr-only sm:not-sr-only sm:whitespace-nowrap">
                        Export
                    </span>
                </Button> -->
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
        </main>
        <Toaster />
    </AdminLayout>
</template>

<script setup>
import { ref } from "vue";
import { Tabs, TabsContent, TabsList, TabsTrigger } from "@/Components/ui/tabs";
import { Button } from "@/Components/ui/button";
import EventBasics from "./EventBasics.vue";
// import ParticipantDetails from "./ParticipantDetails";
// import LocationSchedule from "./LocationSchedule";
// import TeamSetup from "./TeamSetup";
// import RulesNotifications from "./RulesNotifications";
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
import { Toaster } from "@/Components/ui/toast";
import { File, ListFilter, CirclePlus } from "lucide-vue-next";
import EventCreationForm from "./Create.vue";

const steps = [
    "Event Details",
    "Registration Setup",
    "Team Setup",
    "Review & Publish",
];

const currentStep = ref(0);

const handleNext = () => {
    currentStep.value = Math.min(currentStep.value + 1, steps.length - 1);
};

const handleBack = () => {
    currentStep.value = Math.max(currentStep.value - 1, 0);
};
</script>
