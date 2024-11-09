<script setup>
import { Button } from "@/Components/ui/button";
import { Input } from "@/Components/ui/input";
import { FormField } from "@/Components/ui/form";
import { FormItem } from "@/Components/ui/form";
import { FormLabel } from "@/Components/ui/form";
import { FormControl } from "@/Components/ui/form";
import { FormDescription } from "@/Components/ui/form";
import { FormMessage } from "@/Components/ui/form";
import {
    PinInput,
    PinInputGroup,
    PinInputInput,
} from "@/Components/ui/pin-input";
import { useForm } from "vee-validate";
import { toTypedSchema } from "@vee-validate/zod";
import * as z from "zod";
import { router } from "@inertiajs/vue3"

const formSchema = toTypedSchema(
    z.object({
        email: z.string().email("Invalid email address"),
        pin: z.array(z.coerce.string()).length(5, { message: "Invalid input" }),
    })
);

const { handleSubmit, setFieldValue, values } = useForm({
    validationSchema: formSchema,
    initialValues: {
        pin: [],
        email: "",
    },
});

const onSubmit = handleSubmit(() => {
    console.log("Form submitted!", values);
    router.get(route("create.about"), values);

});
</script>

<template>
    <Head :title="`${$page.props.company} - SportSync`" />
    <div class="flex items-center justify-start h-screen p-48">
        <div class="flex flex-col gap-y-4 max-w-lg">
            <div class="flex flex-col gap-y-2">
                <h2
                    class="scroll-m-20 border-b text-3xl font-semibold tracking-tight transition-colors first:mt-0"
                >
                    {{ $page.props.company }}
                </h2>
                <h1
                    class="scroll-m-20 text-4xl font-extrabold tracking-tight lg:text-5xl"
                >
                    Welcome to SportSync
                </h1>
                <p class="text-base text-muted-foreground">
                    Use the email and registration code provided by your
                    organization to get started.
                </p>
            </div>

            <form @submit="onSubmit" class="space-y-4">
                <FormField v-slot="{ componentField }" name="email">
                    <FormItem>
                        <FormLabel>Email</FormLabel>
                        <div class="flex w-full max-w-sm items-center gap-1.5">
                            <FormControl>
                                <Input
                                    type="email"
                                    placeholder="Your work email"
                                    v-bind="componentField"
                                />
                            </FormControl>
                        </div>
                        <FormMessage />
                    </FormItem>
                </FormField>
                <FormField v-slot="{ componentField, value }" name="pin">
                    <FormItem>
                        <FormLabel>OTP</FormLabel>
                        <FormControl>
                            <PinInput
                                id="pin-input"
                                :model-value="value"
                                placeholder="○"
                                class="flex gap-2 items-center mt-1"
                                otp
                                type="number"
                                :name="componentField.name"
                                @update:model-value="
                                    (arrStr) => {
                                        setFieldValue(
                                            'pin',
                                            arrStr.filter(Boolean)
                                        );
                                    }
                                "
                            >
                                <PinInputGroup>
                                    <PinInputInput
                                        v-for="(id, index) in 5"
                                        :key="id"
                                        :index="index"
                                    />
                                </PinInputGroup>
                            </PinInput>
                        </FormControl>
                        <FormMessage />
                    </FormItem>
                </FormField>
                <Button type="submit" class="w-full max-w-sm">Continue</Button>
            </form>

            <div>
                <p class="text-sm text-muted-foreground">
                    Already have a profile?
                    <Button asChild variant="link" class="px-1">
                        <Link :href="route('login')"> Login </Link>
                    </Button>
                </p>
                <p class="text-sm text-muted-foreground">
                    For assistance, please contact your HR department.
                </p>
            </div>
        </div>
    </div>
</template>
