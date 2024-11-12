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
import { useForm as useVeeForm } from "vee-validate";
import { z } from "zod";
import { router } from "@inertiajs/vue3";
import { toast, Toaster } from "@/Components/ui/toast";
import EditRole from "./EditRole.vue";

const addRoleSchema = toTypedSchema(
    z.object({
        title: z.string().nonempty("Role name is required."),
    })
);

const { handleSubmit: handleRoleSubmit, resetForm: resetRoleForm } = useVeeForm(
    {
        validationSchema: addRoleSchema,
    }
);

const addRole = handleRoleSubmit(async (values) => {
    console.log(values);
    router.post(route("admin.role.create"), values, {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            toast({
                title: "Role added",
                description: "Role has been added successfully.",
                variant: "success",
            });
        },
        onError: () => {
            toast({
                title: "Failed to add role",
                description: "An error occurred while adding the role.",
                variant: "destructive",
            });
        },
    });
    resetRoleForm();
});
</script>

<template>
    <AdminSettingLayout>
        <div class="grid gap-6">
            <Card>
                <CardHeader>
                    <CardTitle>Roles</CardTitle>
                    <CardDescription> List of existing roles. </CardDescription>
                </CardHeader>
                <CardContent>
                    <Table class="no-hover">
                        <TableHeader>
                            <TableRow class="hover:bg-transparent">
                                <TableHead>Role Title</TableHead>
                                <TableHead>Employees</TableHead>
                                <TableHead class="text-right"></TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow
                                v-for="role in $page.props.roles"
                                :key="role.id"
                                class="hover:bg-transparent"
                            >
                                <TableCell>{{ role.title }}</TableCell>
                                <TableCell>{{
                                    role.profiles_count || "-"
                                }}</TableCell>
                                <TableCell class="text-right">
                                    <EditRole :role="role" />
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </CardContent>
            </Card>
            <Card>
                <CardHeader>
                    <CardTitle>Add Role</CardTitle>
                    <CardDescription>
                        Add a new role to the list.
                    </CardDescription>
                </CardHeader>
                <CardContent>
                    <form @submit.prevent="addRole" class="space-y-2">
                        <FormField v-slot="{ componentField }" name="title">
                            <FormItem>
                                <FormLabel>Role Title</FormLabel>
                                <div class="flex items-center gap-1.5">
                                    <FormControl>
                                        <Input
                                            type="text"
                                            placeholder="Role title"
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
        <Toaster />
    </AdminSettingLayout>
</template>
