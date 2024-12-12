<script setup>
import { ref } from "vue";
import { User, Send, ChevronDown, ChevronUp, Navigation } from "lucide-vue-next";
import AuthenticatedLayout from "@/Layouts/EmployeeLayout.vue";
import { Card, CardHeader, CardTitle, CardDescription, CardFooter } from "@/Components/ui/card";
import { Avatar, AvatarImage, AvatarFallback } from "@/Components/ui/avatar";
import { Input } from "@/Components/ui/input";
import { Button } from "@/Components/ui/button";
import { ScrollArea } from "@/Components/ui/scroll-area";
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from "@/Components/ui/dropdown-menu";
import Badge from "@/Components/ui/badge/Badge.vue";
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
import { toast, Toaster } from "@/Components/ui/toast";
import { LogOut, CirclePlus, MessageCircle, EditIcon, CircleX } from "lucide-vue-next";
import { router, usePage } from "@inertiajs/vue3";
import CreateSessionForm from "../Session/CreateSessionForm.vue";
import EditSessionForm from '../Session/EditSessionForm.vue';
import FeedbackForm from "../Session/FeedbackForm.vue";

const { props } = usePage();

const sessions = ref(props.availableSessions || []);
const joinedSessions = ref(props.joinedSessions || []);
const pastSessions = ref(props.pastSessions || []);

const leaveGroup = () => {
    if (props.group.leader.id === props.auth.user.id) {
        toast({
            title: "Action Denied",
            description:
                "As the group leader, you cannot leave the group. Please contact the admin to transfer leadership.",
            variant: "destructive",
        });
        return;
    }

    router.post(
        route("employee.groups.leave"),
        {
            id: props.group.id,
        },
        {
            onError: (errors) => {
                toast({
                    title: "Failed to Leave Group",
                    description: errors[0].message,
                    variant: "destructive",
                });
            },
        }
    );
};

const messages = ref([
    { id: 1, sender: "Alice Johnson", text: "Hey team, how's everyone doing today?" },
    { id: 2, sender: "Bob Smith", text: "I'm good! Just finished the report." },
    { id: 3, sender: "You", text: "Great job, Bob! Can you share it with the team?" },
    { id: 4, sender: "Charlie Brown", text: "Looking forward to reviewing it!" },
]);

const newMessage = ref("");

const sendMessage = () => {
    if (newMessage.value.trim()) {
        messages.value.push({
            id: messages.value.length + 1,
            sender: "You",
            text: newMessage.value.trim(),
        });
        newMessage.value = "";
    }
};

const isMembersExpanded = ref(false);
const isSessionsExpanded = ref(false);
const isJoinedSessionsExpanded = ref(false);
const isPastSessionsExpanded = ref(false);

const toggleMembers = () => {
    isMembersExpanded.value = !isMembersExpanded.value;
};

const toggleSessions = () => {
    isSessionsExpanded.value = !isSessionsExpanded.value;
};

const toggleJoinedSessions = () => {
    isJoinedSessionsExpanded.value = !isJoinedSessionsExpanded.value;
};

const togglePastSessions = () => {
    isPastSessionsExpanded.value = !isPastSessionsExpanded.value;
};

const selectedSession = ref(null);
const isChatView = ref(true);
const isFeedbackView = ref(false);

const viewSessionDetails = (session) => {
    selectedSession.value = session;
    isChatView.value = false;
    isFeedbackView.value = false;
};

const isValidUrl = (string) => {
    try {
        const url = new URL(string);
        return url.protocol === "http:" || url.protocol === "https:";
    } catch (e) {
        return false;
    }
};

const goBackToChat = () => {
    isChatView.value = true;
    isFeedbackView.value = false;
};

// console.log("Sessions data from props:", props.sessions);

const updateSessionLists = () => {
    const now = new Date();

    joinedSessions.value = joinedSessions.value.filter((session) => {
        const sessionEnd = new Date(session.date_time);
        sessionEnd.setMinutes(sessionEnd.getMinutes() + session.duration);

        if (now >= sessionEnd) {
            pastSessions.value.push(session);
            return false;
        }

        return true;
    });

    // Update regular sessions list
    sessions.value = sessions.value.filter((session) => {
        const sessionEnd = new Date(session.date_time);
        sessionEnd.setMinutes(sessionEnd.getMinutes() + session.duration);

        return now < sessionEnd; // Keep future sessions
    });
};

