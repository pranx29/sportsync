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

const addEmployeeSchema = toTypedSchema(
    z.object({
        first_name: z.string().nonempty("First name is required."),
        last_name: z.string().optional(),
        email: z.string().email("Invalid email address."),
        password: z.string().min(6, "Password must be at least 6 characters."),
    })
);

const { handleSubmit: handleEmployeeSubmit, resetForm: resetEmployeeForm } =
    useVeeForm({
        validationSchema: addEmployeeSchema,
    });

const onEmployeeSubmit = handleEmployeeSubmit(async (values) => {
    router.post(route("admin.employees.create"), values, {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            toast({
                title: "Employee added",
                description: "Employee details have been added successfully.",
                variant: "success",
            });
            isAddFormOpen.value = false;
        },
        onError: () => {
            toast({
                title: "Failed to add employee",
                description: "An error occurred while adding employee details.",
                variant: "destructive",
            });
        },
    });
    resetEmployeeForm();
});
</script>

<template>
    <Sheet v-model:open="isAddFormOpen">
        <SheetTrigger as-child>
            <Button size="sm" class="h-7 gap-1">
                <CirclePlus class="h-3.5 w-3.5" />
                <span class="sr-only sm:not-sr-only sm:whitespace-nowrap">
                    Add Employee
                </span>
            </Button>
        </SheetTrigger>
        <SheetContent>
            <SheetHeader>
                <SheetTitle>Add Employee</SheetTitle>
                <SheetDescription>
                    Enter the details of the new employee.
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
                                <Input
                                    v-bind="componentField"
                                    type="password"
                                    placeholder="Password"
                                />
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
