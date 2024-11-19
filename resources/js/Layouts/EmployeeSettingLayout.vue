<script setup>
import AuthenticatedLayout from "@/Layouts/EmployeeLayout.vue";
import { Separator } from "@/Components/ui/separator";
import SidebarNav from "@/Components/SidebarNav.vue";
import Toaster from "@/Components/ui/toast/Toaster.vue";
</script>

<template>
    
    <AuthenticatedLayout>
        <div class="space-y-6 p-4 md:p-16">
            <div class="space-y-0.5">
                <h2 class="text-xl md:text-2xl font-bold tracking-tight">Settings</h2>
                <p class="text-muted-foreground text-sm md:text-base">
                    Manage your profile settings and set availability
                    preferences.
                </p>
            </div>
            <Separator class="my-6" />
            <div class="flex flex-col space-y-8 lg:flex-row lg:space-x-12 lg:space-y-0">
                <aside class="lg:w-1/5">
                    <SidebarNav />
                </aside>
                <div class="flex-1 lg:max-w-2xl">
                    <div class="space-y-6">
                        <slot name="content" />
                    </div>
                    <Toaster />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
