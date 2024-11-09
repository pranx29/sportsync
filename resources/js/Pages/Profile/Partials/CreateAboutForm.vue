<script setup>
import ProfileCreationLayout from "@/Layouts/ProfileCreationLayout.vue";
import { Button } from "@/Components/ui/button";
import { Input } from "@/Components/ui/input";
import {
    Select,
    SelectContent,
    SelectGroup,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from "@/Components/ui/select";
import { Avatar, AvatarFallback, AvatarImage } from "@/Components/ui/avatar";
import {
    FormField,
    FormItem,
    FormLabel,
    FormControl,
    FormDescription,
    FormMessage,
} from "@/Components/ui/form";
import { useForm } from "vee-validate";
import { toTypedSchema } from "@vee-validate/zod";
import * as z from "zod";
import { Calendar } from "@/Components/ui/calendar";
import {
    Popover,
    PopoverContent,
    PopoverTrigger,
} from "@/Components/ui/popover";
import { cn } from "@/lib/utils";
import {
    CalendarDate,
    DateFormatter,
    getLocalTimeZone,
    parseDate,
    today,
} from "@internationalized/date";
import { Calendar as CalendarIcon } from "lucide-vue-next";
import { toDate } from "radix-vue/date";
import { computed, h, ref } from "vue";
import { router } from "@inertiajs/vue3";

const schema = z.object({
    firstName: z.string().min(1, { message: "First name is required" }),
    lastName: z.string().optional(),
    dob: z.string().refine((v) => v, { message: "A date of birth is required." }),
    jobTitle: z.string().min(1, { message: "Job title is required" }),
    department: z.string().min(1, { message: "Department is required" }),
    gender: z.string().min(1, { message: "Gender is required" }),
});

const df = new DateFormatter("en-US", {
    dateStyle: "long",
});

const placeholder = ref();
const value = computed({
    get: () => (values.dob ? parseDate(values.dob) : undefined),
    set: (val) => val,
});

import { usePage } from "@inertiajs/vue3";

const { props } = usePage();
const { handleSubmit, setFieldValue, values } = useForm({
    validationSchema: toTypedSchema(schema),
    initialValues: {
        firstName: props.auth.user.first_name,
        lastName: props.auth.user.last_name,
        jobTitle: "",
        department: "",
        dob: "",
        gender: "",
    },
});

const onSubmit = handleSubmit((values) => {
    router.post(route("create.about"), values);
    console.log(values);
});
</script>

<template>
    <Head title="Create Profile | About" />
    <ProfileCreationLayout>
        <template #title> Let’s set up your profile! </template>
        <template #form-title> About </template>
        <template #form>
            <form @submit="onSubmit">
                <div class="flex flex-col gap-4">
                    <div class="flex gap-4">
                        <FormField v-slot="{ componentField }" name="firstName">
                            <FormItem>
                                <FormLabel>First Name</FormLabel>
                                <div class="flex items-center gap-1.5">
                                    <FormControl>
                                        <Input
                                            type="text"
                                            placeholder="Your first name"
                                            v-bind="componentField"
                                        />
                                    </FormControl>
                                </div>
                                <FormMessage />
                            </FormItem>
                        </FormField>

                        <FormField v-slot="{ componentField }" name="lastName">
                            <FormItem>
                                <FormLabel>Last Name</FormLabel>
                                <div class="flex items-center gap-1.5">
                                    <FormControl>
                                        <Input
                                            type="text"
                                            placeholder="Your last name"
                                            v-bind="componentField"
                                        />
                                    </FormControl>
                                </div>
                                <FormMessage />
                            </FormItem>
                        </FormField>
                    </div>

                    <FormField name="dob">
                        <FormItem class="flex flex-col">
                            <FormLabel>Date of birth</FormLabel>
                            <Popover>
                                <PopoverTrigger as-child>
                                    <FormControl>
                                        <Button
                                            variant="outline"
                                            :class="
                                                cn(
                                                    'w-[240px] ps-3 text-start font-normal',
                                                    !value &&
                                                        'text-muted-foreground'
                                                )
                                            "
                                        >
                                            <span>{{
                                                value
                                                    ? df.format(toDate(value))
                                                    : "Pick a date"
                                            }}</span>
                                            <CalendarIcon
                                                class="ms-auto h-4 w-4 opacity-50"
                                            />
                                        </Button>
                                        <input hidden />
                                    </FormControl>
                                </PopoverTrigger>
                                <PopoverContent class="w-auto p-0">
                                    <Calendar
                                        v-model:placeholder="placeholder"
                                        v-model="value"
                                        calendar-label="Date of birth"
                                        initial-focus
                                        :min-value="
                                            new CalendarDate(1900, 1, 1)
                                        "
                                        :max-value="today(getLocalTimeZone())"
                                        @update:model-value="
                                            (v) => {
                                                if (v) {
                                                    setFieldValue(
                                                        'dob',
                                                        v.toString()
                                                    );
                                                } else {
                                                    setFieldValue(
                                                        'dob',
                                                        undefined
                                                    );
                                                }
                                            }
                                        "
                                    />
                                </PopoverContent>
                            </Popover>
                            <FormMessage />
                        </FormItem>
                    </FormField>

                    <FormField v-slot="{ componentField }" name="gender">
                        <FormItem>
                            <FormLabel>Gender</FormLabel>
                            <Select v-bind="componentField">
                                <div
                                    class="flex w-full max-w-sm items-center gap-1.5"
                                >
                                    <FormControl>
                                        <SelectTrigger>
                                            <SelectValue
                                                placeholder="Select your gender"
                                            />
                                        </SelectTrigger>
                                    </FormControl>
                                </div>
                                <SelectContent>
                                    <SelectGroup>
                                        <SelectItem value="male"
                                            >Male</SelectItem
                                        >
                                        <SelectItem value="female"
                                            >Female</SelectItem
                                        >
                                        <SelectItem value="other"
                                            >Other</SelectItem
                                        >
                                    </SelectGroup>
                                </SelectContent>
                            </Select>
                            <FormMessage />
                        </FormItem>
                    </FormField>

                    <FormField v-slot="{ componentField }" name="jobTitle">
                        <FormItem>
                            <FormLabel>Job Title</FormLabel>
                            <Select v-bind="componentField">
                                <div
                                    class="flex w-full max-w-sm items-center gap-1.5"
                                >
                                    <FormControl>
                                        <SelectTrigger>
                                            <SelectValue
                                                placeholder="Select a job title"
                                            />
                                        </SelectTrigger>
                                    </FormControl>
                                </div>
                                <SelectContent>
                                    <SelectGroup>
                                        <SelectItem
                                            v-for="job in $page.props.jobs"
                                            :key="job.id"
                                            :value="job.title"
                                        >
                                            {{ job.title }}
                                        </SelectItem>
                                    </SelectGroup>
                                </SelectContent>
                            </Select>
                        </FormItem>
                    </FormField>

                    <FormField v-slot="{ componentField }" name="department">
                        <FormItem>
                            <FormLabel>Department</FormLabel>
                            <Select v-bind="componentField">
                                <div
                                    class="flex w-full max-w-sm items-center gap-1.5"
                                >
                                    <FormControl>
                                        <SelectTrigger>
                                            <SelectValue
                                                placeholder="Select a department"
                                            />
                                        </SelectTrigger>
                                    </FormControl>
                                </div>
                                <SelectContent>
                                    <SelectGroup>
                                        <SelectItem
                                            v-for="department in $page.props
                                                .departments"
                                            :key="department.id"
                                            :value="department.name"
                                        >
                                            {{ department.name }}
                                        </SelectItem>
                                    </SelectGroup>
                                </SelectContent>
                            </Select>
                            <FormMessage />
                        </FormItem>
                    </FormField>

                    <div class="grid w-full max-w-sm items-center gap-4">
                        <FormField v-slot="{ componentField }" name="picture">
                            <FormItem>
                                <FormLabel>Profile Picture</FormLabel>
                                <div
                                    class="flex w-full max-w-sm items-center gap-4"
                                >
                                    <FormControl>
                                        <Avatar class="w-24 h-24 rounded-md">
                                            <AvatarImage
                                                src=""
                                                alt="Profile Picture"
                                            />
                                            <AvatarFallback>CN</AvatarFallback>
                                        </Avatar>
                                        <Input
                                            id="picture"
                                            type="file"
                                            v-bind="componentField"
                                        />
                                    </FormControl>
                                </div>
                                <FormMessage />
                            </FormItem>
                        </FormField>
                    </div>
                </div>
                <div class="flex justify-end mt-4">
                    <Button type="submit"> Continue </Button>
                </div>
            </form>
        </template>
    </ProfileCreationLayout>
</template>
