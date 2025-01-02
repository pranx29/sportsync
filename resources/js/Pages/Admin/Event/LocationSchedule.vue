<script setup>
import { ref, watch } from "vue";
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from "@/Components/ui/select";
import { Input } from "@/Components/ui/input";
import { Label } from "@/Components/ui/label";
import { RadioGroup, RadioGroupItem } from "@/Components/ui/radio-group";
import { Switch } from "@/Components/ui/switch";
import {
    FormField,
    FormItem,
    FormLabel,
    FormControl,
    FormMessage,
} from "@/Components/ui/form";
import EventPreview from "./EventPreview.vue";

const props = defineProps({
    eventDetails: Object,
});

const customLocation = ref(false);
const customLocationName = ref("");
const customLocationLink = ref("");
const venue = ref("");

const resetCustomLocation = (value) => {
    if (!value) {
        customLocationName.value = "";
        customLocationLink.value = "";
    } else {
        venue.value = ""; // Reset venue when custom location is enabled
    }
};

watch(customLocation, (newValue) => {
    if (newValue) {
        venue.value = ""; // Clear venue if custom location is enabled
    } else {
        customLocationName.value = "";
        customLocationLink.value = "";
    }
});
</script>


<template>
    <div class="grid grid-cols-2 gap-8">
        <div class="space-y-6">
            <!-- Venue -->
            <FormField v-if="!customLocation" v-slot="{ componentField }" name="venue">
                <FormItem>
                    <FormLabel>Venue/Location</FormLabel>
                    <FormControl>
                        <Select v-bind="componentField" v-model="venue">
                            <SelectTrigger id="venue">
                                <SelectValue placeholder="Select a venue" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem value="Main stadium">Main Stadium</SelectItem>
                                <SelectItem value="Central park">Central Park</SelectItem>
                                <SelectItem value="Community gym">Community Gym</SelectItem>
                            </SelectContent>
                        </Select>
                    </FormControl>
                    <FormMessage />
                </FormItem>
            </FormField>

            <!-- Custom Location -->
            <FormField v-slot="{ value, handleChange }" name="customLocation">
                <FormItem>
                    <FormControl>
                        <div class="flex items-center space-x-2">
                            <Switch
                                :checked="value"
                                @update:checked="(val) => {
                                    handleChange(val);
                                    resetCustomLocation(val);
                                    customLocation = val;
                                }"
                            />
                            <Label for="custom-location">Custom Location</Label>
                        </div>
                    </FormControl>
                    <FormMessage />
                </FormItem>
            </FormField>

            <div v-if="customLocation" class="space-y-4">
                <FormField v-slot="{ componentField }" name="customLocationName">
                    <FormItem>
                        <FormLabel>Location Name</FormLabel>
                        <FormControl>
                            <Input
                                v-bind="componentField"
                                v-model="customLocationName"
                                placeholder="Enter custom location name"
                            />
                        </FormControl>
                        <FormMessage />
                    </FormItem>
                </FormField>

                <FormField v-slot="{ componentField }" name="customLocationLink">
                    <FormItem>
                        <FormLabel>Location Link</FormLabel>
                        <FormControl>
                            <Input
                                v-bind="componentField"
                                v-model="customLocationLink"
                                placeholder="Enter location link (e.g., Google Maps URL)"
                            />
                        </FormControl>
                        <FormMessage />
                    </FormItem>
                </FormField>
            </div>

            <FormField v-slot="{ componentField }" name="locationType">
                <FormItem>
                    <FormLabel>Location Type</FormLabel>
                    <FormControl>
                        <RadioGroup
                            v-bind="componentField"
                            class="flex space-x-4 mt-2"
                        >
                            <div class="flex items-center space-x-2">
                                <RadioGroupItem value="indoor" id="indoor" />
                                <Label for="indoor">Indoor</Label>
                            </div>
                            <div class="flex items-center space-x-2">
                                <RadioGroupItem value="outdoor" id="outdoor" />
                                <Label for="outdoor">Outdoor</Label>
                            </div>
                        </RadioGroup>
                    </FormControl>
                    <FormMessage />
                </FormItem>
            </FormField>

            <FormField v-slot="{ componentField }" name="eventDate">
                <FormItem>
                    <FormLabel for="event-date">Event Date</FormLabel>
                    <FormControl>
                        <Input
                            v-bind="componentField"
                            id="event-date"
                            type="date"
                            class="w-fit"
                        />
                    </FormControl>
                    <FormMessage />
                </FormItem>
            </FormField>

            <div class="grid grid-cols-2 gap-4 w-fit">
                <FormField v-slot="{ componentField }" name="startTime">
                    <FormItem>
                        <FormLabel for="start-time">Start Time</FormLabel>
                        <FormControl>
                            <Input
                                v-bind="componentField"
                                id="start-time"
                                type="time"
                            />
                        </FormControl>
                        <FormMessage />
                    </FormItem>
                </FormField>

                <FormField v-slot="{ componentField }" name="endTime">
                    <FormItem>
                        <FormLabel for="end-time">End Time</FormLabel>
                        <FormControl>
                            <Input
                                v-bind="componentField"
                                id="end-time"
                                type="time"
                            />
                        </FormControl>
                        <FormMessage />
                    </FormItem>
                </FormField>
            </div>
        </div>
        <!-- Preview -->
        <EventPreview
            :eventName="eventDetails.eventName"
            :description="eventDetails.eventDescription"
            :posterImage="eventDetails.eventImageUrl"
            :sportType="eventDetails.sportType"
            :registrationDeadline="eventDetails.registrationDeadline"
            :maxParticipants="eventDetails.maxParticipants"
            :registrationType="eventDetails.registrationType"
            :numberOfTeams="Number(props.eventDetails.numberOfTeams) || 0"
            :venue="venue"
            :locationType="eventDetails.locationType"
            :eventDate="eventDetails.eventDate"
            :startTime="eventDetails.startTime"
            :endTime="eventDetails.endTime"
            :customLocation="customLocation"
            :customLocationName="customLocationName"
        />
    </div>
</template>

