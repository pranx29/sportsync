<script setup>
import AdminSettingLayout from "@/Layouts/AdminSettingLayout.vue";
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from "@/Components/ui/card";
import { Input } from "@/Components/ui/input";
import { Sheet, SheetContent, SheetTrigger } from "@/Components/ui/sheet";
import { CircleUser, Menu, Package2, Search } from "lucide-vue-next";
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from "@/Components/ui/table";
import {
    Form,
    FormControl,
    FormDescription,
    FormField,
    FormItem,
    FormLabel,
    FormMessage,
} from "@/Components/ui/form";
import { Button } from "@/Components/ui/button";
import { toTypedSchema } from "@vee-validate/zod";
import { Field, useForm as useVeeForm } from "vee-validate";
import { z } from "zod";
import { router } from "@inertiajs/vue3";
import { toast } from "@/Components/ui/toast";
import EditDepartment from "./EditDepartment.vue";

const addDepartmentSchema = toTypedSchema(
    z.object({
        name: z.string().nonempty("Department name is required."),
    })
);

const { handleSubmit: handleDepartmentSubmit, resetForm: resetDepartmentForm } =
    useVeeForm({
        validationSchema: addDepartmentSchema,
    });

const addDepartment = handleDepartmentSubmit(async (values) => {
    console.log(values);
    router.post(route("admin.department.create"), values, {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            toast({
                title: "Department added",
                description: "Department has been added successfully.",
                variant: "success",
            });
        },
        onError: () => {
            toast({
                title: "Failed to add department",
                description: "An error occurred while adding the department.",
                variant: "destructive",
            });
        },
    });
    resetDepartmentForm();
});
</script>

<template>
    <Head title="Settings | Department" />
    <AdminSettingLayout>
        <div class="grid gap-6">
            <Card>
                <CardHeader>
                    <CardTitle>Departments</CardTitle>
                    <CardDescription>
                        List of existing departments.
                    </CardDescription>
                </CardHeader>
                <CardContent>
                    <Table class="no-hover">
                        <TableHeader>
                            <TableRow class="hover:bg-transparent">
                                <TableHead>Department Name</TableHead>
                                <TableHead>Employees</TableHead>
                                <TableHead class="text-right"></TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow
                                v-for="department in $page.props.departments"
                                :key="department.id"
                                class="hover:bg-transparent"
                            >
                                <TableCell>{{ department.name }}</TableCell>
                                <TableCell>{{
                                    department.profiles_count || "-"
                                }}</TableCell>
                                <TableCell class="text-right">
                                    <EditDepartment :department="department" />
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </CardContent>
            </Card>
            <Card>
                <CardHeader>
                    <CardTitle>Add Department</CardTitle>
                    <CardDescription>
                        Add a new department to the list.
                    </CardDescription>
                </CardHeader>
                <CardContent>
                    <form @submit.prevent="addDepartment" class="space-y-2">
                        <FormField v-slot="{ componentField }" name="name">
                            <FormItem>
                                <FormLabel>Department Name</FormLabel>
                                <div class="flex items-center gap-1.5">
                                    <FormControl>
                                        <Input
                                            type="text"
                                            placeholder="Department Name"
                                            v-bind="componentField"
                                        />
                                        <Button type="submit">Add</Button>
                                    </FormControl>
                                </div>
                                <FormMessage />
                            </FormItem>
                        </FormField>
                    </form>
                </CardContent>
            </Card>
        </div>
    </AdminSettingLayout>
</template>
