<script setup>
import ProfileCreationLayout from "@/Layouts/ProfileCreationLayout.vue";
import { Button } from "@/Components/ui/button";
import { Input } from "@/Components/ui/input";
import { Label } from "@/Components/ui/label";
import { Checkbox } from "@/Components/ui/checkbox";
import {
    FormField,
    FormItem,
    FormLabel,
    FormControl,
    FormDescription,
    FormMessage,
} from "@/Components/ui/form";
import { useForm as useVeeForm } from "vee-validate";
import { toTypedSchema } from "@vee-validate/zod";
import * as z from "zod";
import { useForm } from "@inertiajs/vue3";

const formSchema = toTypedSchema(
    z.object({
        sports: z
            .array(z.string())
            .refine((value) => value.some((sport) => sport), {
                message: "You have to select at least one item.",
            }),
    })
);

const validateForm = useVeeForm({
    validationSchema: formSchema,
    initialValues: {
        sports: [],
    },
});

const form = useForm({
    sports: [],
});

const onSubmit = () => {
    validateForm.validate().then(() => {
        if (Object.keys(validateForm.errors.value).length > 0) {
            console.log("Client-side validation failed");
            return;
        }
        console.log("Client-side validation passed");
        form.clearErrors();
        form.post(route("create.interest"), {
            onError: () => {
                console.log(form.errors);
            },
        });
    });
};
</script>

<template>
    <Head title="Create Profile | Interests" />
    <ProfileCreationLayout>
        <template #title> What sports are you interested in? </template>
        <template #form>
            <form @submit.prevent="onSubmit" class="w-full max-w-sm">
                <FormField name="sports">
                    <FormItem>
                        <div class="mb-4">
                            <FormLabel class="text-base"> Sport </FormLabel>
                            <FormDescription>
                                Select the sports you are interested in.
                            </FormDescription>
                        </div>

                        <FormField
                            v-for="sport in $page.props.sports"
                            v-slot="{ value, handleChange }"
                            v-model:model-value="form.sports"
                            :key="sport.id"
                            type="checkbox"
                            :value="sport.name"
                            :unchecked-value="false"
                            name="sports"
                        >
                            <FormItem
                                class="flex flex-row items-start gap-x-3 rounded-md border p-4 w-full max-w-sm"
                            >
                                <FormLabel class="w-full font-normal text-sm">
                                    {{ sport.name }}
                                </FormLabel>
                                <FormControl class="ml-auto">
                                    <Checkbox
                                        :checked="value.includes(sport.name)"
                                        @update:checked="handleChange"
                                        class="space-y-0"
                                    />
                                </FormControl>
                            </FormItem>
                        </FormField>
                    </FormItem>
                    <FormMessage />
                </FormField>
                <div class="flex justify-left mt-4">
                    <Button type="submit"> Continue </Button>
                </div>
            </form>
        </template>
    </ProfileCreationLayout>
</template>
