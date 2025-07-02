<script setup lang="ts">
import Table from '@/components/ui/table/Table.vue';
import TableBody from '@/components/ui/table/TableBody.vue';
import TableHead from '@/components/ui/table/TableHead.vue';
import TableHeader from '@/components/ui/table/TableHeader.vue';
import TableRow from '@/components/ui/table/TableRow.vue';
import UserLists from '@/components/UserLists.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Users',
        href: '/users',
    },
];

defineProps({
    users: Object,
});

const tableHead = ['ID', 'Name', 'Email', 'Roles', 'Created At', 'Actions'];
</script>

<template>
    <Head title="Users" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-5">
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
