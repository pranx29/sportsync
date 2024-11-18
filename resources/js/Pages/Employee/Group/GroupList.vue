<script setup>
import { Button } from "@/Components/ui/button";
import {
    Card,
    CardContent,
    CardDescription,
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
import { ListFilter } from "lucide-vue-next";
import { defineProps } from "vue";

const props = defineProps({
    groups: {
        type: Array,
        required: true,
    },
});
</script>

<template>
    <Card class="lg:w-3/4">
        <CardHeader>
            <CardTitle>Groups</CardTitle>
        </CardHeader>
        <CardContent>
            <div class="flex justify-end mb-4 w-full">
                <DropdownMenu>
                    <DropdownMenuTrigger as-child>
                        <Button
                            variant="outline"
                            size="sm"
                            class="h-8 gap-2 px-3"
                        >
                            <ListFilter class="h-4 w-4" />
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
                            <DropdownMenuItem>Active</DropdownMenuItem>
                        </Link>
                        <Link
                            :href="
                                route('admin.employees.index', {
                                    active: false,
                                })
                            "
                        >
                            <DropdownMenuItem>Inactive</DropdownMenuItem>
                        </Link>
                        <DropdownMenuItem>Archived</DropdownMenuItem>
                    </DropdownMenuContent>
                </DropdownMenu>
            </div>

            <template v-if="groups.length">
                <Card
                    v-for="group in groups"
                    :key="group.id"
                    class="flex flex-col sm:flex-row items-center w-full"
                >
                    <div
                        class="flex flex-col sm:flex-row sm:items-center w-full"
                    >
                        <CardHeader
                            class="flex-1 flex flex-col sm:flex-row items-start"
                        >
                            <img
                                :src="group.image"
                                :alt="group.name"
                                class="w-full h-auto sm:w-32 sm:h-32 rounded-md object-cover mb-4 sm:mb-0 sm:mr-4"
                            />
                            <div class="mr-4 flex-1">
                                <CardTitle>{{ group.name }}</CardTitle>
                                <p class="text-sm text-muted-foreground">
                                    members
                                </p>
                                <CardDescription>
                                    {{ group.description }}
                                </CardDescription>
                            </div>

                            <Link
                                class="self-center"
                                :href="
                                    route('employee.groups.join', {
                                        group_id: group.id,
                                    })
                                "
                                method="post"
                            >
                                <Button>Join</Button>
                            </Link>
                        </CardHeader>
                    </div>
                </Card>
            </template>
            <template v-else>
                <p class="text-sm text-muted-foreground">
                    No groups available.
                </p>
            </template>
        </CardContent>
    </Card>
</template>
