<template>
    <article class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-white/10 dark:bg-[#171717]">
        <div class="flex items-center justify-between gap-3 border-b border-slate-200 px-5 py-4 dark:border-white/10">
            <div>
                <h2 class="text-base font-semibold text-slate-900 dark:text-white">{{ charterLabel }} IT Initiatives</h2>
            </div>
            <Link
                :href="`/it-initiatives?status=${completedStatusId}`"
                class="text-xs font-semibold text-indigo-600 hover:text-indigo-700 dark:text-indigo-400 dark:hover:text-indigo-300"
            >
                Lihat {{ completedStatusLabel }}
            </Link>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[920px] divide-y divide-slate-200 dark:divide-white/5">
                <thead class="bg-slate-50 dark:bg-white/5">
                    <tr>
                        <th scope="col" class="w-10 whitespace-nowrap px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500 dark:text-slate-400">No</th>
                        <th scope="col" class="w-16 whitespace-nowrap px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500 dark:text-slate-400">Code</th>
                        <th scope="col" class="whitespace-nowrap px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500 dark:text-slate-400">IT Arsitektur Building Blok</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500 dark:text-slate-400">Daftar Inisiatif</th>
                        <th scope="col" class="whitespace-nowrap px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500 dark:text-slate-400">State</th>
                        <th scope="col" class="w-1/3 px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500 dark:text-slate-400">Status Terbaru</th>
                        <th scope="col" class="whitespace-nowrap px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-slate-500 dark:text-slate-400">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white dark:divide-white/5 dark:bg-[#1a1a1a]">
                    <template v-for="(item, index) in items" :key="`it-open-${item.id}`">
                        <tr class="group transition-colors hover:bg-slate-50 dark:hover:bg-white/5">
                            <td class="whitespace-nowrap px-6 py-4 text-xs font-medium text-slate-600 dark:text-slate-400">{{ index + 1 }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-xs font-medium text-slate-600 dark:text-slate-400">{{ item.code || '-' }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-xs text-slate-700 dark:text-slate-200">
                                <span class="inline-flex rounded-full bg-blue-100 px-2 py-0.5 text-[10px] font-semibold text-blue-800 dark:bg-blue-500/20 dark:text-blue-300">
                                    {{ item.charter?.category || 'Uncategorized' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-xs text-slate-700 dark:text-slate-200">
                                <span class="font-medium text-slate-700 dark:text-slate-200">{{ item.name || '-' }}</span>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4">
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-[10px] font-medium capitalize" :class="statusBadgeClassById(item.status)">
                                    {{ statusLabelFromOptions(item.status, statusOptions) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-xs text-slate-700 dark:text-slate-200">
                                <span
                                    v-if="latestItStatus(item)"
                                    class="inline-flex items-center rounded-md bg-slate-100 px-2 py-1 text-[11px] font-medium text-slate-700 capitalize dark:bg-white/10 dark:text-slate-300 break-words whitespace-normal leading-relaxed"
                                    :title="latestItStatus(item)"
                                >
                                    {{ latestItStatus(item) }}
                                </span>
                                <span v-else class="text-slate-400 dark:text-slate-500 italic text-xs">-</span>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <Link
                                        :href="`/it-initiatives/${item.id}`"
                                        class="rounded-lg p-1.5 text-slate-400 transition-colors hover:bg-slate-100 hover:text-indigo-600 dark:text-slate-500 dark:hover:bg-white/5 dark:hover:text-indigo-400"
                                        title="View"
                                    >
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </Link>
                                    <Link
                                        :href="`/it-initiatives/${item.id}/edit`"
                                        class="rounded-lg p-1.5 text-slate-400 transition-colors hover:bg-slate-100 hover:text-amber-600 dark:text-slate-500 dark:hover:bg-white/5 dark:hover:text-amber-400"
                                        title="Edit"
                                    >
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                    </Link>
                                </div>
                            </td>
                        </tr>
                    </template>

                    <tr v-if="items.length === 0">
                        <td colspan="7" class="px-6 py-8 text-center text-xs text-slate-500 dark:text-slate-400">
                            Semua IT initiatives sudah {{ lowerCompletedStatusLabel }}.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </article>
</template>

<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import { statusBadgeClassById, statusLabelFromOptions } from '@/Composables/initiativeStatus';

const props = defineProps({
    items: {
        type: Array,
        default: () => [],
    },
    completedStatusId: {
        type: Number,
        default: 5,
    },
    completedStatusLabel: {
        type: String,
        default: 'Baseline',
    },
    statusOptions: {
        type: Array,
        default: () => [],
    },
    charterLabel: {
        type: String,
        default: 'Scope Charter',
    },
});

const lowerCompletedStatusLabel = computed(() => String(props.completedStatusLabel || '').toLowerCase());

const latestItStatus = (item) => {
    const rawStatus = item?.latest_implementation_status
        ?? item?.latestImplementationStatus
        ?? item?.latest_pc_status_implementation?.status
        ?? item?.latestPcStatusImplementation?.status
        ?? item?.pc_status_implementations?.[0]?.status
        ?? item?.pcStatusImplementations?.[0]?.status
        ?? null;

    const normalizedStatus = String(rawStatus ?? '').trim();

    return normalizedStatus.length > 0 ? normalizedStatus : null;
};
</script>
