<template>
    <div class="grid gap-6 md:grid-cols-3">
        <Card class="md:col-span-2">
            <CardHeader class="relative p-0">
                <img
                    :src="event.posterImage"
                    :alt="event.name"
                    width="800"
                    height="400"
                    class="w-full h-[300px] object-cover rounded-t-lg"
                />
                <div class="absolute top-4 right-4 flex gap-2">
                    <Badge>{{ event.sportType }}</Badge>
                </div>
            </CardHeader>
            <CardContent class="p-6">
                <div class="space-y-6">
                    <div>
                        <h2 class="text-2xl font-semibold mb-2">
                            About the Event
                        </h2>
                        <p class="text-muted-foreground">
                            {{ event.description }}
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
                                <span
                                    >{{ event.venue }} ({{
                                        event.locationType
                                    }})</span
                                >
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
                                <span>Register by {{}}</span>
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
                        <!-- Show Document Download Link -->
                        <Button variant="link" class="p-0 text-base" asChild>
                            <a target="_blank"> Download Rules Document </a>
                        </Button>
                    </div>
                </div>
            </CardContent>
        </Card>

        <Card class="h-fit">
            <CardContent class="p-6">
                <div class="space-y-6">
                    <div>
                        <CardTitle class="text-xl mb-2">Event Status</CardTitle>
                        <Badge
                            :variant="
                                event.status === 'canceled'
                                    ? 'danger'
                                    : event.status === 'upcoming'
                                    ? ''
                                    : 'outline'
                            "
                        >
                            {{
                                event.status.charAt(0).toUpperCase() +
                                event.status.slice(1)
                            }}
                        </Badge>
                    </div>

                    <Separator />
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
                            v-if="event.status === ''"
                            @click="isRegisterModalOpen = true"
                            class="w-full"
                        >
                            Register for Event
                        </Button>

                        <EventFeedbackModal :eventId="event.id" />
                    </div>

                    <Separator />
                </div>
            </CardContent>
        </Card>

        <RegisterModal
            :isOpen="isRegisterModalOpen"
            @close="isRegisterModalOpen = false"
            :event="event"
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
import RegisterModal from "./RegisterModal.vue";
import EventFeedbackModal from "./EventFeedbackModal.vue";

const props = defineProps({
    event: {
        type: Object,
        required: true,
    },
});

const isRegisterModalOpen = ref(false);
</script>
