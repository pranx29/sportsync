<script setup>
import { ref } from "vue";
import { RadioGroup, RadioGroupItem } from "@/Components/ui/radio-group";
import { Input } from "@/Components/ui/input";
import { Label } from "@/Components/ui/label";
import { Checkbox } from "@/Components/ui/checkbox";
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from "@/Components/ui/select";
import {
    Form,
    FormControl,
    FormField,
    FormItem,
    FormLabel,
    FormMessage,
} from "@/Components/ui/form";
import EventPreview from "./EventPreview.vue";
import { useField } from "vee-validate";

const props = defineProps({
    eventDetails: Object,
});

const showTeamOptions = ref(props.eventDetails.registrationType === "team");
</script>

<template>
    <div class="grid grid-cols-2 gap-8">
        <div class="space-y-6">
            <FormField v-slot="{ componentField }" name="registrationType">
                <FormItem>
                    <FormLabel>Registration Type</FormLabel>
                    <FormControl>
                        <RadioGroup class="flex gap-6" v-bind="componentField">
                            <FormItem
                                class="flex items-center space-y-0 gap-x-3"
                            >
                                <FormControl>
                                    <RadioGroupItem
                                        value="individual"
                                        id="individual"
                                        @click="showTeamOptions = false"
                                    />
                                </FormControl>
                                <FormLabel class="font-normal" for="individual">
                                    Individual
                                </FormLabel>
                            </FormItem>
                            <FormItem
                                class="flex items-center space-y-0 gap-x-3"
                            >
                                <FormControl>
                                    <RadioGroupItem
                                        value="team"
                                        id="team"
                                        @click="showTeamOptions = true"
                                    />
                                </FormControl>
                                <FormLabel class="font-normal" for="team">
                                    Team-Based
                                </FormLabel>
                            </FormItem>
                        </RadioGroup>
                    </FormControl>
                    <FormMessage />
                </FormItem>
            </FormField>
            <FormField v-slot="{ componentField }" name="maxParticipants">
                <FormItem>
                    <FormLabel for="max-participants"
                        >Max Participants</FormLabel
                    >
                    <FormControl>
                        <Input
                            id="max-participants"
                            type="number"
                            v-bind="componentField"
                            class="w-fit"
                        />
                    </FormControl>
                    <FormMessage />
                </FormItem>
            </FormField>
            <FormField v-slot="{ componentField }" name="registrationDeadline">
                <FormItem>
                    <FormLabel for="registration-deadline"
                        >Registration Deadline</FormLabel
                    >
                    <FormControl>
                        <Input
                            id="registration-deadline"
                            type="datetime-local"
                            v-bind="componentField"
                            class="w-fit"
                        />
                    </FormControl>
                    <FormMessage />
                </FormItem>
            </FormField>

            <template v-if="showTeamOptions">
                <FormField v-slot="{ componentField }" name="numberOfTeams">
                    <FormItem>
                        <FormLabel for="number-of-teams"
                            >Number of Teams</FormLabel
                        >
                        <FormControl>
                            <Select v-bind="componentField">
                                <SelectTrigger id="number-of-teams">
                                    <SelectValue
                                        placeholder="Select number of teams"
                                    />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem
                                        v-for="(num,index) in [
                                            '2',
                                            '4',
                                            '6',
                                            '8',
                                            '10',
                                        ]"
                                        :key="num"
                                        :value="num"
                                        :id="`btnNoTeams-${index + 1}`"
                                    >
                                        {{ num }}
                                    </SelectItem>
                                </SelectContent>
                            </Select>
                        </FormControl>
                        <FormMessage />
                    </FormItem>
                </FormField>
                <FormField v-slot="{ componentField }" name="teamAssignment">
                    <FormItem>
                        <FormLabel>Team Assignment Options</FormLabel>
                        <FormControl>
                            <RadioGroup
                                v-bind="componentField"
                                class="flex flex-col space-y-2 mt-2"
                            >
                                <div class="flex items-center space-x-2">
                                    <RadioGroupItem
                                        value="automatic"
                                        id="automatic"
                                    />
                                    <Label for="automatic"
                                        >Automatic Assignment
                                        (Skill-Based)</Label
                                    >
                                </div>
                                <div class="flex items-center space-x-2">
                                    <RadioGroupItem
                                        value="manual"
                                        id="manual"
                                    />
                                    <Label for="manual"
                                        >Manual Assignment</Label
                                    >
                                </div>
                            </RadioGroup>
                        </FormControl>
                        <FormMessage />
                    </FormItem>
                </FormField>
            </template>
        </div>

        <!-- Preview -->
        <EventPreview
            :eventName="eventDetails.eventName"
            :description="eventDetails.eventDescription"
            :eventImage="eventDetails.eventImage"
            :sportType="eventDetails.sportType"
            :registrationDeadline="eventDetails.registrationDeadline"
            :maxParticipants="eventDetails.maxParticipants"
            :registrationType="eventDetails.registrationType"
            :numberOfTeams="eventDetails.numberOfTeams"
        />
    </div>
</template>
