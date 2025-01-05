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

const upcomingEvents = [
    {
        id: "1",
        name: "Summer Basketball Tournament",
        sportType: "Basketball",
        description:
            "Join us for the annual summer basketball tournament! Compete against the best teams in the city.",
        posterImage: "/placeholder.svg",
        registrationType: "team",
        maxParticipants: 48,
        numberOfTeams: 8,
        registrationDeadline: "2024-07-01",
        venue: "Main Stadium",
        locationType: "indoor",
        eventDate: "2024-07-15",
        startTime: "09:00",
        endTime: "18:00",
        status: "upcoming",
    },
    {
        id: "2",
        name: "City Tennis Championship",
        sportType: "Tennis",
        description:
            "Participate in the city-wide tennis championship. Singles and doubles categories available.",
        posterImage: "/placeholder.svg",
        registrationType: "individual",
        maxParticipants: 64,
        registrationDeadline: "2024-06-15",
        venue: "Central Tennis Courts",
        locationType: "outdoor",
        eventDate: "2024-06-30",
        startTime: "08:00",
        endTime: "20:00",
        status: "upcoming",
    },
    {
        id: "3",
        name: "Corporate Volleyball League",
        sportType: "Volleyball",
        description:
            "Form a team with your colleagues and compete in our monthly corporate volleyball league.",
        posterImage: "/placeholder.svg",
        registrationType: "team",
        maxParticipants: 72,
        numberOfTeams: 12,
        registrationDeadline: "2024-08-20",
        venue: "Beach Volleyball Arena",
        locationType: "outdoor",
        eventDate: "2024-09-05",
        startTime: "10:00",
        endTime: "16:00",
        status: "upcoming",
    },
];

const selectedEvent = ref(null);

const handleJoinEvent = (event) => {
    selectedEvent.value = event;
    console.log("Joining event", event);
};

</script>
