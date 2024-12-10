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
import { router } from "@inertiajs/vue3";
import {
    useSetFieldError,
    useSetFieldValue,
    useForm as useVeeForm,
} from "vee-validate";
import { toTypedSchema } from "@vee-validate/zod";
import * as z from "zod";
import { toast, Toaster, ToastAction } from "@/Components/ui/toast";
import { h } from "vue";
import Logo from "@/Components/Logo.vue";
import { useColorMode } from "@vueuse/core";

const mode = useColorMode();
mode.value = "light";

const formSchema = toTypedSchema(
    z.object({
        email: z.string().email("Invalid email address"),
        password: z.string(),
    })
);

const { handleSubmit: handleLogin, setFieldValue } = useVeeForm({
    validationSchema: formSchema,
    initialValues: {
        email: "",
        password: "",
    },
});

const onSubmit = handleLogin(async (values) => {
    router.post(route("login"), values, {
        preserveScroll: true,
        preserveState: true,
        onError: () => {
            setFieldValue("email", values.email);
            setFieldValue("password", "");
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
</script>

<template>
    <Head title="Login" />

    <div class="flex flex-col lg:flex-row min-h-screen">
        <div class="hidden lg:flex lg:w-1/2 relative">
            <div class="w-full h-full">
                <svg
                    class="w-full h-full object-cover"
                    viewBox="0 0 1080 1080"
                    fill="none"
                    xmlns="http://www.w3.org/2000/svg"
                    preserveAspectRatio="xMidYMid slice"
                >
                    <path
                        d="M1080 0H0V1080H1012.96C1031.82 978.856 1029.94 923.138 998.768 825.984C963.629 738.02 945.754 688.007 921.939 595.728C904.705 460.996 905.959 382.859 967.449 229.392C1008.26 138.171 1033.09 88.0455 1080 0Z"
                        fill="url(#paint0_linear_347_6)"
                    />
                    <defs>
                        <linearGradient
                            id="paint0_linear_347_6"
                            x1="830.223"
                            y1="1080"
                            x2="358.744"
                            y2="-45.6411"
                            gradientUnits="userSpaceOnUse"
                        >
                            <stop offset="0.151689" stop-color="#336C6C" />
                            <stop offset="0.347569" stop-color="#368E81" />
                            <stop offset="0.444501" stop-color="#379887" />
                            <stop offset="0.683079" stop-color="#309487" />
                            <stop offset="0.795149" stop-color="#336C6C" />
                        </linearGradient>
                    </defs>
                </svg>
            </div>

            <div
                class="absolute inset-0 flex flex-col items-start ml-16 justify-center"
            >
                <div class="absolute top-10 flex items-center">
                    <Logo class="w-16 h-16 text-background" />
                    <p class="ml-4 text-3xl font-extrabold text-background">
                        SportSync
                    </p>
                </div>
                <div class="space-y-4">
                    <h1
                        class="-ml-1 scroll-m-20 text-8xl font-extrabold tracking-tight text-background"
                    >
                        CONNECT
                    </h1>
                    <h2
                        class="scroll-m-20 text-4xl font-bold tracking-tight transition-colors first:mt-0 text-muted"
                    >
                        WITH YOUR MATES
                    </h2>
                    <span class="block mt-4">
                        <svg
                            width="130"
                            height="25"
                            viewBox="0 0 130 25"
                            fill="none"
                            xmlns="http://www.w3.org/2000/svg"
                            class="text-background"
                        >
                            <path
                                d="M0 12.5C0 5.59644 5.59644 0 12.5 0V0C19.4036 0 25 5.59644 25 12.5V12.5C25 19.4036 19.4036 25 12.5 25V25C5.59644 25 0 19.4036 0 12.5V12.5Z"
                                class="fill-current"
                            />
                            <path
                                d="M35 12.5C35 5.59644 40.5964 0 47.5 0V0C54.4036 0 60 5.59644 60 12.5V12.5C60 19.4036 54.4036 25 47.5 25V25C40.5964 25 35 19.4036 35 12.5V12.5Z"
                                class="fill-current"
                            />
                            <path
                                d="M70 12.5C70 5.59644 75.5964 0 82.5 0V0C89.4036 0 95 5.59644 95 12.5V12.5C95 19.4036 89.4036 25 82.5 25V25C75.5964 25 70 19.4036 70 12.5V12.5Z"
                                class="fill-current"
                            />
                            <path
                                d="M105 12.5C105 5.59644 110.596 0 117.5 0V0C124.404 0 130 5.59644 130 12.5V12.5C130 19.4036 124.404 25 117.5 25V25C110.596 25 105 19.4036 105 12.5V12.5Z"
                                class="fill-current"
                            />
                        </svg>
                    </span>
                </div>
            </div>
        </div>
        <div class="flex items-center justify-center py-12 lg:w-1/2">
            <div
                class="mx-auto grid w-full max-w-md gap-6 px-4 sm:px-6 lg:px-8"
            >
                <div class="grid gap-2 text-center">
                    <!-- <h2
                        class="scroll-m-20 border-b text-3xl font-semibold tracking-tight transition-colors first:mt-0"
                    >
                        {{ $page.props.companyName }}
                    </h2> -->
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
                                        placeholder="Your work email"
                                        v-bind="componentField"
                                    />
                                </FormControl>
                                <FormMessage />
                            </FormItem>
                        </FormField>
                        <FormField v-slot="{ componentField }" name="password">
                            <FormItem>
                                <FormLabel>Password</FormLabel>
                                <FormControl>
                                    <Input
                                        type="password"
                                        placeholder="Your password"
                                        v-bind="componentField"
                                    />
                                </FormControl>
                                <FormMessage />
                            </FormItem>
                        </FormField>
                        <Button class="mt-2" type="submit"> Login </Button>
                    </div>
                </form>
                <div class="text-center text-sm">
                    <!-- <p class="text-sm text-muted-foreground">
                        Forgot your password?
                        <Button asChild variant="link" class="px-1">
                            <Link :href="route('password.request')">
                                Reset
                            </Link>
                        </Button>
                    </p> -->
                    <p class="text-sm text-muted-foreground">
                        For assistance, please contact your HR department.
                    </p>
                </div>
            </div>
        </div>
    </div>
    <Toaster />
</template>
