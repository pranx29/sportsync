<script setup>
import { ref } from "vue";
import { Input } from "@/Components/ui/input";
import { Textarea } from "@/Components/ui/textarea";
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from "@/Components/ui/select";
import {
    Form,
    FormField,
    FormItem,
    FormLabel,
    FormControl,
    FormMessage,
} from "@/Components/ui/form";
import { useField } from "vee-validate";
import EventPreview from "./EventPreview.vue";

const { value: sportType } = useField("sportType");
const { value: eventName } = useField("eventName");
const { value: eventDescription } = useField("eventDescription");
const { value: eventImage } = useField("eventImage");

const handleImageUpload = (e) => {
    const file = e.target.files?.[0];
    if (file) {
        eventImage.value = file;
    }
};
</script>

<template>
    <div class="grid grid-cols-2 gap-8">
        <div class="space-y-4">
            <!-- Event Name -->
            <FormField v-slot="{ componentField }" name="eventName">
                <FormItem>
                    <FormLabel>Event Name</FormLabel>
                    <FormControl>
                        <Input
                            v-bind="componentField"
                            @input="eventName = $event.target.value"
                        />
                    </FormControl>
                    <FormMessage />
                </FormItem>
            </FormField>

            <!-- Event Description -->
            <FormField v-slot="{ componentField }" name="eventDescription">
                <FormItem>
                    <FormLabel>Event Description</FormLabel>
                    <FormControl>
                        <Textarea
                            rows="5"
                            v-bind="componentField"
                            @input="eventDescription = $event.target.value"
                        />
                    </FormControl>
                    <FormMessage />
                </FormItem>
            </FormField>

            <!-- Sport Type -->
            <FormField v-slot="{ componentField }" name="sportType">
                <FormItem>
                    <FormLabel>Sport Type</FormLabel>
                    <FormControl>
                        <Select v-bind="componentField">
                            <SelectTrigger>
                                <SelectValue placeholder="Select a sport" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem
                                    v-for="sport in $page.props.sports"
                                    :key="sport.id"
                                    :value="sport.name"
                                    >{{ sport.name }}</SelectItem
                                >
                            </SelectContent>
                        </Select>
                    </FormControl>
                    <FormMessage />
                </FormItem>
            </FormField>

            <!-- Event Image -->
            <FormField name="eventImage">
                <FormItem>
                    <FormLabel>Event Poster/Image</FormLabel>
                    <FormControl>
                        <Input
                            type="file"
                            accept="image/*"
                            @change="
                                (event) => {
                                    handleImageUpload(event);
                                }
                            "
                        />
                    </FormControl>
                    <FormMessage />
                </FormItem>
            </FormField>
        </div>

        <!-- Preview -->
        <EventPreview
            :eventName="eventName"
            :description="eventDescription"
            :eventImage="eventImage"
            :sportType="sportType"
        />
    </div>
</template>
