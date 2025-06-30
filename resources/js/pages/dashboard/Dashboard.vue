<script setup lang="ts">
import FinancialSummary from '@/components/FinancialSummary.vue';
import RecentTransaction from '@/components/RecentTransaction.vue';
import Button from '@/components/ui/button/Button.vue';
import { Drawer, DrawerClose, DrawerContent, DrawerDescription, DrawerFooter, DrawerHeader, DrawerTitle } from '@/components/ui/drawer';
import Select from '@/components/ui/select/Select.vue';
import SelectContent from '@/components/ui/select/SelectContent.vue';
import SelectItem from '@/components/ui/select/SelectItem.vue';
import SelectTrigger from '@/components/ui/select/SelectTrigger.vue';
import SelectValue from '@/components/ui/select/SelectValue.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { format, formatDistanceToNowStrict } from 'date-fns';
import { PhilippinePesoIcon } from 'lucide-vue-next';
import { ref } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

const drawerData: any = ref(null);

const isDrawerOpen = ref(false);

const drawerItem = ref('');

const viewAs = ref('all');

defineProps({
    totalBalance: Number,
    totalExpense: Number,
    totalLoan: Number,
    grossBalance: Number,
    walletDetails: Object,
    expenseDetails: Object,
    loanDetails: Object,
    walletChart: Object,
    expenseChart: Object,
    loanChart: Object,
    recentTransactions: Object,
});

