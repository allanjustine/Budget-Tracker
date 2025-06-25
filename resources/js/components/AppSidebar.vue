<script setup lang="ts">
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/vue3';
import { BanknoteIcon, ChartBarStacked, HandCoins, Handshake, LayoutGrid, PiggyBank, Wallet2 } from 'lucide-vue-next';
import AppLogo from './AppLogo.vue';

const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
        icon: LayoutGrid,
    },
    {
        title: 'Account Wallets',
        href: '/account-wallets',
        icon: Wallet2,
    },
    {
        title: 'Expenses',
        href: '/expenses',
        icon: BanknoteIcon,
    },
    {
        title: 'Loans',
        href: '/loans',
        icon: HandCoins,
    },
    {
        title: 'Bank Types',
        href: '/bank-types',
        icon: PiggyBank,
    },
    {
        title: 'Loan Types',
        href: '/loan-types',
        icon: Handshake,
    },
    {
        title: 'Expense Categories',
        href: '/expense-categories',
        icon: ChartBarStacked,
    },
];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="route('dashboard')">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
