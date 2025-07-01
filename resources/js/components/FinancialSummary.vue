<script setup lang="ts">
import BarChart from '@/components/ui/charts/BarChart.vue';

const props = defineProps({
    loanChart: Object,
    walletChart: Object,
    expenseChart: Object,
});

const chartData = {
    datasets: [
        {
            label: 'Wallet/Balance',
            backgroundColor: 'violet',
            data:
                props?.walletChart?.labels?.map((label: string, i: number) => ({
                    x: label,
                    y: props?.walletChart?.data?.[i] || 0,
                })) || [],
        },
        {
            label: 'Expense',
            backgroundColor: 'pink',
            data:
                props?.expenseChart?.labels?.map((label: string, i: number) => ({
                    x: label,
                    y: props?.expenseChart?.data?.[i] || 0,
                })) || [],
        },
        {
            label: 'Loan',
            backgroundColor: 'red',
            data:
                props?.loanChart?.labels?.map((label: string, i: number) => ({
                    x: label,
                    y: props?.loanChart?.data?.[i] || 0,
                })) || [],
        },
    ],
};

const chartOptions = {
    responsive: true,
    plugins: {
        tooltip: {
            callbacks: {
                label: (context: any) => {
                    const label = context.dataset.label || '';
                    const value = context.raw.y;
                    const formatted = new Intl.NumberFormat('en-PH', {
                        style: 'currency',
                        currency: 'PHP',
                    }).format(value);
                    return `${label}: ${formatted}`;
                },
            },
        },
    },
    scales: {
        y: {
            ticks: {
                callback: (value: any) => {
                    return new Intl.NumberFormat('en-PH', {
                        style: 'currency',
                        currency: 'PHP',
                    }).format(value);
                },
            },
        },
    },
};
</script>

<template>
    <div class="w-full">
        <BarChart :chart-data="chartData" :chart-options="chartOptions" />
    </div>
</template>
