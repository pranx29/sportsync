<template>
    <div>
        <h2 class="text-2xl font-bold mb-4">Upcoming Events</h2>
        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-2">
            <EventCard
                v-for="event in upcomingEvents"
                :key="event.id"
                :event="event"
                @join="handleJoinEvent(event)"
            />
        </div>
    </div>
</template>

<script setup>
import { ref } from "vue";
import EventCard from "./EventCard.vue";

const props = defineProps({
    upcomingEvents: {
        type: Array,
        required: true,
    },
});

const selectedEvent = ref(null);

const handleJoinEvent = (eventId) => {
    props.upcomingEvents = props.upcomingEvents.filter(
        (event) => event.id !== eventId
    );
};

</script>
