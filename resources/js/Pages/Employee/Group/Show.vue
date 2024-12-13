<script setup>
import { ref, onMounted } from "vue";
import { User, Send, ChevronDown, ChevronUp } from "lucide-vue-next";
import AuthenticatedLayout from "@/Layouts/EmployeeLayout.vue";
import {
    Card,
    CardHeader,
    CardTitle,
    CardDescription,
    CardFooter,
} from "@/Components/ui/card";
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
import {
    MoreVertical,
    LogOut,
    CirclePlus,
    MessageCircle,
    EditIcon,
    CircleX,
} from "lucide-vue-next";
import { router, usePage } from "@inertiajs/vue3";
import { Tabs, TabsContent, TabsList, TabsTrigger } from "@/Components/ui/tabs";
import {
    Collapsible,
    CollapsibleContent,
    CollapsibleTrigger,
} from "@/Components/ui/collapsible";
import Chat from "./Partials/Chat.vue";
import CreateSessionForm from "../Session/CreateSessionForm.vue";
import EditSessionForm from "../Session/EditSessionForm.vue";
import MemberList from "./Partials/MemberList.vue";
import SessionList from "../Session/SessionList.vue";
import ShowSession from "../Session/ShowSession.vue";

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

const isChatOpen = ref(true);
const selectedSession = ref(null);
const handleSelectedSession = (sessionId) => {
    selectedSession.value = usePage().props.group.sessions.find(
        (session) => session.id === sessionId
    );
    isChatOpen.value = false;
};

const handleCancelledSession = () => {
    selectedSession.value = null;
    isChatOpen.value = true;
};

const handleUpdatedSession = () => {
    console.log("Updated session");
    selectedSession.value = usePage().props.group.sessions.find(
        (session) => session.id === selectedSession.value.id
    );
    console.log(selectedSession.value);
};

const handleJoinedsession = () => {
    console.log("Joined session");
    selectedSession.value = usePage().props.group.sessions.find(
        (session) => session.id === selectedSession.value.id
    );
    console.log(selectedSession.value);
};
</script>

<template>
    <Head :title="`Group | ${$page.props.group?.name}`" />
    <AuthenticatedLayout>
        <div class="mx-auto p-4 space-y-4">
            <div class="ml-auto flex items-center justify-between gap-1">
                <h1 class="text-2xl font-bold text-foreground">
                    {{ $page.props.group.name }}
                </h1>
                <div class="flex space-x-2">
                    <AlertDialog>
                        <CreateSessionForm />
                        <AlertDialogTrigger as-child>
                            <Button variant="outline" size="sm">
                                <LogOut class="w-4 h-4 mr-2" />
                                <span
                                    class="sr-only sm:not-sr-only sm:whitespace-nowrap"
                                    >Leave Group</span
                                >
                            </Button>
                        </AlertDialogTrigger>
                        <AlertDialogContent>
                            <AlertDialogHeader>
                                <AlertDialogTitle
                                    >Are you sure you want to leave the
                                    group?</AlertDialogTitle
                                >
                                <AlertDialogDescription>
                                    This action cannot be undone. You will be
                                    removed from the group and lose access to
                                    its resources.
                                </AlertDialogDescription>
                            </AlertDialogHeader>
                            <AlertDialogFooter>
                                <AlertDialogCancel>Cancel</AlertDialogCancel>
                                <AlertDialogAction @click="leaveGroup"
                                    >Continue</AlertDialogAction
                                >
                            </AlertDialogFooter>
                        </AlertDialogContent>
                    </AlertDialog>
                    
                </div>
            </div>

            <div class="grid lg:grid-cols-[300px_1fr] gap-4">
                <Tabs default-value="members">
                    <TabsList class="grid w-full grid-cols-2">
                        <TabsTrigger value="members"> Members </TabsTrigger>
                        <TabsTrigger value="sessions"> Sessions </TabsTrigger>
                    </TabsList>
                    <TabsContent value="members">
                        <MemberList
                            class="h-[calc(100vh-27vh)]"
                            :leader="$page.props.group.leader"
                            :members="$page.props.group.users"
                        />
                    </TabsContent>
                    <TabsContent value="sessions">
                        <SessionList
                            class="h-[calc(100vh-27vh)]"
                            :sessions="$page.props.group.sessions"
                            @selectedSessionId="handleSelectedSession"
                        />
                    </TabsContent>
                </Tabs>

                <div class="flex flex-col gap-2 h-full">
                    <Button
                        variant="outline"
                        class="w-20 h-10"
                        @click="
                            isChatOpen = true;
                            selectedSession = null;
                        "
                        v-if="!isChatOpen"
                    >
                        <MessageCircle class="h-4 w-4" />
                        <span
                            class="sr-only sm:not-sr-only sm:whitespace-nowrap"
                            >Chat</span
                        >
                    </Button>
                    <Collapsible
                        v-model:open="isChatOpen"
                        v-if="!selectedSession"
                        class="h-full"
                    >
                        <CollapsibleContent class="h-full">
                            <Chat
                                :messages="$page.props.group.messages"
                                :group_id="$page.props.group.id"
                                class="h-[calc(100vh-20vh)]"
                            />
                        </CollapsibleContent>
                    </Collapsible>

                    <!-- Session Details View -->
                    <ShowSession
                        v-if="selectedSession"
                        :session="selectedSession"
                        @cancelledSession="handleCancelledSession"
                        @joinedSession="handleJoinedsession"
                        @updatedSession="handleUpdatedSession"
                    />
                </div>
            </div>
        </div>
        <Toaster />
    </AuthenticatedLayout>
</template>
