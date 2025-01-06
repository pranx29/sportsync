<template>
    <Card>
        <CardHeader>
            <CardTitle>{{ report.name }}</CardTitle>
        </CardHeader>
        <CardContent>
            <p class="text-sm text-muted-foreground mb-4">
                Period: {{ format(new Date(report.startDate), "P") }} -
                {{ format(new Date(report.endDate), "P") }}
            </p>
            <div class="h-[400px] w-full">
                <div v-if="report.data != null" class="h-full">
                    <Bar
                        v-if="report.chart == 'bar'"
                        :data="chartData"
                        :options="barChartOptions"
                    />
                    <Pie
                        v-else-if="report.chart == 'pie'"
                        :data="chartData"
                        :options="pieChartOptions"
                    />
                    <Line
                        v-else-if="report.chart == 'line'"
                        :data="chartData"
                        :options="lineChartOptions"
                    />
                </div>
                <div v-else>
                    <p class="text-center text-muted-foreground">
                        No data to display
                    </p>
                </div>
            </div>
        </CardContent>
    </Card>
</template>

<script setup>
import { ref, computed, watch } from "vue";
import { Card, CardContent, CardHeader, CardTitle } from "@/Components/ui/card";
import { Bar, Pie, Line } from "vue-chartjs";
import { format } from "date-fns";
import {
    Chart as ChartJS,
    Title,
    Tooltip,
    Legend,
    BarElement,
    CategoryScale,
    LinearScale,
    ArcElement,
    PointElement,
    LineElement,
} from "chart.js";

ChartJS.register(
    Title,
    Tooltip,
    Legend,
    BarElement,
    CategoryScale,
    LinearScale
);
ChartJS.register(ArcElement, Tooltip, Legend);
ChartJS.register(
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    Title,
    Tooltip,
    Legend
);

const props = defineProps({
    report: {
        type: Object,
        required: true,
    },
});

const report = ref({ ...props.report });

watch(
    () => props.report,
    (newReport) => {
        report.value = { ...newReport };
    }
);

const chartData = ref({
    labels: report.value.data.labels,
    datasets: [
        {
            label: report.value.data.label,
            data: [...report.value.data.datasets],
            backgroundColor: [
                "#338888",
                "#40b3b3",
                "#004d4d",
                "#1a8c8c",
                "#66cccc",
            ],
        },
    ],
});

const barChartOptions = ref({
    responsive: true,
    maintainAspectRatio: false,
    scales: {
        x: {
            grid: {
                display: false,
            },
        },
        y: {
            grid: {
                display: false,
            },
        },
    },
    plugins: {
        legend: {
            display: false,
        },
    },
    animation: {
        animateScale: true,
        animateRotate: true,
    },
});

const pieChartOptions = ref({
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            display: true,
            position: "right",
        },
    },
    animation: {
        animateScale: true,
        animateRotate: true,
    },
});

const lineChartOptions = ref({
    responsive: true,
    maintainAspectRatio: false,
    scales: {
        x: {
            grid: {
                display: false,
            },
        },
        y: {
            grid: {
                display: false,
            },
        },
    },
    animation: {
        animateScale: true,
        animateRotate: true,
    },
});
</script>
