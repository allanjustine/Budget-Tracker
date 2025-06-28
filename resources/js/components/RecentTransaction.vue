<script setup lang="ts">
import { format, formatDistanceToNowStrict } from 'date-fns';
import { computed } from 'vue';
import Card from './ui/card/Card.vue';
import CardContent from './ui/card/CardContent.vue';
import CardDescription from './ui/card/CardDescription.vue';
import CardHeader from './ui/card/CardHeader.vue';
import CardTitle from './ui/card/CardTitle.vue';

const props = defineProps({
    transaction: Object,
    title: String,
});

const formattedAmount = computed(() => {
    return Number(props?.transaction?.amount)?.toLocaleString('en-PH', { style: 'currency', currency: 'PHP' });
});

const formattedDate = computed(() => {
    return format(props?.transaction?.created_at, 'MMM dd, yyyy hh:mm a');
});

const diffForHumans = computed(() => {
    return formatDistanceToNowStrict(props?.transaction?.created_at, { addSuffix: true });
});
</script>

<template>
    <Card class="relative transition-shadow hover:shadow-md">
        <div class="absolute top-1 right-2 text-[10px] text-gray-400">{{ diffForHumans }}</div>
        <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2 md:flex-col lg:flex-row">
            <div class="flex items-center space-x-4">
                <div>
                    <CardTitle class="text-xs font-medium">
                        {{ transaction?.bank_type?.name }}
                    </CardTitle>
                    <CardDescription class="text-xs">
                        {{ formattedDate }}
                    </CardDescription>
                </div>
            </div>
            <div class="flex flex-col items-end">
                <CardTitle class="flex text-sm font-medium"
                    ><span>{{ title === 'wallet' ? '+' : '-' }}</span> <span>{{ formattedAmount }}</span></CardTitle
                >
            </div>
        </CardHeader>
        <CardContent :class="{ hidden: title === 'wallet' }">
            <div class="grid grid-cols-2 items-center gap-4 text-xs text-muted-foreground">
                <span v-if="transaction?.loan_type"
                    >Loan Type:
                    <span
                        class="rounded-2xl px-1 py-0.5 text-[9px] font-bold uppercase"
                        :class="{
                            'bg-green-600 text-green-100': transaction?.loan_type?.name === 'cash',
                            'bg-blue-600 text-blue-100': transaction?.loan_type?.name === 'lending',
                            'bg-yellow-600 text-yellow-100': transaction?.loan_type?.name === 'bank',
                        }"
                        >{{ transaction?.loan_type?.name }}</span
                    ></span
                >
                <span v-if="transaction?.loan"
                    >Loan Amount: {{ Number(transaction?.loan?.amount)?.toLocaleString('en-PH', { style: 'currency', currency: 'PHP' }) }}</span
                >
                <span v-if="transaction?.expense_category"
                    >Expense Category: <span class="font-bold">{{ transaction?.expense_category?.name }}</span></span
                >
                <span v-if="transaction?.expense_detail"
                    >Expense To: <span class="font-bold">{{ transaction?.expense_detail?.name }}</span></span
                >
            </div>
        </CardContent>
    </Card>
</template>
