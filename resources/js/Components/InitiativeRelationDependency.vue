<template>
    <section
        class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-white/10 dark:bg-[#171717]"
    >
        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-200 px-5 py-4 dark:border-white/10">
            <h2 class="text-base font-semibold text-slate-900 dark:text-white">Initiative Relation Dependency</h2>

            <div class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                <label for="initiative-type-filter" class="text-[11px]">Filter Type</label>
                <select
                    id="initiative-type-filter"
                    v-model="selectedType"
                    class="rounded-lg border border-slate-200 bg-white px-2.5 py-1 text-xs font-semibold text-slate-700 shadow-sm transition focus:border-slate-300 focus:outline-none dark:border-white/10 dark:bg-[#1f1f1f] dark:text-slate-200"
                >
                    <option value="all">All</option>
                    <option value="digital">Digital</option>
                    <option value="it">IT</option>
                </select>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[720px] border-collapse text-sm">
                <thead class="bg-slate-50 dark:bg-white/5">
                    <tr>
                        <th
                            class="w-10 border border-slate-200 px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider text-slate-500 dark:border-white/10 dark:text-slate-400"
                        >
                            Code
                        </th>
                        <th
                            class="border border-slate-200 px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500 dark:border-white/10 dark:text-slate-400"
                        >
                            Name
                        </th>
                        <th
                            class="w-64 border border-slate-200 px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500 dark:border-white/10 dark:text-slate-400"
                        >
                            Source
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-if="!filteredInitiatives.length">
                        <td
                            colspan="3"
                            class="border border-slate-200 px-4 py-6 text-center text-sm text-slate-500 dark:border-white/10 dark:text-slate-400"
                        >
                            Belum ada data initiative.
                        </td>
                    </tr>
                    <tr v-for="initiative in filteredInitiatives" :key="initiative.id">
                        <td class="border border-slate-200 px-4 py-3 text-center text-sm text-slate-800 dark:border-white/10 dark:text-slate-200">
                            {{ initiative.code ?? '-' }}
                        </td>
                        <td class="border border-slate-200 px-4 py-3 text-sm text-slate-800 dark:border-white/10 dark:text-slate-200">
                            {{ initiative.name ?? '-' }}
                        </td>
                        <td class="border border-slate-200 px-4 py-3 text-sm text-slate-800 dark:border-white/10 dark:text-slate-200">
                            {{ initiative.status ?? '-' }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>
</template>

<script setup>
import { computed, ref } from 'vue';

const props = defineProps({
    mstInitiatives: {
        type: Array,
        default: () => [],
    },
});

const selectedType = ref('all');

const filteredInitiatives = computed(() => {
    if (selectedType.value === 'all') {
        return props.mstInitiatives;
    }

    const expectedType = selectedType.value === 'digital' ? 1 : 2;
    return props.mstInitiatives.filter((initiative) => Number(initiative.tipe_initiative) === expectedType);
});
</script>
