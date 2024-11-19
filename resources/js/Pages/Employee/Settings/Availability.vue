<script setup>
import {
    FormControl,
    FormField,
    FormItem,
    FormLabel,
    FormMessage,
} from "@/Components/ui/form";
import { Button } from "@/Components/ui/button";
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
import SettingLayout from "@/Layouts/EmployeeSettingLayout.vue";
import { ref } from "vue";
import * as z from "zod";
import { toTypedSchema } from "@vee-validate/zod";
import { useForm } from "vee-validate";
import { usePage, router } from "@inertiajs/vue3";

const availabilitySchema = toTypedSchema(
    z.object({
        Monday: z.string().optional(),
        Tuesday: z.string().optional(),
        Wednesday: z.string().optional(),
        Thursday: z.string().optional(),
        Friday: z.string().optional(),
        Saturday: z.string().optional(),
        Sunday: z.string().optional(),
    })
);

const { props } = usePage();
const availability = props.availability;

const initialValues = availability.reduce(
    (acc, { day, time_slot }) => {
        acc[day] = time_slot;
        return acc;
    },
    {
        Monday: "",
        Tuesday: "",
        Wednesday: "",
        Thursday: "",
        Friday: "",
        Saturday: "",
        Sunday: "",
    }
);

const { handleSubmit } = useForm({
    validationSchema: availabilitySchema,
    initialValues,
});

const onSubmit = handleSubmit((values) => {
    console.log(values);
    const availability = Object.entries(values).map(([day, time_slot]) => ({
        day,
        time_slot,
    }));
    router.patch(route("availability.update"), availability, {
        preserveState: true,
        onSuccess: () => {
            toast({
                title: "Availability updated successfully.",
            });
        },
        onError: (errors) => {
            toast({
                title: "Failed to update availability.",
                type: "error",
            });
        },
    });
});
</script>

<template>
    <Head title="Settings | Availabiltiy" />
    <SettingLayout>
        <template #content>
            <div>
                <h3 class="text-lg font-medium">Availability Settings</h3>
                <p class="text-sm text-muted-foreground">
                    Set your availability for each day of the week.
                </p>
            </div>
            <Separator />
            <form class="space-y-8" @submit.prevent="onSubmit">
                <div
                    v-for="day in $page.props.days"
                    :key="day"
                    class="day-availability"
                >
                    <FormField v-slot="{ componentField }" :name="day">
                        <FormItem>
                            <FormLabel :for="day">{{ day }}</FormLabel>
                            <FormControl>
                                <Select :id="day" v-bind="componentField">
                                    <SelectTrigger>
                                        <SelectValue
                                            placeholder="Select a time slot"
                                        />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectGroup>
                                            <SelectItem
                                                v-for="slot in $page.props
                                                    .timeslots"
                                                :key="slot"
                                                :value="slot"
                                            >
                                                {{ slot }}
                                            </SelectItem>
                                        </SelectGroup>
                                    </SelectContent>
                                </Select>
                            </FormControl>
                            <FormMessage />
                        </FormItem>
                    </FormField>
                </div>
                <div class="flex gap-2 justify-start">
                    <Button type="submit">Save changes </Button>
                </div>
            </form>
        </template>
    </SettingLayout>
</template>
