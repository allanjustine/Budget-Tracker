<script setup lang="ts">
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import { BanknoteIcon, ChartBarStacked, HandCoins, Handshake, LayoutGrid, PhilippinePesoIcon, PiggyBank, Users2, Wallet2 } from 'lucide-vue-next';
import AppLogo from './AppLogo.vue';

const user: any = usePage().props.auth.user;

const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
        icon: LayoutGrid,
        show: true,
    },
    {
        title: 'Account Wallets',
        href: '/account-wallets',
        icon: Wallet2,
        show: true,
    },
    {
        title: 'Expenses',
        href: '/expenses',
        icon: PhilippinePesoIcon,
        show: true,
    },
    {
        title: 'Expense Details',
        href: '/expense-details',
        icon: BanknoteIcon,
        show: true,
    },
    {
        title: 'Loans',
        href: '/loans',
        icon: HandCoins,
        show: true,
    },
    {
        title: 'Bank Types',
        href: '/bank-types',
        icon: PiggyBank,
        show: user.is_admin,
    },
    {
        title: 'Loan Types',
        href: '/loan-types',
        icon: Handshake,
        show: user.is_admin,
    },
    {
        title: 'Expense Categories',
        href: '/expense-categories',
        icon: ChartBarStacked,
        show: user.is_admin,
    },
    {
        title: 'Users',
        href: '/users',
        icon: Users2,
        show: user.is_admin,
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
