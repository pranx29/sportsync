<script setup>
import { ref } from "vue";
import { Button } from "@/Components/ui/button";
import {
    Card,
    CardContent,
    CardFooter,
    CardHeader,
} from "@/Components/ui/card";
import { Badge } from "@/Components/ui/badge";
import { CalendarIcon, Clock, MapPin, Users } from "lucide-vue-next";
import {
    AlertDialog,
    AlertDialogAction,
    AlertDialogCancel,
    AlertDialogContent,
    AlertDialogDescription,
    AlertDialogFooter,
    AlertDialogHeader,
    AlertDialogTitle,
    AlertDialogTrigger,
} from "@/Components/ui/alert-dialog";

const props = defineProps({
    event: {
        type: Object,
        required: true,
        default: () => ({
            id: "",
            name: "",
            sportType: "",
            description: "",
            posterImage: "",
            registrationType: "",
            maxParticipants: 0,
            numberOfTeams: 0,
            registrationDeadline: "",
            venue: "",
            locationType: "",
            eventDate: "",
            startTime: "",
            endTime: "",
            status: "",
        }),
    },
    onJoin: {
        type: Function,
        required: true,
    },
});

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString("en-US", {
        year: "numeric",
        month: "long",
        day: "numeric",
    });
};
</script>

<template>
    <Card class="flex flex-col h-full">
        <CardHeader class="p-0">
            <div class="relative h-48 w-full">
                <img
                    :src="event.posterImage"
                    :alt="event.name"
                    class="w-full h-full object-cover rounded-t-lg"
                />
                <Badge class="absolute top-2 right-2">{{
                    event.sportType
                }}</Badge>
            </div>
        </CardHeader>
        <CardContent class="flex-grow p-4">
            <h2 class="text-xl font-semibold mb-2">{{ event.name }}</h2>
            <p class="text-muted-foreground text-sm mb-4 line-clamp-2">
                {{ event.description }}
            </p>
            <div class="space-y-2 text-sm">
                <div class="flex items-center">
                    <CalendarIcon class="w-4 h-4 mr-2 text-muted-foreground" />
                    <span>{{ formatDate(event.eventDate) }}</span>
                </div>
                <div class="flex items-center">
                    <Clock class="w-4 h-4 mr-2 text-muted-foreground" />
                    <span>{{ event.startTime }} - {{ event.endTime }}</span>
                </div>
                <div class="flex items-center">
                    <MapPin class="w-4 h-4 mr-2 text-muted-foreground" />
                    <span>{{ event.venue }} ({{ event.locationType }})</span>
                </div>
                <div class="flex items-center">
                    <Users class="w-4 h-4 mr-2 text-muted-foreground" />
                    <span>
                        {{
                            event.registrationType === "team"
                                ? `${event.numberOfTeams} Teams`
                                : `${event.maxParticipants} Participants`
                        }}
                    </span>
                </div>
            </div>
        </CardContent>
        <CardFooter class="p-4">
            <AlertDialog>
                <AlertDialogTrigger as-child class="w-full">
                    <Button> Join Event </Button>
                </AlertDialogTrigger>
                <AlertDialogContent>
                    <AlertDialogHeader>
                        <AlertDialogTitle
                            >Confirm Registration</AlertDialogTitle
                        >
                        <AlertDialogDescription>
                            Are you sure you want to join this event? Please
                            confirm your registration.
                        </AlertDialogDescription>
                    </AlertDialogHeader>
                    <AlertDialogFooter>
                        <AlertDialogCancel>Cancel</AlertDialogCancel>
                        <AlertDialogAction @click="onJoin"
                            >Confirm</AlertDialogAction
                        >
                    </AlertDialogFooter>
                </AlertDialogContent>
            </AlertDialog>
        </CardFooter>
    </Card>
</template>
