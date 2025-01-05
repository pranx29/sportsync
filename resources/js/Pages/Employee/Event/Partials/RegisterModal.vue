<template>
    <Dialog :open="isOpen" @open-change="onClose">
        <DialogContent class="sm:max-w-[425px]">
            <DialogHeader>
                <DialogTitle>Register for: {{ event.name }}</DialogTitle>
                <DialogDescription>
                    {{
                        event.registrationType === "team"
                            ? "Enter your team name or select an existing team to join this event."
                            : "Confirm your registration for this event."
                    }}
                </DialogDescription>
            </DialogHeader>
            <form @submit.prevent="handleSubmit">
                <div
                    v-if="event.registrationType === 'team'"
                    class="grid gap-4 py-4"
                >
                    <div class="grid grid-cols-4 items-center gap-4">
                        <Label for="team-name" class="text-right"
                            >Team Name</Label
                        >
                        <Input
                            id="team-name"
                            v-model="teamName"
                            class="col-span-3"
                        />
                    </div>
                    <div class="grid grid-cols-4 items-center gap-4">
                        <Label for="existing-team" class="text-right"
                            >Or Select Team</Label
                        >
                        <Select v-model="selectedTeam">
                            <SelectTrigger
                                id="existing-team"
                                class="col-span-3"
                            >
                                <SelectValue placeholder="Select a team" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem value="">Select a team</SelectItem>
                                <SelectItem value="team1">Team 1</SelectItem>
                                <SelectItem value="team2">Team 2</SelectItem>
                                <SelectItem value="team3">Team 3</SelectItem>
                            </SelectContent>
                        </Select>
                    </div>
                </div>
                <div v-else class="py-4">
                    <p>
                        Click confirm to register for this event as an
                        individual participant.
                    </p>
                </div>
                <DialogFooter>
                    <Button type="submit">Confirm Registration</Button>
                </DialogFooter>
            </form>
        </DialogContent>
    </Dialog>
</template>

<script setup>
import { ref } from "vue";
import { Button } from "@/Components/ui/button";
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from "@/Components/ui/dialog";
import { Input } from "@/Components/ui/input";
import { Label } from "@/Components/ui/label";
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from "@/Components/ui/select";

const props = defineProps({
    isOpen: Boolean,
    onClose: Function,
    event: Object,
});

const teamName = ref("");
const selectedTeam = ref("");

const handleSubmit = () => {
    console.log("Registering for event:", props.event.id, {
        teamName: teamName.value,
        selectedTeam: selectedTeam.value,
    });
    props.onClose();
};
</script>
