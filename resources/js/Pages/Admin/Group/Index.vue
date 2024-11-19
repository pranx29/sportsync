<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Button } from "@/Components/ui/button";
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
import { File, ListFilter, PlusCircle } from "lucide-vue-next";
import AddGroupForm from "./AddGroupForm.vue";
import GroupTable from "./GroupTable.vue";
import GroupDetails from "./GroupDetails.vue";
import { ref } from "vue";
import { usePage } from "@inertiajs/vue3";

const { props } = usePage();
const selectedGroup = ref(props.groups[0]);

const handleGroupSelected = (group) => {
    selectedGroup.value = group;
};
</script>

<template>
    <Head title="Groups" />
    <AdminLayout>
        <template #title> Groups </template>
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
                <AddGroupForm />
            </div>
            <div
                class="min-h-screen flex flex-col md:flex-row gap-4 overflow-x-auto"
            >
                <div class="flex-1 md:w-1/3">
                    <GroupTable
                        :groups="$page.props.groups"
                        @group-selected="handleGroupSelected"
                    />
                </div>
                <div class="flex-1 md:w-1/2">
                    <GroupDetails :group="selectedGroup" />
                </div>
            </div>
        </main>
        <Toaster />
    </AdminLayout>
</template>
