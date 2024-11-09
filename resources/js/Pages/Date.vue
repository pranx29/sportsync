<script setup>
import { ref, computed, h } from "vue";
import {
    CalendarCell,
    CalendarCellTrigger,
    CalendarGrid,
    CalendarGridBody,
    CalendarGridHead,
    CalendarGridRow,
    CalendarHeadCell,
    CalendarHeader,
    CalendarHeading,
} from "@/Components/ui/calendar";
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from "@/Components/ui/select";
import { cn } from "@/lib/utils";
import { CalendarRoot, useDateFormatter } from "radix-vue";
import { today, getLocalTimeZone, toDate } from "@internationalized/date";
import { Button } from "@/Components/ui/button";
import { Calendar } from "@/Components/ui/calendar";
import {
    FormControl,
    FormDescription,
    FormField,
    FormItem,
    FormLabel,
    FormMessage,
} from "@/Components/ui/form";
import {
    Popover,
    PopoverContent,
    PopoverTrigger,
} from "@/Components/ui/popover";
import { toast } from "@/Components/ui/toast";
import { useForm } from "vee-validate";
import { z } from "zod";

// Initialize date formatter
const df = new Intl.DateTimeFormat("en-US", { dateStyle: "long" });

// Define form validation schema using zod
const formSchema = z.object({
    dob: z.string().nonempty({ message: "A date of birth is required." }),
});

// Reactive state for the selected date
const placeholder = ref(null);

// Vee-validate form setup
const { handleSubmit, setFieldValue, values } = useForm({
    validationSchema: formSchema,
});

const value = computed({
    get: () => (values.dob ? toDate(new Date(values.dob)) : null),
    set: (val) => val,
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
    <form class="space-y-8" @submit="onSubmit">
        <FormField name="dob">
            <FormItem class="flex flex-col">
                <FormLabel>Date of birth</FormLabel>
                <Popover>
                    <PopoverTrigger as-child>
                        <FormControl>
                            <Button
                                variant="outline"
                                :class="
                                    cn(
                                        'w-[240px] ps-3 text-start font-normal',
                                        !value && 'text-muted-foreground'
                                    )
                                "
                            >
                                <span>{{
                                    value
                                        ? df.format(toDate(value))
                                        : "Pick a date"
                                }}</span>
                                <CalendarIcon
                                    class="ms-auto h-4 w-4 opacity-50"
                                />
                            </Button>
                            <input hidden />
                        </FormControl>
                    </PopoverTrigger>
                    <PopoverContent class="w-auto p-0">
                        <Calendar
                            v-model:placeholder="placeholder"
                            v-model="value"
                            calendar-label="Date of birth"
                            initial-focus
                            :min-value="new CalendarDate(1900, 1, 1)"
                            :max-value="today(getLocalTimeZone())"
                            @update:model-value="
                                (v) => {
                                    if (v) {
                                        setFieldValue('dob', v.toString());
                                    } else {
                                        setFieldValue('dob', undefined);
                                    }
                                }
                            "
                        />
                    </PopoverContent>
                </Popover>
                <FormDescription>
                    Your date of birth is used to calculate your age.
                </FormDescription>
                <FormMessage />
            </FormItem>
        </FormField>
        <Button type="submit"> Submit </Button>
    </form>
</template>
