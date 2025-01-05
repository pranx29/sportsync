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
import { router, usePage } from "@inertiajs/vue3";
import { defineProps, ref, watch } from "vue";
import { toast } from "@/Components/ui/toast";

const props = defineProps({
    events: Array,
});

const events = ref(props.events);

watch(
    () => props.events,
    (newEvents) => {
        events.value = newEvents;
    }
);

const showEvent = (event) => {
    router.get(route("admin.events.show", { event: event.id }));
};

const editEvent = (event) => {
    router.get(route("admin.events.show", { event: event.id, editMode: true }));
};

const cancelEvent = (event) => {
    router.patch(route("admin.events.cancel", { event: event.id }), null, {
        preserveScroll: true,
        onSuccess: () => {
            toast({
                title: "Event canceled",
                description: "Event has been canceled successfully",
            });
            events.value = usePage().props.events;
        },
    });
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
                    <TableHead>
                        <span class="sr-only">Actions</span>
                    </TableHead>
                </TableRow>
            </TableHeader>
            <TableBody>
                <TableRow v-for="event in events" :key="event.id">
                    <TableCell class="hidden sm:table-cell">
                        <div class="h-16 w-24 rounded-md overflow-hidden">
                            <img
                                :src="event.image"
                                :alt="event.name"
                                width="64"
                                height="64"
                                class="h-full w-full object-cover"
                            />
                        </div>
                    </TableCell>
                    <TableCell class="font-medium">{{ event.name }}</TableCell>
                    <TableCell class="hidden md:table-cell">{{
                        event.sport.name
                    }}</TableCell>
                    <TableCell class="hidden md:table-cell">{{
                        event.registration_type.charAt(0).toUpperCase() +
                        event.registration_type.slice(1)
                    }}</TableCell>
                    <TableCell
                        class="hidden md:table-cell"
                        v-if="event.venue && !event.custom_location_name"
                        >{{ event.venue.name }}</TableCell
                    >
                    <TableCell class="hidden md:table-cell" v-else>
                        <Button variant="link" asChild class="p-0">
                            <a
                                :href="event.custom_location_link"
                                target="_blank"
                            >
                                {{ event.custom_location_name }}
                            </a>
                        </Button>
                    </TableCell>

                    <TableCell>
                        {{
                            formatDate(new Date(event.event_date), "YYYY-MM-DD")
                        }}
                        at {{ event.start_time }}
                    </TableCell>
                    <TableCell class="hidden md:table-cell">
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

                                <DropdownMenuItem @click="cancelEvent(event)">
                                    <Trash2 class="w-4 h-4 mr-2" />
                                    Cancel
                                </DropdownMenuItem>
                            </DropdownMenuContent>
                        </DropdownMenu>
                    </TableCell>
                </TableRow>

                <TableRow v-if="events.length === 0">
                    <TableCell :colspan="7" class="text-center">
                        No events found
                    </TableCell>
                </TableRow>
            </TableBody>
        </Table>
    </div>
</template>
