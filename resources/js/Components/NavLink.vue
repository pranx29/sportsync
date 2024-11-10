<script setup>
import { computed } from "vue";
import { Link } from "@inertiajs/vue3";

const props = defineProps({
    href: {
        type: String,
        required: true,
    },
    active: {
        type: Boolean,
    },
});

const classes = computed(() =>
    props.active
        ? "flex items-center gap-2 text-lg font-semibold md:text-base"
        : "text-muted-foreground transition-colors hover:text-foreground"
);
</script>

<template>
    <Link :href="href" :class="classes">
        <slot />
    </Link>
</template>
