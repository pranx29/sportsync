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
                                <h3 class="font-medium">{{ event.eventName }}</h3>
                                <div
                                    class="text-sm text-muted-foreground space-y-1"
                                >
                                    <div class="flex items-center">
                                        <CalendarIcon class="w-4 h-4 mr-2" />
                                        {{
                                            new Date(
                                                event.eventDate
                                            ).toLocaleDateString("en-US", {
                                                year: "numeric",
                                                month: "long",
                                                day: "numeric",
                                            })
                                        }}
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <MapPin class="w-4 h-4" />
                                        <span>
                                            {{ event.customLocationName || event.venue }}
                                            ({{ event.locationType }})
                                        </span>
                                    </div>
                                </div>
                                <Link
                                    :href="route('employee.events.view', {
                                            event: event.id,
                                    })"
                                >
                                    <Button class="w-full" variant="outline" size="sm">
                                        View Details
                                    </Button>
                                </Link>
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

const props = defineProps({
    registeredEvents: {
        type: Array,
        default: () => [],
    },
});

const filter = ref("upcoming");
const currentDate = new Date();

const filteredEvents = computed(() => {
    return (props.registeredEvents || []).filter((event) => {
        const eventDate = new Date(event.eventDate);
        return filter.value === "upcoming"
            ? eventDate >= currentDate
            : eventDate < currentDate;
    });
});
</script>