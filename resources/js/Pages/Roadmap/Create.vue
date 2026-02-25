<template>
    <UserLayout title="Create Roadmap">
        <div class="space-y-5 print:space-y-0 max-w-7xl mx-auto">
            <section class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm dark:border-white/10 dark:bg-[#171717]">
                <div class="mb-4">
                    <Link href="/roadmap" class="text-sm text-indigo-600 dark:text-indigo-400 font-medium hover:underline mb-2 block">
                        &larr; Back to Roadmap List
                    </Link>
                    <h1 class="text-xl font-bold text-slate-900 dark:text-white">Create New Roadmap</h1>
                    <p class="text-sm text-slate-500 dark:text-slate-400">
                        Add a new roadmap entry.
                    </p>
                </div>

                <div class="max-w-2xl rounded-xl border border-slate-200 bg-slate-50 p-4 dark:border-white/10 dark:bg-[#111827]">
                    <h3 class="mb-2 text-sm font-semibold text-slate-800 dark:text-slate-100">
                        Milestone Type (Backend Defined)
                    </h3>
                    <p class="mb-3 text-xs text-slate-500 dark:text-slate-400">
                        Simpan ke database pakai angka 1-5.
                    </p>

                    <div class="grid grid-cols-2 gap-2 sm:grid-cols-5">
                        <div
                            v-for="option in milestoneTypeOptionsDisplay"
                            :key="`create-type-${option.value}`"
                            class="rounded-md border border-slate-200 bg-white px-3 py-2 text-center text-xs font-semibold text-slate-700 dark:border-white/10 dark:bg-[#1f2937] dark:text-slate-200"
                        >
                            {{ option.label }}
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </UserLayout>
</template>

<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import UserLayout from '@/Layouts/UserLayout.vue';

const props = defineProps({
    milestoneTypeOptions: {
        type: Array,
        default: () => [],
    },
});

const milestoneTypeOptionsDisplay = computed(() => {
    if (Array.isArray(props.milestoneTypeOptions) && props.milestoneTypeOptions.length > 0) {
        return props.milestoneTypeOptions;
    }

    return [1, 2, 3, 4, 5].map((value) => ({
        value,
        label: String(value),
    }));
});
</script>
