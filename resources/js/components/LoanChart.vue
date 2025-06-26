<script setup lang="ts">
import BarChart from '@/components/ui/charts/BarChart.vue';

const props = defineProps({
    loanChart: Object,
});

const chartData = {
    labels: props?.loanChart?.labels,
    datasets: [
        {
            label: 'Loan',
            backgroundColor: 'pink',
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
