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
import { FieldArray, useForm } from "vee-validate";
import { h, ref } from "vue";
import * as z from "zod";
import SettingLayout from "@/Layouts/SettingLayout.vue";


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

        dateOfBirth: z
            .string()
            .refine((val) => !isNaN(Date.parse(val)), {
                message: "Invalid date format.",
            })
            .refine((val) => new Date(val) <= new Date(), {
                message: "Date of birth cannot be in the future.",
            }),
        bio: z
            .string()
            .max(160, {
                message: "Bio must not be longer than 160 characters.",
            })
            .min(4, { message: "Bio must be at least 2 characters." }),
    })
);

const { handleSubmit, resetForm } = useForm({
    validationSchema: profileFormSchema,
    initialValues: {
        bio: "I own a computer.",
    },
});

const onSubmit = handleSubmit((values) => {
    toast({
        title: "You submitted the following values:",
        description: h(
            "pre",
            { class: "mt-2 w-[340px] rounded-md bg-slate-950 p-4" },
            h("code", { class: "text-white" }, JSON.stringify(values, null, 2))
        ),
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
            <form class="space-y-8" @submit="onSubmit">
                <FormField v-slot="{ componentField }" name="firstName">
                    <FormItem>
                        <FormLabel>First Name</FormLabel>
                        <FormControl>
                            <Input
                                type="text"
                                placeholder="John"
                                v-bind="componentField"
                            />
                        </FormControl>
                        <FormMessage />
                    </FormItem>
                </FormField>

                <FormField v-slot="{ componentField }" name="lastName">
                    <FormItem>
                        <FormLabel>Last Name</FormLabel>
                        <FormControl>
                            <Input
                                type="text"
                                placeholder="Doe"
                                v-bind="componentField"
                            />
                        </FormControl>
                        <FormMessage />
                    </FormItem>
                </FormField>

                <FormField v-slot="{ componentField }" name="dateOfBirth">
                    <FormItem>
                        <FormLabel>Date of Birth</FormLabel>
                        <FormControl>
                            <Input
                                class="w-1/4"
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
                    <Button type="submit"> Update profile </Button>
                </div>
            </form>
        </template>
    </SettingLayout>
</template>
