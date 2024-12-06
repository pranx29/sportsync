<!-- <script setup>
import { Button } from "@/Components/ui/button";
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from "@/Components/ui/card";
import { defineProps } from "vue";

const props = defineProps({
    sessions: {
        type: Array,
        required: true,
    },
});
</script>

<template>
    <Card class="lg:w-3/4">
        <CardHeader>
            <CardTitle>Sessions</CardTitle>
        </CardHeader>
        <CardContent>
            <template v-if="sessions.length">
                <Card
                    v-for="session in sessions"
                    :key="session.id"
                    class="flex flex-col sm:flex-row items-center w-full mb-4"
                >
                    <div class="flex flex-col sm:flex-row sm:items-center w-full">
                        <CardHeader class="flex-1 flex flex-col sm:flex-row items-start">
                            <div class="mr-4 flex-1">
                                <CardTitle>{{ session.name }}</CardTitle>
                                <p class="text-sm text-muted-foreground">
                                    {{ session.date }}
                                </p>
                                <CardDescription>
                                    {{ session.description }}
                                </CardDescription>
                            </div>
                            <Button class="self-center">Join</Button>
                        </CardHeader>
                    </div>
                </Card>
            </template>
            <template v-else>
                <p class="text-sm text-muted-foreground">
                    No sessions available.
                </p>
            </template>
        </CardContent>
    </Card>
</template> -->
