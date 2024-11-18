<script setup>
import {
    FormControl,
    FormDescription,
    FormField,
    FormItem,
    FormLabel,
    FormMessage,
} from "@/Components/ui/form";
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
import { Separator } from "@/Components/ui/separator";
import { toast } from "@/Components/ui/toast";
import { cn } from "@/lib/utils";
import { toTypedSchema } from "@vee-validate/zod";
import { useForm } from "vee-validate";
import { h, ref } from "vue";
import * as z from "zod";
import SettingLayout from "@/Layouts/SettingLayout.vue";
import { usePage, router } from "@inertiajs/vue3";

const profileFormSchema = toTypedSchema(
    z.object({
        firstName: z
            .string()
            .min(2, {
                message: "First name must be at least 2 characters.",
            })
            .max(30, {
                message: "First name must not be longer than 30 characters.",
            }),
        lastName: z.string().optional(),
        dateOfBirth: z
            .string()
            .refine((val) => !isNaN(Date.parse(val)), {
                message: "Invalid date format.",
            })
            .refine((val) => new Date(val) <= new Date(), {
                message: "Date of birth cannot be in the future.",
            }),
        jobTitle: z.string().min(1, { message: "Job title is required" }),
        department: z.string().min(1, { message: "Department is required" }),
    })
);

const { props } = usePage();
const { handleSubmit } = useForm({
    validationSchema: profileFormSchema,
    initialValues: {
        firstName: props.auth.user.first_name,
        lastName: props.auth.user.last_name,
        dateOfBirth: props.profile.date_of_birth,
        jobTitle: props.profile.role.title,
        department: props.profile.department.name,
    },
});

const onSubmit = handleSubmit((values) => {
    values.department = props.departments.find(
        (department) => department.name === values.department
    ).id;
    values.jobTitle = props.jobs.find(
        (job) => job.title === values.jobTitle
    ).id;

    router.patch(route("profile.update"), values, {
        onSuccess: () => {
            toast({
                title: "Profile updated successfully.",
            });
        },
        onError: (errors) => {
            console.log(errors);
        },
    });
});
</script>

<template>
    <SettingLayout>
        <template #content>
            <div>
                <h3 class="text-lg font-medium">Profile</h3>
                <p class="text-sm text-muted-foreground">
                    This information will be visible to other users.
                </p>
            </div>
            <Separator />
            <form class="space-y-8" @submit.prevent="onSubmit">
                <FormField v-slot="{ componentField }" name="firstName">
                    <FormItem>
                        <FormLabel>First Name</FormLabel>
                        <FormControl>
                            <Input type="text" v-bind="componentField" />
                        </FormControl>
                        <FormMessage />
                    </FormItem>
                </FormField>

                <FormField v-slot="{ componentField }" name="lastName">
                    <FormItem>
                        <FormLabel>Last Name</FormLabel>
                        <FormControl>
                            <Input type="text" v-bind="componentField" />
                        </FormControl>
                        <FormMessage />
                    </FormItem>
                </FormField>

                <FormField v-slot="{ componentField }" name="dateOfBirth">
                    <FormItem>
                        <FormLabel>Date of Birth</FormLabel>
                        <FormControl>
                            <Input
                                class="w-[150px]"
                                type="date"
                                v-bind="componentField"
                                :min="
                                    new Date(1900, 0, 1)
                                        .toISOString()
                                        .split('T')[0]
                                "
                                :max="new Date().toISOString().split('T')[0]"
                            />
                        </FormControl>
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

                <div class="flex gap-2 justify-start">
                    <Button type="submit"> Save changes </Button>
                </div>
            </form>
        </template>
    </SettingLayout>
</template>


