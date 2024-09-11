<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import VueApexCharts from 'vue3-apexcharts';

const props = defineProps({
    workout: {
        type: Object,
        required: true,
    },
});

let options = {
    chart: {
        type: "area",
        fontFamily: "Inter, sans-serif",
        dropShadow: {
            enabled: false,
        },
        toolbar: {
            show: true,
        },
        zoom: {
            enabled: true,
            type: 'x',
            autoScaleYaxis: false,
            zoomedArea: {
                fill: {
                    color: '#90CAF9',
                    opacity: 0.4
                },
                stroke: {
                    color: '#0D47A1',
                    opacity: 0.4,
                    width: 1
                }
            }
        },
        events: {
            mounted: function (chartContext, config) {
                const chartElement = document.getElementById("area-chart");
                chartElement.addEventListener('wheel', function (event) {
                    event.preventDefault();
                    const delta = Math.sign(event.deltaY);
                    const zoomLevel = chartContext.w.globals.zoomed ? chartContext.w.globals.zoomed : 1;
                    const newZoomLevel = zoomLevel + delta * 0.1;
                    chartContext.zoomX(newZoomLevel);
                });
            }
        }
    },
    tooltip: {
        enabled: true,
        x: {
            format: 'HH:mm'
        },
    },
    fill: {
        type: "gradient",
        gradient: {
            opacityFrom: 0.55,
            opacityTo: 0,
            shade: "#f21c1c",
            gradientToColors: ["#f21c1c"],
        },
    },
    dataLabels: {
        enabled: false,
    },
    stroke: {
        width: 2,
    },
    grid: {
        show: true,
        strokeDashArray: 4,
        padding: {
            left: 2,
            right: 2,
            top: 0
        },
    },
    series: [
        {
            name: "HR",
            data: props.workout.trackpoints_heart_rate,
            color: "#f21c1c",
        },
        {
            name: "Speed",
            data: props.workout.trackpoints_speed,
            color: "#1F51FF",
        },
    ],
    xaxis: {
        type: "datetime",
        categories: props.workout.labels,
        labels: {
            style: {
                fontFamily: "Inter, sans-serif",
                cssClass: 'text-xs font-normal fill-gray-500 dark:fill-gray-400'
            },
            format: 'HH:mm'
        },
        axisBorder: {
            show: false,
        },
        tickAmount: 3,
    },
    yaxis: [
        {
            seriesName: "HR",
            labels: {
                formatter: (value) => {
                    return Math.floor(value);
                },
            }
        },
        {
            opposite: true,
            seriesName: "Speed",
            labels: {
                formatter: (value) => {
                    return value.toFixed(1);
                },
            }
        },
    ]
};

if (document.getElementById("area-chart") && typeof ApexCharts !== 'undefined') {
    const chart = new ApexCharts(document.getElementById("area-chart"), options);
    chart.render();
}

const formatTime = (seconds) => {
    const hours = Math.floor(seconds / 3600);
    const minutes = Math.floor((seconds % 3600) / 60);
    const remainingSeconds = seconds % 60;
    return `${hours}:${minutes < 10? 0 : ''}${minutes}:${remainingSeconds < 10? 0 : ''}${remainingSeconds}`;
};

function formatDate(dateString) {
    const options = { weekday: 'short', month: 'short', day: 'numeric' };
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', options);
}

</script>

<template>

    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">{{ workout.name }}</h2>
        </template>

        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 pb-6">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="text-gray-900 dark:text-gray-100">
                        <div class="w-full bg-white rounded-lg shadow dark:bg-gray-800 p-4 md:p-6">
                            <dl
                                class="grid w-full grid-cols-2 gap-8 pt-2 pb-6 mx-auto text-gray-900 sm:grid-cols-5 dark:text-white">
                                <div class="flex flex-col items-center justify-center">
                                    <dt class="mb-2 text-3xl font-extrabold">{{ workout.distance }}<small class="text-base font-light"> km</small></dt>
                                    <dd class="text-gray-500 dark:text-gray-400">Distance</dd>
                                </div>
                                <div class="flex flex-col items-center justify-center">
                                    <dt class="mb-2 text-3xl font-extrabold">{{ formatTime(workout.ride_time) }}<small class="text-base font-light"> h</small></dt>
                                    <dd class="text-gray-500 dark:text-gray-400">Ride Time</dd>
                                </div>
                                <div class="flex flex-col items-center justify-center">
                                    <dt class="mb-2 text-3xl font-extrabold">{{ formatTime(workout.duration - workout.ride_time) }}<small class="text-base font-light"> h</small></dt>
                                    <dd class="text-gray-500 dark:text-gray-400">Pause Time</dd>
                                </div>
                                <div class="flex flex-col items-center justify-center">
                                    <dt class="mb-2 text-3xl font-extrabold">{{ workout.total_ascent }}<small class="text-base font-light"> m</small></dt>
                                    <dd class="text-gray-500 dark:text-gray-400">Ascent</dd>
                                </div>
                                <div class="flex flex-col items-center justify-center">
                                    <dt class="mb-2 text-3xl font-extrabold">{{ workout.total_descent }}<small class="text-base font-light"> m</small></dt>
                                    <dd class="text-gray-500 dark:text-gray-400">Descent</dd>
                                </div>
                                <div class="flex flex-col items-center justify-center">
                                    <dt class="mb-2 text-3xl font-extrabold">{{ workout.avg_speed }}<small class="text-base font-light"> km/h</small></dt>
                                    <dd class="text-gray-500 dark:text-gray-400">Avg Speed</dd>
                                </div>
                                <div class="flex flex-col items-center justify-center">
                                    <dt class="mb-2 text-3xl font-extrabold">{{ workout.max_speed }}<small class="text-base font-light"> km/h</small></dt>
                                    <dd class="text-gray-500 dark:text-gray-400">Max Speed</dd>
                                </div>
                                <div class="flex flex-col items-center justify-center">
                                    <dt class="mb-2 text-3xl font-extrabold">{{ workout.avg_hr }}<small class="text-base font-light"> bpm</small></dt>
                                    <dd class="text-gray-500 dark:text-gray-400">Avg HR</dd>
                                </div>
                                <div class="flex flex-col items-center justify-center">
                                    <dt class="mb-2 text-3xl font-extrabold">{{ workout.max_hr }}<small class="text-base font-light"> bpm</small></dt>
                                    <dd class="text-gray-500 dark:text-gray-400">Max HR</dd>
                                </div>
                                <div class="flex flex-col items-center justify-center">
                                    <dt class="mb-2 text-3xl font-extrabold">{{ formatDate(workout.date_gpx) }}</dt>
                                    <dd class="text-gray-500 dark:text-gray-400">Date</dd>
                                </div>
                            </dl>
                            <VueApexCharts id="area-chart" width="100%" height="300" type="area" :options="options"
                                :series="options.series" />
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </AuthenticatedLayout>
</template>