// Set up a periodic update to session lists every minute
setInterval(updateSessionLists, 10000);

const joinSession = async () => {
    try {
        const response = await axios.post(route("sessions.join", { id: selectedSession.value.id }));
        
        selectedSession.value.participants = response.data.session.participants;
        
        toast({
            title: "Joined Session",
            description: response.data.message,
            variant: "success",
        });
    } catch (error) {
        toast({
            title: "Cannot join the session",
            description: error.response?.data.message || "Unable to join session.",
            variant: "destructive",
        });
    }
};

const leaveSession = async () => {
    try {
        await router.post(route("sessions.leave", { id: selectedSession.value.id }));
        selectedSession.value.participants = selectedSession.value.participants.filter(
            (participant) => participant.id !== props.auth.user.id
        );
        toast({ 
            title: "Left Session", 
            description: "You successfully left the session.", 
            variant: "destructive" });
    } catch (error) {
        toast({ 
            title: "Error", 
            description: error.response?.data.message || "Unable to leave session.", 
            variant: "destructive" });
    }
};

const cancelSession = async () => {
    try {
        await router.delete(route("sessions.destroy", { id: selectedSession.value.id }));
        sessions.value = sessions.value.filter((session) => session.id !== selectedSession.value.id);
        toast({ title: "Session Cancelled", description: "The session was successfully cancelled.", variant: "success" });
        goBackToChat();
    } catch (error) {
        toast({ title: "Error", description: error.response?.data.message || "Unable to cancel session.", variant: "destructive" });
    }
};

const viewFeedbackForm = (session) => {
    selectedSession.value = session;
    isChatView.value = false;
    isFeedbackView.value = true;
};
</script>

