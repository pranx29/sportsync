<script setup>
import {
    Card,
    CardContent,
    CardDescription,
    CardFooter,
    CardHeader,
    CardTitle,
} from "@/Components/ui/card";
import { Label } from "@/Components/ui/label";
import { Separator } from "radix-vue";
import { Progress } from "@/Components/ui/progress";
import {
    Form,
    FormControl,
    FormDescription,
    FormField,
    FormItem,
    FormLabel,
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
import {
    Sheet,
    SheetClose,
    SheetContent,
    SheetDescription,
    SheetFooter,
    SheetHeader,
    SheetTitle,
    SheetTrigger,
} from "@/Components/ui/sheet";
import { Slider } from "@/Components/ui/slider";
import { Input } from "@/Components/ui/input";
import { Button } from "@/Components/ui/button";
import { toTypedSchema } from "@vee-validate/zod";
import { h } from "vue";
import * as z from "zod";
import { Pencil, CirclePlus } from "lucide-vue-next";
import { useForm as useVeeForm } from "vee-validate";
import { ref } from "vue";
import { router } from "@inertiajs/vue3";
import { toast } from "@/Components/ui/toast";

const isAddFormOpen = ref(false);
const props = defineProps({
    getSkillLevel: Function,
});
const addInterestSchema = toTypedSchema(
    z.object({
        sport_id: z.string({
            required_error: "Please select a sport.",
        }),

        interest_level: z.string({
            required_error: "Please select an interest.",
        }),
        skill_level: z.array(
            z
                .number({
                    required_error: "Please select a skill level.",
                })
                .min(25, "Skill level must be at least 25.")
                .max(100, "Skill level must be at most 100.")
        ),
    })
);
const { handleSubmit: handleAddSubmit, resetForm: resetAddForm } = useVeeForm({
    validationSchema: addInterestSchema,
    initialValues: {
        skill_level: [25],
    },
});

const onAddSubmit = handleAddSubmit(async (values) => {
    values.skill_level = props.getSkillLevel(values.skill_level[0]);
    values.sport_id = parseInt(values.sport_id);
    router.post(route("create.interest"), values, {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            toast({
                title: "Interest added",
                description: "Your interest has been added successfully.",
                variant: "success",
            });
        },
        onError: () => {
            toast({
                title: "Failed to add interest",
                description: "An error occurred while adding your interest.",
                variant: "destructive",
            });
        },
    });
    resetAddForm();
    isAddFormOpen.value = false;
});
</script>

<template>
    <Sheet v-model:open="isAddFormOpen">
        <SheetTrigger as-child>
            <Button variant="outline" size="icon">
                <CirclePlus class="w-4 h-4" />
            </Button>
        </SheetTrigger>
        <SheetContent>
            <SheetHeader>
                <SheetTitle>Add interest</SheetTitle>
                <SheetDescription>
                    Select your favorite sport to add it to your interests.
                </SheetDescription>
            </SheetHeader>
            <div class="grid gap-4 py-4">
                <form class="w-2/3 space-y-6" @submit.prevent="onAddSubmit">
                    <!-- Select a sport -->
                    <FormField v-slot="{ componentField }" name="sport_id">
                        <FormItem>
                            <FormLabel>Sport</FormLabel>
                            <Select v-bind="componentField">
                                <FormControl>
                                    <SelectTrigger>
                                        <SelectValue
                                            placeholder="Select a sport"
                                        />
                                    </SelectTrigger>
                                </FormControl>
                                <SelectContent>
                                    <SelectGroup>
                                        <SelectItem
                                            v-for="sport in $page.props.sports"
                                            :key="sport.id"
                                            :value="sport.id.toString()"
                                        >
                                            {{ sport.name }}
                                        </SelectItem>
                                    </SelectGroup>
                                </SelectContent>
                            </Select>
                            <FormMessage />
                        </FormItem>
                    </FormField>

                    <!-- Select interest level -->
                    <FormField
                        v-slot="{ componentField }"
                        name="interest_level"
                    >
                        <FormItem>
                            <FormLabel>Interest level</FormLabel>

                            <Select v-bind="componentField">
                                <FormControl>
                                    <SelectTrigger>
                                        <SelectValue
                                            placeholder="Select a interest"
                                        />
                                    </SelectTrigger>
                                </FormControl>
                                <SelectContent>
                                    <SelectGroup>
                                        <SelectItem value="low">
                                            Low
                                        </SelectItem>
                                        <SelectItem value="medium">
                                            Medium
                                        </SelectItem>
                                        <SelectItem value="high">
                                            High
                                        </SelectItem>
                                    </SelectGroup>
                                </SelectContent>
                            </Select>
                            <FormMessage />
                        </FormItem>
                    </FormField>

                    <!-- Set skill level -->
                    <FormField
                        v-slot="{ componentField, value }"
                        name="skill_level"
                    >
                        <FormItem>
                            <FormLabel>Skill level</FormLabel>
                            <FormControl>
                                <Slider
                                    v-bind="componentField"
                                    :default-value="[value]"
                                    :max="100"
                                    :min="25"
                                    :step="25"
                                />
                                <FormDescription class="flex justify-between">
                                    <span v-if="value?.[0] === 25"
                                        >Beginner</span
                                    >
                                    <span v-else-if="value?.[0] === 50"
                                        >Intermediate</span
                                    >
                                    <span v-else-if="value?.[0] === 75"
                                        >Advanced</span
                                    >
                                    <span v-else-if="value?.[0] === 100"
                                        >Expert</span
                                    >
                                </FormDescription>
                            </FormControl>
                            <FormMessage />
                        </FormItem>
                    </FormField>

                    <Button type="submit"> Save </Button>
                </form>
            </div>
        </SheetContent>
    </Sheet>
</template>
