<script setup>
import AdminSettingLayout from "@/Layouts/AdminSettingLayout.vue";
import Profile from "./Profile.vue";
import ChangePassword from "./ChangePassword.vue";
</script>

<template>
      <Head title="Settings | Account" />
    <AdminSettingLayout>
        <div class="grid gap-6">
            <Profile :user="$page.props.auth.user" />
            <ChangePassword />
        </div>
    </AdminSettingLayout>
</template>
