<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {
    Card,
    CardContent,
    CardDescription,
    CardFooter,
    CardHeader,
    CardTitle,
} from "@/Components/ui/card";
import { Label } from "@/Components/ui/label";
import { Separator } from "radix-vue";
import { Progress } from "@/Components/ui/progress";
import {
    Form,
    FormControl,
    FormDescription,
    FormField,
    FormItem,
    FormLabel,
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
import {
    Sheet,
    SheetClose,
    SheetContent,
    SheetDescription,
    SheetFooter,
    SheetHeader,
    SheetTitle,
    SheetTrigger,
} from "@/Components/ui/sheet";
import { Slider } from "@/Components/ui/slider";
import { Input } from "@/Components/ui/input";
import { Button } from "@/Components/ui/button";
import { toTypedSchema } from "@vee-validate/zod";
import { h } from "vue";
import * as z from "zod";
import { Pencil, CirclePlus } from "lucide-vue-next";
import { useForm as useVeeForm } from "vee-validate";
import { ref } from "vue";
import { router } from "@inertiajs/vue3";
import { toast, Toaster } from "@/Components/ui/toast";
import InterestForm from "./InterestForm.vue";

// Convert slider value to skill level
function getSkillLevel(value) {
    if (value === 25) {
        return "beginner";
    } else if (value === 50) {
        return "intermediate";
    } else if (value === 75) {
        return "advanced";
    } else if (value === 100) {
        return "expert";
    }
}

// Convert skill level to slider value
function getSliderValue(skill) {
    if (skill === "beginner") {
        return 25;
    } else if (skill === "intermediate") {
        return 50;
    } else if (skill === "advanced") {
        return 75;
    } else if (skill === "expert") {
        return 100;
    }
}

const currentSport = ref(null);

const updateInterestSchema = toTypedSchema(
    z.object({
        interest_level: z.string({
            required_error: "Please select an interest.",
        }),
        skill_level: z.array(
            z
                .number({
                    required_error: "Please select a skill level.",
                })
                .min(25, "Skill level must be at least 25.")
                .max(100, "Skill level must be at most 100.")
        ),
    })
);

const {
    handleSubmit: handleUpdateSubmit,
    resetForm: resetUpdateForm,
    setValues: setUpdateForm,
} = useVeeForm({
    validationSchema: updateInterestSchema,
});

function loadFormData(sport) {
    currentSport.value = sport.id;
    setUpdateForm({
        interest_level: sport.interest_level,
        skill_level: [getSliderValue(sport.skill_level)],
    });
}

const onUpdateSubmit = handleUpdateSubmit(async (values) => {
    values.skill_level = getSkillLevel(values.skill_level[0]);
    router.patch(
        route("interest.update"),
        {
            sport_id: currentSport.value,
            ...values,
        },
        {
            preserveScroll: true,
            preserveState: true,
            onSuccess: () => {
                toast({
                    title: "Interest updated",
                    description: "Your interest has been updated successfully.",
                    variant: "success",
                });
            },
            onError: () => {
                toast({
                    title: "Failed to update interest",
                    description:
                        "An error occurred while updating your interest.",
                    variant: "destructive",
                });
            },
        }
    );
    console.log("Update interest form submitted:", values);
    resetUpdateForm();
});
</script>

<template>
    <AuthenticatedLayout>
        <div class="flex flex-col lg:flex-row items-start min-h-screen p-4 lg:p-16 gap-4">
            <div class="flex flex-col lg:flex-row items-center justify-start w-full lg:w-1/3 h-full">
                <Card class="w-full">
                    <CardHeader>
                        <CardTitle>My Profile</CardTitle>
                    </CardHeader>
                    <CardContent class="flex flex-col gap-4">
                        <!-- Profile picture -->
                        <div class="flex flex-row items-center justify-center">
                            <img
                                :src="$page.props.profile.profile_image"
                                alt="Profile Picture"
                                class="w-full max-w-xs sm:max-w-sm md:max-w-md lg:max-w-lg xl:max-w-xl rounded-sm object-cover"
                            />
                        </div>
                        <!-- Profile details -->
                        <div class="flex flex-col items-start justify-start">
                            <h4
                                class="scroll-m-20 text-xl font-semibold tracking-tight"
                            >
                                {{ $page.props.auth.user.first_name }}
                                {{ $page.props.auth.user.last_name }}
                            </h4>
                            <h5
                                class="scroll-m-20 text-md font-medium tracking-tight"
                            >
                                {{ $page.props.profile.role.title }}
                            </h5>
                            <div class="flex items-center space-x-2 mt-2">
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke-width="1.5"
                                    stroke="currentColor"
                                    class="size-4 text-foreground"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21"
                                    />
                                </svg>

                                <p
                                    class="scroll-m-20 text-sm font-normal tracking-tight"
                                >
                                    {{ $page.props.profile.department.name }}
                                </p>
                            </div>
                            <div class="flex items-center space-x-2 mt-2">
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke-width="1.5"
                                    stroke="currentColor"
                                    class="size-4 text-foreground"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75"
                                    />
                                </svg>

                                <p
                                    class="scroll-m-20 text-sm font-normal tracking-tight"
                                >
                                    {{ $page.props.auth.user.email }}
                                </p>
                            </div>
                        </div>
                        <!-- Count of Interest, number of groups, joined event, points -->
                        <div class="flex justify-evenly w-full">
                            <div
                                class="flex flex-col items-center justify-center"
                            >
                                <span
                                    class="scroll-m-20 text-xl font-semibold tracking-tight text-primary"
                                >
                                    {{ $page.props.interests.length }}
                                </span>

                                <p
                                    class="scroll-m-20 text-sm font-medium tracking-tight"
                                >
                                    Interests
                                </p>
                            </div>
                            <Separator class="border" orientation="vertical" />
                            <div
                                class="flex flex-col items-center justify-center"
                            >
                                <span
                                    class="scroll-m-20 text-xl font-semibold tracking-tight text-primary"
                                >
                                    3
                                </span>

                                <p
                                    class="scroll-m-20 text-sm font-medium tracking-tight"
                                >
                                    Groups
                                </p>
                            </div>
                            <Separator class="border" orientation="vertical" />
                            <div
                                class="flex flex-col items-center justify-center"
                            >
                                <span
                                    class="scroll-m-20 text-xl font-semibold tracking-tight text-primary"
                                >
                                    2
                                </span>

                                <p
                                    class="scroll-m-20 text-sm font-medium tracking-tight"
                                >
                                    Events
                                </p>
                            </div>
                            <Separator class="border" orientation="vertical" />
                            <div
                                class="flex flex-col items-center justify-center"
                            >
                                <span
                                    class="scroll-m-20 text-xl font-semibold tracking-tight text-primary"
                                >
                                    125
                                </span>

                                <p
                                    class="scroll-m-20 text-sm font-medium tracking-tight"
                                >
                                    Points
                                </p>
                            </div>
                        </div>
                    </CardContent>

                </Card>
            </div>
            <!-- Interests and Groups -->
            <div class="flex flex-col gap-4 items-center justify-start w-full lg:w-2/3">
                <Card class="w-full">
                    <CardHeader>
                        <div class="flex gap-4 items-center justify-between">
                            <CardTitle>My Interests</CardTitle>
                            <InterestForm :get-skill-level="getSkillLevel" />
                        </div>
                    </CardHeader>
                    <CardContent>
                        <div class="flex flex-wrap gap-4 items-start justify-start">
                            <Card
                                class="w-full sm:w-1/2 md:w-1/3 lg:w-1/4 p-6"
                                v-for="sport in $page.props.interests"
                                :key="sport.name"
                            >
                                <div
                                    class="flex gap-x-4 items-center justify-between"
                                >
                                    <h4
                                        class="scroll-m-20 text-xl font-semibold tracking-tight"
                                    >
                                        {{ sport.name }}
                                    </h4>
                                    <Sheet>
                                        <SheetTrigger as-child>
                                            <Button
                                                variant="outline"
                                                size="icon"
                                                @click="loadFormData(sport)"
                                            >
                                                <Pencil class="w-4 h-4" />
                                            </Button>
                                        </SheetTrigger>
                                        <SheetContent>
                                            <SheetHeader>
                                                <SheetTitle
                                                    >Edit Interest</SheetTitle
                                                >
                                                <SheetDescription>
                                                    Update your interest level
                                                    and skill level. Click save
                                                    when you're done.
                                                </SheetDescription>
                                            </SheetHeader>
                                            <div class="grid gap-4 py-4">
                                                <form
                                                    class="w-full space-y-6"
                                                    @submit.prevent="
                                                        onUpdateSubmit
                                                    "
                                                >
                                                    <!-- Select a sport -->
                                                    <FormField
                                                        v-slot="{
                                                            componentField,
                                                        }"
                                                        name="interest_level"
                                                    >
                                                        <FormItem>
                                                            <FormLabel
                                                                >Interest
                                                                level</FormLabel
                                                            >

                                                            <Select
                                                                v-bind="
                                                                    componentField
                                                                "
                                                            >
                                                                <FormControl>
                                                                    <SelectTrigger>
                                                                        <SelectValue
                                                                            placeholder="Select a interest"
                                                                        />
                                                                    </SelectTrigger>
                                                                </FormControl>
                                                                <SelectContent>
                                                                    <SelectGroup>
                                                                        <SelectItem
                                                                            value="low"
                                                                        >
                                                                            Low
                                                                        </SelectItem>
                                                                        <SelectItem
                                                                            value="medium"
                                                                        >
                                                                            Medium
                                                                        </SelectItem>
                                                                        <SelectItem
                                                                            value="high"
                                                                        >
                                                                            High
                                                                        </SelectItem>
                                                                    </SelectGroup>
                                                                </SelectContent>
                                                            </Select>
                                                            <FormMessage />
                                                        </FormItem>
                                                    </FormField>

                                                    <!-- Select interest level -->
                                                    <FormField
                                                        v-slot="{
                                                            componentField,
                                                            value,
                                                        }"
                                                        name="skill_level"
                                                    >
                                                        <FormItem>
                                                            <FormLabel
                                                                >Skill
                                                                level</FormLabel
                                                            >
                                                            <FormControl>
                                                                <Slider
                                                                    v-bind="
                                                                        componentField
                                                                    "
                                                                    :default-value="[
                                                                        25,
                                                                    ]"
                                                                    :max="100"
                                                                    :min="25"
                                                                    :step="25"
                                                                />
                                                                <FormDescription
                                                                    class="flex justify-between"
                                                                >
                                                                    <span
                                                                        v-if="
                                                                            value?.[0] ===
                                                                            25
                                                                        "
                                                                        >Beginner</span
                                                                    >
                                                                    <span
                                                                        v-else-if="
                                                                            value?.[0] ===
                                                                            50
                                                                        "
                                                                        >Intermediate</span
                                                                    >
                                                                    <span
                                                                        v-else-if="
                                                                            value?.[0] ===
                                                                            75
                                                                        "
                                                                        >Advanced</span
                                                                    >
                                                                    <span
                                                                        v-else-if="
                                                                            value?.[0] ===
                                                                            100
                                                                        "
                                                                        >Expert</span
                                                                    >
                                                                </FormDescription>
                                                            </FormControl>
                                                            <FormMessage />
                                                        </FormItem>
                                                    </FormField>
                                                    <SheetClose>
                                                        <Button type="submit">
                                                            Save changes
                                                        </Button>
                                                    </SheetClose>
                                                </form>
                                            </div>
                                        </SheetContent>
                                    </Sheet>
                                </div>

                                <!-- Interest Level -->
                                <div class="mt-2">
                                    <Label> Interest Level </Label>
                                    <div
                                        class="scroll-m-20 text-lg font-semibold tracking-tight text-primary"
                                    >
                                        {{
                                            sport.interest_level
                                                .charAt(0)
                                                .toUpperCase() +
                                            sport.interest_level.slice(1)
                                        }}
                                    </div>
                                </div>

                                <!-- Skill Level -->
                                <div class="mt-1">
                                    <Label> Skill Level </Label>
                                    <Progress
                                        :model-value="
                                            getSliderValue(sport.skill_level)
                                        "
                                    />
                                </div>
                            </Card>
                        </div>
                    </CardContent>
                    <CardFooter>
                        <div></div>
                    </CardFooter>
                </Card>

                <!-- Groups -->
                <Card class="w-full">
                    <CardHeader>
                        <CardTitle>Groups</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div
                            class="flex flex-col items-start justify-start"
                        ></div>
                    </CardContent>
                    <CardFooter>
                        <div></div>
                    </CardFooter>
                </Card>
            </div>
        </div>
        <Toaster />
    </AuthenticatedLayout>
</template>
