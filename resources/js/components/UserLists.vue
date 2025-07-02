<script setup lang="ts">
import { format } from 'date-fns';
import TableCell from './ui/table/TableCell.vue';

defineProps({
    user: Object,
});
</script>

<template>
    <TableCell>
        {{ user?.id }}
    </TableCell>
    <TableCell>
        {{ user?.name }}
    </TableCell>
    <TableCell>
        {{ user?.email }}
    </TableCell>
    <TableCell>
        <div class="flex gap-1">
            <span
                class="rounded-lg px-1.5 py-0.5 text-[8px] font-bold text-white uppercase"
                v-for="(role, index) in user?.roles"
                :key="index"
                :class="{ 'bg-blue-500': role?.name === 'admin', 'bg-indigo-500': role?.name === 'user' }"
                >{{ role?.name }}</span
            >
        </div>
    </TableCell>
    <TableCell>
        <span class="text-xs">{{ format(user?.created_at, 'MMM dd, yyyy hh:mm a') }}</span>
    </TableCell>
    <TableCell> - </TableCell>
</template>
