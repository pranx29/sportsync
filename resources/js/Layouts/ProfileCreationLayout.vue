<script setup>
import { Head } from "@inertiajs/vue3";

import { ref } from 'vue';

const pageTitle = ref('Create Profile');

</script>

<template>
    <div class="flex min-h-screen">
        <!-- Left Section with Title -->
        <div
            class="w-1/2 bg-primary flex items-center justify-center p-12"
        >
            <h1
                class="scroll-m-20 text-4xl font-extrabold tracking-tight lg:text-5xl text-background"
            >
                <slot name="title"></slot>
            </h1>
        </div>

        <div class="w-1/2 flex items-center justify-center p-12">
            <div class="w-full max-w-md">
                <h2
                    class="scroll-m-20 text-3xl font-semibold tracking-tight mb-2"
                >
                    <slot name="form-title"></slot>
                </h2>

                <div class="flex flex-col gap-y-4">
                    <slot name="form"></slot>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "ProfileCreationLayout",
};
</script>
