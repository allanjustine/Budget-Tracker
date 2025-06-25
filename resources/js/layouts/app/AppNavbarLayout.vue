<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { HandCoinsIcon } from 'lucide-vue-next';
</script>

<template>
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100">
        <!-- Navigation -->
        <nav class="container mx-auto flex items-center justify-between px-6 py-6">
            <div class="flex items-center space-x-2">
                <HandCoinsIcon class="h-8 w-8 text-blue-500" />
                <span class="text-xl font-bold text-blue-600">E-Track</span>
            </div>
            <div class="flex items-center space-x-6">
                <Link href="#" class="text-gray-600 transition hover:text-blue-600">Features</Link>
                <Link href="#" class="text-gray-600 transition hover:text-blue-600">Pricing</Link>
                <Link href="#" class="text-gray-600 transition hover:text-blue-600">About</Link>
                <div v-if="!$page.props.auth.user">
                    <Link href="/login" class="rounded-lg border-blue-600 px-3 py-2 text-blue-600 hover:bg-blue-50">Login</Link>
                    <Link href="/register" class="rounded-lg bg-blue-600 px-3 py-2 hover:bg-blue-700">Register</Link>
                </div>
                <div v-else>
                    <Link href="/dashboard" class="rounded-lg bg-blue-600 px-3 py-2 hover:bg-blue-700">Dashboard</Link>
                </div>
            </div>
        </nav>
        <slot />
    </div>
</template>
