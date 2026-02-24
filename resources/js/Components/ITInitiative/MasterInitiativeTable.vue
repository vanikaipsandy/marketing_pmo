<template>
    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white dark:border-white/5 dark:bg-[#1a1a1a]">
        <div class="border-b border-slate-200 px-4 py-3 dark:border-white/10">
            <p class="text-xs font-semibold uppercase tracking-[0.08em] text-slate-500 dark:text-slate-400">
                Data Sumber `mst_initiative` (tipe_initiative = 2)
            </p>
        </div>

        <div class="overflow-x-hidden">
            <table class="w-full table-fixed divide-y divide-slate-200 text-[11px] dark:divide-white/5">
                <colgroup>
                    <col class="w-[7%]">
                    <col class="w-[10%]">
                    <col class="w-[30%]">
                    <col class="w-[13%]">
                    <col class="w-[13%]">
                    <col class="w-[27%]">
                </colgroup>
                <thead class="bg-slate-50 dark:bg-white/5">
                    <tr>
                        <th class="px-3 py-2 text-left text-[10px] font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">No</th>
                        <th class="px-3 py-2 text-left text-[10px] font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Code</th>
                        <th class="px-3 py-2 text-left text-[10px] font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Nama Inisiatif</th>
                        <th class="px-3 py-2 text-left text-[10px] font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">COE</th>
                        <th class="px-3 py-2 text-left text-[10px] font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Business Unit</th>
                        <th class="px-3 py-2 text-left text-[10px] font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white dark:divide-white/5 dark:bg-[#1a1a1a]">
                    <tr v-for="(item, index) in items" :key="`mst-it-${item.id}`" class="transition-colors hover:bg-slate-50 dark:hover:bg-white/5">
                        <td class="px-3 py-3 text-[11px] font-medium text-slate-600 dark:text-slate-400">{{ index + 1 }}</td>
                        <td class="px-3 py-3 text-[11px] font-medium text-slate-700 dark:text-slate-200">{{ item.code ?? '-' }}</td>
                        <td class="px-3 py-3 text-[11px] text-slate-700 dark:text-slate-200">
                            <span class="font-medium break-words">{{ item.name ?? '-' }}</span>
                        </td>
                        <td class="px-3 py-3 text-[11px] text-slate-700 dark:text-slate-200">{{ item.coe_id ?? '-' }}</td>
                        <td class="px-3 py-3 text-[11px] text-slate-700 dark:text-slate-200">{{ item.business_unit ?? '-' }}</td>
                        <td class="px-3 py-3 text-[11px] text-slate-700 dark:text-slate-200">
                            <span
                                class="inline-flex items-center rounded-full bg-slate-100 px-2 py-0.5 text-[10px] font-medium text-slate-700 dark:bg-white/10 dark:text-slate-300"
                                :title="item.status || '-'"
                            >
                                {{ item.status || '-' }}
                            </span>
                        </td>
                    </tr>

                    <tr v-if="items.length === 0">
                        <td colspan="6" class="px-6 py-8 text-center text-xs text-slate-500 dark:text-slate-400">
                            Tidak ada data `mst_initiative` tipe 2.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script setup>
defineProps({
    items: {
        type: Array,
        default: () => [],
    },
});
</script>
