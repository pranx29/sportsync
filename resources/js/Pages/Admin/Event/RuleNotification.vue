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
            <FormField v-slot="{ componentField }" name="notifyCreation">
                <FormItem>
                    <div class="flex items-center space-x-2">
                        <FormControl>
                            <Checkbox
                                v-bind="componentField"
                                id="notify-creation"
                            />
                        </FormControl>
                        <FormLabel for="notify-creation"
                            >Notify Participants on Event Creation</FormLabel
                        >
                    </div>
                    <FormMessage />
                </FormItem>
            </FormField>
            <FormField v-slot="{ componentField }" name="sendReminder">
                <FormItem>
                    <div class="flex items-center space-x-2">
                        <FormControl>
                            <Checkbox
                                v-bind="componentField"
                                id="send-reminder"
                            />
                        </FormControl>
                        <FormLabel for="send-reminder"
                            >Send Reminder (1 day before)</FormLabel
                        >
                    </div>
                    <FormMessage />
                </FormItem>
            </FormField>
            <FormField v-slot="{ componentField }" name="notifyAssignments">
                <FormItem>
                    <div class="flex items-center space-x-2">
                        <FormControl>
                            <Checkbox
                                v-bind="componentField"
                                id="notify-assignments"
                            />
                        </FormControl>
                        <FormLabel for="notify-assignments"
                            >Notify Participants of Team Assignments</FormLabel
                        >
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
const { value: notifyCreation } = useField("notifyCreation");
const { value: sendReminder } = useField("sendReminder");
const { value: notifyAssignments } = useField("notifyAssignments");

notifyCreation.value = false;
sendReminder.value = false;
notifyAssignments.value = false;

const handleFileUpload = (e) => {
    const file = e.target.files?.[0];
    if (file) {
        rulesDocument.value = file;
    }
};
</script>
