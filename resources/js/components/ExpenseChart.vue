<script setup lang="ts">
import BarChart from '@/components/ui/charts/BarChart.vue';

const props = defineProps({
    expenseChart: Object,
});

const chartData = {
    labels: props?.expenseChart?.labels,
    datasets: [
        {
            label: 'Expense',
            backgroundColor: 'violet',
            data: props?.expenseChart?.data,
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
