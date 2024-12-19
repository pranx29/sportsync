<script setup>
import { ref, defineEmits } from "vue";
import {
    ChevronDown,
    CirclePlus,
    LogOut,
    EditIcon,
    User,
    Calendar,
    Clock,
    Users,
    Dumbbell,
    MapPin,
    Star,
} from "lucide-vue-next";
import { router, usePage } from "@inertiajs/vue3";
import { toast } from "@/Components/ui/toast";
import { Avatar, AvatarImage, AvatarFallback } from "@/Components/ui/avatar";
import { Button } from "@/Components/ui/button";
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from "@/Components/ui/dropdown-menu";
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
import {
    Card,
    CardContent,
    CardDescription,
    CardFooter,
    CardHeader,
    CardTitle,
} from "@/Components/ui/card";
import { ScrollArea } from "@/Components/ui/scroll-area";
import Badge from "@/Components/ui/badge/Badge.vue";
import EditSessionForm from "./EditSessionForm.vue";
import {
    Accordion,
    AccordionContent,
    AccordionItem,
    AccordionTrigger,
} from "@/Components/ui/accordion";
import ProvideSessionFeedback from "../Session/ProvideSessionFeedback.vue";

const props = defineProps({
    session: Object,
});

const emit = defineEmits(["cancelledSession", "updatedSession"]);

// Cancel a session
const cancelSession = () => {
    router.delete(route("group.sessions.destroy", { id: props.session.id }), {
        onSuccess: () => {
            toast({
                title: "Session Cancelled",
                description: "The session was successfully cancelled.",
                variant: "success",
            });
            emit("cancelledSession");
        },
        onError: (error) => {
            toast({
                title: "Error",
                description: error || "Unable to cancel session.",
                variant: "destructive",
            });
        },
    });
};

// Join a session
const joinSession = async () => {
    router.post(
        route("group.sessions.join", { session: props.session.id }),
        {},
        {
            preserveScroll: true,
            preserveState: true,
            onSuccess: () => {
                toast({
                    title: "Session Joined",
                    description: "You have successfully joined the session.",
                    variant: "success",
                });
                props.session.participants.push(usePage().props.auth.user);
            },
            onError: () => {
                toast({
                    title: "Error",
                    description: "Unable to join session.",
                    variant: "destructive",
                });
            },
        }
    );
};

// Leave a session
const leaveSession = async () => {
    router.post(
        route("group.sessions.leave", { session: props.session.id }),
        {},
        {
            preserveScroll: true,
            preserveState: true,
            onSuccess: () => {
                toast({
                    title: "Session Left",
                    description: "You have successfully left the session.",
                    variant: "success",
                });
                const userIndex = props.session.participants.findIndex(
                    (participant) =>
                        participant.id === usePage().props.auth.user.id
                );
                if (userIndex !== -1) {
                    props.session.participants.splice(userIndex, 1);
                }
            },
            onError: (error) => {
                toast({
                    title: "Error",
                    description: error || "Unable to leave session.",
                    variant: "destructive",
                });
            },
        }
    );
};

// Update the session
const updateSession = () => {
    emit("updatedSession");
};
</script>

