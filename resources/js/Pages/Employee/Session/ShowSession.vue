<script setup>
import { ref, defineEmits } from "vue";
import {
    ChevronDown,
    CirclePlus,
    LogOut,
    EditIcon,
    User,
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
import Badge from "@/Components/ui/badge/Badge.vue";

const props = defineProps({
    session: Object,
});

const emit = defineEmits(["cancelledSession"]);

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
</script>

<template>
    <Card>
        <CardHeader>
            <div class="flex justify-end items-center gap-2">
                <template v-if="session.leader_id === $page.props.auth.user.id">
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

                    <Button variant="outline" size="sm">
                        <EditIcon class="w-4 h-4" />
                    </Button>
                </template>

                <Button
                    @click="joinSession"
                    class="bg-primary"
                    size="sm"
                    v-if="
                        !session.participants.some(
                            (participant) =>
                                participant.id === $page.props.auth.user.id
                        ) && session.leader_id !== $page.props.auth.user.id
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
        </CardHeader>
        <CardContent>
            <div class="space-y-4" v-if="session">
                <div class="grid gap-0.5">
                    <p class="text-sm text-muted-foreground py-2">
                        <strong>{{ session?.description }}</strong>
                    </p>
                    <p>
                        <strong>Location:</strong>
                        {{ session.location }}
                    </p>
                    <p class="text-sm">
                        <strong>Participation Limit:</strong>
                        {{ session?.participation_limit }}
                    </p>
                    <p class="text-sm">
                        <strong>Equipment Provided:</strong>
                        {{ session?.equipment_provided ? "Yes" : "No" }}
                    </p>
                    <p class="text-sm p-4">
                        <strong>Group Members Joined:</strong>
                    </p>

                    <!-- Member List -->
                    <ul class="space-y-4 px-4">
                        <!-- Leader -->
                        <li
                            v-if="session?.leader"
                            class="flex items-center space-x-3"
                        >
                            <Avatar>
                                <AvatarImage
                                    :src="`https://api.dicebear.com/6.x/initials/svg?seed=${session.leader.first_name}+${session.leader.last_name}&fontSize=32`"
                                />
                                <AvatarFallback>
                                    <User class="w-4 h-4" />
                                </AvatarFallback>
                            </Avatar>
                            <div class="w-full">
                                <div class="flex justify-between">
                                    <h3 class="font-medium text-sm">
                                        {{ session.leader.first_name }}
                                        {{ session.leader.last_name }}
                                    </h3>
                                    <Badge variant="outline">Leader</Badge>
                                </div>
                                <p class="text-xs text-muted-foreground">
                                    {{ session.leader.email }}
                                </p>
                            </div>
                        </li>

                        <!-- Participants -->
                        <li
                            v-for="member in session?.participants"
                            :key="member.id"
                            class="flex items-center space-x-3"
                        >
                            <Avatar>
                                <AvatarImage
                                    :src="`https://api.dicebear.com/6.x/initials/svg?seed=${member.first_name}+${member.last_name}&fontSize=32`"
                                />
                                <AvatarFallback>
                                    <User class="w-4 h-4" />
                                </AvatarFallback>
                            </Avatar>
                            <div>
                                <h3 class="font-medium text-sm">
                                    {{ member.first_name }}
                                    {{ member.last_name }}
                                </h3>
                                <p class="text-xs text-muted-foreground">
                                    {{ member.email }}
                                </p>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </CardContent>
    </Card>
</template>
