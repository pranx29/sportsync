<template>
    <Dialog :open="true" @update:open="onClose">
        <DialogContent class="sm:max-w-[425px]">
            <DialogHeader>
                <DialogTitle>Register for: {{ event.name }}</DialogTitle>
                <DialogDescription>
                    {{
                        event.registration_type === "team"
                            ? "Enter your team name or select an existing team to join this event."
                            : "Confirm your registration for this event."
                    }}
                </DialogDescription>
            </DialogHeader>
            <form @submit.prevent="handleSubmit">
                <div
                    v-if="event.registration_type === 'team'"
                    class="grid gap-4 py-4"
                >
                    <div class="grid grid-cols-4 items-center gap-4">
                        <Label for="existing-team" class="text-right"
                            >Select Team</Label
                        >
                        <Select v-model="selectedTeam">
                            <SelectTrigger
                                id="existing-team"
                                class="col-span-3"
                            >
                                <SelectValue placeholder="Select a team" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem
                                    v-for="team in event.teams"
                                    :key="team.id"
                                    :value="team.id"
                                >
                                    {{ team.team_name }}
                                </SelectItem>
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
                    <Button type="submit">Confirm</Button>
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
import { toast } from "@/Components/ui/toast";
import { router } from "@inertiajs/vue3";

const props = defineProps({
    onClose: Function,
    event: Object,
});

const selectedTeam = ref("");

const handleSubmit = () => {
    console.log("Registering for event:", props.event.id);

    if (props.event.registration_type === "individual") {
        router.post(
            route("employee.events.register", props.event.id),
            {},
            {
                preserveScroll: true,
                onSuccess: () => {
                    toast({
                        title: "Registration Successful",
                        description:
                            "You have successfully registered for the event.",
                        variant: "success",
                    });
                },
                onError: (errors) => {
                    toast({
                        title: "Registration Failed",
                        description:
                            "There was an error registering for the event. Please try again.",
                        variant: "error",
                    });
                },
            }
        );

        props.onClose();
    } else {
        if (!selectedTeam.value) {
            toast({
                title: "Registration Failed",
                description:
                    "Please enter a team name or select an existing team to join the event.",
                variant: "error",
            });
            return;
        }

        router.post(
            route("employee.events.register", props.event.id),
            {
                selectedTeam: selectedTeam.value,
            },
            {
                preserveScroll: true,
                onSuccess: () => {
                    toast({
                        title: "Registration Successful",
                        description:
                            "You have successfully registered for the event.",
                        variant: "success",
                    });
                },
                onError: (errors) => {
                    toast({
                        title: "Registration Failed",
                        description:
                            "There was an error registering for the event. Please try again.",
                        variant: "error",
                    });
                },
            }
        );
    }
    props.onClose();
};
</script>
