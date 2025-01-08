<template>
    <Head title="Groups" />
    <AuthenticatedLayout>
        <div class="container mx-auto py-8">
            <div class="mb-8">
                <Link :href="route('employee.events.index')">
                    <Button variant="ghost" class="mb-4">
                        <ChevronLeft class="w-4 h-4 mr-2" />
                        Back to Events
                    </Button>
                </Link>
                <h1 class="text-4xl font-bold">{{ $page.props.event.name }}</h1>
            </div>
            <div class="grid gap-6 md:grid-cols-3">
                <Card class="md:col-span-2">
                    <CardHeader class="relative p-0">
                        <img
                            :src="$page.props.event.image"
                            :alt="$page.props.event.name"
                            width="800"
                            height="400"
                            class="w-full h-[300px] object-cover rounded-t-lg"
                        />
                        <div class="absolute top-4 right-4 flex gap-2">
                            <Badge>{{ $page.props.event.sport.name }}</Badge>
                        </div>
                    </CardHeader>
                    <CardContent class="p-6">
                        <div class="space-y-6">
                            <div>
                                <h2 class="text-2xl font-semibold mb-2">
                                    About the Event
                                </h2>
                                <p class="text-muted-foreground">
                                    {{ $page.props.event.description }}
                                </p>
                            </div>

                            <Separator />

                            <div class="space-y-4">
                                <h3 class="text-lg font-semibold">
                                    Event Details
                                </h3>
                                <div class="grid gap-4 sm:grid-cols-2">
                                    <div class="flex items-center gap-2">
                                        <CalendarIcon
                                            class="w-4 h-4 text-muted-foreground"
                                        />
                                        {{
                                            formatDate(
                                                new Date(
                                                    $page.props.event.event_date
                                                ),
                                                "dd MMM yyyy"
                                            )
                                        }}
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <Clock
                                            class="w-4 h-4 text-muted-foreground"
                                        />
                                        <span
                                            >{{ $page.props.event.start_time }}
                                            -
                                            {{
                                                $page.props.event.end_time
                                            }}</span
                                        >
                                    </div>
                                    <div
                                        v-if="
                                            !$page.props.event
                                                .customLocationLink
                                        "
                                        class="flex items-center gap-2"
                                    >
                                        <MapPin
                                            class="w-4 h-4 text-muted-foreground"
                                        />
                                        <span
                                            >{{
                                                $page.props.event.venue.name
                                            }}
                                            ({{
                                                $page.props.event.location_type
                                            }})</span
                                        >
                                    </div>
                                    <div
                                        v-if="
                                            $page.props.event
                                                .custom_location_link
                                        "
                                        class="flex items-center gap-2"
                                    >
                                        <a
                                            :href="
                                                $page.props.event
                                                    .custom_location_link
                                            "
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            class="text-primary hover:underline"
                                        >
                                            View on Map
                                        </a>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <Users
                                            class="w-4 h-4 text-muted-foreground"
                                        />
                                        <span
                                            >Max Participants:
                                            {{
                                                $page.props.event
                                                    .max_participants
                                            }}</span
                                        >
                                    </div>
                                    <div
                                        v-if="$page.props.event.number_of_teams"
                                        class="flex items-center gap-2"
                                    >
                                        <Users2
                                            class="w-4 h-4 text-muted-foreground"
                                        />
                                        <span
                                            >Number of Teams:
                                            {{
                                                $page.props.event
                                                    .number_of_teams
                                            }}</span
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
                                        <Users2
                                            class="w-4 h-4 text-muted-foreground"
                                        />
                                        <span class="capitalize"
                                            >{{
                                                $page.props.event
                                                    .registration_type
                                            }}
                                            Registration</span
                                        >
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <CalendarIcon
                                            class="w-4 h-4 text-muted-foreground"
                                        />
                                        <span
                                            >Register by
                                            {{
                                                formatDate(
                                                    new Date(
                                                        $page.props.event.registration_deadline
                                                    ),
                                                    "dd MMM yyyy hh:mm a"
                                                )
                                            }}</span
                                        >
                                    </div>
                                </div>
                            </div>

                            <div v-if="$page.props.event.rules_description">
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
                                            {{
                                                $page.props.event
                                                    .rules_description
                                            }}
                                        </p>
                                    </div>
                                </div>
                                <!-- Show Document Download Link -->
                                <Button
                                    variant="link"
                                    class="p-0 text-base"
                                    asChild
                                >
                                    <a
                                        target="_blank"
                                        :href="$page.props.event.rules_document"
                                    >
                                        Download Rules Document
                                    </a>
                                </Button>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <Card class="h-fit">
                    <CardContent class="p-6">
                        <div class="space-y-6">
                            <div>
                                <CardTitle class="text-xl mb-2"
                                    >Event Status</CardTitle
                                >
                                <Badge
                                    :variant="
                                        $page.props.event.status === 'canceled'
                                            ? 'danger'
                                            : $page.props.event.status ===
                                              'upcoming'
                                            ? ''
                                            : 'outline'
                                    "
                                >
                                    {{
                                        $page.props.event.status
                                            .charAt(0)
                                            .toUpperCase() +
                                        $page.props.event.status.slice(1)
                                    }}
                                </Badge>
                            </div>

                            <Separator />

                            <div>
                                <CardTitle class="text-xl mb-2"
                                    >Notifications</CardTitle
                                >
                                <div class="flex items-center gap-2">
                                    <Bell
                                        class="w-4 h-4 text-muted-foreground"
                                    />
                                    <span>{{
                                        $page.props.event.notify_creation ||
                                        $page.props.event.notify_assignments ||
                                        $page.props.event.send_reminder
                                            ? "Enabled"
                                            : "Disabled"
                                    }}</span>
                                </div>
                            </div>

                            <Separator />

                            <div v-if="$page.props.myTeam">
                                <CardTitle class="text-xl mb-2">{{
                                    $page.props.myTeam.team_name
                                }}</CardTitle>
                                <div class="flex items-center gap-2">
                                    <ScrollArea
                                        class="flex-1 overflow-y-auto max-h-64"
                                    >
                                        <ul class="space-y-4">
                                            <li
                                                v-for="member in $page.props
                                                    .myTeam.users"
                                                :key="member.id"
                                                class="flex items-center space-x-3"
                                            >
                                                <div class="w-full">
                                                    <div
                                                        class="flex justify-between"
                                                    >
                                                        <h3
                                                            class="font-medium text-sm"
                                                        >
                                                            {{
                                                                member.first_name
                                                            }}
                                                            {{
                                                                member.last_name
                                                            }}
                                                        </h3>
                                                    </div>
                                                    <p
                                                        class="text-xs text-muted-foreground"
                                                    >
                                                        {{ member.email }}
                                                    </p>
                                                </div>
                                            </li>
                                        </ul>
                                    </ScrollArea>
                                </div>
                            </div>

                            <Separator />

                            <div>
                                <CardTitle class="text-xl mb-2"
                                    >Actions</CardTitle
                                >
                                <Button
                                    v-if="$page.props.event.status === ''"
                                    @click="isRegisterModalOpen = true"
                                    class="w-full"
                                >
                                    Register for Event
                                </Button>

                                <EventFeedbackModal
                                    v-if="
                                        $page.props.event.users.some(
                                            (user) =>
                                                user.id ===
                                                $page.props.auth.user.id
                                        ) &&
                                        $page.props.event.status ===
                                            'completed' &&
                                        !$page.props.event.feedbacks.some(
                                            (feedback) =>
                                                feedback.user_id ===
                                                $page.props.auth.user.id
                                        )
                                    "
                                    :eventId="$page.props.event.id"
                                />
                                <AlertDialog
                                    v-if="
                                        $page.props.event.users.some(
                                            (user) =>
                                                user.id ===
                                                $page.props.auth.user.id
                                        ) &&
                                        $page.props.event.status !==
                                            'completed' &&
                                        $page.props.event.status !== 'canceled'
                                    "
                                >
                                    <AlertDialogTrigger as-child>
                                        <Button
                                            variant="outline"
                                            class="w-full"
                                        >
                                            Cancel Registration
                                        </Button>
                                    </AlertDialogTrigger>
                                    <AlertDialogContent>
                                        <AlertDialogHeader>
                                            <AlertDialogTitle>
                                                Are you sure you want to cancel
                                                your registration?
                                            </AlertDialogTitle>
                                            <AlertDialogDescription>
                                                This action cannot be undone.
                                                You will lose your spot in the
                                                event.
                                            </AlertDialogDescription>
                                        </AlertDialogHeader>
                                        <AlertDialogFooter>
                                            <AlertDialogCancel>
                                                No, keep my registration
                                            </AlertDialogCancel>
                                            <AlertDialogAction
                                                @click="
                                                    handleCancelRegistration
                                                "
                                            >
                                                Yes, cancel my registration
                                            </AlertDialogAction>
                                        </AlertDialogFooter>
                                    </AlertDialogContent>
                                </AlertDialog>
                            </div>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref } from "vue";
import { Badge } from "@/Components/ui/badge";
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
import { formatDate } from "date-fns";
import EventFeedbackModal from "./Partials/EventFeedbackModal.vue";
import { Button } from "@/Components/ui/button";
import { ChevronLeft } from "lucide-vue-next";
import AuthenticatedLayout from "@/Layouts/EmployeeLayout.vue";
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
import { usePage } from "@inertiajs/vue3";
import { toast } from "@/Components/ui/toast";

const event = usePage().props.event;

const handleCancelRegistration = () => {
    router.post(
        route("employee.events.unregister", { event: event.id }),
        {},
        {
            preserveScroll: true,
            preserveState: true,
            onSuccess: () => {
                toast({
                    title: "Cancellation Successful",
                    description:
                        "You have successfully canceled your registration for the event.",
                    variant: "success",
                });
            },
            onError: (error) => {
                toast({
                    title: "Cancellation Failed",
                    description:
                        "There was an error while trying to cancel your registration for the event.",
                    variant: "error",
                });
            },
        }
    );
};
</script>
