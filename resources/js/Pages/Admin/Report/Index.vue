<template>
    <AdminLayout>
        <template #title> Reports </template>
        <main
            class="grid items-center gap-4 p-4 sm:px-6 sm:py-0 md:gap-8 flex-1 min-h-screen"
        >
            <div class="flex justify-between">
                <div class="grid gap-4 sm:grid-cols-2">
                    <div class="space-y-2">
                        <label class="text-sm font-medium">Report Type</label>
                        <Select v-model="reportType">
                            <SelectTrigger>
                                <SelectValue placeholder="Select report type" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem
                                    v-for="type in reportTypes"
                                    :key="type.value"
                                    :value="type.value"
                                    :data-cy="`report-type-${type.value}`"
                                >
                                    {{ type.label }}
                                </SelectItem>
                            </SelectContent>
                        </Select>
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-medium">Date Range</label>
                        <div :class="cn('grid gap-2', $attrs.class ?? '')">
                            <Popover>
                                <PopoverTrigger as-child>
                                    <Button
                                        id="date"
                                        :variant="'outline'"
                                        :class="
                                            cn(
                                                'w-[280px] justify-start text-left font-normal h-10',
                                                !date && 'text-muted-foreground'
                                            )
                                        "
                                    >
                                        <CalendarIcon class="mr-2 h-4 w-4" />

                                        <span>
                                            {{
                                                date.start
                                                    ? date.end
                                                        ? `${format(
                                                              date.start,
                                                              "LLL dd, y"
                                                          )} - ${format(
                                                              date.end,
                                                              "LLL dd, y"
                                                          )}`
                                                        : format(
                                                              date.start,
                                                              "LLL dd, y"
                                                          )
                                                    : "Pick a date"
                                            }}
                                        </span>
                                    </Button>
                                </PopoverTrigger>
                                <PopoverContent
                                    class="w-auto p-0"
                                    align="start"
                                >
                                    <Calendar
                                        v-model.range="date"
                                        :columns="2"
                                    />
                                </PopoverContent>
                            </Popover>
                        </div>
                    </div>
                </div>

                <div class="space-x-4">
                    <Button
                        @click="handleGenerateReport"
                        :disabled="!reportType"
                        class="self-end"
                        >Generate Report</Button
                    >
                    <Button
                        v-if="generatedReport"
                        @click="handleDownloadReport"
                        :disabled="!generatedReport"
                        class="self-end"
                        variant="secondary"
                    >
                        Download Report
                    </Button>
                </div>
            </div>
            <div class="min-h-screen gap-4 overflow-x-auto">
                <ReportDisplay
                    id="report"
                    v-if="generatedReport"
                    :report="generatedReport"
                />
            </div>
        </main>
    </AdminLayout>
</template>

<script setup>
import { ref } from "vue";
import { Button } from "@/Components/ui/button";
import {
    Card,
    CardContent,
    CardFooter,
    CardHeader,
    CardTitle,
} from "@/Components/ui/card";
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from "@/Components/ui/select";
import ReportDisplay from "./ReportDisplay.vue";
import {
    Popover,
    PopoverContent,
    PopoverTrigger,
} from "@/Components/ui/popover";
import { Calendar } from "@/Components/ui/v-calendar";
import { addDays, format, subDays } from "date-fns";
import { Calendar as CalendarIcon } from "lucide-vue-next";
import { cn } from "@/lib/utils";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { router } from "@inertiajs/vue3";
import jsPDF from "jspdf";
import html2canvas from "html2canvas";

const reportTypes = [
    { value: "popular-sports", label: "Popular Sports", chart: "pie" },
    {
        value: "active-groups",
        label: "Active Groups",
        chart: "bar",
    },
];

const reportType = ref("");

const date = ref({
    start: subDays(new Date(), 7),
    end: addDays(new Date(), 1),
});

const generatedReport = ref(null);

const handleGenerateReport = () => {
    generatedReport.value = null;
    router.get(
        route("admin.reports.index"),
        {
            reportType: reportType.value,
            startDate: date.value.start.toISOString(),
            endDate: date.value.end.toISOString(),
        },
        {
            preserveState: true,
            onSuccess: (page) => {
                generatedReport.value = {
                    chart: reportTypes.find(
                        (chart) => chart.value === reportType.value
                    ).chart,
                    name: reportTypes.find(
                        (type) => type.value === reportType.value
                    ).label,
                    startDate: date.value.start,
                    endDate: date.value.end,
                    data: page.props.reportData,
                };
            },
        }
    );
};

const handleDownloadReport = async () => {
    if (!generatedReport.value) return;

    const reportElement = document.querySelector("#report");

    try {
        const canvas = await html2canvas(reportElement, {
            scale: 2,
        });
        const imgData = canvas.toDataURL("image/png");
        const pdf = new jsPDF("p", "mm", "a4");

        const imgProps = pdf.getImageProperties(imgData);
        const pdfWidth = pdf.internal.pageSize.getWidth();
        const pdfHeight = (imgProps.height * pdfWidth) / imgProps.width;

        pdf.addImage(imgData, "PNG", 0, 0, pdfWidth, pdfHeight);
        pdf.save(`${generatedReport.value.name}.pdf`);
    } catch (error) {
        console.error("Error generating PDF:", error);
    }
};
</script>
