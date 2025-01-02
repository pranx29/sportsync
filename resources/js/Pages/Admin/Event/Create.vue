<template>
    <Head title="Create Event" />
    <AdminLayout>
        <template #title>Create Event</template>

        <main
            class="grid items-start gap-2 p-4 sm:px-6 sm:py-0 md:gap-4 flex-1"
        >
            <Form
                v-slot="{ meta, values, validate }"
                as=""
                keep-values
                :validation-schema="currentSchema"
            >
                <Stepper
                    v-slot="{
                        isNextDisabled,
                        isPrevDisabled,
                        nextStep,
                        prevStep,
                    }"
                    v-model="stepIndex"
                    class="flex flex-col"
                >
                    <form
                        @submit="
                            (e) => {
                                e.preventDefault();
                                validate();
                                if (stepIndex === steps.length && meta.valid) {
                                    handleSubmit(values);
                                }
                            }
                        "
                    >
                        <div class="flex justify-end items-center gap-4 mb-4">
                            <Link :href="route('admin.events.index')">
                                <Button
                                    v-if="stepIndex === 1"
                                    variant="outline"
                                    size="sm"
                                >
                                    Discard
                                </Button>
                            </Link>
                            <Button
                                v-if="stepIndex !== 1"
                                :disabled="isPrevDisabled"
                                variant="outline"
                                size="sm"
                                @click="
                                    prevStep();
                                    eventDetails = values;
                                "
                            >
                                Back
                            </Button>
                            <Button
                                v-if="stepIndex !== steps.length"
                                :disabled="isNextDisabled"
                                size="sm"
                                @click="
                                    meta.valid && nextStep();
                                    eventDetails = values;
                                "
                            >
                                Next
                            </Button>
                            <Button
                                v-if="stepIndex === steps.length"
                                type="submit"
                                size="sm"
                            >
                                Save
                            </Button>
                        </div>
                        <div class="flex w-full items-start gap-2 mb-4">
                            <StepperItem
                                v-for="step in steps"
                                :key="step.step"
                                :step="step.step"
                                class="relative flex w-full flex-col items-center justify-center"
                                v-slot="{ state }"
                            >
                                <StepperSeparator
                                    v-if="
                                        step.step !==
                                        steps[steps.length - 1].step
                                    "
                                    class="absolute left-[calc(50%+20px)] right-[calc(-50%+10px)] top-5 block h-0.5 shrink-0 rounded-full bg-muted group-data-[state=completed]:bg-primary"
                                />
                                <StepperTrigger as-child>
                                    <Button
                                        :variant="
                                            state === 'completed' ||
                                            state === 'active'
                                                ? 'default'
                                                : 'outline'
                                        "
                                        size="icon"
                                        class="z-10 rounded-full shrink-0"
                                        :class="[
                                            state === 'active' &&
                                                'ring-2 ring-ring ring-offset-2 ring-offset-background',
                                        ]"
                                        :disabled="
                                            state !== 'completed' && !meta.valid
                                        "
                                    >
                                        <Check
                                            v-if="state === 'completed'"
                                            class="size-5"
                                        />
                                        <Circle v-if="state === 'active'" />
                                        <Dot v-if="state === 'inactive'" />
                                    </Button>
                                </StepperTrigger>
                                <div
                                    class="mt-5 flex flex-col items-center text-center"
                                >
                                    <StepperTitle
                                        :class="[
                                            state === 'active' &&
                                                'text-primary',
                                        ]"
                                        class="text-sm font-semibold transition lg:text-base"
                                    >
                                        {{ step.title }}
                                    </StepperTitle>
                                </div>
                            </StepperItem>
                        </div>

                        <div class="flex flex-col gap-4 mt-4">
                            <template v-if="stepIndex === 1">
                                <EventBasics :eventDetails="eventDetails" />
                            </template>
                            <template v-if="stepIndex === 2">
                                <Participants :eventDetails="eventDetails" />
                            </template>
                            <template v-if="stepIndex === 3">
                                <LocationSchedule :eventDetails="eventDetails" />
                            </template>
                            <template v-if="stepIndex === 4">
                                <RuleNotification :eventDetails="eventDetails" />
                            </template>
                        </div>
                    </form>
                </Stepper>
            </Form>
        </main>
    </AdminLayout>
</template>

<script setup>
import { Button } from "@/Components/ui/button";
import { Input } from "@/Components/ui/input";
import {
    Breadcrumb,
    BreadcrumbItem,
    BreadcrumbLink,
    BreadcrumbList,
    BreadcrumbPage,
    BreadcrumbSeparator,
} from "@/Components/ui/breadcrumb";
import {
    Stepper,
    StepperDescription,
    StepperIndicator,
    StepperItem,
    StepperSeparator,
    StepperTitle,
    StepperTrigger,
} from "@/Components/ui/stepper";
import { Check, Circle, Dot, TestTube } from "lucide-vue-next";
import { toast } from "@/Components/ui/toast";
import { toTypedSchema } from "@vee-validate/zod";
import { ref, computed } from "vue";
import * as z from "zod";
import {
    Form,
    FormControl,
    FormField,
    FormItem,
    FormLabel,
    FormMessage,
} from "@/Components/ui/form";
import { useForm } from "vee-validate";
import axios from "axios";
import EventBasics from "./EventBasics.vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import Participants from "./RegistrationSetup.vue";
import LocationSchedule from "./LocationSchedule.vue";
import TeamSetup from "./EventPreview.vue";
import RuleNotification from "./RuleNotification.vue";