<template>
    <Card>
        <CardHeader>
            <div class="flex justify-end items-center gap-2">
                <ProvideSessionFeedback
                    @feedbackSubmitted="updateSession"
                    :sessionId="session.id"
                    v-if="
                        new Date(session.date_time) < new Date() &&
                        session.participants.some(
                            (participant) =>
                                participant.id === $page.props.auth.user.id &&
                                session.leader_id != $page.props.auth.user.id &&
                                !session.feedbacks.some(
                                    (feedback) =>
                                        feedback.user_id === participant.id
                                )
                        )
                    "
                />
                <template v-if="session.leader_id === $page.props.auth.user.id && new Date(session.date_time) > new Date()">
                    <AlertDialog>
                        <AlertDialogTrigger as-child>
                            <Button variant="outline" size="sm">
                                <LogOut class="w-4 h-4 mr-2" />
                                <span
                                    class="sr-only sm:not-sr-only sm:whitespace-nowrap"
                                    >Cancel Session</span
                                >
                            </Button>
                        </AlertDialogTrigger>
                        <AlertDialogContent>
                            <AlertDialogHeader>
                                <AlertDialogTitle
                                    >Are you sure you want to cancel the
                                    session?</AlertDialogTitle
                                >
                                <AlertDialogDescription>
                                    Are you sure you want to cancel this
                                    session? This action cannot be undone.
                                </AlertDialogDescription>
                            </AlertDialogHeader>
                            <AlertDialogFooter>
                                <AlertDialogCancel>Cancel</AlertDialogCancel>
                                <AlertDialogAction @click="cancelSession"
                                    >Confirm</AlertDialogAction
                                >
                            </AlertDialogFooter>
                        </AlertDialogContent>
                    </AlertDialog>

                    <EditSessionForm
                        :session="session"
                        @updatedSession="updateSession"
                    />
                </template>
                <Button
                    @click="joinSession"
                    class="bg-primary"
                    size="sm"
                    v-if="
                        !session.participants.some(
                            (participant) =>
                                participant.id === $page.props.auth.user.id
                        ) &&
                        session.leader_id !== $page.props.auth.user.id &&
                        new Date(session.date_time) > new Date() && session.participants.length < session.participation_limit
                    "
                >
                    <CirclePlus class="h-3.5 w-3.5" />
                    <span class="sr-only sm:not-sr-only sm:whitespace-nowrap"
                        >Join Session</span
                    >
                </Button>

                <Button
                    @click="leaveSession"
                    class="bg-danger"
                    size="sm"
                    variant="outline"
                    v-if="
                        session.participants.some(
                            (participant) =>
                                participant.id === $page.props.auth.user.id
                        ) && session.leader_id != $page.props.auth.user.id
                    "
                >
                    <LogOut class="w-4 h-4 mr-2" />
                    <span class="sr-only sm:not-sr-only sm:whitespace-nowrap"
                        >Leave Session</span
                    >
                </Button>
            </div>
            <CardTitle class="text-2xl font-bold">{{
                session.session_name
            }}</CardTitle>
        </CardHeader>
        <CardContent>
            <div class="space-y-4">
                <p class="text-muted-foreground col-span-full">
                    {{ session.description }}
                </p>
                <div
                    class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 sm:gap-4"
                >
                    <div class="flex items-center gap-2">
                        <Calendar class="w-5 h-5 text-primary" />
                        <span>{{
                            new Date(session.date_time).toLocaleDateString(
                                "en-US",
                                {
                                    year: "numeric",
                                    month: "long",
                                    day: "numeric",
                                }
                            )
                        }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <Clock class="w-5 h-5 text-primary" />
                        <span
                            >{{
                                new Date(session.date_time).toLocaleTimeString(
                                    "en-US",
                                    {
                                        hour: "numeric",
                                        minute: "numeric",
                                        hour12: true,
                                    }
                                )
                            }}
                            -
                            {{
                                new Date(
                                    new Date(session.date_time).getTime() +
                                        session.duration * 60 * 60 * 1000
                                ).toLocaleTimeString("en-US", {
                                    hour: "numeric",
                                    minute: "numeric",
                                    hour12: true,
                                })
                            }}
                        </span>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <Users class="w-5 h-5 text-primary" />
                    <span>
                        {{ session.participants.length }} /
                        {{ session.participation_limit }} participants
                    </span>
                </div>

                <div class="flex items-center gap-2">
                    <Dumbbell class="w-5 h-5 text-primary" />
                    <span
                        >Equipment provided:
                        {{ session.equipment_provided ? "Yes" : "No" }}</span
                    >
                </div>

                <div class="flex items-center gap-2 col-span-full">
                    <MapPin class="w-5 h-5 text-primary" />
                    <span>{{ session.location }}</span>
                </div>
            </div>

            <Accordion type="single" collapsible class="w-full py-4">
                <AccordionItem value="members">
                    <AccordionTrigger>Participant List</AccordionTrigger>
                    <AccordionContent>
                        <ScrollArea
                            class="h-[200px] w-full rounded-md border p-4"
                        >
                            <div
                                v-for="(
                                    participant, index
                                ) in session.participants"
                                :key="index"
                                class="mb-2"
                            >
                                <p class="font-medium">
                                    {{ participant.first_name }}
                                    {{ participant.last_name }}
                                </p>
                                <p class="text-sm text-muted-foreground">
                                    {{ participant.email }}
                                </p>
                            </div>
                        </ScrollArea>
                    </AccordionContent>
                </AccordionItem>
                <AccordionItem value="feedbacks">
                    <AccordionTrigger>Session Feedbacks</AccordionTrigger>
                    <AccordionContent>
                        <ScrollArea
                            class="h-[300px] w-full rounded-md border p-4"
                        >
                            <div
                                v-for="feedback in session.feedbacks"
                                :key="feedback.id"
                                class="mb-4 pb-4 border-b border-gray-200 last:border-b-0"
                            >
                                <div class="flex items-center justify-between">
                                    <span class="font-medium">{{
                                        feedback.user.first_name +
                                        " " +
                                        feedback.user.last_name
                                    }}</span>
                                    <div class="flex items-center">
                                        <template
                                            v-for="index in 5"
                                            :key="index"
                                        >
                                            <Star
                                                class="w-4 h-4"
                                                :class="
                                                    index <= feedback.rating
                                                        ? 'text-yellow-400 fill-yellow-400'
                                                        : 'text-gray-300'
                                                "
                                            />
                                        </template>
                                    </div>
                                </div>
                                <p
                                    v-if="feedback.comments"
                                    class="text-sm text-gray-600"
                                >
                                    {{ feedback.comments }}
                                </p>
                            </div>
                        </ScrollArea>
                    </AccordionContent>
                </AccordionItem>
            </Accordion>
        </CardContent>
    </Card>
</template>
