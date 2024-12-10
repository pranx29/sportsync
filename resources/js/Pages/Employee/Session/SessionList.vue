<script setup>
import AuthenticatedLayout from "@/Layouts/EmployeeLayout.vue";
import {
    Card,
    CardHeader,
    CardTitle,
    CardDescription,
} from "@/Components/ui/card";
import { ScrollArea } from "@/Components/ui/scroll-area";
import { Badge } from "@/Components/ui/badge";
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from "@/Components/ui/dropdown-menu";
import { ListFilter } from "lucide-vue-next";
import { Button } from "@/Components/ui/button";
import { cn } from "@/lib/utils";
import { ref } from "vue";

const props = defineProps({
    sessions: Array,
});

const selectedSession = ref(null);
</script>

<template>
    <!-- Sessions List -->
    <Card class="flex flex-col h-[calc(100vh-35vh)]">
        <template v-if="sessions.length">
            <div class="flex items-center justify-end px-4 pt-2">
                <DropdownMenu>
                    <DropdownMenuTrigger as-child>
                        <Button variant="outline" size="sm" class="h-7 gap-1">
                            <ListFilter class="h-3.5 w-3.5" />
                            <span
                                class="sr-only sm:not-sr-only sm:whitespace-nowrap"
                            >
                                Filter
                            </span>
                        </Button>
                    </DropdownMenuTrigger>
                    <DropdownMenuContent align="end">
                        <DropdownMenuLabel>Filter by</DropdownMenuLabel>
                        <DropdownMenuSeparator />
                        <DropdownMenuItem> All </DropdownMenuItem>
                        <DropdownMenuItem> Joined </DropdownMenuItem>
                        <DropdownMenuItem> Upcoming </DropdownMenuItem>
                        <DropdownMenuItem> Past </DropdownMenuItem>
                    </DropdownMenuContent>
                </DropdownMenu>
            </div>
            <ScrollArea class="h-screen flex p-4">
                <div class="flex-1 flex flex-col gap-2 pt-0">
                    <TransitionGroup name="list" appear>
                        <button
                            v-for="session of sessions"
                            :key="session.id"
                            :class="
                                cn(
                                    'flex flex-col items-start gap-3 rounded-lg border p-4 text-left text-sm transition-all hover:bg-accent',
                                    selectedSession === session.id && 'bg-muted'
                                )
                            "
                            @click="
                                selectedSession = session.id;
                                $emit('selectedSessionId', selectedSession);
                            "
                        >
                            <!-- Session Details Section -->
                            <div class="flex w-full flex-col gap-1">
                                <!-- Session Name and Date/Time -->
                                <div class="flex items-center">
                                    <div class="font-semibold text-base">
                                        {{ session.session_name }}
                                    </div>
                                    <div
                                        :class="
                                            cn(
                                                'ml-auto text-xs',
                                                selectedSession === session.id
                                                    ? 'text-foreground'
                                                    : 'text-muted-foreground'
                                            )
                                        "
                                    >
                                        {{
                                            new Date(session.date_time)
                                                .toLocaleString("en-GB", {
                                                    day: "2-digit",
                                                    month: "short",
                                                    hour: "2-digit",
                                                    minute: "2-digit",
                                                    hour12: false,
                                                })
                                                .replace(",", "")
                                        }}
                                    </div>
                                </div>

                                <!-- Location -->
                                <div class="font-medium text-muted-foreground">
                                    At {{ session.location }}
                                </div>
                            </div>

                            <!-- Session Badges Section -->
                            <div class="flex items-center gap-3">
                                <!-- Participants Badge -->
                                <Badge variant="secondary">
                                    {{
                                        session.participation_limit -
                                        session.participants.length
                                    }}
                                    spots left
                                </Badge>

                                <!-- Conditional Badges -->
                                <Badge
                                    v-if="
                                        session.participants.find(
                                            (participant) =>
                                                participant.id ===
                                                    $page.props.auth.user.id &&
                                                participant.id !==
                                                    session.leader_id
                                        )
                                    "
                                >
                                    Joined
                                </Badge>

                                <Badge
                                    v-if="
                                        session.leader_id ===
                                        $page.props.auth.user.id
                                    "
                                >
                                    Leader
                                </Badge>
                            </div>
                        </button>
                    </TransitionGroup>
                </div>
            </ScrollArea>
        </template>
        <template v-else>
            <CardDescription class="text-muted-foreground px-4 pt-2">
                No sessions yet.
            </CardDescription>
        </template>
    </Card>
</template>
