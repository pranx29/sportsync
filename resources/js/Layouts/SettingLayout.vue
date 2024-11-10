<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Separator } from "@/Components/ui/separator";
import SidebarNav from "@/Components/SidebarNav.vue";
import Toaster from '@/Components/ui/toast/Toaster.vue'
</script>


<template>
    <AuthenticatedLayout>
        <div class="md:hidden ">
            <!-- <VPImage
                alt="Forms"
                width="1280"
                height="1214"
                class="block"
                :image="{
                    dark: '/examples/forms-dark.png',
                    light: '/examples/forms-light.png',
                }"
            /> -->
        </div>
        <div class="hidden space-y-6 p-16 md:block">
            <div class="space-y-0.5">
                <h2 class="text-2xl font-bold tracking-tight">Settings</h2>
                <p class="text-muted-foreground">
                    Manage your profile settings and set availability preferences.
                </p>
            </div>
            <Separator class="my-6" />
            <div
                class="flex flex-col space-y-8 lg:flex-row lg:space-x-12 lg:space-y-0"
            >
                <aside class="-mx-4 lg:w-1/5">
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
