<script setup>
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from "@/Components/ui/card";
import { Input } from "@/Components/ui/input";
import {
    Form,
    FormControl,
    FormDescription,
    FormField,
    FormItem,
    FormLabel,
    FormMessage,
} from "@/Components/ui/form";
import { Button } from "@/Components/ui/button";
import { toast } from "@/Components/ui/toast";
import { toTypedSchema } from "@vee-validate/zod";
import { useForm as useVeeForm } from "vee-validate";
import { z } from "zod";
import { router } from "@inertiajs/vue3";

const props = defineProps({
    user: {
        type: Object,
        required: true,
    },
});

const updateAccountSchema = toTypedSchema(
    z.object({
        first_name: z.string().min(1, "First name is required."),
        last_name: z.string().min(1, "Last name is required."),
        email: z
            .string()
            .email("Invalid email address.")
            .min(1, "Email is required."),
    })
);

const { handleSubmit: handleAccountSubmit, resetForm: resetAccountForm } =
    useVeeForm({
        validationSchema: updateAccountSchema,
        initialValues: {
            first_name: props.user.first_name,
            last_name: props.user.last_name,
            email: props.user.email,
        },
    });

const updateAccount = handleAccountSubmit(async (values) => {
    router.patch(route("admin.account.update"), values, {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            toast({
                title: "Account updated",
                description: "Account details have been updated successfully.",
                variant: "success",
            });
        },
        onError: () => {
            toast({
                title: "Failed to update account",
                description: "An error occurred while updating the account.",
                variant: "destructive",
            });
        },
    });
});
</script>

<template>
    <Card>
        <CardHeader>
            <CardTitle>Profile Information</CardTitle>
            <CardDescription>
                Update your profile information.
            </CardDescription>
        </CardHeader>
        <CardContent>
            <form @submit.prevent="updateAccount" class="space-y-2">
                <FormField v-slot="{ componentField }" name="first_name">
                    <FormItem>
                        <FormLabel>First Name</FormLabel>
                        <div class="flex items-center gap-1.5">
                            <FormControl>
                                <Input
                                    type="text"
                                    placeholder="First Name"
                                    v-bind="componentField"
                                />
                            </FormControl>
                        </div>
                        <FormMessage />
                    </FormItem>
                </FormField>
                <FormField v-slot="{ componentField }" name="last_name">
                    <FormItem>
                        <FormLabel>Last Name</FormLabel>
                        <div class="flex items-center gap-1.5">
                            <FormControl>
                                <Input
                                    type="text"
                                    placeholder="Last Name"
                                    v-bind="componentField"
                                />
                            </FormControl>
                        </div>
                        <FormMessage />
                    </FormItem>
                </FormField>
                <FormField v-slot="{ componentField }" name="email">
                    <FormItem>
                        <FormLabel>Email</FormLabel>
                        <div class="flex items-center gap-1.5">
                            <FormControl>
                                <Input
                                    type="email"
                                    placeholder="Email"
                                    v-bind="componentField"
                                />
                            </FormControl>
                        </div>
                        <FormMessage />
                    </FormItem>
                </FormField>
                <Button type="submit">Update</Button>
            </form>
        </CardContent>
    </Card>
</template>