const stepIndex = ref(1);

const steps = [
    {
        step: 1,
        title: "Event Basics",
    },
    {
        step: 2,
        title: "Registration Setup",
    },
    {
        step: 3,
        title: "Location & Schedule",
    },
    {
        step: 4,
        title: "Rules & Notification",
    },
];

const formSchema = [
    // Validation for event basics
    z.object({
        eventName: z.string().min(2, "Event name is required"),
        eventDescription: z.string().min(2, "Event description is required"),
        sportType: z.string().min(2, "Sport type is required"),
        eventImage: z.any(),
    }),

    // Validation for registration setup
    z
        .object({
            registrationType: z
                .string()
                .min(2, "Registration type is required"),
            maxParticipants: z.number().min(1, "Max participants is required"),
            registrationDeadline: z.string(),
            numberOfTeams: z
                .string()
                // .refine((value) => /^[1-9][0-9]*$/.test(value), {
                //     message: "The number of teams must be a positive integer.",
                // })
                .optional(),
            teamAssignment: z.string().optional(),
        })
        .refine(
            (data) => {
                if (data.registrationType === "team") {
                    return data.numberOfTeams;
                }
                return true;
            },
            {
                message: "Number of teams is required",
                path: ["numberOfTeams"],
            }
        )
        .refine(
            (data) => {
                if (data.registrationType === "team") {
                    return data.teamAssignment;
                }
                return true;
            },
            {
                message: "Team assignment is required",
                path: ["teamAssignment"],
            }
        ),

    // Validation for location and schedule
    z.object({
        venue: z.string().optional(),
        customLocation: z.boolean().optional(),
        customLocationName: z.string().optional(),
        customLocationLink: z.string().url().optional().or(z.literal("")),
        locationType: z.enum(["indoor", "outdoor"]),
        eventDate: z.string(),
        startTime: z.string(),
        endTime: z.string(),
    }).refine(
        (data) => {
            if (data.customLocation) {
                return !!data.customLocationName;
            }
            return true;
        },
        {
            message: "Custom location name is required",
            path: ["customLocationName"],
        }
    ).refine(
        (data) => {
            if (data.customLocation) {
                return !!data.customLocationLink;
            }
            return true;
        },
        {
            message: "Custom location link is required when custom location is enabled.",
            path: ["customLocationLink"],
        }
    ).refine(
        (data) => {
            if (!data.customLocation) {
                return !!data.venue;
            }
            return true;
        },
        {
            message: "Venue is required",
            path: ["venue"],
        }
    ),

    // Validation for rules and notifications
    z.object({
        rulesDocument: z.instanceof(File).refine((file) => {
            return (
                file &&
                [
                    "application/pdf",
                    "application/msword",
                    "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
                ].includes(file.type)
            );
        }, "Invalid file type. Only PDF, DOC, and DOCX are allowed."),
        rulesDescription: z
            .string()
            .min(10, "Short rules description is required"),
        notifyCreation: z.boolean().optional(),
        sendReminder: z.boolean().optional(),
        notifyAssignments: z.boolean().optional(),
    }),
];

const eventDetails = ref({});

const currentSchema = computed(() => {
    return toTypedSchema(formSchema[stepIndex.value - 1]);
});

const { handleSubmit: validateForm } = useForm({
    validationSchema: currentSchema,
});

const nextStep = async () => {
    const isValid = await validateForm();
    if (isValid) {
        stepIndex.value += 1;
    }
};

const prevStep = () => {
    stepIndex.value -= 1;
};

const handleSubmit = async (values) => {
    try {
        console.log("Submitted values:", values); // Log submitted values

        // Declare formData properly
        const formData = new FormData();



        // Populate remaining fields
        Object.keys(values).forEach((key) => {
            if (
                key !== "customLocationName" &&
                key !== "customLocationLink" &&
                key !== "venue" // Already handled above
            ) {
                formData.append(key, values[key]);
            }
        });

        // // Populate formData with values
        // Object.keys(values).forEach((key) => {
        //     formData.append(key, values[key]);
        // });

        // Ensure boolean fields are correctly converted
        formData.set("customLocation", values.customLocation ? "1" : "0");
        formData.set("notifyCreation", values.notifyCreation ? "1" : "0");
        formData.set("sendReminder", values.sendReminder ? "1" : "0");
        formData.set("notifyAssignments", values.notifyAssignments ? "1" : "0");

        console.log("notifyCreation:", values.notifyCreation);
        console.log("sendReminder:", values.sendReminder);
        console.log("notifyAssignments:", values.notifyAssignments);

        console.log("FormData prepared:", formData); // Debug FormData contents

        // Make the API call
        await axios.post(route("admin.events.store"), formData, {
            headers: {
                "Content-Type": "multipart/form-data",
            },
        });

        // Notify success
        toast({
            title: "Event created successfully!",
            description: "Your event has been saved to the database.",
        });

        // Redirect after successful creation
        //// router.visit(route("admin.events.index"));
    } catch (error) {
        
        // Handle validation or submission errors
        console.error("Error during form submission:", error.response?.data?.errors || error);

        if (error.response?.data?.errors) {
            toast({
                title: "Validation Error",
                description: JSON.stringify(error.response.data.errors),
            });
        } else {
            toast({
                title: "Error",
                description: "There was an error saving your event.",
            });
        }
    }
};
</script>
