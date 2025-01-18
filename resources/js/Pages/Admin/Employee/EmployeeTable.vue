<script setup>
import {
    Card,
    CardContent,
    CardDescription,
    CardFooter,
    CardHeader,
    CardTitle,
} from "@/Components/ui/card";
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from "@/Components/ui/dropdown-menu";
import { Sheet, SheetContent, SheetTrigger } from "@/Components/ui/sheet";
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from "@/Components/ui/table";
import { Badge } from "@/Components/ui/badge";
import { MoreHorizontal } from "lucide-vue-next";
import { Avatar, AvatarFallback, AvatarImage } from "@/Components/ui/avatar";
import EditEmployeeForm from "./EditEmployeeForm.vue";

defineProps({
    employees: {
        type: Array,
        required: true,
    },
});
</script>

<template>
    <Card>
        <CardContent>
            <Table class="w-full">
                <TableHeader>
                    <TableRow>
                        <TableHead class="hidden w-[100px] sm:table-cell">
                            <span class="sr-only">img</span>
                        </TableHead>
                        <TableHead>Name</TableHead>
                        <TableHead>Email</TableHead>
                        <TableHead class="hidden md:table-cell">
                            Department
                        </TableHead>
                        <TableHead class="hidden md:table-cell">
                            Role
                        </TableHead>
                        <TableHead class="hidden md:table-cell">
                            Status
                        </TableHead>
                        <TableHead class="hidden md:table-cell">
                            Created at
                        </TableHead>
                        <TableHead>
                            <span class="sr-only">Actions</span>
                        </TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow v-for="employee in employees" :key="employee.id">
                        <TableCell class="hidden sm:table-cell">
                            <Avatar class="w-16 h-16 rounded-sm">
                                <AvatarImage
                                    :src="employee.profile?.profile_image"
                                    alt="@radix-vue"
                                />
                                <AvatarFallback>
                                    {{ employee.first_name.charAt(0) }}
                                    {{ employee.last_name.charAt(0) }}
                                </AvatarFallback>
                            </Avatar>
                        </TableCell>
                        <TableCell class="font-medium">
                            {{ employee.first_name }}
                            {{ employee.last_name }}
                        </TableCell>
                        <TableCell>
                            {{ employee.email }}
                        </TableCell>
                        <TableCell class="hidden md:table-cell">
                            {{
                                employee.profile
                                    ? employee.profile.department.name
                                    : "-"
                            }}
                        </TableCell>
                        <TableCell class="hidden md:table-cell">
                            {{
                                employee.profile
                                    ? employee.profile.role.title
                                    : "-"
                            }}
                        </TableCell>
                        <TableCell class="hidden md:table-cell">
                            <Badge
                                :variant="
                                    employee.is_active ? 'default' : 'secondary'
                                "
                            >
                                {{ employee.is_active ? "Active" : "Inactive" }}
                            </Badge>
                        </TableCell>
                        <TableCell class="hidden md:table-cell">
                            {{ new Date(employee.created_at).toLocaleString() }}
                        </TableCell>
                        <TableCell>
                            <DropdownMenu>
                                <DropdownMenuTrigger as-child>
                                    <Button
                                        aria-haspopup="true"
                                        size="icon"
                                        variant="ghost"
                                    >
                                        <MoreHorizontal class="h-4 w-4" />
                                        <span class="sr-only">Toggle menu</span>
                                    </Button>
                                </DropdownMenuTrigger>
                                <DropdownMenuContent align="end">
                                    <DropdownMenuLabel
                                        >Actions</DropdownMenuLabel
                                    >
                                    <EditEmployeeForm :employee="employee">
                                        <DropdownMenuItem>
                                            Edit
                                        </DropdownMenuItem>
                                    </EditEmployeeForm>
                                </DropdownMenuContent>
                            </DropdownMenu>
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>
        </CardContent>
        <CardFooter>
            <div class="text-xs text-muted-foreground">
                Showing
                <strong>{{ $page.props.employees.from }}</strong>
                to
                <strong>{{ $page.props.employees.to }}</strong>
                of
                <strong>{{ $page.props.employees.total }}</strong>
                employees
            </div>
        </CardFooter>
    </Card>
</template>
