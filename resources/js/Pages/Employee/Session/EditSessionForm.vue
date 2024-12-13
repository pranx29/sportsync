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
import {
    Sheet,
    SheetContent,
    SheetTrigger,
    SheetTitle,
    SheetDescription,
} from "@/Components/ui/sheet";
import { useForm as useVeeForm } from "vee-validate";
import { ref } from "vue";
import * as z from "zod";
import { toTypedSchema } from "@vee-validate/zod";
import { router, usePage } from "@inertiajs/vue3";
import { toast } from "@/Components/ui/toast";
import { CirclePlus, EditIcon } from "lucide-vue-next";
import EditSessionForm from "@/Pages/Employee/Session/EditSessionForm.vue";

const props = defineProps({
    session: {
        type: Object,
        required: true,
    },
});

const emit = defineEmits(["updatedSession"]);

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

const { handleSubmit: handleSessionSubmit, setFieldError } = useVeeForm({
    validationSchema: createSessionSchema,
    initialValues: {
        session_name: props.session?.session_name || "",
        date_time: props.session?.date_time || "",
        duration: props.session?.duration || 1,
        location: props.session?.location || "",
        participation_limit: props.session?.participation_limit || 1,
        equipment_provided: props.session?.equipment_provided ? "yes" : "no",
        description: props.session?.description || "",
    },
});

const onSessionSubmit = handleSessionSubmit(async (values) => {
    router.put(
        route("group.sessions.update", { session: props.session.id }),
        values,
        {
            preserveScroll: true,
            preserveState: true,
            onSuccess: () => {
                toast({
                    title: "Session Updated",
                    description: "The session has been updated successfully.",
                    variant: "success",
                });
                emit("updatedSession");
                isAddFormOpen.value = false;
            },
            onError: (errors) => {
                if (errors) {
                    Object.entries(errors).forEach(([key, message]) => {
                        setFieldError(key, message[0]);
                    });
                }
                toast({
                    title: "Failed to Update Session",
                    description: "Please check the form for errors.",
                    variant: "destructive",
                });
            },
        }
    );
});
</script>

<template>
    <Sheet v-model:open="isAddFormOpen">
        <SheetTrigger as-child>
            <Button variant="outline" size="sm">
                <EditIcon class="w-4 h-4" />
            </Button>
        </SheetTrigger>
        <SheetContent class="overflow-y-auto py-10">
            <SheetTitle>
                <h2 class="text-xl font-semibold">Edit Session details</h2>
            </SheetTitle>
            <SheetDescription>
                <p class="text-sm text-gray-500">
                    Update the details of the session below.
                </p>
            </SheetDescription>
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

                <Button type="submit">Save Changes</Button>
            </form>
        </SheetContent>
    </Sheet>
</template>