<template>
    <AuthenticatedLayout>
        <div class="container mx-auto p-4 space-y-4">
            <div class="ml-auto flex items-center justify-between gap-1">
                <h1 class="text-2xl font-bold text-foreground">{{ $page.props.group.name }}</h1>
                <div class="flex space-x-2">
                    <AlertDialog>
                        <CreateSessionForm />
                        <AlertDialogTrigger as-child>
                            <Button variant="outline" size="sm">
                                <LogOut class="w-4 h-4 mr-2" />
                                <span class="sr-only sm:not-sr-only sm:whitespace-nowrap">Leave Group</span>
                            </Button>
                        </AlertDialogTrigger>
                        <AlertDialogContent>
                            <AlertDialogHeader>
                                <AlertDialogTitle>Are you sure you want to leave the group?</AlertDialogTitle>
                                <AlertDialogDescription>
                                    This action cannot be undone. You will be removed from the group and lose access to its resources.
                                </AlertDialogDescription>
                            </AlertDialogHeader>
                            <AlertDialogFooter>
                                <AlertDialogCancel>Cancel</AlertDialogCancel>
                                <AlertDialogAction @click="leaveGroup">Continue</AlertDialogAction>
                            </AlertDialogFooter>
                        </AlertDialogContent>
                    </AlertDialog>
                </div>
            </div>

            <div class="grid lg:grid-cols-[300px_1fr] gap-4">
                <!-- Members List -->
                <Card class="h-[calc(100vh-10rem)] flex flex-col">
                    <CardHeader @click="toggleMembers" class="cursor-pointer">
                        <CardTitle>Members <span v-if="!isMembersExpanded">+</span><span v-else>-</span></CardTitle>
                    </CardHeader>
                    <transition
                        name="dropdown"
                        enter-active-class="transition-max-height duration-300 ease-in-out"
                        leave-active-class="transition-max-height duration-300 ease-in-out"
                        enter-from-class="max-h-0"
                        enter-to-class="max-h-[500px]"
                        leave-from-class="max-h-[500px]"
                        leave-to-class="max-h-0">
                        <ScrollArea v-show="isMembersExpanded" class="flex-1 overflow-y-auto p-6">
                            <ul class="space-y-4">
                                <li class="flex items-center space-x-3">
                                    <Avatar>
                                        <AvatarImage
                                            :src="`https://api.dicebear.com/6.x/initials/svg?seed=${$page.props.leader.first_name}+${$page.props.leader.last_name}&fontSize=32`"
                                        />
                                        <AvatarFallback>
                                            <User class="w-4 h-4" />
                                        </AvatarFallback>
                                    </Avatar>
                                    <div class="w-full">
                                        <div class="flex justify-between">
                                            <h3 class="font-medium text-sm">
                                                {{ $page.props.leader.first_name }} {{ $page.props.leader.last_name }}
                                            </h3>
                                            <Badge variant="outline">Leader</Badge>
                                        </div>
                                        <p class="text-xs text-muted-foreground">{{ $page.props.leader.email }}</p>
                                    </div>
                                </li>
                                <li v-for="member in $page.props.members" :key="member.id" class="flex items-center space-x-3">
                                    <Avatar>
                                        <AvatarImage
                                            :src="`https://api.dicebear.com/6.x/initials/svg?seed=${member.first_name}+${member.last_name}&fontSize=32`"
                                        />
                                        <AvatarFallback>
                                            <User class="w-4 h-4" />
                                        </AvatarFallback>
                                    </Avatar>
                                    <div>
                                        <h3 class="font-medium text-sm">{{ member.first_name }} {{ member.last_name }}</h3>
                                        <p class="text-xs text-muted-foreground">{{ member.email }}</p>
                                    </div>
                                </li>
                            </ul>
                        </ScrollArea>
                    </transition>

                    <!-- Sessions List -->
                    <CardHeader @click="toggleSessions" class="cursor-pointer">
                        <CardTitle>Sessions <span v-if="!isSessionsExpanded">+</span><span v-else>-</span></CardTitle>
                    </CardHeader>
                    <transition
                        name="dropdown"
                        enter-active-class="transition-max-height duration-300 ease-in-out"
                        leave-active-class="transition-max-height duration-300 ease-in-out"
                        enter-from-class="max-h-0"
                        enter-to-class="max-h-[500px]"
                        leave-from-class="max-h-[500px]"
                        leave-to-class="max-h-0">
                        <ScrollArea v-show="isSessionsExpanded" class="flex-1 overflow-y-auto p-6">
                            <template v-if="sessions && sessions.length">
                                <div v-for="session in sessions" :key="session.id" class="mb-2">
                                    <Card @click="viewSessionDetails(session)" class="flex flex-col sm:flex-row items-center cursor-pointer">
                                        <CardHeader class="flex-1 flex flex-col sm:flex-row items-start">
                                            <div class="flex-1">
                                                <CardTitle class="text-xl">{{ session.session_name }}</CardTitle>
                                                <time class="text-sm">{{ new Date(session.date_time).toLocaleString() }}</time>
                                                <CardDescription>{{ session.description }}</CardDescription>
                                            </div>
                                        </CardHeader>
                                    </Card>
                                </div>
                            </template>
                            <template v-else>
                                <p class="text-sm text-muted-foreground">No sessions available.</p>
                            </template>
                        </ScrollArea>
                    </transition>

                    <!-- Joined Sessions -->
                    <CardHeader @click="toggleJoinedSessions" class="cursor-pointer">
                        <CardTitle>Joined Sessions <span v-if="!isJoinedSessionsExpanded">+</span><span v-else>-</span></CardTitle>
                    </CardHeader>
                    <transition 
                        name="dropdown" 
                        enter-active-class="transition-max-height duration-300 ease-in-out" 
                        leave-active-class="transition-max-height duration-300 ease-in-out" 
                        enter-from-class="max-h-0" 
                        enter-to-class="max-h-[500px]" 
                        leave-from-class="max-h-[500px]" 
                        leave-to-class="max-h-0">
                        <ScrollArea v-show="isJoinedSessionsExpanded" class="flex-1 overflow-y-auto p-6">
                            <template v-if="joinedSessions && joinedSessions.length">
                                <div v-for="session in joinedSessions" :key="session.id" class="mb-2">
                                    <Card @click="viewSessionDetails(session)" class="flex flex-col sm:flex-row items-center cursor-pointer">
                                        <CardHeader class="flex-1 flex flex-col sm:flex-row items-start">
                                            <div class="flex-1">
                                                <CardTitle class="text-xl">{{ session.session_name }}</CardTitle>
                                                <time class="text-sm">{{ new Date(session.date_time).toLocaleString() }}</time>
                                                <CardDescription>{{ session.description }}</CardDescription>
                                            </div>
                                        </CardHeader>
                                    </Card>
                                </div>
                            </template>
                            <template v-else>
                                <p class="text-sm text-muted-foreground">No joined sessions available.</p>
                            </template>
                        </ScrollArea>
                    </transition>

                    <!-- Past Sessions -->
                    <CardHeader @click="togglePastSessions" class="cursor-pointer">
                        <CardTitle>
                            Past Sessions 
                            <span v-if="!isPastSessionsExpanded">+</span>
                            <span v-else>-</span>
                        </CardTitle>
                    </CardHeader>
                    <transition
                        name="dropdown"
                        enter-active-class="transition-max-height duration-300 ease-in-out"
                        leave-active-class="transition-max-height duration-300 ease-in-out"
                        enter-from-class="max-h-0"
                        enter-to-class="max-h-[500px]"
                        leave-from-class="max-h-[500px]"
                        leave-to-class="max-h-0"
                    >
                        <ScrollArea v-show="isPastSessionsExpanded" class="flex-1 overflow-y-auto p-6">
                            <template v-if="pastSessions && pastSessions.length">
                                <div v-for="session in pastSessions" :key="session.id" class="mb-2">
                                    <Card class="flex flex-col sm:flex-row items-center">
                                        <CardHeader class="flex-1 flex flex-col sm:flex-row items-start">
                                            <div class="mr-4 flex-1">
                                                <CardTitle>{{ session.session_name }}</CardTitle>
                                                <time class="text-sm">{{ new Date(session.date_time).toLocaleString() }}</time>
                                                <CardDescription>{{ session.description }}</CardDescription>
                                            </div>
                                        </CardHeader>
                                        <div class="flex space-x-2 p-3">
                                            <Button @click="viewFeedbackForm(session)" class="bg-primary" size="sm">
                                                Feedback
                                            </Button>
                                        </div>
                                    </Card>
                                </div>
                            </template>
                            <template v-else>
                                <p class="text-sm text-muted-foreground">No past sessions available.</p>
                            </template>
                        </ScrollArea>
                    </transition>
                </Card>
                
                <Card class="h-[calc(100vh-12rem)] flex flex-col">
                    <CardHeader v-if="isChatView">
                        <CardTitle>Group Chat</CardTitle>
                    </CardHeader>
                    <CardHeader v-else-if="!isFeedbackView && selectedSession" class="flex flex-row items-start bg-muted/50">
                        <div class="grid gap-0.5">
                            <CardTitle class="group flex items-center gap-2 text-lg">
                                {{ selectedSession.session_name }}
                            </CardTitle>
                            <CardDescription>
                                <time
                                    :dateTime="new Date(selectedSession.date_time).toISOString()"
                                >
                                    {{
                                        new Date(selectedSession.date_time).toLocaleDateString(
                                            "en-US",
                                            { year: "numeric", month: "long", day: "numeric" }
                                        )
                                    }}
                                    at
                                    {{
                                        new Date(selectedSession.date_time).toLocaleTimeString(
                                            "en-US",
                                            { hour: "2-digit", minute: "2-digit" }
                                        )
                                    }}
                                </time>
                            </CardDescription>
                        </div>
                        <div class="ml-auto flex items-center gap-1">
                            <template v-if="selectedSession && props.auth.user.id === selectedSession.leader_id">
                                <AlertDialog>
                                    <AlertDialogTrigger as-child>
                                        <Button variant="outline" size="sm">
                                            <CircleX class="w-4 h-4" />
                                            <span class="sr-only sm:not-sr-only sm:whitespace-nowrap">Cancel Session</span>
                                        </Button>
                                    </AlertDialogTrigger>
                                    <AlertDialogContent>
                                        <AlertDialogHeader>
                                            <AlertDialogTitle>Are you sure you want to cancel the session?</AlertDialogTitle>
                                            <AlertDialogDescription>
                                                Are you sure you want to cancel this session? This action cannot be undone.
                                            </AlertDialogDescription>
                                        </AlertDialogHeader>
                                        <AlertDialogFooter>
                                            <AlertDialogCancel>Cancel</AlertDialogCancel>
                                            <AlertDialogAction @click="cancelSession">Confirm</AlertDialogAction>
                                        </AlertDialogFooter>
                                    </AlertDialogContent>
                                </AlertDialog>
                                <DropdownMenu>
                                    <DropdownMenuTrigger as-child>
                                        <Button size="icon" variant="outline" class="h-8 w-8">
                                            <ChevronDown class="h-4 w-4" />
                                            <span class="sr-only">Session Options</span>
                                        </Button>
                                    </DropdownMenuTrigger>
                                    <DropdownMenuContent align="end">
                                        <EditSessionForm :session="selectedSession">
                                            <DropdownMenuItem>
                                                <EditIcon class="h-3.5 w-3.5" />
                                                Edit Session
                                            </DropdownMenuItem>
                                        </EditSessionForm>
                                    </DropdownMenuContent>
                                </DropdownMenu>
                            </template>      
                            <template v-else>
                                <Button
                                    v-if="!selectedSession.participants.find((participant) => participant.id === props.auth.user.id)"
                                    @click="joinSession"
                                    class="bg-primary"
                                    size="sm"
                                >
                                    <CirclePlus class="h-3.5 w-3.5" />
                                    <span class="sr-only sm:not-sr-only sm:whitespace-nowrap">Join Session</span>
                                </Button>
                                <Button
                                    v-else
                                    @click="leaveSession"
                                    class="bg-danger"
                                    size="sm"
                                    variant="outline"
                                >
                                    <LogOut class="w-4 h-4 mr-2" />
                                    <span class="sr-only sm:not-sr-only sm:whitespace-nowrap">Leave Session</span>
                                </Button>
                            </template>                      
                        </div>
                    </CardHeader>

                    <div class="flex-1 overflow-y-auto p-4 space-y-4">
                        <!-- Chat View -->
                        <div v-if="isChatView">
                            <div
                                v-for="message in messages"
                                :key="message.id"
                                class="flex"
                                :class="{ 'justify-end': message.sender === 'You' }"
                            >
                                <div
                                    class="max-w-[80%] px-4 py-2 rounded-lg"
                                    :class="message.sender === 'You'
                                        ? 'bg-primary text-primary-foreground'
                                        : 'bg-muted'"
                                >
                                    <p class="text-sm">{{ message.text }}</p>
                                    <span class="text-xs mt-1 opacity-75 block">{{ message.sender }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Feedback View -->
                        <div v-else-if="isFeedbackView">
                            <FeedbackForm :session="selectedSession" />
                        </div>
                        
                        <!-- Session Details View -->
                        <div v-else class="space-y-4">
                            <div class="grid gap-0.5">
                                <p class="text-sm text-muted-foreground py-2">
                                    <strong>{{ selectedSession?.description }}</strong>
                                </p>
                                <p>
                                    <strong>Location: </strong>
                                    <span v-if="isValidUrl(selectedSession.location)">
                                        <a :href="selectedSession.location" target="_blank" rel="noopener noreferrer" 
                                            class="text-blue-600 hover:text-blue-800"
                                        >
                                            {{ selectedSession.location }}
                                        </a>
                                    </span>
                                    <span v-else>
                                        {{ selectedSession.location }}
                                    </span>
                                </p>
                                <p class="text-sm">
                                    <strong>Session duration:</strong> 
                                    {{ selectedSession?.duration }}
                                </p>
                                <p class="text-sm">
                                    <strong>Participation Limit:</strong> 
                                    {{ selectedSession?.participation_limit }}
                                </p>
                                <p class="text-sm">
                                    <strong>Equipment Provided:</strong>
                                    {{ selectedSession?.equipment_provided ? 'Yes' : 'No' }}
                                </p>
                                <p class="text-sm p-4">
                                    <strong>Group Members Joined:</strong>
                                </p>
                                
                                <!-- Member List -->
                                <ul class="space-y-4 px-4">                                    
                                    <li
                                        v-for="member in selectedSession?.participants"
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
                                            <h3 class="font-medium text-sm">{{ member.first_name }} {{ member.last_name }}</h3>
                                            <p class="text-xs text-muted-foreground">{{ member.email }}</p>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>

                    </div>

                    <!-- Chat Input Area -->
                    <div class="p-4 border-t border-border" v-if="isChatView">
                        <form @submit.prevent="sendMessage" class="flex space-x-2">
                            <Input
                                v-model="newMessage"
                                type="text"
                                placeholder="Type a message..."
                                class="flex-1"
                            />
                            <Button type="submit">
                                Send
                                <Send class="ml-2 h-4 w-4" />
                            </Button>
                        </form>
                    </div>
                    <div v-else class="p-4">
                        <Button @click="goBackToChat" class="mt-4" size="sm">
                            <span class="sr-only sm:not-sr-only sm:whitespace-nowrap">Group Chat</span>
                            <MessageCircle class="w-4 h-4" />
                        </Button>
                    </div>
                </Card>

            </div>
        </div>
        <Toaster/>
    </AuthenticatedLayout>
</template>
