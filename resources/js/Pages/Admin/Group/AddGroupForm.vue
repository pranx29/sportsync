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
import { ScrollArea } from "@/Components/ui/scroll-area";
import { toTypedSchema } from "@vee-validate/zod";
import { h } from "vue";
import * as z from "zod";
import { Pencil, CirclePlus } from "lucide-vue-next";
import { Avatar, AvatarFallback, AvatarImage } from "@/Components/ui/avatar";
import { useForm as useVeeForm } from "vee-validate";
import { ref } from "vue";
import { router } from "@inertiajs/vue3";
import { toast } from "@/Components/ui/toast";

const isAddFormOpen = ref(false);

const addGroupSchema = toTypedSchema(
    z.object({
        group_name: z.string().nonempty("Group name is required."),
        description: z.string().optional(),
        sport_id: z.string().nonempty("Sport is required."),
        leader: z.string().email("Invalid email address."),
        image: z.any(),
    })
);

const {
    handleSubmit: handleGroupSubmit,
    resetForm: resetGroupForm,
    setFieldValue,
    setFieldError,
} = useVeeForm({
    validationSchema: addGroupSchema,
});

const onGroupSubmit = handleGroupSubmit(async (values) => {
    router.post(route("admin.groups.create"), values, {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            toast({
                title: "Group added",
                description: "Group details have been added successfully.",
                variant: "success",
            });
            isAddFormOpen.value = false;
        },
        onError: (errors) => {
            setFieldError("leader", errors.leader);
            toast({
                title: "Failed to add group",
                description: 'Please check the form for errors.',
                variant: "destructive",
            });
        },
    });
    resetGroupForm();
});

const groupImageUrl = ref("");

const handleFileChange = (event) => {
    const file = event.target.files[0];
    if (file) {
        groupImageUrl.value = URL.createObjectURL(file);
    }
    setFieldValue("image", file);
    console.log(file);
};
</script>

<template>
    <Sheet v-model:open="isAddFormOpen">
        <SheetTrigger as-child>
            <Button size="sm" class="h-7 gap-1">
                <CirclePlus class="h-3.5 w-3.5" />
                <span class="sr-only sm:not-sr-only sm:whitespace-nowrap">
                    Add Group
                </span>
            </Button>
        </SheetTrigger>
        <ScrollArea></ScrollArea>
        <SheetContent>
            <SheetHeader>
                <SheetTitle>Add Group</SheetTitle>
                <SheetDescription>
                    Enter the details of the new group.
                </SheetDescription>
            </SheetHeader>
            <div class="grid gap-4 py-4">
                <form class="space-y-6" @submit.prevent="onGroupSubmit">
                    <!-- Group Name -->
                    <FormField v-slot="{ componentField }" name="group_name">
                        <FormItem>
                            <FormLabel>Group Name</FormLabel>
                            <FormControl>
                                <Input
                                    v-bind="componentField"
                                    placeholder="Group Name"
                                />
                            </FormControl>
                            <FormMessage />
                        </FormItem>
                    </FormField>

                    <!-- Description -->
                    <FormField v-slot="{ componentField }" name="description">
                        <FormItem>
                            <FormLabel>Description</FormLabel>
                            <FormControl>
                                <Input
                                    v-bind="componentField"
                                    placeholder="Description"
                                />
                            </FormControl>
                            <FormMessage />
                        </FormItem>
                    </FormField>

                    <!-- Sport type -->
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
                                            v-for="sport in $page.props
                                                .sportWithoutGroups"
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

                    <!-- Leader (Employee email) -->
                    <FormField v-slot="{ componentField }" name="leader">
                        <FormItem>
                            <FormLabel>Leader</FormLabel>
                            <FormControl>
                                <Input
                                    v-bind="componentField"
                                    placeholder="Leader's email"
                                    type="email"
                                />
                            </FormControl>
                            <FormMessage />
                        </FormItem>
                    </FormField>

                    <!-- Group Image -->
                    <FormField name="image">
                        <FormItem>
                            <FormLabel>Group Image</FormLabel>
                            <div
                                class="flex w-full max-w-sm items-center gap-4"
                            >
                                <FormControl>
                                    <Avatar class="w-24 h-24 rounded-md">
                                        <AvatarImage
                                            :src="groupImageUrl"
                                            alt="Group Image"
                                        />
                                        <AvatarFallback>GI</AvatarFallback>
                                    </Avatar>
                                    <Input
                                        id="image"
                                        type="file"
                                        @change="handleFileChange"
                                        accept=".jpeg, .png, .jpg, .svg"
                                    />
                                </FormControl>
                            </div>
                            <FormMessage />
                        </FormItem>
                    </FormField>

                    <Button type="submit"> Save </Button>
                </form>
            </div>
        </SheetContent>
    </Sheet>
</template>
