<script setup>
import { ref } from "vue";
import {
    Card,
    CardContent,
    CardFooter,
    CardHeader,
} from "@/Components/ui/card";
import { Badge } from "@/Components/ui/badge";
import { CalendarIcon, MapPinIcon, UsersIcon } from "lucide-vue-next";
import { defineProps, watch } from "vue";

const props = defineProps({
    eventName: String,
    sportType: String,
    description: String,
    eventImage: File,
    registrationType: String,
    maxParticipants: String,
    registrationDeadline: String,
    venue: String,
    locationType: String,
    eventDate: String,
    startTime: String,
    endTime: String,
    customLocation: Boolean,
    customLocationName: String,
    numberOfTeams: String,
});

const posterImageUrl = ref(null);
watch(
    () => props.eventImage,
    (newImage) => {
        if (newImage) {
            posterImageUrl.value = URL.createObjectURL(newImage);
        }
    },
    { immediate: true }
);
</script>

<template>
    <Card class="w-full max-w-md mx-auto">
        <CardHeader class="relative p-0">
            <template v-if="posterImageUrl">
                <img
                    :src="posterImageUrl"
                    :alt="eventName"
                    width="400"
                    height="200"
                    class="w-full h-48 object-cover rounded-t-lg"
                />
            </template>
            <template v-else>
                <div
                    class="w-full h-48 bg-muted flex items-center justify-center rounded-t-lg"
                >
                    <span class="text-muted-foreground"
                        >No image available</span
                    >
                </div>
            </template>
            <Badge v-if="sportType" class="absolute top-2 right-2">{{
                sportType
            }}</Badge>
        </CardHeader>
        <CardContent class="pt-4">
            <h3 class="text-2xl font-bold mb-2">
                {{ eventName || "Event Name" }}
            </h3>
            <p class="text-muted-foreground mb-4 line-clamp-2">
                {{ description || "Event description" }}
            </p>
            <div class="space-y-2">
                <div class="flex items-center">
                    <CalendarIcon class="w-4 h-4 mr-2" />
                    <span>{{
                        eventDate
                            ? `${eventDate} ${startTime} - ${endTime}`
                            : "Date and time not set"
                    }}</span>
                </div>
                <div
                    v-if="venue || customLocationName"
                    class="flex items-center"
                >
                    <div v-if="!customLocation" class="flex items-center">
                        <MapPinIcon class="w-4 h-4 mr-2" />
                        <span
                            >{{ venue || "Venue not set" }} ({{
                                locationType
                            }})</span
                        >
                    </div>
                    <div v-else class="flex items-center">
                        <MapPinIcon class="w-4 h-4 mr-2" />
                        <span>{{
                            customLocationName || "Custom location"
                        }}</span>
                    </div>
                </div>
                <div class="flex items-center">
                    <UsersIcon class="w-4 h-4 mr-2" />
                    <span
                        >{{
                            registrationType === "individual"
                                ? "Individual"
                                : "Team"
                        }}
                        Registration</span
                    >
                </div>
                <div
                    v-if="registrationType === 'team'"
                    class="flex items-center"
                >
                    <UsersIcon class="w-4 h-4 mr-2" />
                    <span
                        >Number of Teams: {{ numberOfTeams || "Not set" }}</span
                    >
                </div>
            </div>
        </CardContent>
        <CardFooter class="flex justify-between">
            <div>
                <p class="text-sm text-muted-foreground">
                    Max Participants: {{ maxParticipants || "Not set" }}
                </p>
            </div>
            <div>
                <p class="text-sm text-muted-foreground">
                    Register by: {{ registrationDeadline || "Not set" }}
                </p>
            </div>
        </CardFooter>
    </Card>
</template>
