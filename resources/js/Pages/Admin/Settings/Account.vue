<script setup>
import AdminSettingLayout from "@/Layouts/AdminSettingLayout.vue";
import { Toaster } from "@/Components/ui/toast";
import Profile from "./Profile.vue";
import ChangePassword from "./ChangePassword.vue";
</script>

<template>
    <AdminSettingLayout>
        <div class="grid gap-6">
            <Profile :user="$page.props.auth.user" />
            <ChangePassword />
        </div>
        <Toaster />
    </AdminSettingLayout>
</template>
