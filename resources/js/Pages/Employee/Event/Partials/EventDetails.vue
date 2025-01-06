<template>
    <div class="grid gap-6 md:grid-cols-3">
        <Card class="md:col-span-2">
            <CardHeader class="relative p-0">
                <img
                    :src="event.eventImage"
                    :alt="event.eventName"
                    width="800"
                    height="400"
                    class="w-full h-[300px] object-cover rounded-t-lg"
                />
                <div class="absolute top-4 right-4 flex gap-2">
                    <Badge>{{ event.sportType }}</Badge>
                    <StatusBadge :status="event.status" />
                </div>
            </CardHeader>
            <CardContent class="p-6">
                <div class="space-y-6">
                    <div>
                        <h2 class="text-2xl font-semibold mb-2">
                            About the Event
                        </h2>
                        <p class="text-muted-foreground">
                            {{ event.eventDescription }}
                        </p>
                    </div>

                    <Separator />

                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold">Event Details</h3>
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div class="flex items-center gap-2">
                                <CalendarIcon
                                    class="w-4 h-4 text-muted-foreground"
                                />
                                <span>
                                    {{
                                        new Date(
                                            event.eventDate
                                        ).toLocaleDateString("en-US", {
                                            year: "numeric",
                                            month: "long",
                                            day: "numeric",
                                        })
                                    }}
                                </span>
                            </div>
                            <div class="flex items-center gap-2">
                                <Clock class="w-4 h-4 text-muted-foreground" />
                                <span
                                    >{{ event.startTime }} -
                                    {{ event.endTime }}</span
                                >
                            </div>
                            <div class="flex items-center gap-2">
                                <MapPin class="w-4 h-4 text-muted-foreground" />
                                <span v-if="event.customLocationName"
                                    >{{ event.customLocationName }} ({{
                                        event.locationType
                                    }})</span
                                >
                                <span v-else
                                    >{{ event.venue }} ({{
                                        event.locationType
                                    }})</span>
                            </div>
                            <div
                                v-if="event.customLocationLink"
                                class="flex items-center gap-2"
                            >
                                <a
                                    :href="event.customLocationLink"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="text-primary hover:underline"
                                >
                                    View on Map
                                </a>
                            </div>
                            <div class="flex items-center gap-2">
                                <Users class="w-4 h-4 text-muted-foreground" />
                                <span
                                    >Max Participants:
                                    {{ event.maxParticipants }}</span
                                >
                            </div>
                            <div
                                v-if="event.numberOfTeams"
                                class="flex items-center gap-2"
                            >
                                <Users2 class="w-4 h-4 text-muted-foreground" />
                                <span
                                    >Number of Teams:
                                    {{ event.numberOfTeams }}</span
                                >
                            </div>
                        </div>
                    </div>

                    <Separator />

                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold">
                            Registration Information
                        </h3>
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div class="flex items-center gap-2">
                                <Users2 class="w-4 h-4 text-muted-foreground" />
                                <span class="capitalize"
                                    >{{
                                        event.registrationType
                                    }}
                                    Registration</span
                                >
                            </div>
                            <div class="flex items-center gap-2">
                                <CalendarIcon
                                    class="w-4 h-4 text-muted-foreground"
                                />
                                <span>Register by:
                                    {{
                                        new Date(
                                            event.eventDate
                                        ).toLocaleDateString("en-US", {
                                            year: "numeric",
                                            month: "long",
                                            day: "numeric",
                                        })
                                    }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div v-if="event.rulesDescription">
                        <Separator />
                        <div class="space-y-4">
                            <h3 class="text-lg font-semibold">
                                Rules & Guidelines
                            </h3>
                            <div class="flex items-start gap-2">
                                <ClipboardCheck
                                    class="w-4 h-4 text-muted-foreground mt-1"
                                />
                                <p class="text-muted-foreground">
                                    {{ event.rulesDescription }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <Separator />

                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold">
                            Contact Information
                        </h3>
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div class="flex items-center gap-2">
                                <Mail class="w-4 h-4 text-muted-foreground" />
                                <a
                                    :href="`mailto:${event.contactEmail}`"
                                    class="text-primary hover:underline"
                                >
                                    <!-- {{ event.contactEmail }} -->
                                    sports@example.com
                                </a>
                            </div>
                            <div class="flex items-center gap-2">
                                <Phone class="w-4 h-4 text-muted-foreground" />
                                <a
                                    :href="`tel:${event.contactPhone}`"
                                    class="text-primary hover:underline"
                                >
                                    <!-- {{ event.contactPhone }} -->
                                    +1 (555) 123-4567
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </CardContent>
        </Card>

        <Card class="h-fit">
            <CardContent class="p-6">
                <div class="space-y-6">
                    <div>
                        <CardTitle class="text-xl mb-2">Event Status</CardTitle>
                        <StatusBadge :status="event.status" />
                    </div>

                    <Separator />

                    <div>
                        <CardTitle class="text-xl mb-2">Organizer</CardTitle>
                        <p class="text-muted-foreground">
                            <!-- {{ event.organizer }} -->
                              Sports Department
                        </p>
                    </div>

                    <Separator />

                    <div>
                        <CardTitle class="text-xl mb-2"
                            >Notifications</CardTitle
                        >
                        <div class="flex items-center gap-2">
                            <Bell class="w-4 h-4 text-muted-foreground" />
                            <span>{{
                                event.notificationsEnabled
                                    ? "Enabled"
                                    : "Disabled"
                            }}</span>
                        </div>
                    </div>

                    <Separator />

                    <div>
                        <CardTitle class="text-xl mb-2">Actions</CardTitle>
                        <Button
                            v-if="!event.isUserRegistered"
                            @click="isRegisterModalOpen = true"
                            class="w-full"
                        >
                            Register for Event
                        </Button>
                        <Button
                            v-else
                            variant="destructive"
                            @click="leaveEvent"
                            class="w-full"
                        >
                            Leave Event
                        </Button>
                    </div>

                    <Separator />

                    <div>
                        <CardTitle class="text-xl mb-2"
                            >Additional Information</CardTitle
                        >
                        <div class="space-y-2 text-sm text-muted-foreground">
                            <p>Event ID: {{ event.id }}</p>
                        </div>
                    </div>
                </div>
            </CardContent>
        </Card>

        <RegisterModal
            :isOpen="isRegisterModalOpen"
            :event="event"
            :teams="event.teams || []"
            @close="isRegisterModalOpen = false"
            @success="handleRegistrationSuccess"
        />
    </div>
</template>

<script setup>
import { ref } from "vue";
import { Badge } from "@/Components/ui/badge";
import { Button } from "@/Components/ui/button";
import { Card, CardContent, CardHeader, CardTitle } from "@/Components/ui/card";
import { Separator } from "@/Components/ui/separator";
import {
    CalendarIcon,
    Clock,
    MapPin,
    Users,
    Users2,
    ClipboardCheck,
    Bell,
    Mail,
    Phone,
} from "lucide-vue-next";
import { formatDate } from "@vueuse/core";
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

const leaveEvent = () => {
    router.post(route("employee.events.leave", { event: props.event.id }), {
        onSuccess: () => {
            toast({
                title: "Event Left",
                description: "You have successfully left the event.",
                variant: "success",
            });
            props.onLeave(props.event.id);
        },
        onError: (errors) => {
            console.error(errors);
            toast({
                title: "Error",
                description: "There was an error leaving the event. Please try again.",
                variant: "destructive",
            });
        },
    });
};

const handleRegistrationSuccess = () => {
    toast({
        title: "Registration Successful",
        description: "You have successfully registered for the event.",
        variant: "success",
    });
    isRegisterModalOpen.value = false;
    props.onJoin(props.event.id);
};

// const getStatusBadge = (status) => {
//     const statusStyles = {
//         upcoming: "bg-blue-100 text-blue-800 hover:bg-blue-100/80",
//         "in-progress": "bg-green-100 text-green-800 hover:bg-green-100/80",
//         completed: "bg-gray-100 text-gray-800 hover:bg-gray-100/80",
//         "registration-closed":
//             "bg-yellow-100 text-yellow-800 hover:bg-yellow-100/80",
//         cancelled: "bg-red-100 text-red-800 hover:bg-red-100/80",
//     };

//     const statusLabels = {
//         upcoming: "Upcoming",
//         "in-progress": "In Progress",
//         completed: "Completed",
//         "registration-closed": "Registration Closed",
//         cancelled: "Cancelled",
//     };

//     // return (
//     //     <Badge variant="secondary" class={statusStyles[status]}>
//     //         {statusLabels[status]}
//     //     </Badge>
//     // );
// };
</script>
