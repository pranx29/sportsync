<template>
    <Dialog :open="isOpen" @open-change="onClose">
        <DialogContent class="sm:max-w-[425px]">
            <DialogHeader>
                <DialogTitle>Register for: {{ event.eventName }}</DialogTitle>
                <DialogDescription>
                    {{
                        event.registrationType === "team"
                            ? "Enter your team name or select an existing team to join this event."
                            : "Confirm your registration for this event."
                    }}
                </DialogDescription>
            </DialogHeader>
            <form @submit.prevent="handleSubmit">
                <div v-if="event.registrationType === 'team'" class="grid gap-4 py-4">
                    <!-- Team Name Field -->
                    <div class="grid grid-cols-4 items-center gap-4">
                        <div class="col-span-3">
                            <Label for="team-name" class="text-right">Team Name</Label>
                            <Input
                                id="team-name"
                                v-model="teamName"
                                :disabled="isTeamSelected"
                                class="col-span-3"
                            />
                            <p v-if="errors.team_name" class="text-red-500 text-sm">
                                {{ errors.team_name }}
                            </p>
                            <!-- Clear Button for Team Name -->
                            <div class="col-span-1">
                                <Button
                                    v-if="teamName"
                                    type="button"
                                    variant="ghost"
                                    size="sm"
                                    class="mt-2 text-red-700"
                                    @click="clearTeamName"
                                >
                                    Clear Selection
                                </Button>
                            </div>
                        </div>
                    </div>
                    <div>
                        <Label>Or</Label>
                    </div>

                    <!-- Team Dropdown -->
                    <div class="grid grid-cols-4 items-center gap-4">
                        <div class="col-span-3">
                            <Select v-model="selectedTeam" :disabled="isTeamNameEntered">
                                <Label for="existing-team" class="text-right">Select Team</Label>
                                <SelectTrigger id="existing-team">
                                    <SelectValue
                                        :value="selectedTeam"
                                        placeholder="Select a team"
                                    />
                                </SelectTrigger>
                                <SelectContent>
                                    <template v-for="(team, index) in teams" :key="index">
                                        <SelectItem :value="team">{{ team }}</SelectItem>
                                    </template>
                                </SelectContent>
                            </Select>
                            <p v-if="errors.selected_team" class="text-red-500 text-sm">
                                {{ errors.selected_team }}
                            </p>
                            <!-- Clear Button for Select Team -->
                            <div class="col-span-1">
                                <Button
                                    v-if="selectedTeam"
                                    type="button"
                                    variant="ghost"
                                    size="sm"
                                    class="mt-2 text-red-700"
                                    @click="clearSelectedTeam"
                                >
                                    Clear Selection
                                </Button>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-else class="py-4">
                    <p>
                        Click confirm to register for this event as an individual participant.
                    </p>
                </div>
                <DialogFooter>
                    <Button :disabled="!isFormValid" type="submit">
                        Confirm Registration
                    </Button>
                </DialogFooter>
            </form>
        </DialogContent>
    </Dialog>
</template>

<script setup>
import { ref, computed } from "vue";
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
import { toast } from "@/Components/ui/toast";
import { router } from "@inertiajs/vue3";

const props = defineProps({
    isOpen: Boolean,
    onClose: Function,
    event: Object,
    teams: Array,
});

const teamName = ref(null || "");
const selectedTeam = ref(null || "");
const errors = ref({});

const emit = defineEmits(["close"]);

const isTeamSelected = computed(() => {
    return selectedTeam.value !== null && selectedTeam.value !== "";
});

const isTeamNameEntered = computed(() => {
    return teamName.value !== null && teamName.value !== "";
});

const isFormValid = computed(() => {
    return (isTeamNameEntered.value && !isTeamSelected.value) || (!isTeamNameEntered.value && isTeamSelected.value);
});

const clearSelectedTeam = () => {
    selectedTeam.value = null;
};

const clearTeamName = () => {
    teamName.value = null;
};

const handleSubmit = () => {
    const isTeamRegistration = props.event.registrationType === "team";

    const payload = isTeamRegistration
        ? {
              team_name: teamName.value || null,
              selected_team: selectedTeam.value || null,
          }
        : {};

    router.post(
        route("employee.events.join", props.event.id),
        payload,
        {
            onSuccess: () => {
                emit("close");
                errors.value = {}; // Clear errors on success
            },
            onError: (backendErrors) => {
                console.log(backendErrors); // Debug error structure
                errors.value = backendErrors; // Assign errors to reactive object
            },
        }
    );
};

</script>
