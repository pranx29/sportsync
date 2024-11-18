<template>
    <div class="container mx-auto p-4 max-w-2xl">
        <h1 class="text-2xl font-bold mb-6">Sports Selection</h1>
        <form @submit.prevent="handleSubmit">
            <div
                v-for="(sport, index) in sports"
                :key="index"
                class="mb-6 p-4 bg-card rounded-lg shadow"
            >
                <div class="grid gap-4 mb-4">
                    <div class="space-y-2">
                        <Label :for="`sport-${index}`">Sport</Label>
                        <Select :id="`sport-${index}`" v-model="sport.name">
                            <option value="" disabled>Select a sport</option>
                            <option v-for="s in sportsList" :key="s" :value="s">
                                {{ s }}
                            </option>
                        </Select>
                    </div>
                    <div class="space-y-2">
                        <Label :for="`interest-${index}`">Interest Level</Label>
                        <Select
                            :id="`interest-${index}`"
                            v-model="sport.interestLevel"
                        >
                            <option value="" disabled>
                                Select interest level
                            </option>
                            <option
                                v-for="level in interestLevels"
                                :key="level"
                                :value="level"
                            >
                                {{ level }}
                            </option>
                        </Select>
                    </div>
                    <div class="space-y-2">
                        <Label :for="`skill-${index}`">Skill Level</Label>
                        <Select
                            :id="`skill-${index}`"
                            v-model="sport.skillLevel"
                        >
                            <option value="" disabled>
                                Select skill level
                            </option>
                            <option
                                v-for="level in skillLevels"
                                :key="level"
                                :value="level"
                            >
                                {{ level }}
                            </option>
                        </Select>
                    </div>
                </div>
                <Button
                    type="button"
                    variant="destructive"
                    @click="removeSport(index)"
                    class="w-full"
                >
                    Remove Sport
                </Button>
            </div>
            <div class="space-y-4">
                <Button type="button" @click="addSport" class="w-full">
                    Add Another Sport
                </Button>
                <Button type="submit" class="w-full">Submit</Button>
            </div>
        </form>
    </div>
</template>

<script setup>
import { ref } from "vue";
import { Button } from "@/Components/ui/button";
import { Select } from "@/Components/ui/select";
import { Label } from "@/Components/ui/label";

const sportsList = [
    "Basketball",
    "Football",
    "Tennis",
    "Swimming",
    "Volleyball",
    "Baseball",
    "Soccer",
    "Golf",
    "Running",
    "Cycling",
];

const interestLevels = ["Low", "Medium", "High"];
const skillLevels = ["Beginner", "Intermediate", "Advanced", "Expert"];

const sports = ref([{ name: "", interestLevel: "", skillLevel: "" }]);

const addSport = () => {
    sports.value.push({ name: "", interestLevel: "", skillLevel: "" });
};

const removeSport = (index) => {
    sports.value.splice(index, 1);
};

const handleSubmit = () => {
    console.log("Submitted sports:", sports.value);
    // Here you would typically send the data to a server or perform other actions
};
</script>
