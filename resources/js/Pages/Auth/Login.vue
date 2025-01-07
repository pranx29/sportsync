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
import Autoplay from "embla-carousel-autoplay";
import {
    Carousel,
    CarouselContent,
    CarouselItem,
    CarouselNext,
    CarouselPrevious,
} from "@/Components/ui/carousel";
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

    <div class="flex flex-col lg:flex-row min-h-screen no-scroll">
        <!-- Left Content -->
        <div class="flex items-center justify-center lg:w-1/2 p-12">
            <div class="mx-auto grid w-full max-w-md gap-6">
                <div class="text-center">
                    <!-- Logo Section -->
                    <div class="flex items-center justify-center mb-10">
                        <Logo class="w-16 h-16 text-teal-600 mr-6" />
                        <h1
                            class="text-teal-600 text-5xl font-bold font-poppins"
                        >
                            SPORT
                        </h1>
                        <h1
                            class="text-teal-800 text-5xl font-bold font-poppins"
                        >
                            SYNC
                        </h1>
                    </div>

                    <h1
                        class="text-4xl font-extrabold font-poppins tracking-tight lg:text-5xl text-slate-900 mb-8"
                    >
                        LOGIN
                    </h1>
                </div>

                <!-- Login Form -->
                <form @submit.prevent="onSubmit">
                    <div class="flex flex-col gap-4">
                        <!-- Email Field -->
                        <FormField v-slot="{ componentField }" name="email">
                            <FormItem>
                                <FormLabel> Email </FormLabel>
                                <FormControl>
                                    <Input
                                        placeholder="Your work email"
                                        class="border-gray-300"
                                        type="email"
                                        v-bind="componentField"
                                        id="txtEmail"
                                    />
                                </FormControl>
                                <FormMessage />
                            </FormItem>
                        </FormField>

                        <!-- Password Field -->
                        <FormField v-slot="{ componentField }" name="password">
                            <FormItem>
                                <FormLabel> Password </FormLabel>
                                <FormControl>
                                    <Input
                                        placeholder="Your password"
                                        class="border-gray-300"
                                        type="password"
                                        v-bind="componentField"
                                        id="txtPassword"
                                    />
                                </FormControl>
                                <FormMessage />
                            </FormItem>
                        </FormField>

                        <!-- Login Button -->
                        <Button type="submit" id="btnLogin"> Login </Button>
                    </div>
                </form>

                <!-- Connect Section -->
                <div class="text-center mt-8">
                    <h3 class="text-teal-800 text-2xl font-bold mb-2">
                        CONNECT
                    </h3>
                    <p class="text-teal-800 text-lg font-bold">
                        with
                        <span class="inline-flex gap-2">
                            <span
                                v-for="i in 4"
                                :key="i"
                                class="h-3 w-3 bg-teal-800 rounded-full"
                            >
                            </span>
                        </span>
                        your mates
                    </p>
                </div>
            </div>
        </div>

        <!-- Right Carousel -->
        <div class="flex items-center justify-center lg:w-1/2">
            <Carousel
                :opts="{
                    align: 'start',
                    loop: true,
                }"
                :plugins="[
                    Autoplay({
                        delay: 2500,
                    }),
                ]"
            >
                <CarouselContent class="snap-x mr-4">
                    <CarouselItem class="snap-center">
                        <img
                            src="/images/Basketball.svg"
                            alt="Slide 1"
                            class="object-contain"
                        />
                    </CarouselItem>
                    <CarouselItem class="snap-center">
                        <img
                            src="/images/Football.svg"
                            alt="Slide 2"
                            class="object-contain rounded-lg"
                        />
                    </CarouselItem>
                    <CarouselItem class="snap-center">
                        <img
                            src="/images/Cricket.svg"
                            alt="Slide 3"
                            class="object-contain rounded-lg"
                        />
                    </CarouselItem>
                    <CarouselItem class="snap-center">
                        <img
                            src="/images/Rugby.svg"
                            alt="Slide 3"
                            class="object-contain rounded-lg"
                        />
                    </CarouselItem>
                    <CarouselItem class="snap-center">
                        <img
                            src="/images/Tennis.svg"
                            alt="Slide 3"
                            class="object-contain rounded-lg"
                        />
                    </CarouselItem>
                </CarouselContent>
            </Carousel>
        </div>
    </div>
    <Toaster />
</template>
