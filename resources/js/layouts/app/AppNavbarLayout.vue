<script setup lang="ts">
import Button from '@/components/ui/button/Button.vue';
import { Link } from '@inertiajs/vue3';
import { HandCoinsIcon, Menu, X } from 'lucide-vue-next';
import { nextTick, onBeforeUnmount, onMounted, ref } from 'vue';

const isOpenNavbar = ref(false);

const buttonRef = ref<HTMLButtonElement | undefined>(undefined);

const sidebarRef = ref<HTMLDivElement | undefined>(undefined);

const handleOpenNavBar = () => {
    isOpenNavbar.value = !isOpenNavbar.value;
};

const handleClickOutside = (e: MouseEvent) => {
    if (buttonRef.value && !buttonRef.value.contains(e.target as Node) && sidebarRef.value && !sidebarRef.value.contains(e.target as Node)) {
        console.log('click outside');
        isOpenNavbar.value = false;
    }
};

onMounted(() => {
    nextTick(() => {
        document.addEventListener('mousedown', handleClickOutside);
    });
});

onBeforeUnmount(() => {
    document.removeEventListener('mousedown', handleClickOutside);
});
</script>

<template>
    <div class="min-h-screen">
        <!-- Navigation -->
        <div class="sticky top-0 z-50 bg-white shadow-lg dark:bg-gray-800">
            <div
                class="absolute z-50 h-screen w-full transition-all duration-300 ease-in-out md:-translate-x-full"
                :class="{ 'translate-x-0 bg-gray-800/40': isOpenNavbar, '-translate-x-full': !isOpenNavbar }"
            >
                <X class="absolute top-3 right-18 cursor-pointer text-black dark:text-white" @click="handleOpenNavBar" />
                <div class="h-screen w-10/12 bg-white p-3 dark:bg-gray-800" ref="sidebarRef">
                    <div class="flex flex-col space-y-5 p-5">
                        <Link href="#" class="text-gray-600 transition hover:text-blue-600 dark:text-gray-200 dark:hover:text-blue-600"
                            >Features</Link
                        >
                        <Link href="#" class="text-gray-600 transition hover:text-blue-600 dark:text-gray-200 dark:hover:text-blue-600">Pricing</Link>
                        <Link href="#" class="text-gray-600 transition hover:text-blue-600 dark:text-gray-200 dark:hover:text-blue-600">About</Link>

                        <Link
                            v-if="!$page.props.auth.user"
                            href="/login"
                            class="text-gray-600 transition hover:text-blue-600 dark:text-gray-200 dark:hover:text-blue-600"
                            >Login</Link
                        >
                        <Link
                            v-if="!$page.props.auth.user"
                            href="/register"
                            class="text-gray-600 transition hover:text-blue-600 dark:text-gray-200 dark:hover:text-blue-600"
                            >Register</Link
                        >
                        <Link
                            v-else
                            href="/dashboard"
                            class="text-gray-600 transition hover:text-blue-600 dark:text-gray-200 dark:hover:text-blue-600"
                            >Dashboard</Link
                        >
                    </div>
                </div>
            </div>
            <nav class="container mx-auto flex items-center justify-between px-6 py-6">
                <div class="flex items-center space-x-2">
                    <HandCoinsIcon class="h-8 w-8 text-blue-500" />
                    <span class="text-xl font-bold text-blue-600">E-Track</span>
                </div>
                <div class="hidden md:block">
                    <div class="flex items-center space-x-6">
                        <Link href="#" class="text-gray-600 transition hover:text-blue-600 dark:text-gray-200 dark:hover:text-blue-600"
                            >Features</Link
                        >
                        <Link href="#" class="text-gray-600 transition hover:text-blue-600 dark:text-gray-200 dark:hover:text-blue-600">Pricing</Link>
                        <Link href="#" class="text-gray-600 transition hover:text-blue-600 dark:text-gray-200 dark:hover:text-blue-600">About</Link>
                        <div v-if="!$page.props.auth.user">
                            <Link href="/login" class="rounded-lg border-blue-600 px-3 py-2 text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/50"
                                >Login</Link
                            >
                            <Link href="/register" class="rounded-lg bg-blue-600 px-3 py-2 text-white hover:bg-blue-700">Register</Link>
                        </div>
                        <div v-else>
                            <Link href="/dashboard" class="rounded-lg bg-blue-600 px-3 py-2 text-white hover:bg-blue-700">Dashboard</Link>
                        </div>
                    </div>
                </div>
                <div class="block md:hidden" v-if="!isOpenNavbar">
                    <Button type="button" variant="outline" @click="handleOpenNavBar" ref="buttonRef">
                        <Menu v-if="!isOpenNavbar" />
                    </Button>
                </div>
            </nav>
        </div>
        <slot />
    </div>
</template>
