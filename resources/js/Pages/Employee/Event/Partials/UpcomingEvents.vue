<template>
    <div class="w-full">
        <h2 class="text-2xl font-bold mb-4">Upcoming Events</h2>
        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-2">
            <EventCard
                v-for="event in $page.props.upcomingEvents"
                :key="event.id"
                :event="event"
                @register="handleJoinEvent(event)"
            />
        </div>
        <!-- If no events -->
        <div
            v-if="$page.props.upcomingEvents.length === 0"
            class="text-center text-gray-500"
        >
            No upcoming events.
        </div>
    </div>
    <RegisterModal
        v-if="selectedEvent"
        :event="selectedEvent"
        @close="selectedEvent = null"
    />
</template>

<script setup>
import { ref } from "vue";
import EventCard from "./EventCard.vue";
import RegisterModal from "./RegisterModal.vue";

const selectedEvent = ref(null);

const handleJoinEvent = (event) => {
    selectedEvent.value = event;
};
</script>
