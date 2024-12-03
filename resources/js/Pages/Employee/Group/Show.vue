<script setup>
import { ref } from "vue";
import { User, Send } from "lucide-vue-next";
import AuthenticatedLayout from "@/Layouts/EmployeeLayout.vue";
import { Card, CardHeader, CardTitle } from "@/Components/ui/card";
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
import { MoreVertical, LogOut } from "lucide-vue-next";
import { router, usePage } from "@inertiajs/vue3";
import Chat from "./Chat.vue";

const { props } = usePage();

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
</script>

<template>
    <Head :title="`Group | ${$page.props.group?.name}`" />
    <AuthenticatedLayout>
        <div class="container mx-auto p-4 space-y-4">
            <div class="ml-auto flex items-center justify-between gap-1">
                <h1 class="text-2xl font-bold text-foreground">
                    {{ $page.props.group.name }}
                </h1>
                <AlertDialog>
                    <AlertDialogTrigger as-child>
                        <Button variant="outline">
                            <LogOut class="w-4 h-4 mr-2" />
                            Leave Group
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
                                removed from the group and lose access to its
                                resources.
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
            <div class="grid lg:grid-cols-[300px_1fr] gap-4">
                <!-- Members List -->
                <Card class="h-[calc(100vh-12rem)] flex flex-col">
                    <CardHeader>
                        <CardTitle>Members</CardTitle>
                    </CardHeader>
                    <ScrollArea class="flex-1 overflow-y-auto p-4">
                        <ul class="space-y-4">
                            <li class="flex items-center space-x-3">
                                <Avatar>
                                    <AvatarImage
                                        :src="
                                            $page.props.leader.profile
                                                .profile_image
                                        "
                                    />
                                    <AvatarFallback>
                                        {{
                                            props.leader.first_name.charAt(0) +
                                            props.leader.last_name.charAt(0)
                                        }}
                                    </AvatarFallback>
                                </Avatar>
                                <div class="w-full">
                                    <div class="flex justify-between">
                                        <h3 class="font-medium text-sm">
                                            {{ $page.props.leader.first_name }}
                                            {{ $page.props.leader.last_name }}
                                        </h3>
                                        <Badge variant="outline">Leader</Badge>
                                    </div>
                                    <p class="text-xs text-muted-foreground">
                                        {{ $page.props.leader.email }}
                                    </p>
                                </div>
                            </li>
                            <li
                                v-for="member in $page.props.members"
                                :key="member.id"
                                class="flex items-center space-x-3"
                            >
                                <Avatar>
                                    <AvatarImage :src="member.profile?.profile_image" />
                                    <AvatarFallback>
                                        {{
                                            member.first_name.charAt(0) +
                                            member.last_name.charAt(0)
                                        }}
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
                    </ScrollArea>
                </Card>

                <!-- Chat Area -->
                <Chat
                    :messages="$page.props.messages"
                    :group_id="$page.props.group.id"
                />
            </div>
        </div>
    </AuthenticatedLayout>
    <Toaster />
</template>
