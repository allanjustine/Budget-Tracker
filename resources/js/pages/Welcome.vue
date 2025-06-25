<script setup lang="ts">
import LandingPage from '@/components/LandingPage.vue';
import AppFooter from '@/layouts/app/AppFooter.vue';
import AppNavbarLayout from '@/layouts/app/AppNavbarLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <AppNavbarLayout>
        <Head title="Home" />
        <LandingPage />
        <AppFooter />
    </AppNavbarLayout>
</template>
