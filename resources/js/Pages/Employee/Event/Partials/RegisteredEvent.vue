<script setup>
import { ref, computed } from "vue";
import { Button } from "@/Components/ui/button";
import { ScrollArea } from "@/Components/ui/scroll-area";
import { CalendarIcon, Divide, MapPin } from "lucide-vue-next";
import { Card, CardContent, CardHeader, CardTitle } from "@/Components/ui/card";
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from "@/Components/ui/select";
import { router } from "@inertiajs/vue3";

const filter = ref("upcoming");

const handleViewDetails = (event) => {
    router.get(route("employee.events.show", event.id));
};
</script>

<template>
    <div class="w-full sm:max-w-xs mx-auto">
        <Card>
            <CardHeader>
                <CardTitle>Registered Events</CardTitle>
            </CardHeader>
            <CardContent>
                <div class="p-4 border-b">
                    <Select v-model="filter">
                        <SelectTrigger>
                            <SelectValue placeholder="Filter Events" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem value="upcoming">Upcoming</SelectItem>
                            <SelectItem value="past">Past</SelectItem>
                        </SelectContent>
                    </Select>
                </div>
                <ScrollArea class="h-[calc(100vh-20rem)]">
                    <div class="space-y-4 p-4">
                        <div v-if="$page.props.registeredEvents">
                            <div
                                v-for="event in $page.props.registeredEvents"
                                :key="event.id"
                                class="space-y-2 mb-4"
                            >
                                <h3 class="font-medium">{{ event.name }}</h3>
                                <div
                                    class="text-sm text-muted-foreground space-y-1"
                                >
                                    <div class="flex items-center">
                                        <CalendarIcon class="w-4 h-4 mr-2" />
                                        {{
                                            new Date(
                                                event.event_date
                                            ).toLocaleDateString("en-US", {
                                                year: "numeric",
                                                month: "long",
                                                day: "numeric",
                                            })
                                        }}
                                    </div>
                                    <div class="flex items-center">
                                        <MapPin class="w-4 h-4 mr-2" />
                                        {{ event.venue.name }} ({{ event.location_type }})
                                    </div>
                                </div>

                                <Button
                                    variant="secondary"
                                    size="sm"
                                    class="w-full"
                                    @click="handleViewDetails(event)"
                                >
                                    View Details
                                </Button>
                            </div>
                        </div>
                        <p v-else class="text-muted-foreground text-sm">
                            No {{ filter }} events found.
                        </p>
                    </div>
                </ScrollArea>
            </CardContent>
        </Card>
    </div>
</template>
