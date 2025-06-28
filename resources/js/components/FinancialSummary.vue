<script setup lang="ts">
import BarChart from '@/components/ui/charts/BarChart.vue';

const props = defineProps({
    loanChart: Object,
    walletChart: Object,
    expenseChart: Object,
});

const chartData = {
    labels: props?.loanChart?.labels || props?.walletChart?.labels || props?.expenseChart?.labels,
    datasets: [
        {
            label: 'Wallet/Balance',
            backgroundColor: 'violet',
            data: props?.walletChart?.data,
        },
        {
            label: 'Expense',
            backgroundColor: 'pink',
            data: props?.expenseChart?.data,
        },
        {
            label: 'Loan',
            backgroundColor: 'red',
            data: props?.loanChart?.data,
        },
    ],
};

const chartOptions = {
    responsive: true,
    maintainAspectRatio: false,
};
</script>

<template>
    <BarChart :chart-data="chartData" :chart-options="chartOptions" />
</template>
