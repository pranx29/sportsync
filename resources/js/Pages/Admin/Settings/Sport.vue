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
import {
    Select,
    SelectContent,
    SelectGroup,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from "@/Components/ui/select";
import { Button } from "@/Components/ui/button";
import { toTypedSchema } from "@vee-validate/zod";
import { Field, useForm as useVeeForm } from "vee-validate";
import { z } from "zod";
import { router } from "@inertiajs/vue3";
import { toast, Toaster } from "@/Components/ui/toast";
import EditSport from "./EditSport.vue";

const addSportSchema = toTypedSchema(
    z.object({
        name: z.string().nonempty("Sport name is required."),
        category: z.enum(["team", "individual"]),
        focus: z.enum(["physical", "intellectual"]),
    })
);

const { handleSubmit: handleSportSubmit, resetForm: resetSportForm } =
    useVeeForm({
        validationSchema: addSportSchema,
    });

const addSport = handleSportSubmit(async (values) => {
    console.log(values);
    router.post(route("admin.sport.create"), values, {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            toast({
                title: "Sport added",
                description: "Sport has been added successfully.",
                variant: "success",
            });
        },
        onError: () => {
            toast({
                title: "Failed to add sport",
                description: "An error occurred while adding the sport.",
                variant: "destructive",
            });
        },
    });
    resetSportForm();
});
</script>

<template>
    <Head title="Settings | Sport" />
    <AdminSettingLayout>
        <div class="grid gap-6">
            <Card>
                <CardHeader>
                    <CardTitle>Sports</CardTitle>
                    <CardDescription>
                        List of existing sports.
                    </CardDescription>
                </CardHeader>
                <CardContent>
                    <Table class="no-hover">
                        <TableHeader>
                            <TableRow class="hover:bg-transparent">
                                <TableHead>Sport Name</TableHead>
                                <TableHead>Interested</TableHead>
                                <TableHead class="text-right"></TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow
                                v-for="sport in $page.props.sports"
                                :key="sport.id"
                                class="hover:bg-transparent"
                            >
                                <TableCell>{{ sport.name }}</TableCell>
                                <TableCell>{{
                                    sport.user_count || "-"
                                }}</TableCell>
                                <TableCell class="text-right">
                                    <EditSport :sport="sport" />
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </CardContent>
            </Card>
            <Card>
                <CardHeader>
                    <CardTitle>Add Sport</CardTitle>
                    <CardDescription>
                        Add a new sport to the list.
                    </CardDescription>
                </CardHeader>
                <CardContent>
                    <form @submit.prevent="addSport" class="space-y-2">
                        <FormField v-slot="{ componentField }" name="name">
                            <FormItem>
                                <FormLabel>Sport Name</FormLabel>
                                <FormControl>
                                    <Input
                                        type="text"
                                        placeholder="Sport Name"
                                        v-bind="componentField"
                                    />
                                </FormControl>
                                <FormMessage />
                            </FormItem>
                        </FormField>

                        <!-- Category type -->
                        <FormField v-slot="{ componentField }" name="category">
                            <FormItem>
                                <FormLabel>Category</FormLabel>
                                <Select v-bind="componentField">
                                    <FormControl>
                                        <SelectTrigger>
                                            <SelectValue
                                                placeholder="Select a category"
                                            />
                                        </SelectTrigger>
                                    </FormControl>
                                    <SelectContent>
                                        <SelectGroup>
                                            <SelectItem
                                                v-for="category in $page.props
                                                    .categories"
                                                :key="category"
                                                :value="category"
                                            >
                                                {{
                                                    category
                                                        .charAt(0)
                                                        .toUpperCase() +
                                                    category.slice(1)
                                                }}
                                            </SelectItem>
                                        </SelectGroup>
                                    </SelectContent>
                                </Select>
                                <FormMessage />
                            </FormItem>
                        </FormField>

                        <!-- Category type -->
                        <FormField v-slot="{ componentField }" name="focus">
                            <FormItem>
                                <FormLabel>Focus</FormLabel>
                                <Select v-bind="componentField">
                                    <FormControl>
                                        <SelectTrigger>
                                            <SelectValue
                                                placeholder="Select a focus"
                                            />
                                        </SelectTrigger>
                                    </FormControl>
                                    <SelectContent>
                                        <SelectGroup>
                                            <SelectItem
                                                v-for="focus in $page.props
                                                    .focus"
                                                :key="focus"
                                                :value="focus"
                                            >
                                                {{
                                                    focus
                                                        .charAt(0)
                                                        .toUpperCase() +
                                                    focus.slice(1)
                                                }}
                                            </SelectItem>
                                        </SelectGroup>
                                    </SelectContent>
                                </Select>
                                <FormMessage />
                            </FormItem>
                        </FormField>

                        <Button type="submit">Add</Button>
                    </form>
                </CardContent>
            </Card>
        </div>
        <Toaster />
    </AdminSettingLayout>
</template>
