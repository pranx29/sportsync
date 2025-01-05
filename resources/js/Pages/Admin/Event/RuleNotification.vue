<template>
    <div class="space-y-6">
        <FormField name="rulesDocument">
            <FormItem>
                <FormLabel>Rules Document</FormLabel>
                <FormControl>
                    <Input
                        id="rules-document"
                        type="file"
                        accept=".pdf,.doc,.docx"
                        @change="
                            (event) => {
                                handleFileUpload(event);
                            }
                        "
                    />
                </FormControl>
                <FormMessage />
            </FormItem>
        </FormField>
        <FormField v-slot="{ componentField }" name="rulesDescription">
            <FormItem>
                <FormLabel>Short Rules Description</FormLabel>
                <FormControl>
                    <Textarea
                        v-bind="componentField"
                        id="rules-description"
                        rows="4"
                    />
                </FormControl>
                <FormMessage />
            </FormItem>
        </FormField>
        <div class="space-y-2">
            <FormField
                v-slot="{ value, handleChange }"
                name="notifyCreation"
                type="checkbox"
            >
                <FormItem>
                    <div class="flex items-center space-x-2">
                        <FormControl>
                            <Checkbox
                                :checked="value"
                                @update:checked="handleChange"
                                id="notify-creation"
                            />
                        </FormControl>
                        <FormLabel for="notify-creation">
                            Notify Participants on Event Creation
                        </FormLabel>
                    </div>
                    <FormMessage />
                </FormItem>
            </FormField>
            <!-- <FormField
                v-slot="{ value, handleChange }"
                name="sendReminder"
                type="checkbox"
            >
                <FormItem>
                    <div class="flex items-center space-x-2">
                        <FormControl>
                            <Checkbox
                                :checked="value"
                                @update:checked="handleChange"
                                id="send-reminder"
                            />
                        </FormControl>
                        <FormLabel for="send-reminder">
                            Send Reminder (1 day before)
                        </FormLabel>
                    </div>
                    <FormMessage />
                </FormItem>
            </FormField> -->
            <FormField
                v-slot="{ value, handleChange }"
                name="notifyAssignments"
                type="checkbox"
            >
                <FormItem>
                    <div class="flex items-center space-x-2">
                        <FormControl>
                            <Checkbox
                                :checked="value"
                                @update:checked="handleChange"
                                id="notify-assignments"
                            />
                        </FormControl>
                        <FormLabel for="notify-assignments">
                            Notify Participants of Team Assignments
                        </FormLabel>
                    </div>
                    <FormMessage />
                </FormItem>
            </FormField>
        </div>
    </div>
</template>

<script setup>
import { ref } from "vue";
import { Input } from "@/Components/ui/input";
import { Textarea } from "@/Components/ui/textarea";
import { Label } from "@/Components/ui/label";
import { Checkbox } from "@/Components/ui/checkbox";
import {
    FormField,
    FormItem,
    FormLabel,
    FormControl,
    FormMessage,
} from "@/Components/ui/form";
import { useField } from "vee-validate";

const { value: rulesDocument } = useField("rulesDocument");

const handleFileUpload = (e) => {
    const file = e.target.files?.[0];
    if (file) {
        rulesDocument.value = file;
    }
};
</script>
