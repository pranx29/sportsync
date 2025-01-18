<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import SummaryCards from "./SumarryCards.vue";
import ActiveGroupsTable from "./ActiveGroupTable.vue";
import EmployeeSummaryTable from "./EmployeeSummary.vue";
</script>

<template>
    <Head title="Dashboard" />
    <AdminLayout>
        <template #title> Dashboard </template>
        <div class="flex-1 space-y-4 p-8 pt-6">
            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                <SummaryCards />
            </div>
            <div className="grid gap-4 md:grid-cols-2 lg:grid-cols-7">
                <ActiveGroupsTable class="col-span-3" />
                <EmployeeSummaryTable class="col-span-4" />
            </div>

        </div>
    </AdminLayout>
</template>
