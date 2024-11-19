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
import { RotateCw } from "lucide-vue-next";

const isEditFormOpen = ref(false);
const props = defineProps({
    sport: {
        type: Object,
        required: true,
    },
});

const sportSchema = toTypedSchema(
    z.object({
        name: z.string().nonempty("Sport name is required."),
    })
);

const { handleSubmit: handleSportSubmit, resetForm: resetSportForm } =
    useVeeForm({
        validationSchema: sportSchema,
        initialValues: {
            ...props.sport,
        },
    });

const onSportSubmit = handleSportSubmit(async (values) => {
    router.patch(
        route("admin.sport.update", props.sport.id),
        values,
        {
            preserveScroll: true,
            preserveState: true,
            onSuccess: () => {
                toast({
                    title: "Sport updated",
                    description:
                        "Sport details have been updated successfully.",
                    variant: "success",
                });
                isEditFormOpen.value = false;
            },
            onError: () => {
                toast({
                    title: "Failed to update sport",
                    description:
                        "An error occurred while updating sport details.",
                    variant: "destructive",
                });
            },
        }
    );
    resetSportForm();
});
</script>

<template>
    <Sheet v-model:open="isEditFormOpen">
        <SheetTrigger as-child>
            <Button variant="outline" size="sm">Edit</Button>
        </SheetTrigger>
        <SheetContent>
            <SheetHeader>
                <SheetTitle>Edit Sport</SheetTitle>
                <SheetDescription>
                    Update the details of the sport.
                </SheetDescription>
            </SheetHeader>
            <div class="grid gap-4 py-4">
                <form
                    class="w-2/3 space-y-6"
                    @submit.prevent="onSportSubmit"
                >
                    <!-- Sport Name -->
                    <FormField v-slot="{ componentField }" name="name">
                        <FormItem>
                            <FormLabel>Sport Name</FormLabel>
                            <FormControl>
                                <Input
                                    v-bind="componentField"
                                    placeholder="Sport Name"
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
