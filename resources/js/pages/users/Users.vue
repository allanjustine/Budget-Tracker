<script setup lang="ts">
import Button from '@/components/ui/button/Button.vue';
import Table from '@/components/ui/table/Table.vue';
import TableBody from '@/components/ui/table/TableBody.vue';
import TableHead from '@/components/ui/table/TableHead.vue';
import TableHeader from '@/components/ui/table/TableHeader.vue';
import TableRow from '@/components/ui/table/TableRow.vue';
import UserLists from '@/components/UserLists.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import { Loader2, RefreshCwIcon } from 'lucide-vue-next';
import { ref } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Users',
        href: '/users',
    },
];

defineProps({
    users: Object,
});

const isRefresh = ref<boolean>(false);

const tableHead = ['ID', 'Name', 'Email', 'Roles', 'Created At', 'Actions'];

const handleRefreshUsers = () => {
    router.reload({
        only: ['users'],
        onStart: () => (isRefresh.value = true),
        onFinish: () => (isRefresh.value = false),
    });
};
</script>

<template>
    <Head title="Users" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-5">
            <Button :disabled="isRefresh" class="mb-2 bg-blue-500 text-white hover:bg-blue-600" @click="handleRefreshUsers" size="sm">
                <RefreshCwIcon class="mr-2 h-4 w-4" v-show="!isRefresh" />
                <Loader2 v-show="isRefresh" class="mr-2 h-4 w-4 animate-spin" />
                <span v-show="!isRefresh">Refresh</span>
                <span v-show="isRefresh">Refreshing...</span>
            </Button>
            <div class="w-full">
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead v-for="(item, id) in tableHead" :key="id">{{ item }}</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="(user, index) in users" :user="user" :key="index">
                            <UserLists :user="user" />
                        </TableRow>
                    </TableBody>
                </Table>
            </div>
        </div>
    </AppLayout>
</template>
