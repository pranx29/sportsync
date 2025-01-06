<template>
    <Head title="Groups" />
    <AuthenticatedLayout>
        <div class="container mx-auto py-8">
            <div class="mb-8">
                <Link :href="route('employee.events.index')">
                    <Button variant="ghost" class="mb-4">
                        <ChevronLeft class="w-4 h-4 mr-1" />
                        Back to Events
                    </Button>
                </Link>
                <h1 class="text-4xl font-bold">{{ event.eventName }}</h1>
            </div>
            <EventDetails :event="event" />
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref } from "vue";
import EventDetails from "./Partials/EventDetails.vue";
import { Button } from "@/Components/ui/button";
import { ChevronLeft } from "lucide-vue-next";
import AuthenticatedLayout from "@/Layouts/EmployeeLayout.vue";

const props = defineProps({
    event: {
        type: Object,
        required: true,
    },
});

// const event = ref({
//     id: "1",
//     name: "Summer Basketball Tournament",
//     sportType: "Basketball",
//     description:
//         "Join us for the annual summer basketball tournament! Compete against the best teams in the city and showcase your skills on the court. This event is perfect for both seasoned players and enthusiastic amateurs looking for a fun, competitive experience.",
//     posterImage: "/placeholder.svg",
//     registrationType: "team",
//     maxParticipants: 48,
//     numberOfTeams: 8,
//     registrationDeadline: "2024-07-01",
//     venue: "Main Stadium",
//     locationType: "indoor",
//     customLocationLink: "https://maps.example.com/main-stadium",
//     eventDate: "2024-07-15",
//     startTime: "09:00",
//     endTime: "18:00",
//     status: "upcoming",
//     rulesDescription:
//         "Standard basketball rules apply. Each team must have a minimum of 5 players and a maximum of 8 players. Games will be 4 quarters of 10 minutes each. Full rules will be provided to registered teams.",
//     notificationsEnabled: true,
//     createdAt: "2024-01-15T10:00:00Z",
//     organizer: "Sports Department",
//     contactEmail: "sports@example.com",
//     contactPhone: "+1 (555) 123-4567",
// });
</script>
