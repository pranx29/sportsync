<script setup>
import {
    Form,
    FormField,
    FormItem,
    FormLabel,
    FormControl,
    FormMessage,
} from "@/Components/ui/form";
import {
    Select,
    SelectContent,
    SelectGroup,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from "@/Components/ui/select";
import { DialogTitle, DialogDescription } from "radix-vue";
import { Input } from "@/Components/ui/input";
import { Textarea } from "@/Components/ui/textarea";
import { Button } from "@/Components/ui/button";
import { Sheet, SheetContent, SheetTrigger } from "@/Components/ui/sheet";
import { useForm as useVeeForm } from "vee-validate";
import { ref } from "vue";
import * as z from "zod";
import { toTypedSchema } from "@vee-validate/zod";
import { router, usePage } from "@inertiajs/vue3";
import { toast } from "@/Components/ui/toast";
import { CirclePlus } from "lucide-vue-next";

const { props } = usePage();
const groupId = props.group?.id;

const isAddFormOpen = ref(false);

const createSessionSchema = toTypedSchema(
    z.object({
        session_name: z.string().nonempty("Session name is required."),
        date_time: z.string().nonempty("Date and time are required."),
        duration: z
            .number()
            .min(1, "Duration must be at least 1 hour.")
            .max(5, "Duration cannot exceed 5 hours."),
        location: z.string().nonempty("Location is required."),
        participation_limit: z
            .number()
            .positive("Participation limit must be greater than 0.")
            .int("Participation limit must be an integer."),
        equipment_provided: z
            .string()
            .nonempty("Please specify if equipment will be provided.")
            .refine((value) => ["yes", "no"].includes(value), {
                message: "Invalid selection for equipment provided.",
            }),
        description: z.string().optional(),
    })
);

const {
    handleSubmit: handleSessionSubmit,
    resetForm: resetSessionForm,
    setFieldError,
} = useVeeForm({
    validationSchema: createSessionSchema,
});

const onSessionSubmit = handleSessionSubmit(async (values) => {
    const payload = {
        ...values,
        group_id: groupId,
    };

    router.post(route("sessions.create", { group: groupId }), payload, {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            toast({
                title: "Session Created",
                description: "The session has been created successfully.",
                variant: "success",
            });
            isAddFormOpen.value = false;
        },
        onError: (errors) => {
            if (errors) {
                Object.entries(errors).forEach(([key, message]) => {
                    setFieldError(key, message[0]);
                });
            }
            toast({
                title: "Failed to Create Session",
                description: "Please check the form for errors.",
                variant: "destructive",
            });
        },
    });

    resetSessionForm();
});
</script>

<template>
    <Sheet v-model:open="isAddFormOpen">
        <SheetTrigger as-child>
            <Button size="sm" class="mb-4">
                <CirclePlus class="h-3.5 w-3.5" />
                <span class="sr-only sm:not-sr-only sm:whitespace-nowrap"
                    >Create Session</span
                >
            </Button>
        </SheetTrigger>
        <SheetContent class="overflow-y-auto py-10">
            <div class="mb-4">
                <DialogTitle>
                    <h2 class="text-xl font-semibold">
                        Create Session for {{ props.group.name }}
                    </h2>
                </DialogTitle>
                <DialogDescription>
                    <p class="text-sm text-gray-500">
                        Enter the details to create a new session for this
                        group.
                    </p>
                </DialogDescription>
            </div>
            <form class="space-y-6" @submit.prevent="onSessionSubmit">
                <FormField v-slot="{ componentField }" name="session_name">
                    <FormItem>
                        <FormLabel>Session Name</FormLabel>
                        <FormControl>
                            <Input
                                v-bind="componentField"
                                placeholder="Enter session name"
                            />
                        </FormControl>
                        <FormMessage />
                    </FormItem>
                </FormField>
                <!-- Container for Date and Time and Duration fields -->
                <div class="flex flex-col sm:flex-row gap-4">
                    <!-- Field to get start time  -->
                    <FormField v-slot="{ componentField }" name="date_time">
                        <FormItem>
                            <FormLabel>Date & Time</FormLabel>
                            <FormControl>
                                <Input
                                    v-bind="componentField"
                                    type="datetime-local"
                                    placeholder="Select date and time"
                                    class="w-full"
                                    :min="
                                        new Date(Date.now() + 86400000)
                                            .toISOString()
                                            .slice(0, 16)
                                    "
                                    :max="
                                        new Date(Date.now() + 2592000000)
                                            .toISOString()
                                            .slice(0, 16)
                                    "
                                />
                            </FormControl>
                            <FormMessage />
                        </FormItem>
                    </FormField>
                    <!-- Field to get duration -->
                    <FormField v-slot="{ componentField }" name="duration">
                        <FormItem>
                            <FormLabel>Duration</FormLabel>
                            <FormControl>
                                <Input
                                    v-bind="componentField"
                                    type="number"
                                    min="1"
                                    max="5"
                                    value="1"
                                    class="w-28"
                                    placeholder="1-5"
                                />
                            </FormControl>
                            <FormMessage />
                        </FormItem>
                    </FormField>
                </div>

                <FormField
                    v-slot="{ componentField }"
                    name="participation_limit"
                >
                    <FormItem>
                        <FormLabel>Participation Limit</FormLabel>
                        <FormControl>
                            <Input
                                v-bind="componentField"
                                type="number"
                                min="1"
                                placeholder="Enter participation limit"
                            />
                        </FormControl>
                        <FormMessage />
                    </FormItem>
                </FormField>

                <FormField
                    v-slot="{ componentField }"
                    name="equipment_provided"
                >
                    <FormItem>
                        <FormLabel>Equipment Provided</FormLabel>
                        <Select v-bind="componentField">
                            <FormControl>
                                <SelectTrigger>
                                    <SelectValue
                                        placeholder="Will equipment be provided?"
                                    />
                                </SelectTrigger>
                            </FormControl>
                            <SelectContent>
                                <SelectGroup>
                                    <SelectItem value="yes">Yes</SelectItem>
                                    <SelectItem value="no">No</SelectItem>
                                </SelectGroup>
                            </SelectContent>
                        </Select>
                        <FormMessage />
                    </FormItem>
                </FormField>

                <FormField v-slot="{ componentField }" name="location">
                    <FormItem>
                        <FormLabel>Location</FormLabel>
                        <FormControl>
                            <Input
                                v-bind="componentField"
                                placeholder="Enter location"
                            />
                        </FormControl>
                        <FormMessage />
                    </FormItem>
                </FormField>

                <FormField v-slot="{ componentField }" name="description">
                    <FormItem>
                        <FormLabel>Description</FormLabel>
                        <FormControl>
                            <Textarea
                                v-bind="componentField"
                                placeholder="Enter an optional description"
                            />
                        </FormControl>
                        <FormMessage />
                    </FormItem>
                </FormField>

                <Button type="submit">Create Session</Button>
            </form>
        </SheetContent>
    </Sheet>
</template>
