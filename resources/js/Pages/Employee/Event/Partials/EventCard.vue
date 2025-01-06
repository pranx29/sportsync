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
import RegisterModal from "./RegisterModal.vue";

const props = defineProps({
    event: {
        type: Object,
        required: true,
    },
    onJoin: {
        type: Function,
        required: true,
    },
});

const isRegisterModalOpen = ref(false);

const handleRegistrationSuccess = () => {
    toast({
        title: "Registration Successful",
        description: "You have successfully registered for the event.",
        variant: "success",
    });
    props.onJoin(props.event.id);
};

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
                    :src="event.image"
                    :alt="event.name"
                    class="w-full h-full object-cover rounded-t-lg"
                />
                <Badge class="absolute top-2 right-2">{{
                    event.sport.name
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
                    <span>{{ formatDate(event.event_date) }}</span>
                </div>
                <div class="flex items-center">
                    <Clock class="w-4 h-4 mr-2 text-muted-foreground" />
                    <span>{{ event.start_time }} - {{ event.end_time }}</span>
                </div>
                <div
                    v-if="event.venue && !custom_location_name"
                    class="flex items-center"
                >
                    <MapPin class="w-4 h-4 mr-2 text-muted-foreground" />
                    <span
                        >{{ event.venue.name }} ({{
                            event.location_type
                        }})</span
                    >
                </div>
                <div v-else class="flex items-center">
                    <MapPin class="w-4 h-4 mr-2 text-muted-foreground" />
                    <span>{{ event.custom_location_name }}</span>
                </div>
                <div class="flex items-center">
                    <Users class="w-4 h-4 mr-2 text-muted-foreground" />
                    <span>
                        {{
                            event.registration_type === "team"
                                ? `${event.number_of_teams} Teams`
                                : `${event.max_participants} Participants`
                        }}
                    </span>
                </div>
            </div>
        </CardContent>
        <CardFooter class="p-4">
            <div class="w-full">
                <Button @click="handleRegistrationSuccess" class="w-full">
                    Register for Event
                </Button>
            </div>
        </CardFooter>
    </Card>
</template>
