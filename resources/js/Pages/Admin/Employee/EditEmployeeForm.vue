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
import { useSetFieldValue, useForm as useVeeForm } from "vee-validate";
import { ref } from "vue";
import { router } from "@inertiajs/vue3";
import { toast } from "@/Components/ui/toast";
import { RotateCw } from "lucide-vue-next";

const isEditFormOpen = ref(false);
const props = defineProps({
    employee: {
        type: Object,
        required: true,
    },
});

const employeeSchema = toTypedSchema(
    z.object({
        first_name: z.string().nonempty("First name is required."),
        last_name: z.string().optional(),
        email: z.string().email("Invalid email address."),
        password: z.string().optional(),
        is_active: z.union([z.literal("1"), z.literal("0")]).default("1"),
    })
);

const { handleSubmit: handleEmployeeSubmit, resetForm: resetEmployeeForm, setFieldValue } =
    useVeeForm({
        validationSchema: employeeSchema,
        initialValues: {
            ...props.employee,
            password: "",
            is_active: props.employee.is_active.toString(),
        },
    });

const onEmployeeSubmit = handleEmployeeSubmit(async (values) => {
    router.patch(route("admin.employees.update", props.employee.id), values, {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            toast({
                title: "Employee updated",
                description: "Employee details have been updated successfully.",
                variant: "success",
            });
            isEditFormOpen.value = false;
        },
        onError: () => {
            toast({
                title: "Failed to update employee",
                description:
                    "An error occurred while updating employee details.",
                variant: "destructive",
            });
        },
    });
    resetEmployeeForm();
});
</script>

<template>
    <Sheet v-model:open="isEditFormOpen">
        <SheetTrigger as-child>
            <Button
                class="px-2 py-1.5 text-sm outline-none transition-colors focus:bg-accent focus:text-accent-foreground cursor-default w-full justify-start font-normal"
                variant="ghost"
                size="sm"
            >
                Edit
            </Button>
        </SheetTrigger>
        <SheetContent>
            <SheetHeader>
                <SheetTitle>Edit Employee</SheetTitle>
                <SheetDescription>
                    Update the details of the employee.
                </SheetDescription>
            </SheetHeader>
            <div class="grid gap-4 py-4">
                <form
                    class="w-2/3 space-y-6"
                    @submit.prevent="onEmployeeSubmit"
                >
                    <!-- First Name -->
                    <FormField v-slot="{ componentField }" name="first_name">
                        <FormItem>
                            <FormLabel>First Name</FormLabel>
                            <FormControl>
                                <Input
                                    v-bind="componentField"
                                    placeholder="First Name"
                                />
                            </FormControl>
                            <FormMessage />
                        </FormItem>
                    </FormField>

                    <!-- Last Name -->
                    <FormField v-slot="{ componentField }" name="last_name">
                        <FormItem>
                            <FormLabel>Last Name</FormLabel>
                            <FormControl>
                                <Input
                                    v-bind="componentField"
                                    placeholder="Last Name"
                                />
                            </FormControl>
                            <FormMessage />
                        </FormItem>
                    </FormField>

                    <!-- Email -->
                    <FormField v-slot="{ componentField }" name="email">
                        <FormItem>
                            <FormLabel>Email</FormLabel>
                            <FormControl>
                                <Input
                                    v-bind="componentField"
                                    placeholder="Email"
                                />
                            </FormControl>
                            <FormMessage />
                        </FormItem>
                    </FormField>

                    <!-- Password -->
                    <FormField v-slot="{ componentField }" name="password">
                        <FormItem>
                            <FormLabel>Password</FormLabel>
                            <FormControl>
                                <div class="flex items-center gap-x-2">
                                    <Input
                                        v-bind="componentField"
                                        type="password"
                                        placeholder="Password"
                                        disabled
                                    />
                                    <Button
                                        size="icon"
                                        variant="outline"
                                        type="button"
                                        @click="() => setFieldValue('password', 'password')"
                                    >
                                        <RotateCw class="h-4 w-4" />
                                    </Button>
                                </div>
                            </FormControl>
                            <FormMessage />
                        </FormItem>
                    </FormField>

                    <!-- Active Status -->
                    <FormField v-slot="{ componentField }" name="is_active">
                        <FormItem>
                            <FormLabel>Active Status</FormLabel>
                            <FormControl>
                                <Select v-bind="componentField">
                                    <SelectTrigger>
                                        <SelectValue
                                            placeholder="Select status"
                                        />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectGroup>
                                            <SelectItem value="1"
                                                >Active</SelectItem
                                            >
                                            <SelectItem value="0"
                                                >Inactive</SelectItem
                                            >
                                        </SelectGroup>
                                    </SelectContent>
                                </Select>
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
