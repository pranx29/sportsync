<script setup>
import { ref } from "vue";
import { Badge } from "@/Components/ui/badge";
import { Card, CardContent, CardHeader } from "@/Components/ui/card";
import { Input } from "@/Components/ui/input";
import { Textarea } from "@/Components/ui/textarea";
import { Button } from "@/Components/ui/button";
import { Separator } from "@/Components/ui/separator";
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from "@/Components/ui/select";
import { formatDate } from "@vueuse/core";
import {
    CalendarIcon,
    Clock,
    MapPin,
    Users,
    Users2,
    ClipboardCheck,
    Bell,
    Calendar,
    Edit,
    Save,
    ChevronLeft,
} from "lucide-vue-next";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { usePage } from "@inertiajs/vue3";
import {
    Form,
    FormField,
    FormItem,
    FormLabel,
    FormControl,
    FormMessage,
} from "@/Components/ui/form";
import { useForm } from "vee-validate";
import { toTypedSchema } from "@vee-validate/zod";
import * as z from "zod";
import { router } from "@inertiajs/vue3";
import { toast } from "@/Components/ui/toast";
import { useSetFieldError } from "vee-validate";

const event = ref(usePage().props.event);

const isEditing = ref(usePage().props.editMode);

const handleEdit = () => {
    isEditing.value = isEditing.value ? false : true;
};

const editEventSchema = toTypedSchema(
    z.object({
        eventDescription: z.string().min(2, "Event description is required"),
        eventName: z.string().min(2, "Event name is required"),
        maxParticipants: z.number().min(1, "Max participants is required"),

        eventDate: z.string("Event date is required").refine((value) => {
            return new Date(value) > new Date();
        }, "Event date must be in the future"),

        startTime: z.string("Start time is required"),
        endTime: z.string("End time is required"),

        venue: z.string().min(2, "Venue is required").optional(),
        customLocationName: z.any().optional(),
        customLocationLink: z.any().optional(),
        registrationDeadline: z
            .string("Registration deadline is required")
            .refine((value) => {
                return new Date(value) > new Date();
            }, "Registration deadline must be in the future"),
        rulesDescription: z
            .string()
            .min(10, "Short rules description is required"),
    })
);

const { handleSubmit } = useForm({
    validationSchema: editEventSchema,
    initialValues: {
        eventName: event.value.name,
        venue: event.value.venue ? event.value.venue.name : "",
        eventDescription: event.value.description,
        eventDate: new Date(event.value.event_date).toISOString().split("T")[0],
        startTime: event.value.start_time,
        endTime: event.value.end_time,
        customLocationName: event.value.custom_location_name,
        customLocationLink: event.value.custom_location_link,
        maxParticipants: event.value.max_participants,
        registrationDeadline: new Date(event.value.registration_deadline)
            .toISOString()
            .split("T")[0],
        numberOfTeams: event.value.number_of_teams,
        rulesDescription: event.value.rules_description,
    },
    keepValuesOnUnmount: true,
});

const onSubmit = handleSubmit(async (values) => {
    console.log(values);

    alert("Form submitted");
    // router.patch(
    //     route("admin.events.update", { event: event.value.id }),
    //     values,
    //     {
    //         preserveScroll: true,
    //         preserveState: true,
    //         onSuccess: () => {
    //             toast({
    //                 title: "Event updated",
    //                 description:
    //                     "Event details have been updated successfully.",
    //                 variant: "success",
    //             });
    //             isEditing.value = false;
    //             // Refetch the event data
    //             event.value = usePage().props.event;
    //         },
    //         onError: (errors) => {
    //             useSetFieldError("eventName", errors.eventName);
    //             useSetFieldError("eventDescription", errors.eventDescription);
    //             useSetFieldError("maxParticipants", errors.maxParticipants);
    //             useSetFieldError("eventDate", errors.eventDate);
    //             useSetFieldError("startTime", errors.startTime);
    //             useSetFieldError("endTime", errors.endTime);
    //             useSetFieldError(
    //                 "customLocationName",
    //                 errors.customLocationName
    //             );
    //             useSetFieldError(
    //                 "customLocationLink",
    //                 errors.customLocationLink
    //             );
    //             useSetFieldError(
    //                 "registrationDeadline",
    //                 errors.registrationDeadline
    //             );
    //             useSetFieldError("rulesDescription", errors.rulesDescription);
    //             toast({
    //                 title: "Failed to update event",
    //                 description: "Please check the form for errors.",
    //                 variant: "destructive",
    //             });
    //         },
    //     }
    // );
});

