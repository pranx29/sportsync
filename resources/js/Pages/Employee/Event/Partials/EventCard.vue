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
import { router } from "@inertiajs/vue3";
import { toast } from "@/Components/ui/toast";
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
    isRegisterModalOpen.value = false;
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
    <Card class="flex flex-col h-full hover:bg-neutral-100 shadow-lg">
        <Link :href="route('employee.events.view', { event: event.id })">
            <CardHeader class="p-0">
                <div class="relative h-48 w-full">
                    <img
                        :src="event.eventImage"
                        :alt="event.eventName"
                        class="w-full h-full object-cover rounded-t-lg"
                    />
                    <Badge class="absolute top-2 right-2">{{ event.sportType }}</Badge>
                </div>
            </CardHeader>
            <CardContent class="flex-grow p-4">
                <h2 class="text-xl font-semibold mb-2">{{ event.eventName }}</h2>
                <p class="text-muted-foreground text-sm mb-4 line-clamp-2">
                    {{ event.eventDescription }}
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
                    <div class="flex items-center gap-2">
                        <MapPin class="w-4 h-4 text-muted-foreground" />
                        <span>
                            {{ event.customLocationName || event.venue }}
                            ({{ event.locationType }})
                        </span>
                    </div>
                    <div class="flex items-center">
                        <Users class="w-4 h-4 mr-2 text-muted-foreground" />
                        <span>
                            {{
                                event.registrationType.charAt(0).toUpperCase() +
                                event.registrationType.slice(1) +
                                " Registration"
                            }}
                        </span>
                    </div>
                </div>
            </CardContent>
        </Link>
        <CardFooter class="p-4">
            <div class="w-full">
                <Button
                    @click="isRegisterModalOpen = true"
                    class="w-full"
                >
                    Register for Event
                </Button>
            </div>
        </CardFooter>
    </Card>

<RegisterModal
    :isOpen="isRegisterModalOpen"
    :event="event"
    :teams="event.teams || []"
    @close="isRegisterModalOpen = false"
    @success="handleRegistrationSuccess"
/>
</template>