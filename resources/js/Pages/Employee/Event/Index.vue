<script setup>
import UpcomingEvents from "./Partials/UpcomingEvents.vue";
import RegisteredEvent from "./Partials/RegisteredEvent.vue";
import AuthenticatedLayout from "@/Layouts/EmployeeLayout.vue";


</script>

<template>
    <Head title="Groups" />
    <AuthenticatedLayout>
        <div class="flex flex-col lg:flex-row p-4 lg:p-16 gap-8">
            <!-- Upcoming Events Section -->
            <UpcomingEvents />
            <!-- Joined Groups List Section -->
            <RegisteredEvent />
        </div>
    </AuthenticatedLayout>
</template>
