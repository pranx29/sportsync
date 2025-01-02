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

const event = ref({
    id: "1",
    name: "Annual Sports Meet",
    sportType: "Athletics",
    description: "A fun-filled day of various athletic events.",
    posterImage:
        "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcT7EQSsfP6Uge_1q4lV90npE1JzSEMBHZGVCQ&s",
    registrationType: "team",
    maxParticipants: 100,
    numberOfTeams: "10",
    registrationDeadline: "2024-12-30",
    customLocationName: "Sports Complex",
    locationType: "Outdoor",
    customLocation: true,
    customLocationLink: "https://g.co/kgs/WAqhajW",
    eventDate: "2024-12-30",
    startTime: "09:00",
    endTime: "17:00",
    status: "upcoming",
    rulesDescription: "Follow the rules and guidelines provided.",
    notificationsEnabled: true,
    createdAt: "2024-11-30",
});

const statusStyles = ref({
    upcoming: "bg-blue-100 text-blue-800 hover:bg-blue-100/80",
    "in-progress": "bg-green-100 text-green-800 hover:bg-green-100/80",
    completed: "bg-gray-100 text-gray-800 hover:bg-gray-100/80",
    "registration-closed":
        "bg-yellow-100 text-yellow-800 hover:bg-yellow-100/80",
    cancelled: "bg-red-100 text-red-800 hover:bg-red-100/80",
});

const statusLabels = ref({
    upcoming: "Upcoming",
    "in-progress": "In Progress",
    completed: "Completed",
    "registration-closed": "Registration Closed",
    cancelled: "Cancelled",
});

const isEditing = ref(usePage().props.editMode);

const handleEdit = () => {
    isEditing.value = true;
};

const editEventSchema = toTypedSchema(
    z.object({
        posterImage: z.any(),
        eventName: z.string().min(2, "Event name is required"),
        eventDescription: z.string().min(2, "Event description is required"),
        eventDate: z.string("Event date is required").refine((value) => {
            return new Date(value) > new Date();
        }, "Event date must be in the future"),
        startTime: z.string("Start time is required"),
        endTime: z.string("End time is required"),
        customLocationName: z.string(),
        customLocationLink: z.string().url({ message: "Invalid URL" }),
        maxParticipants: z.number().int().min(1, "Minimum 1 participant"),
        registrationDeadline: z
            .string("Registration deadline is required")
            .refine((value) => {
                return new Date(value) > new Date();
            }, "Registration deadline must be in the future"),
        numberOfTeams: z.string().optional(),
        rulesDescription: z
            .string()
            .min(10, "Short rules description is required"),
    })
);

const { handleSubmit } = useForm({
    validationSchema: editEventSchema,
    initialValues: {
        posterImage: event.value.posterImage,
        eventName: event.value.name,
        eventDescription: event.value.description,
        eventDate: event.value.eventDate,
        startTime: event.value.startTime,
        endTime: event.value.endTime,
        customLocationName: event.value.customLocationName,
        customLocationLink: event.value.customLocationLink,
        maxParticipants: event.value.maxParticipants,
        registrationDeadline: event.value.registrationDeadline,
        numberOfTeams: event.value.numberOfTeams,
        rulesDescription: event.value.rulesDescription,
    },
    keepValuesOnUnmount: true,
});

const onSubmit = handleSubmit(async (values) => {
    isEditing.value = false;
    event.value = {
        ...event.value,
        name: values.eventName,
        description: values.eventDescription,
        eventDate: values.eventDate,
        startTime: values.startTime,
        endTime: values.endTime,
        customLocationName: values.customLocationName,
        customLocationLink: values.customLocationLink,
        maxParticipants: values.maxParticipants,
        registrationDeadline: values.registrationDeadline,
        numberOfTeams: values.numberOfTeams,
        rulesDescription: values.rulesDescription,
    };

    // TODO: Send the updated event data to the server
});

