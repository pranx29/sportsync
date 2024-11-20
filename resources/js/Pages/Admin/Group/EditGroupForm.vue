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
import { ref, defineProps } from "vue";
import { router } from "@inertiajs/vue3";
import { toast } from "@/Components/ui/toast";

const props = defineProps({
    group: {
        type: Object,
        required: true,
    },
});
const isAddFormOpen = ref(false);

const addGroupSchema = toTypedSchema(
    z.object({
        group_name: z.string().min(1, "Group name is required."),
        description: z.string().optional(),
        leader: z.string().email("Invalid email address."),
        image: z
            .union([
                z.string(),
                z
                    .instanceof(File)
                    .refine(
                        (file) =>
                            typeof file === "string" ||
                            file.size < 2 * 1024 * 1024,
                        {
                            message:
                                "Image must less than 2MB",
                        }
                    ),
            ])
            .optional(),
        is_active: z.union([z.literal("1"), z.literal("0")]).default("1"),
    })
);

const {
    handleSubmit: handleGroupSubmit,
    resetForm: resetGroupForm,
    setFieldValue,
    setFieldError,
} = useVeeForm({
    validationSchema: addGroupSchema,
    initialValues: {
        group_name: props.group.name,
        description: props.group.description,
        leader: props.group.leader.email,
        image: props.group.image,
        is_active: props.group.is_active.toString(),
    },
});

const onGroupSubmit = handleGroupSubmit(async (values) => {
    if (values.image === props.group.image) {
        delete values.image;
    }
    router.post(
        route("admin.groups.update", {
            id: props.group.id,
        }),
        values,
        {
            preserveScroll: true,
            preserveState: true,
            onSuccess: () => {
                toast({
                    title: "Group updated",
                    description:
                        "Group details have been updated successfully.",
                    variant: "success",
                });
                isAddFormOpen.value = false;
            },
            onError: (errors) => {
                setFieldError("leader", errors.leader);
                setFieldError("group_name", errors.group_name);
                setFieldError("description", errors.description);
                setFieldError("is_active", errors.is_active);
                setFieldError("image", errors.image);
                console.log(errors);
                toast({
                    title: "Failed to update group",
                    description: "Please check the form for errors.",
                    variant: "destructive",
                });
            },
        }
    );
    resetGroupForm();
});

const groupImageUrl = ref(props.group.image);

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
            <Button
                class="px-2 py-1.5 text-sm outline-none transition-colors focus:bg-accent focus:text-accent-foreground cursor-default w-full justify-start font-normal"
                variant="ghost"
                size="sm"
            >
                Edit
            </Button>
        </SheetTrigger>
        <ScrollArea></ScrollArea>
        <SheetContent>
            <SheetHeader>
                <SheetTitle>Edit Group</SheetTitle>
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
                                        accept="image/*"
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
