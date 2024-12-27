<script setup>
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from "@/Components/ui/table";
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from "@/Components/ui/dropdown-menu";
import { Badge } from "@/Components/ui/badge";
import { Button } from "@/Components/ui/button";
import { MoreHorizontal, Edit, Trash2, Eye } from "lucide-vue-next";
import { formatDate } from "@vueuse/core";
import { router } from "@inertiajs/vue3";

const events = [
    {
        id: "1",
        name: "Summer Basketball Tournament",
        sportType: "Basketball",
        registrationType: "team",
        venue: "Main Stadium",
        eventDate: "2024-07-15",
        startTime: "09:00",
        status: "Upcoming",
        createdAt: "2024-12-26T14:30:00Z",
        posterImage: "/placeholder.svg",
    },
    {
        id: "2",
        name: "City Tennis Championship",
        sportType: "Tennis",
        registrationType: "individual",
        venue: "Tennis Complex",
        eventDate: "2024-06-20",
        startTime: "10:00",
        status: "Completed",
        createdAt: "2024-12-25T09:15:00Z",
        posterImage: "/placeholder.svg",
    },
    {
        id: "3",
        name: "Volleyball League",
        sportType: "Volleyball",
        registrationType: "team",
        venue: "Community Center",
        eventDate: "2024-08-01",
        startTime: "14:00",
        status: "Canceled",
        createdAt: "2024-12-24T16:45:00Z",
        posterImage: "/placeholder.svg",
    },
];

const showEvent = (event) => {
    router.get(route("admin.events.show", { event: event.id }));
};

const editEvent = (event) => {
    router.get(route("admin.events.show", { event: event.id, editMode: true }));
};

</script>

<template>
    <div class="rounded-md border">
        <Table class="w-full">
            <TableHeader>
                <TableRow>
                    <TableHead class="hidden w-[100px] sm:table-cell">
                        <span class="sr-only">img</span>
                    </TableHead>
                    <TableHead>Event</TableHead>
                    <TableHead class="hidden md:table-cell"
                        >Sport Type</TableHead
                    >
                    <TableHead class="hidden md:table-cell"
                        >Registration</TableHead
                    >
                    <TableHead class="hidden md:table-cell">Venue</TableHead>
                    <TableHead>Date & Time</TableHead>
                    <TableHead class="hidden md:table-cell">Status</TableHead>
                    <TableHead class="hidden md:table-cell">Created</TableHead>
                    <TableHead>
                        <span class="sr-only">Actions</span>
                    </TableHead>
                </TableRow>
            </TableHeader>
            <TableBody>
                <TableRow v-for="event in events" :key="event.id">
                    <TableCell class="hidden sm:table-cell">
                        <div class="h-16 w-16 rounded-md overflow-hidden">
                            <img
                                :src="event.posterImage"
                                :alt="event.name"
                                width="64"
                                height="64"
                                class="h-full w-full object-cover"
                            />
                        </div>
                    </TableCell>
                    <TableCell class="font-medium">{{ event.name }}</TableCell>
                    <TableCell class="hidden md:table-cell">{{
                        event.sportType
                    }}</TableCell>
                    <TableCell class="hidden md:table-cell">{{
                        event.registrationType
                    }}</TableCell>
                    <TableCell class="hidden md:table-cell">{{
                        event.venue
                    }}</TableCell>
                    <TableCell>
                        {{
                            formatDate(new Date(event.eventDate), "YYYY-MM-DD")
                        }}
                        at {{ event.startTime }}
                    </TableCell>
                    <TableCell class="hidden md:table-cell">{{
                        event.status
                    }}</TableCell>
                    <TableCell class="hidden md:table-cell">
                        {{
                            formatDate(new Date(event.createdAt), "YYYY-MM-DD")
                        }}
                    </TableCell>
                    <TableCell>
                        <DropdownMenu>
                            <DropdownMenuTrigger as-child>
                                <Button variant="ghost" size="icon">
                                    <MoreHorizontal class="h-4 w-4" />
                                    <span class="sr-only">Open menu</span>
                                </Button>
                            </DropdownMenuTrigger>
                            <DropdownMenuContent align="end">
                                <DropdownMenuItem @click="showEvent(event)">
                                    <Eye class="w-4 h-4 mr-2" />
                                    View
                                </DropdownMenuItem>
                                <DropdownMenuItem @click="editEvent(event)">
                                    <Edit class="w-4 h-4 mr-2" />
                                    Edit
                                </DropdownMenuItem>
                                <DropdownMenuItem>
                                    <Trash2 class="w-4 h-4 mr-2" />
                                    Delete
                                </DropdownMenuItem>
                            </DropdownMenuContent>
                        </DropdownMenu>
                    </TableCell>
                </TableRow>
            </TableBody>
        </Table>
    </div>
</template>
