<script setup>
import { Badge } from "@/Components/ui/badge";

import { Card, CardContent } from "@/Components/ui/card";

import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from "@/Components/ui/table";

import { ref, defineEmits } from "vue";

defineProps({
    intialGroup: {
        type: Object,
        required: false,
    },
    groups: {
        type: Array,
        required: true,
    },
});

const selectedGroup = ref(null);
const emit = defineEmits(["group-selected"]);

const handleRowClick = (group) => {
    selectedGroup.value = group;
    console.log(selectedGroup.value);
    emit("group-selected", group);
};
</script>

<template>
    <Card>
        <CardContent>
            <Table>
                <TableHeader>
                    <TableRow>
                        <TableHead>Image</TableHead>
                        <TableHead>Name</TableHead>
                        <TableHead>Sport</TableHead>
                        <TableHead>Members</TableHead>
                        <TableHead>Status</TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow
                        v-for="group in groups"
                        :key="group.id"
                        @click="() => handleRowClick(group)"
                        :class="{ 'bg-accent': selectedGroup === group }"
                    >
                        <TableCell>
                            <img
                                :src="group.image"
                                :alt="group.name"
                                class="w-10 h-10"
                            />
                        </TableCell>
                        <TableCell>{{ group.name }}</TableCell>
                        <TableCell>{{ group.sport.name }}</TableCell>
                        <TableCell>{{ group.users_count }}</TableCell>
                        <TableCell>
                            <Badge class="text-xs" variant="secondary">
                                {{ group.is_active ? "Active" : "Inactive" }}
                            </Badge>
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>
        </CardContent>
    </Card>
</template>