const handleImageUpload = (e) => {
    const file = e.target.files?.[0];
    if (file) {
        const reader = new FileReader();
        reader.onloadend = () => {
            event.value.posterImage = reader.result;
        };
        reader.readAsDataURL(file);
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
                            :src="event.posterImage"
                            :alt="event.name"
                            width="800"
                            height="400"
                            class="w-full h-[400px] object-cover rounded-t-lg"
                        />
                        <template v-if="isEditing" >
                            <FormField name="eventImage" >
                                <FormItem>
                                    <FormControl class="w-fit ml-auto mr-6">
                                        <Input
                                            type="file"
                                            accept="image/*"
                                            @change="
                                                (event) => {
                                                    handleImageUpload(event);
                                                }
                                            "
                                        />
                                    </FormControl>
                                    <FormMessage />
                                </FormItem>
                            </FormField>
                        </template>

                        <div class="absolute top-4 right-4 flex gap-2">
                            <Badge>{{ event.sportType }}</Badge>
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
                                <Button
                                    @click="handleEdit()"
                                    v-if="!isEditing"
                                    variant="outline"
                                >
                                    <Edit class="w-4 h-4 mr-2" /> Edit
                                </Button>
                                <Button
                                    v-if="isEditing"
                                    type="submit"
                                    variant="outline"
                                >
                                    <Save class="w-4 h-4 mr-2" /> Save
                                </Button>
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
                                                    new Date(event.eventDate),
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
                                                >{{ event.startTime }} -
                                                {{ event.endTime }}</span
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
                                                event.customLocation
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
                                            <Select v-bind="componentField">
                                                <SelectTrigger id="venue">
                                                    <SelectValue
                                                        placeholder="Select a venue"
                                                    />
                                                </SelectTrigger>
                                                <SelectContent>
                                                    <SelectItem value="stadium"
                                                        >Main
                                                        Stadium</SelectItem
                                                    >
                                                    <SelectItem value="park"
                                                        >Central
                                                        Park</SelectItem
                                                    >
                                                    <SelectItem value="gym"
                                                        >Community
                                                        Gym</SelectItem
                                                    >
                                                </SelectContent>
                                            </Select>
                                        </template>
                                        <template
                                            v-else-if="
                                                !isEditing &&
                                                event.customLocation
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
                                                            event.customLocationLink
                                                        "
                                                        target="_blank"
                                                    >
                                                        {{
                                                            event.customLocationName
                                                        }}
                                                    </a>
                                                </Button>
                                                ({{ event.locationType }})</span
                                            >
                                        </template>
                                        <template
                                            v-else-if="
                                                !isEditing &&
                                                !event.customLocation
                                            "
                                        >
                                            {{ event.locationType }}
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
                                                    event.maxParticipants
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
                                                event.registrationType
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
                                                        event.numberOfTeams
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
                                                            event.registrationDeadline
                                                        ),
                                                        "YYYY-MM-DD"
                                                    )
                                                }}</span
                                            >
                                        </template>
                                    </div>
                                </div>
                            </div>

                            <template v-if="event.rulesDescription">
                                <Separator />
                                <div class="space-y-4">
                                    <h3 class="text-lg font-semibold">
                                        Rules & Guidelines
                                    </h3>
                                    <div class="flex items-start gap-2">
                                        <ClipboardCheck
                                            class="w-4 h-4 text-muted-foreground mt-1"
                                        />
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
                                            <p class="text-muted-foreground">
                                                {{ event.rulesDescription }}
                                            </p>
                                        </template>
                                    </div>
                                </div>
                            </template>
                        </form>
                    </CardContent>
                </Card>

                <Card class="md:col-span-4 h-fit">
                    <CardContent class="p-6">
                        <div class="space-y-6">
                            <div>
                                <h3 class="text-lg font-semibold mb-2">
                                    Event Status
                                </h3>
                                <Badge :class="statusStyles[event.status]">{{
                                    statusLabels[event.status]
                                }}</Badge>
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
                                        event.notificationsEnabled
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
                                                new Date(event.createdAt),
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
            </div>
        </main>
    </AdminLayout>
</template>
