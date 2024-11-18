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

const updatePasswordSchema = toTypedSchema(
    z.object({
        current_password: z.string().min(1, "Current password is required."),
        new_password: z.string().min(8, "Password must be at least 8 characters."),
        confirm_password: z.string().min(1, "Confirm password is required."),
    }).refine((data) => data.new_password === data.confirm_password, {
        message: "Passwords do not match.",
        path: ["confirm_password"],
    })
);

const { handleSubmit: handlePasswordSubmit, resetForm: resetPasswordForm } =
    useVeeForm({
        validationSchema: updatePasswordSchema,
    });

const updatePassword = handlePasswordSubmit(async (values) => {
    console.log(values);
    router.patch(route("admin.password.update"), values, {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            toast({
                title: "Password updated",
                description: "Password has been updated successfully.",
                variant: "success",
            });
        },
        onError: (errors) => {
            toast({
            title: "Failed to update password",
            description: errors.current_password || errors.new_password || errors.confirm_password || "An error occurred while updating the password.",
            variant: "destructive",
            });
        },
    });
    resetPasswordForm();
});
</script>

<template>
    <Card>
        <CardHeader>
            <CardTitle>Change Password</CardTitle>
            <CardDescription> Update your account password. </CardDescription>
        </CardHeader>
        <CardContent>
            <form @submit.prevent="updatePassword" class="space-y-2">
                <FormField v-slot="{ componentField }" name="current_password">
                    <FormItem>
                        <FormLabel>Current Password</FormLabel>
                        <div class="flex items-center gap-1.5">
                            <FormControl>
                                <Input
                                    type="password"
                                    placeholder="Current Password"
                                    v-bind="componentField"
                                />
                            </FormControl>
                        </div>
                        <FormMessage />
                    </FormItem>
                </FormField>
                <FormField v-slot="{ componentField }" name="new_password">
                    <FormItem>
                        <FormLabel>New Password</FormLabel>
                        <div class="flex items-center gap-1.5">
                            <FormControl>
                                <Input
                                    type="password"
                                    placeholder="New Password"
                                    v-bind="componentField"
                                />
                            </FormControl>
                        </div>
                        <FormMessage />
                    </FormItem>
                </FormField>
                <FormField v-slot="{ componentField }" name="confirm_password">
                    <FormItem>
                        <FormLabel>Confirm Password</FormLabel>
                        <div class="flex items-center gap-1.5">
                            <FormControl>
                                <Input
                                    type="password"
                                    placeholder="Confirm Password"
                                    v-bind="componentField"
                                />
                            </FormControl>
                        </div>
                        <FormMessage />
                    </FormItem>
                </FormField>
                <Button type="submit">Change Password</Button>
            </form>
        </CardContent>
    </Card>
</template>