const members = ref([
    {
        id: 1,
        first_name: "John",
        last_name: "Doe",
        email: "john@ss.com",
    },
    {
        id: 2,
        first_name: "Jane",
        last_name: "Smith",
        email: "jane@ss.com",
    },
    {
        id: 3,
        first_name: "Alice",
        last_name: "Johnson",
        email: "alice@ss.com",
    },
    {
        id: 4,
        first_name: "Bob",
        last_name: "Brown",
        email: "bob@ss.com",
    },
    {
        id: 5,
        first_name: "Charlie",
        last_name: "Davis",
        email: "charlie@ss.com",
    },
]);

const eventImageURL = ref(event.value.image);

const handleImageUpload = (e) => {
    const file = e.target.files?.[0];
    if (file) {
        event.value.posterImage = file;
        eventImageURL.value = URL.createObjectURL(file);
    }
};
</script>

<template>
    <Head title="Create Event" />
    <AdminLayout>
        <template #title
            ><div class="mb-4">
                <Button variant="ghost" asChild>
                    <Link :href="route('admin.events.index')">
                        <ChevronLeft class="w-4 h-4 mr-2" />
                        Back to Events
                    </Link>
                </Button>
            </div>
        </template>

        <main
            class="grid items-start gap-2 p-4 sm:px-6 sm:py-0 md:gap-4 flex-1"
        >
            <div class="grid gap-6 md:grid-cols-12">
                <Card class="md:col-span-8">
                    <CardHeader class="relative p-0">
                        <img
                            :src="eventImageURL"
                            :alt="event.name"
                            width="800"
                            height="400"
                            class="w-full h-[400px] object-cover rounded-t-lg"
                        />

                        <div class="absolute top-4 right-4 flex gap-2">
                            <Badge>{{ event.sport.name }}</Badge>
                        </div>
                    </CardHeader>
                    <CardContent class="p-6">
                        <form @submit.prevent="onSubmit" class="space-y-6">
                            <div class="flex justify-between items-center">
                                <template v-if="isEditing">
                                    <FormField
                                        v-slot="{ componentField }"
                                        name="eventName"
                                    >
                                        <FormItem>
                                            <FormControl>
                                                <Input
                                                    class="text-2xl font-semibold"
                                                    v-bind="componentField"
                                                />
                                            </FormControl>
                                            <FormMessage />
                                        </FormItem>
                                    </FormField>
                                </template>
                                <template v-else>
                                    <h2 class="text-2xl font-semibold">
                                        {{ event.name }}
                                    </h2>
                                </template>
                                <div class="flex items-center gap-2">
                                    <Button
                                        @click="handleEdit()"
                                        v-if="
                                            !isEditing &&
                                            event.status !== 'canceled' &&
                                            event.status !== 'completed'
                                        "
                                        variant="outline"
                                    >
                                        <Edit class="w-4 h-4 mr-2" /> Edit
                                    </Button>
                                    <Button
                                        v-if="isEditing"
                                        variant="outline"
                                        @click="handleEdit()"
                                    >
                                        Cancel
                                    </Button>
                                    <Button v-if="isEditing" type="submit">
                                        <Save class="w-4 h-4 mr-2" /> Save
                                    </Button>
                                </div>
                            </div>

                            <div>
                                <h3 class="text-lg font-semibold mb-2">
                                    About the Event
                                </h3>
                                <template v-if="isEditing">
                                    <!-- Event Description -->
                                    <FormField
                                        v-slot="{ componentField }"
                                        name="eventDescription"
                                    >
                                        <FormItem>
                                            <FormControl>
                                                <Textarea
                                                    rows="5"
                                                    v-bind="componentField"
                                                    @input="
                                                        eventDescription =
                                                            $event.target.value
                                                    "
                                                />
                                            </FormControl>
                                            <FormMessage />
                                        </FormItem>
                                    </FormField>
                                </template>
                                <template v-else>
                                    <p class="text-muted-foreground">
                                        {{ event.description }}
                                    </p>
                                </template>
                            </div>

                            <Separator />

                            <div class="space-y-4">
                                <h3 class="text-lg font-semibold">
                                    Event Details
                                </h3>
                                <div class="grid gap-4 sm:grid-cols-2">
                                    <div class="flex items-center gap-2">
                                        <Calendar
                                            class="w-4 h-4 text-muted-foreground"
                                        />
                                        <template v-if="isEditing">
                                            <FormField
                                                v-slot="{ componentField }"
                                                name="eventDate"
                                            >
                                                <FormItem>
                                                    <FormControl>
                                                        <Input
                                                            v-bind="
                                                                componentField
                                                            "
                                                            id="event-date"
                                                            type="date"
                                                            class="w-fit"
                                                        />
                                                    </FormControl>
                                                    <FormMessage />
                                                </FormItem>
                                            </FormField>
                                        </template>
                                        <template v-else>
                                            <span>{{
                                                formatDate(
                                                    new Date(event.event_date),
                                                    "YYYY-MM-DD"
                                                )
                                            }}</span>
                                        </template>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <Clock
                                            class="w-4 h-4 text-muted-foreground"
                                        />
                                        <template v-if="isEditing">
                                            <div class="flex gap-2">
                                                <FormField
                                                    v-slot="{ componentField }"
                                                    name="startTime"
                                                >
                                                    <FormItem>
                                                        <FormControl>
                                                            <Input
                                                                v-bind="
                                                                    componentField
                                                                "
                                                                id="start-time"
                                                                type="time"
                                                            />
                                                        </FormControl>
                                                        <FormMessage />
                                                    </FormItem>
                                                </FormField>

                                                <FormField
                                                    v-slot="{ componentField }"
                                                    name="endTime"
                                                >
                                                    <FormItem>
                                                        <FormControl>
                                                            <Input
                                                                v-bind="
                                                                    componentField
                                                                "
                                                                id="end-time"
                                                                type="time"
                                                            />
                                                        </FormControl>
                                                        <FormMessage />
                                                    </FormItem>
                                                </FormField>
                                            </div>
                                        </template>
                                        <template v-else>
                                            <span
                                                >{{ event.start_time }} -
                                                {{ event.end_time }}</span
                                            >
                                        </template>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <MapPin
                                            class="w-4 h-4 text-muted-foreground"
                                        />
                                        <template
                                            v-if="
                                                isEditing &&
                                                event.custom_location_name &&
                                                event.custom_location_link
                                            "
                                        >
                                            <FormField
                                                v-slot="{ componentField }"
                                                name="customLocationName"
                                            >
                                                <FormItem>
                                                    <FormControl>
                                                        <Input
                                                            v-bind="
                                                                componentField
                                                            "
                                                            id="custom-location-name"
                                                            placeholder="Enter custom location name"
                                                        />
                                                    </FormControl>
                                                    <FormMessage />
                                                </FormItem>
                                            </FormField>

                                            <FormField
                                                v-slot="{ componentField }"
                                                name="customLocationLink"
                                            >
                                                <FormItem>
                                                    <FormControl>
                                                        <Input
                                                            v-bind="
                                                                componentField
                                                            "
                                                            id="custom-location-link"
                                                            placeholder="Enter location link (e.g., Google Maps URL)"
                                                        />
                                                    </FormControl>
                                                    <FormMessage />
                                                </FormItem>
                                            </FormField>
                                        </template>
                                        <template v-else-if="isEditing">
                                            <FormField
                                                v-slot="{ componentField }"
                                                name="venue"
                                            >
                                                <FormItem class="w-full">
                                                    <FormControl class="w-full">
                                                        <Select
                                                            v-bind="
                                                                componentField
                                                            "
                                                        >
                                                            <SelectTrigger
                                                                id="venue"
                                                            >
                                                                <SelectValue
                                                                    placeholder="Select a venue"
                                                                />
                                                            </SelectTrigger>
                                                            <SelectContent>
                                                                <SelectItem
                                                                    v-for="venue in $page
                                                                        .props
                                                                        .venues"
                                                                    :key="
                                                                        venue.id
                                                                    "
                                                                    :value="
                                                                        venue.name
                                                                    "
                                                                >
                                                                    {{
                                                                        venue.name
                                                                    }}
                                                                </SelectItem>
                                                            </SelectContent>
                                                        </Select>
                                                    </FormControl>
                                                    <FormMessage />
                                                </FormItem>
                                            </FormField>
                                        </template>
                                        <template
                                            v-else-if="
                                                !isEditing &&
                                                event.custom_location_name &&
                                                event.custom_location_link
                                            "
                                        >
                                            <span>
                                                <Button
                                                    variant="link"
                                                    class="p-0 text-base"
                                                    asChild
                                                >
                                                    <a
                                                        :href="
                                                            event.custom_location_link
                                                        "
                                                        target="_blank"
                                                    >
                                                        {{
                                                            event.custom_location_name
                                                        }}
                                                    </a>
                                                </Button>
                                                ({{
                                                    event.location_type
                                                }})</span
                                            >
                                        </template>
                                        <template
                                            v-else-if="
                                                !isEditing &&
                                                !event.custom_location_link &&
                                                !event.custom_location_name
                                            "
                                        >
                                            <span
                                                >{{ event.venue.name }} (
                                                {{ event.location_type }})</span
                                            >
                                        </template>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <Users
                                            class="w-4 h-4 text-muted-foreground"
                                        />
                                        <template v-if="isEditing">
                                            <FormField
                                                v-slot="{ componentField }"
                                                name="maxParticipants"
                                            >
                                                <FormItem>
                                                    <FormControl>
                                                        <Input
                                                            id="max-participants"
                                                            type="number"
                                                            v-bind="
                                                                componentField
                                                            "
                                                            class="w-fit"
                                                        />
                                                    </FormControl>
                                                    <FormMessage />
                                                </FormItem>
                                            </FormField>
                                        </template>
                                        <template v-else>
                                            <span
                                                >Max Participants:
                                                {{
                                                    event.max_participants
                                                }}</span
                                            >
                                        </template>
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
                                                event.registration_type
                                                    .charAt(0)
                                                    .toUpperCase() +
                                                event.registration_type.slice(1)
                                            }}
                                            Registration</span
                                        >
                                    </div>
                                    <template v-if="event.numberOfTeams">
                                        <div class="flex items-center gap-2">
                                            <Users2
                                                class="w-4 h-4 text-muted-foreground"
                                            />
                                            <template v-if="isEditing">
                                                <FormField
                                                    v-slot="{ componentField }"
                                                    name="numberOfTeams"
                                                >
                                                    <FormItem class="w-20">
                                                        <FormControl>
                                                            <Select
                                                                v-bind="
                                                                    componentField
                                                                "
                                                            >
                                                                <SelectTrigger
                                                                    id="number-of-teams"
                                                                >
                                                                    <SelectValue
                                                                        placeholder="Select number of teams"
                                                                    />
                                                                </SelectTrigger>
                                                                <SelectContent>
                                                                    <SelectItem
                                                                        v-for="num in [
                                                                            '2',
                                                                            '4',
                                                                            '6',
                                                                            '8',
                                                                            '10',
                                                                        ]"
                                                                        :key="
                                                                            num
                                                                        "
                                                                        :value="
                                                                            num
                                                                        "
                                                                    >
                                                                        {{
                                                                            num
                                                                        }}
                                                                    </SelectItem>
                                                                </SelectContent>
                                                            </Select>
                                                        </FormControl>
                                                        <FormMessage />
                                                    </FormItem>
                                                </FormField>
                                            </template>
                                            <template v-else>
                                                <span
                                                    >{{
                                                        event.number_of_teams
                                                    }}
                                                    Teams</span
                                                >
                                            </template>
                                        </div>
                                    </template>
                                    <div class="flex items-center gap-2">
                                        <CalendarIcon
                                            class="w-4 h-4 text-muted-foreground"
                                        />
                                        <template v-if="isEditing">
                                            <FormField
                                                v-slot="{ componentField }"
                                                name="registrationDeadline"
                                            >
                                                <FormItem>
                                                    <FormControl>
                                                        <Input
                                                            id="registration-deadline"
                                                            type="date"
                                                            v-bind="
                                                                componentField
                                                            "
                                                            class="w-fit"
                                                        />
                                                    </FormControl>
                                                    <FormMessage />
                                                </FormItem>
                                            </FormField>
                                        </template>
                                        <template v-else>
                                            <span
                                                >Register by
                                                {{
                                                    formatDate(
                                                        new Date(
                                                            event.registration_deadline
                                                        ),
                                                        "YYYY-MM-DD"
                                                    )
                                                }}</span
                                            >
                                        </template>
                                    </div>
                                </div>
                            </div>

                            <template
                                v-if="
                                    event.rules_description ||
                                    event.rules_document
                                "
                            >
                                <Separator />
                                <div class="space-y-4">
                                    <h3 class="text-lg font-semibold">
                                        Rules & Guidelines
                                    </h3>
                                    <div class="flex items-start gap-2">
                                        <ClipboardCheck
                                            class="w-4 h-4 text-muted-foreground mt-1"
                                        />
                                        <div class="w-full">
                                            <template v-if="isEditing">
                                                <FormField
                                                    v-slot="{ componentField }"
                                                    name="rulesDescription"
                                                >
                                                    <FormItem class="w-full">
                                                        <FormControl>
                                                            <Textarea
                                                                v-bind="
                                                                    componentField
                                                                "
                                                                id="rules-description"
                                                                rows="4"
                                                            />
                                                        </FormControl>
                                                        <FormMessage />
                                                    </FormItem>
                                                </FormField>
                                            </template>
                                            <template v-else>
                                                <p
                                                    class="text-muted-foreground"
                                                >
                                                    {{
                                                        event.rules_description
                                                    }}
                                                </p>
                                            </template>
                                        </div>
                                    </div>
                                    <!-- Show Document Download Link -->
                                    <template v-if="event.rules_document">
                                        <Button
                                            variant="link"
                                            class="p-0 text-base"
                                            asChild
                                        >
                                            <a
                                                :href="event.rules_document"
                                                target="_blank"
                                            >
                                                Download Rules Document
                                            </a>
                                        </Button>
                                    </template>
                                </div>
                            </template>
                        </form>
                    </CardContent>
                </Card>

                <div class="flex flex-col gap-4 md:col-span-4">
                    <Card class="md:col-span-4 h-fit">
                        <CardContent class="p-6">
                            <div class="space-y-6">
                                <div>
                                    <h3 class="text-lg font-semibold mb-2">
                                        Event Status
                                    </h3>
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
                                            event.status
                                                .charAt(0)
                                                .toUpperCase() +
                                            event.status.slice(1)
                                        }}
                                    </Badge>
                                </div>

                                <Separator />

                                <div>
                                    <h3 class="text-lg font-semibold mb-2">
                                        Notifications
                                    </h3>
                                    <div class="flex items-center gap-2">
                                        <Bell
                                            class="w-4 h-4 text-muted-foreground"
                                        />
                                        <span>{{
                                            event.notify_creation
                                                ? "Enabled"
                                                : "Disabled"
                                        }}</span>
                                    </div>
                                </div>

                                <Separator />

                                <div>
                                    <h3 class="text-lg font-semibold mb-2">
                                        Additional Information
                                    </h3>
                                    <div
                                        class="space-y-2 text-sm text-muted-foreground"
                                    >
                                        <p>
                                            Created:
                                            {{
                                                formatDate(
                                                    new Date(event.created_at),
                                                    "YYYY-MM-DD"
                                                )
                                            }}
                                        </p>
                                        <p>Event ID: {{ event.id }}</p>
                                    </div>
                                </div>
                            </div>
                        </CardContent>
                    </Card>

                    <Card class="md:col-span-4 h-full">
                        <CardHeader>
                            <h3 class="text-lg font-semibold">
                                Registered Members
                            </h3>
                        </CardHeader>
                        <CardContent>
                            <ScrollArea class="flex-1 overflow-y-auto max-h-64">
                                <ul class="space-y-4">
                                    <li
                                        v-for="member in members"
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
                                        <div class="w-full">
                                            <div class="flex justify-between">
                                                <h3 class="font-medium text-sm">
                                                    {{ member.first_name }}
                                                    {{ member.last_name }}
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
                        </CardContent>
                    </Card>
                </div>
            </div>
        </main>
    </AdminLayout>
</template>
