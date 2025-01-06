<template>
    <div class="w-full">
        <h2 class="text-2xl font-bold mb-4">Upcoming Events</h2>
        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-2">
            <EventCard
                v-for="event in $page.props.upcomingEvents"
                :key="event.id"
                :event="event"
                @join="handleJoinEvent(event)"
            />

            <!-- If no events -->
            <div
                v-if="$page.props.upcomingEvents.length === 0"
                class="text-center text-gray-500"
            >
                No upcoming events.
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from "vue";
import EventCard from "./EventCard.vue";

const selectedEvent = ref(null);

const handleJoinEvent = (event) => {
    selectedEvent.value = event;
    console.log("Joining event", event);
};
</script>
