<script setup>
import { Card, CardContent, CardHeader, CardTitle } from "@/Components/ui/card";
import { Button } from "@/Components/ui/button";
import { defineProps } from "vue";
import { Navigation } from "lucide-vue-next";

const props = defineProps({
    joinedGroups: {
        type: Array,
        required: true,
    },
});
</script>

<template>
    <!-- Joined Groups Section -->
    <Card class="lg:w-1/4">
        <CardHeader>
            <CardTitle>Joined Groups</CardTitle>
        </CardHeader>
        <CardContent>
            <div
                class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-1 gap-4 w-full"
            >
                <template v-if="joinedGroups.length">
                    <Card v-for="group in joinedGroups" :key="group.id">
                        <div class="flex p-2 items-center justify-between">
                            <img
                                :src="group.image"
                                :alt="group.name"
                                class="w-16 h-16 rounded-md object-cover mr-4"
                            />

                            <CardTitle class="text-sm">{{
                                group.name
                            }}</CardTitle>

                            <Link :href="route('employee.groups.show', {
                                group: group.id,
                            })">
                                <Button variant="ghost" size="icon">
                                    <Navigation class="w-4 h-4" />
                                </Button>
                            </Link>
                        </div>
                    </Card>
                </template>
                <template v-else>
                    <p class="text-sm text-muted-foreground">
                        No joined groups yet.
                    </p>
                </template>
            </div>
        </CardContent>
    </Card>
</template>
