<script setup>
import { Input } from "@/Components/ui/input";
import { Button } from "@/Components/ui/button";
import {
    FormField,
    FormItem,
    FormLabel,
    FormControl,
    FormDescription,
    FormMessage,
} from "@/Components/ui/form";
import { useForm } from "@inertiajs/vue3";
import { useForm as useVeeForm } from "vee-validate";
import { toTypedSchema } from "@vee-validate/zod";
import * as z from "zod";
import { toast, Toaster, ToastAction } from "@/Components/ui/toast";
import { h } from "vue";

const formSchema = toTypedSchema(
    z.object({
        email: z.string().email("Invalid email address"),
        password: z.string(),
    })
);

const validateForm = useVeeForm({
    validationSchema: formSchema,
});

const form = useForm({
    password: "",
    email: "",
    remember: false,
});

const onSubmit = () => {
    validateForm.validate().then(() => {
        if (Object.keys(validateForm.errors.value).length > 0) {
            console.log("Client-side validation failed");
            return;
        }
        console.log("Client-side validation passed");
        form.clearErrors();
        form.post(route("login"), {
            onError: () => {
                toast({
                    title: "Login Failed",
                    description: "Invalid credentials. Please try again.",
                    variant: "destructive",
                    action: h(
                        ToastAction,
                        {
                            altText: "Try again",
                        },
                        {
                            default: () => "Try again",
                        }
                    ),
                });
            },
        });
    });
};
</script>

<template>
    <div
        class="w-full lg:grid lg:min-h-[600px] lg:grid-cols-2 xl:min-h-[800px]"
    >
        <div class="flex items-center justify-center py-12">
            <div class="mx-auto grid w-[350px] gap-6">
                <div class="grid gap-2 text-center">
                    <h2
                        class="scroll-m-20 border-b text-3xl font-semibold tracking-tight transition-colors first:mt-0"
                    >
                        {{ $page.props.companyName }}
                    </h2>
                    <h1
                        class="scroll-m-20 text-4xl font-extrabold tracking-tight lg:text-5xl"
                    >
                        Login to your account
                    </h1>
                    <p class="text-sm text-muted-foreground">
                        Please enter your credentials to access your profile and
                        stay connected with sports activities.
                    </p>
                </div>
                <form @submit.prevent="onSubmit">
                    <div class="flex flex-col gap-4">
                        <FormField v-slot="{ componentField }" name="email">
                            <FormItem>
                                <FormLabel>Email</FormLabel>
                                <FormControl>
                                    <Input
                                        type="email"
                                        v-model:modelValue="form.email"
                                        placeholder="Your work email"
                                        v-bind="componentField"
                                    />
                                </FormControl>
                                <FormMessage>
                                    {{ form.errors.email }}
                                </FormMessage>
                            </FormItem>
                        </FormField>
                        <FormField v-slot="{ componentField }" name="password">
                            <FormItem>
                                <FormLabel>Password</FormLabel>
                                <FormControl>
                                    <Input
                                        type="password"
                                        v-model:modelValue="form.password"
                                        placeholder="Your password"
                                        v-bind="componentField"
                                    />
                                </FormControl>
                                <FormMessage>
                                    {{ form.errors.password }}
                                </FormMessage>
                            </FormItem>
                        </FormField>
                        <Button class="mt-2" type="submit"> Login </Button>
                    </div>
                </form>
                <div class="mt-4 text-center text-sm">
                    <p class="text-sm text-muted-foreground">
                        Forgot your password?
                        <Button asChild variant="link" class="px-1">
                            <Link :href="route('password.request')">
                                Reset
                            </Link>
                        </Button>
                    </p>
                    <p class="text-sm text-muted-foreground">
                        For assistance, please contact your HR department.
                    </p>
                </div>
            </div>
        </div>
        <div class="hidden bg-muted lg:block">
            <!-- <img
                src="https://images.pexels.com/photos/1939485/pexels-photo-1939485.jpeg?auto=compress&cs=tinysrgb"
                alt="Image"
                width="1920"
                height="1080"
                class="h-full w-full object-cover dark:brightness-[0.2] dark:grayscale"
            /> -->
        </div>
        <Toaster />
    </div>
</template>
