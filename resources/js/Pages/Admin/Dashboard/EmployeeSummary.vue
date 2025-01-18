<template>
    <Card>
        <CardHeader>
            <CardTitle>Employee Summary</CardTitle>
        </CardHeader>
        <CardContent>
            <Table>
                <TableHeader>
                    <TableRow>
                        <TableHead>Department</TableHead>
                        <TableHead>Total Employees</TableHead>
                        <TableHead>Active in Events</TableHead>
                        <TableHead>Groups Membership</TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow v-for="dept in employeeSummary" :key="dept.department">
                        <TableCell>{{ dept.department_name }}</TableCell>
                        <TableCell>{{ dept.total_employees }}</TableCell>
                        <TableCell>{{ dept.active_in_events }}</TableCell>
                        <TableCell>{{ dept.member_in_groups }}</TableCell>
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
    CardHeader,
    CardTitle,
} from "@/Components/ui/card";
import { usePage } from "@inertiajs/vue3";

const employeeSummary = usePage().props.employeeSummary;

// const employeeSummary = [
//     { department: "Engineering", totalEmployees: 500, activeInEvents: 350, groupsMembership: 15 },
//     { department: "Marketing", totalEmployees: 150, activeInEvents: 120, groupsMembership: 8 },
//     { department: "Sales", totalEmployees: 200, activeInEvents: 180, groupsMembership: 10 },
//     { department: "Human Resources", totalEmployees: 50, activeInEvents: 45, groupsMembership: 6 },
//     { department: "Finance", totalEmployees: 100, activeInEvents: 70, groupsMembership: 5 },
// ]
</script>
