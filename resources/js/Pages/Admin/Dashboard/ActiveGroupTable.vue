<template>
    <Card>
        <CardHeader>
            <CardTitle>Active Groups</CardTitle>
            <CardDescription
                >Groups with the most activity this month based on the number of members,
                sessions, and active messages</CardDescription
            >
        </CardHeader>
        <CardContent>
            <Table>
                <TableHeader>
                    <TableRow>
                        <TableHead>Name</TableHead>
                        <TableHead>Members</TableHead>
                        <TableHead>Sessions</TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow v-for="group in activeGroups" :key="group.name">
                        <TableCell>{{ group.name }}</TableCell>
                        <TableCell>{{ group.users_count }}</TableCell>
                        <TableCell>{{ group.sessions_count }}</TableCell>
                    </TableRow>
                </TableBody>
            </Table>
        </CardContent>
    </Card>
</template>

<script setup>
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from "@/Components/ui/table";
import {
    Card,
    CardContent,
    CardDescription,
    CardFooter,
    CardHeader,
    CardTitle,
} from "@/Components/ui/card";
import { usePage } from "@inertiajs/vue3";

const activeGroups = usePage().props.activeGroups;
console.log(activeGroups);

// const activeGroups = [
//     { name: "Sports Enthusiasts", members: 120, sessions: 25 },
//     { name: "Book Club", members: 45, sessions: 12 },
//     { name: "Tech Innovators", members: 80, sessions: 20 },
//     { name: "Fitness Fanatics", members: 95, sessions: 30 },
//     { name: "Art & Culture", members: 60, sessions: 15 },
// ];
</script>
