<script setup lang="ts">
import { Toaster } from '@/components/ui/sonner';
import AuthLayout from '@/layouts/auth/AuthSimpleLayout.vue';
import 'vue-sonner/style.css';

defineProps<{
    title?: string;
    description?: string;
}>();
</script>

<template>
    <AuthLayout :title="title" :description="description">
        <slot />
    </AuthLayout>
    <Toaster />
</template>
