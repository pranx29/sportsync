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
import {
    Select,
    SelectContent,
    SelectGroup,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from "@/Components/ui/select";
import { RadioGroup, RadioGroupItem } from "@/Components/ui/radio-group";
import { useForm as useVeeForm } from "vee-validate";
import { toTypedSchema } from "@vee-validate/zod";
import * as z from "zod";
import { useForm } from "@inertiajs/vue3";

const formSchema = toTypedSchema(
    z.object({
        days: z
            .array(z.string())
            .refine((value) => value.some((item) => item), {
            message: "Please select at least one day.",
            }),
        timeslot: z.string().nonempty("You have to select a timeslot."),
        })
);

const validateForm = useVeeForm({
    validationSchema: formSchema,
    initialValues: {
        timeslot: "",
        days: [],
    },
});

const form = useForm({
    timeslot: "",
    days: [],
});

const onSubmit = () => {
    validateForm.validate().then(() => {
        if (Object.keys(validateForm.errors.value).length > 0) {
            console.log("Client-side validation failed");
            return;
        }
        console.log("Client-side validation passed");
        form.clearErrors();
        form.post(route("create.availability"), {
            onError: () => {
                console.log(form.errors);
            },
        });
    });
};
</script>

<template>
    <Head title="Create Profile | Availability" />
    <ProfileCreationLayout>
        <template #title>When can you participate?</template>
        <template #form>
            <form @submit.prevent="onSubmit">
                <FormField
                    v-slot="{ componentField }"
                    type="radio"
                    name="timeslot"
                    v-model:model-value="form.timeslot"
                >
                    <FormItem class="space-y-3">
                        <FormLabel>Times</FormLabel>

                        <FormControl>
                            <RadioGroup
                                class="flex flex-col space-y-1"
                                v-bind="componentField"
                            >
                                <FormItem
                                    v-for="timeslot in $page.props.timeslots"
                                    :key="timeslot"
                                    class="flex items-center space-y-0 gap-x-3"
                                >
                                    <FormControl>
                                        <RadioGroupItem :value="timeslot" />
                                    </FormControl>
                                    <FormLabel class="font-normal">
                                        {{ timeslot }}
                                    </FormLabel>
                                </FormItem>
                            </RadioGroup>
                        </FormControl>
                        <FormMessage />
                    </FormItem>
                </FormField>

                <FormField name="days">
                    <FormItem>
                        <div class="mb-4">
                            <FormLabel> Days </FormLabel>
                        </div>
                        <FormField
                            v-for="day in $page.props.days"
                            v-slot="{ value, handleChange }"
                            :key="day"
                            type="checkbox"
                            :value="day"
                            v-model:model-value="form.days"
                            :unchecked-value="false"
                            name="days"
                        >
                            <FormItem
                                class="flex flex-row items-start space-x-3 space-y-0"
                            >
                                <FormControl>
                                    <Checkbox
                                        :checked="value.includes(day)"
                                        @update:checked="handleChange"
                                    />
                                </FormControl>

                                <FormLabel class="font-normal">
                                    {{ day }}
                                </FormLabel>
                            </FormItem>
                        </FormField>

                        <FormMessage />
                    </FormItem>
                </FormField>

                <div class="flex justify-start mt-4">
                    <Button type="submit"> Continue </Button>
                </div>
            </form>
        </template>
    </ProfileCreationLayout>
</template>