const handleOpenDrawer = (expense: any, item: string) => {
    drawerData.value = expense;
    drawerItem.value = item;
    isDrawerOpen.value = true;
};
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <div
                    class="relative flex aspect-video items-center justify-end overflow-hidden rounded-xl border border-sidebar-border/70 p-4 dark:border-sidebar-border"
                >
                    <PhilippinePesoIcon class="absolute top-4 left-0 h-16 w-16 opacity-30 sm:h-20 sm:w-20" />
                    <span class="absolute top-3 right-2 text-xl font-bold text-gray-300 uppercase sm:text-2xl">GROSS</span>
                    <p class="text-lg font-bold sm:text-xl">
                        {{ Number(grossBalance).toLocaleString('en-PH', { style: 'currency', currency: 'PHP' }) }}
                    </p>
                </div>
                <div
                    class="relative flex aspect-video items-center justify-end overflow-hidden rounded-xl border border-sidebar-border/70 p-4 dark:border-sidebar-border"
                >
                    <PhilippinePesoIcon class="absolute top-4 left-0 h-16 w-16 opacity-30 sm:h-20 sm:w-20" />
                    <span class="absolute top-3 right-2 text-xl font-bold text-gray-300 uppercase sm:text-2xl">Balances</span>
                    <p class="text-lg font-bold sm:text-xl">
                        {{ Number(totalBalance).toLocaleString('en-PH', { style: 'currency', currency: 'PHP' }) }}
                    </p>
                </div>
                <div
                    class="relative flex aspect-video items-center justify-end overflow-hidden rounded-xl border border-sidebar-border/70 p-4 dark:border-sidebar-border"
                >
                    <PhilippinePesoIcon class="absolute top-4 left-0 h-16 w-16 opacity-30 sm:h-20 sm:w-20" />
                    <span class="absolute top-3 right-2 text-xl font-bold text-gray-300 uppercase sm:text-2xl">Expenses</span>
                    <p class="text-lg font-bold sm:text-xl">
                        {{ Number(totalExpense).toLocaleString('en-PH', { style: 'currency', currency: 'PHP' }) }}
                    </p>
                </div>
                <div
                    class="relative flex aspect-video items-center justify-end overflow-hidden rounded-xl border border-sidebar-border/70 p-4 dark:border-sidebar-border"
                >
                    <PhilippinePesoIcon class="absolute top-4 left-0 h-16 w-16 opacity-30 sm:h-20 sm:w-20" />
                    <span class="absolute top-3 right-2 text-xl font-bold text-gray-300 uppercase sm:text-2xl">LOANS</span>
                    <p class="text-lg font-bold sm:text-xl">
                        {{ Number(totalLoan).toLocaleString('en-PH', { style: 'currency', currency: 'PHP' }) }}
                    </p>
                </div>
            </div>
            <div class="relative min-h-[50vh] flex-1 rounded-xl border border-sidebar-border/70 sm:min-h-[60vh] dark:border-sidebar-border">
                <div class="flex justify-between">
                    <h2 class="pt-5 pl-5 text-lg font-bold text-gray-200">Other Details</h2>
                    <div class="pt-5 pr-5">
                        <Select v-model="viewAs">
                            <SelectTrigger class="w-[200px]">
                                <SelectValue placeholder="View as"></SelectValue>
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem value="all"> All </SelectItem>
                                <SelectItem value="card"> Card Only </SelectItem>
                                <SelectItem value="recent"> Recent Transactions Only </SelectItem>
                                <SelectItem value="chart"> Chart Only </SelectItem>
                            </SelectContent>
                        </Select>
                    </div>
                </div>
                <div class="p-3" v-if="viewAs === 'card' || viewAs === 'all'">
                    <div class="rounded-lg border p-6">
                        <h2 class="mb-4 text-lg font-bold text-gray-200">Financial Items Log</h2>
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                            <div class="h-fit rounded-lg border p-2">
                                <h2 class="mb-3 text-center text-lg font-bold">Remaining Wallet</h2>
                                <hr class="mb-3" />
                                <div
                                    class="grid grid-cols-2 py-2"
                                    v-for="(wallet, index) in walletDetails"
                                    :key="index"
                                    :class="{ 'border-b': walletDetails?.length > 1 && walletDetails?.length - 1 !== Number(index) }"
                                >
                                    <span class="font-bold uppercase"
                                        >{{ wallet.name }}
                                        <span>
                                            -
                                            <button
                                                type="button"
                                                @click="handleOpenDrawer(wallet, 'wallet')"
                                                class="rounded-full bg-green-200 px-2 py-0.5 text-xs text-green-800 hover:bg-green-300"
                                            >
                                                {{ wallet.wallets_count }}
                                            </button>
                                        </span>
                                    </span>
                                    <div class="flex flex-col text-end">
                                        <span class="font-thin text-gray-300">{{
                                            Number(wallet.wallets_sum_amount - wallet.expenses_sum_amount).toLocaleString('en-PH', {
                                                style: 'currency',
                                                currency: 'PHP',
                                            })
                                        }}</span>
                                        <span class="text-xs text-gray-400">{{
                                            format(wallet?.wallets[0]?.created_at, 'MMM dd, yyyy hh:mm a')
                                        }}</span>
                                        <span class="text-xs text-gray-400">{{
                                            formatDistanceToNowStrict(wallet?.wallets[0]?.created_at, { addSuffix: true })
                                        }}</span>
                                    </div>
                                </div>
                                <div v-if="walletDetails?.length === 0" class="text-center">
                                    <span class="text-gray-400">No wallet found</span>
                                </div>
                            </div>
                            <div class="h-fit rounded-lg border p-2">
                                <h2 class="mb-3 text-center text-lg font-bold">Expense Details</h2>
                                <hr class="mb-3" />
                                <div
                                    class="grid grid-cols-2 py-2"
                                    v-for="(expense, index) in expenseDetails"
                                    :key="index"
                                    :class="{ 'border-b': expenseDetails?.length > 1 && expenseDetails?.length - 1 !== Number(index) }"
                                >
                                    <span class="font-bold uppercase">
                                        {{ expense.name }}
                                        <span>
                                            -
                                            <button
                                                type="button"
                                                @click="handleOpenDrawer(expense, 'expense')"
                                                class="rounded-full bg-blue-200 px-2 py-0.5 text-xs text-blue-800 hover:bg-blue-300"
                                            >
                                                {{ expense.expenses_count }}
                                            </button>
                                        </span>
                                    </span>
                                    <div class="flex flex-col text-end">
                                        <span class="font-thin text-gray-300">{{
                                            Number(expense.expenses_sum_amount).toLocaleString('en-PH', { style: 'currency', currency: 'PHP' })
                                        }}</span>
                                        <span class="text-xs text-gray-400">{{
                                            format(expense?.expenses[0]?.created_at, 'MMM dd, yyyy hh:mm a')
                                        }}</span>
                                        <span class="text-xs text-gray-400">{{
                                            formatDistanceToNowStrict(expense?.expenses[0]?.created_at, { addSuffix: true })
                                        }}</span>
                                    </div>
                                </div>
                                <div v-if="expenseDetails?.length === 0" class="text-center">
                                    <span class="text-gray-400">No expense found</span>
                                </div>
                            </div>
                            <div class="h-fit rounded-lg border p-2">
                                <h2 class="mb-3 text-center text-lg font-bold">Loan Details</h2>
                                <hr class="mb-3" />
                                <div
                                    class="grid grid-cols-2 py-2"
                                    v-for="(loan, index) in loanDetails"
                                    :class="{ 'border-b': loanDetails?.length > 1 && loanDetails?.length - 1 !== Number(index) }"
                                    :key="index"
                                >
                                    <span class="font-bold uppercase"
                                        >{{ loan.name }}
                                        <span>
                                            -
                                            <button
                                                type="button"
                                                @click="handleOpenDrawer(loan, 'loan')"
                                                class="rounded-full bg-yellow-200 px-2 py-0.5 text-xs text-yellow-800 hover:bg-yellow-300"
                                            >
                                                {{ loan.loans_count }}
                                            </button>
                                        </span>
                                    </span>
                                    <div class="flex flex-col text-end">
                                        <span class="font-thin text-gray-300">{{
                                            Number(loan.loans_sum_amount).toLocaleString('en-PH', { style: 'currency', currency: 'PHP' })
                                        }}</span>
                                        <span class="text-xs text-gray-400">{{ format(loan?.loans[0]?.created_at, 'MMM dd, yyyy hh:mm a') }}</span>
                                        <span class="text-xs text-gray-400">{{
                                            formatDistanceToNowStrict(loan?.loans[0]?.created_at, { addSuffix: true })
                                        }}</span>
                                    </div>
                                </div>
                                <div v-if="loanDetails?.length === 0" class="text-center">
                                    <span class="text-gray-400">No loan found</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="p-3" v-if="viewAs === 'recent' || viewAs === 'all'">
                    <div class="rounded-lg border">
                        <div class="overflow-hidden p-6 shadow-sm sm:rounded-lg">
                            <h2 class="mb-4 text-xl font-semibold text-gray-300">Recent Transactions</h2>
                            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                                <div class="h-fit rounded-xl border p-3">
                                    <div class="mb-2 flex justify-between">
                                        <h2 class="text-sm font-bold text-gray-300">Recent Added to Wallet</h2>
                                        <Link href="/account-wallets" class="rounded-xl bg-blue-500 px-2 py-1.5 text-xs text-white hover:bg-blue-600"
                                            >See all</Link
                                        >
                                    </div>
                                    <div class="flex flex-col gap-2 rounded-xl">
                                        <RecentTransaction
                                            v-for="(recentWallet, index) in recentTransactions?.recentWallets"
                                            :key="index"
                                            :transaction="recentWallet"
                                            title="wallet"
                                        />
                                        <span class="my-5 text-center text-xs" v-if="recentTransactions?.recentWallets?.length === 0">
                                            No recent added to wallet
                                        </span>
                                    </div>
                                </div>
                                <div class="h-fit rounded-xl border p-3">
                                    <div class="mb-2 flex justify-between">
                                        <h2 class="text-sm font-bold text-gray-300">Recent Expenses</h2>
                                        <Link href="/expenses" class="rounded-xl bg-blue-500 px-2 py-1.5 text-xs text-white hover:bg-blue-600"
                                            >See all</Link
                                        >
                                    </div>
                                    <div class="flex flex-col gap-2 rounded-xl">
                                        <RecentTransaction
                                            v-for="(recentExpense, index) in recentTransactions?.recentExpenses"
                                            :key="index"
                                            :transaction="recentExpense"
                                            title="expense"
                                        />
                                        <span class="my-5 text-center text-xs" v-if="recentTransactions?.recentExpenses?.length === 0">
                                            No recent added to wallet
                                        </span>
                                    </div>
                                </div>
                                <div class="h-fit rounded-xl border p-3">
                                    <div class="mb-2 flex justify-between">
                                        <h2 class="text-sm font-bold text-gray-300">Recent Loans</h2>
                                        <Link href="/loans" class="rounded-xl bg-blue-500 px-2 py-1.5 text-xs text-white hover:bg-blue-600"
                                            >See all</Link
                                        >
                                    </div>
                                    <div class="flex flex-col gap-2 rounded-xl">
                                        <RecentTransaction
                                            v-for="(recenLoan, index) in recentTransactions?.recentLoans"
                                            :key="index"
                                            :transaction="recenLoan"
                                            title="loan"
                                        />
                                        <span class="my-5 text-center text-xs" v-if="recentTransactions?.recenLoans?.length === 0">
                                            No recent added to wallet
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-if="viewAs === 'chart' || viewAs === 'all'" class="p-3">
                    <div class="mx-auto rounded-lg border">
                        <div class="overflow-hidden p-6 shadow-sm sm:rounded-lg">
                            <div class="flex justify-between">
                                <h2 class="mb-4 text-xl font-semibold text-gray-300">Financial Summary</h2>
                            </div>
                            <div class="h-96">
                                <FinancialSummary :loanChart="loanChart" :walletChart="walletChart" :expenseChart="expenseChart" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <Drawer v-model:open="isDrawerOpen">
            <DrawerContent>
                <div class="mx-auto w-full max-w-sm">
                    <DrawerHeader>
                        <DrawerTitle>{{ drawerData?.name }}</DrawerTitle>
                        <DrawerDescription>
                            <div v-if="drawerItem === 'expense'">
                                You had spent
                                {{ Number(drawerData?.expenses_sum_amount).toLocaleString('en-PH', { style: 'currency', currency: 'PHP' }) }}
                            </div>
                            <div v-if="drawerItem === 'wallet'">
                                You added balance
                                {{ Number(drawerData?.wallets_sum_amount).toLocaleString('en-PH', { style: 'currency', currency: 'PHP' }) }}
                            </div>
                            <div v-if="drawerItem === 'loan'">
                                You added loan
                                {{ Number(drawerData?.loans_sum_amount).toLocaleString('en-PH', { style: 'currency', currency: 'PHP' }) }}
                            </div>
                        </DrawerDescription>
                    </DrawerHeader>
                    <div class="p-4 pb-0">
                        <div class="my-3 h-[200px] overflow-y-auto px-3">
                            <ul>
                                <li v-for="(expense, index) in drawerData?.expenses" :key="index" class="grid grid-cols-2 items-center border-b p-2">
                                    <span>{{ expense.expense_category.name }}</span>
                                    <span class="flex flex-col text-end text-gray-400">
                                        <span class="text-sm">{{
                                            Number(expense.amount).toLocaleString('en-PH', { style: 'currency', currency: 'PHP' })
                                        }}</span>
                                        <span class="text-xs">{{ expense?.expense_detail?.name }}</span>
                                        <span class="text-xs">{{ format(expense.created_at, 'MMM dd, yyyy hh:mm a') }}</span>
                                    </span>
                                </li>
                                <li v-for="(loan, index) in drawerData?.loans" :key="index" class="grid grid-cols-2 items-center border-b p-2">
                                    <span class="uppercase">{{ loan.loan_type.name }}</span>
                                    <span class="flex flex-col text-end text-gray-400">
                                        <span class="text-sm">{{
                                            Number(loan.amount).toLocaleString('en-PH', { style: 'currency', currency: 'PHP' })
                                        }}</span>
                                        <span class="text-xs">{{ format(loan.created_at, 'MMM dd, yyyy hh:mm a') }}</span>
                                    </span>
                                </li>
                                <li v-for="(wallet, index) in drawerData?.wallets" :key="index" class="grid grid-cols-2 items-center border-b p-2">
                                    <span>{{ Number(wallet.amount).toLocaleString('en-PH', { style: 'currency', currency: 'PHP' }) }}</span>
                                    <span class="text-end text-xs text-gray-400">{{ format(wallet.created_at, 'MMM dd, yyyy hh:mm a') }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <DrawerFooter>
                        <DrawerClose as-child>
                            <Button variant="outline"> Close </Button>
                        </DrawerClose>
                    </DrawerFooter>
                </div>
            </DrawerContent>
        </Drawer>
    </AppLayout>
</template>
