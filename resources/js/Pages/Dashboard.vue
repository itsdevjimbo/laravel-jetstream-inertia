<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-white leading-tight">
                Dashboard
            </h2>
        </template>

        <div class="py-4">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        Welcome to Dashboard
                    </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
// import BreezeAuthenticatedLayout from '@/Layouts/Authenticated'
import AppLayout from "@/Layouts/Backend/Default";

export default {
    components: {
        AppLayout,
    },
};
</script>
