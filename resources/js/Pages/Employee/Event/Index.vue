<script setup>
import UpcomingEvents from "./Partials/UpcomingEvents.vue";
import RegisteredEvent from "./Partials/RegisteredEvent.vue";
import AuthenticatedLayout from "@/Layouts/EmployeeLayout.vue";

const props = defineProps({
    upcomingEvents: {
        type: Array,
        required: true,
    },
    registeredEvents: {
        type: Array,
        required: true,
    },
});

</script>

<template>
    <Head title="Groups" />
    <AuthenticatedLayout>
        <div class="flex flex-col lg:flex-row p-4 lg:p-16 gap-8">
            <!-- Upcoming Events Section -->
            <UpcomingEvents :upcomingEvents="upcomingEvents" />
            <!-- Joined Groups List Section -->
            <RegisteredEvent :registeredEvents="registeredEvents" />
        </div>
    </AuthenticatedLayout>
</template>
