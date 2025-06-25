<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { PhilippinePesoIcon } from 'lucide-vue-next';
import PlaceholderPattern from '../components/PlaceholderPattern.vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

defineProps({
    totalBalance: Number,
    totalExpense: Number,
    totalLoan: Number,
    grossBalance: Number,
});
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="grid auto-rows-min gap-4 md:grid-cols-4">
                <div
                    class="relative flex aspect-video items-center justify-end overflow-hidden rounded-xl border border-sidebar-border/70 p-5 dark:border-sidebar-border"
                >
                    <PhilippinePesoIcon class="absolute top-9 left-0 h-22 w-22 opacity-30" />
                    <span class="absolute top-5 -right-2 h-32 w-32 text-3xl font-bold text-gray-300 uppercase">GROSS</span>
                    <p class="text-xl font-bold">{{ Number(grossBalance).toLocaleString('en-PH', { style: 'currency', currency: 'PHP' }) }}</p>
                </div>
                <div
                    class="relative flex aspect-video items-center justify-end overflow-hidden rounded-xl border border-sidebar-border/70 p-5 dark:border-sidebar-border"
                >
                    <PhilippinePesoIcon class="absolute top-9 left-0 h-22 w-22 opacity-30" />
                    <span class="absolute top-5 right-11 h-32 w-32 text-3xl font-bold text-gray-300 uppercase">Balances</span>
                    <p class="text-xl font-bold">{{ Number(totalBalance).toLocaleString('en-PH', { style: 'currency', currency: 'PHP' }) }}</p>
                </div>
                <div
                    class="relative flex aspect-video items-center justify-end overflow-hidden rounded-xl border border-sidebar-border/70 p-5 dark:border-sidebar-border"
                >
                    <PhilippinePesoIcon class="absolute top-9 left-0 h-22 w-22 opacity-30" />
                    <span class="absolute top-5 right-11 h-32 w-32 text-3xl font-bold text-gray-300 uppercase">Expenses</span>
                    <p class="text-xl font-bold">{{ Number(totalExpense).toLocaleString('en-PH', { style: 'currency', currency: 'PHP' }) }}</p>
                </div>
                <div
                    class="relative flex aspect-video items-center justify-end overflow-hidden rounded-xl border border-sidebar-border/70 p-5 dark:border-sidebar-border"
                >
                    <PhilippinePesoIcon class="absolute top-9 left-0 h-22 w-22 opacity-30" />
                    <span class="absolute top-5 -right-4 h-32 w-32 text-3xl font-bold text-gray-300 uppercase">LOANS</span>
                    <p class="text-xl font-bold">{{ Number(totalLoan).toLocaleString('en-PH', { style: 'currency', currency: 'PHP' }) }}</p>
                </div>
            </div>
            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 md:min-h-min dark:border-sidebar-border">
                <PlaceholderPattern />
            </div>
        </div>
    </AppLayout>
</template>
