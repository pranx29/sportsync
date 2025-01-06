<script setup>
import { ref, computed } from "vue";
import { Button } from "@/Components/ui/button";
import { ScrollArea } from "@/Components/ui/scroll-area";
import { CalendarIcon, MapPin } from "lucide-vue-next";
import { Card, CardContent, CardHeader, CardTitle } from "@/Components/ui/card";
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from "@/Components/ui/select";

// Mock data for registered events
const allRegisteredEvents = [
    {
        id: "1",
        name: "Summer Basketball Tournament",
        date: "2024-07-15",
        venue: "Main Stadium",
    },
    {
        id: "2",
        name: "Corporate Volleyball League",
        date: "2024-09-05",
        venue: "Beach Volleyball Arena",
    },
    {
        id: "3",
        name: "Annual Company Run",
        date: "2024-10-10",
        venue: "City Park",
    },
    {
        id: "4",
        name: "Winter Ski Trip",
        date: "2023-12-15",
        venue: "Mountain Resort",
    },
    {
        id: "5",
        name: "Spring Tennis Tournament",
        date: "2023-04-20",
        venue: "City Tennis Courts",
    },
];

const filter = ref("upcoming");

const currentDate = new Date();

const filteredEvents = computed(() => {
    return allRegisteredEvents.filter((event) => {
        const eventDate = new Date(event.date);
        return filter.value === "upcoming"
            ? eventDate >= currentDate
            : eventDate < currentDate;
    });
});
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
                            <SelectValue placeholder="Filter events" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem value="upcoming">Upcoming</SelectItem>
                            <SelectItem value="past">Past</SelectItem>
                        </SelectContent>
                    </Select>
                </div>
                <ScrollArea class="h-[calc(100vh-20rem)]">
                    <div class="space-y-4 p-4">
                        <div v-if="filteredEvents.length > 0">
                            <div
                                v-for="event in filteredEvents"
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
                                                event.date
                                            ).toLocaleDateString("en-US", {
                                                year: "numeric",
                                                month: "long",
                                                day: "numeric",
                                            })
                                        }}
                                    </div>
                                    <div class="flex items-center">
                                        <MapPin class="w-4 h-4 mr-2" />
                                        {{ event.venue }}
                                    </div>
                                </div>

                                <Button
                                    variant="secondary"
                                    size="sm"
                                    class="w-full"
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
