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
import AddEmployeeForm from "./AddEmployeeForm.vue";
import EmployeeTable from "./EmployeeTable.vue";
</script>

<template>
    <Head title="Employees" />
    <AdminLayout>
        <template #title> Employees </template>
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
                                route('admin.employees.index', {
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
                                route('admin.employees.index', {
                                    active: false,
                                })
                            "
                        >
                            <DropdownMenuItem checked>
                                Inactive
                            </DropdownMenuItem>
                        </Link>
                        <Link :href="route('admin.employees.index')">
                            <DropdownMenuItem checked> All </DropdownMenuItem>
                        </Link>
                    </DropdownMenuContent>
                </DropdownMenu>
                <!-- <Button size="sm" variant="outline" class="h-7 gap-1">
                    <File class="h-3.5 w-3.5" />
                    <span class="sr-only sm:not-sr-only sm:whitespace-nowrap">
                        Export
                    </span>
                </Button> -->
                <AddEmployeeForm />
            </div>
            <div class="min-h-screen overflow-x-auto">
                <EmployeeTable :employees="$page.props.employees.data" />
            </div>
            <template v-if="$page.props.employees.last_page > 1">
                <Pagination
                    v-slot="{ page }"
                    :total="$page.props.employees.total"
                    :sibling-count="1"
                    show-edges
                    :default-page="$page.props.employees.current_page"
                >
                    <PaginationList
                        v-slot="{ items }"
                        class="flex items-center gap-1 flex-wrap"
                    >
                        <PaginationPrev />

                        <template v-for="(item, index) in items">
                            <PaginationListItem
                                v-if="item.type === 'page'"
                                :key="index"
                                :value="item.value"
                                as-child
                            >
                                <Link
                                    :href="
                                        route('admin.employees.index', {
                                            page: item.value,
                                        })
                                    "
                                >
                                    <Button
                                        class="w-10 h-10 p-0"
                                        :variant="
                                            item.value === page
                                                ? 'default'
                                                : 'outline'
                                        "
                                    >
                                        {{ item.value }}
                                    </Button>
                                </Link>
                            </PaginationListItem>
                            <PaginationEllipsis
                                v-else
                                :key="item.type"
                                :index="index"
                            />
                        </template>
                        <PaginationNext />
                    </PaginationList>
                </Pagination>
            </template>
        </main>
        <Toaster />
    </AdminLayout>
</template>
