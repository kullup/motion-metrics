<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    workout: {
        type: Array,
    },
});

</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">{{ workout.name }}</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div v-for="trackpoint in workout.trackpoints_heart_rate" :key="workout.id"
                    class="text-gray-900 dark:text-gray-100">{{ trackpoint }}</div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
