<script setup>
import { computed } from 'vue';
import { statusBadgeClassById, statusLabelFromOptions } from '@/Composables/initiativeStatus';

const props = defineProps({
    project: {
        type: Object,
        default: null,
    },
});

const statusOptions = computed(() => {
    const defaultOptions = [
        { id: 1, name: 'drafting', label: 'Drafting' },
        { id: 2, name: 'propose', label: 'Propose' },
        { id: 3, name: 'review', label: 'Review' },
        { id: 4, name: 'approve', label: 'Approve' },
        { id: 5, name: 'baseline', label: 'Baseline' },
    ];

    if (!props.project?.status_ref?.name) {
        return defaultOptions;
    }

    return defaultOptions.map((option) =>
        option.id === Number(props.project?.status)
            ? {
                ...option,
                name: props.project.status_ref.name,
                label: String(props.project.status_ref.name)
                    .replace(/_/g, ' ')
                    .replace(/\b\w/g, (char) => char.toUpperCase()),
            }
            : option
    );
});

const architectureBlockLabel = computed(() => {
    const text = String(props.project?.charter?.category ?? '').trim();
    return text.length > 0 ? text : '-';
});

const implementationHistory = computed(() => {
    const source = props.project?.pc_status_implementations ?? props.project?.pcStatusImplementations ?? [];
    return Array.isArray(source) ? source : [];
});

const latestImplementationLog = computed(() => (
    implementationHistory.value.length > 0 ? implementationHistory.value[0] : null
));

const latestReviewStatus = computed(() => {
    const raw = String(latestImplementationLog.value?.review_status ?? '').trim();
    return raw.length > 0 ? raw : null;
});

const latestImplementationStatus = computed(() => {
    const raw = String(latestImplementationLog.value?.status ?? '').trim();
    return raw.length > 0 ? raw : null;
});

const latestImplementationMonthYear = computed(() => {
    const rawDate = latestImplementationLog.value?.date ?? null;
    if (!rawDate) return null;
    const date = new Date(rawDate);
    if (Number.isNaN(date.getTime())) return null;
    return date.toLocaleDateString('en-US', { year: 'numeric', month: 'long' });
});

const reviewStatusBadgeClass = (reviewStatus) => {
    const normalized = String(reviewStatus ?? '').trim().toLowerCase();
    if (normalized === 'on track') return 'bg-emerald-100 text-emerald-800 dark:bg-emerald-500/20 dark:text-emerald-300';
    if (normalized === 'at risk') return 'bg-amber-100 text-amber-800 dark:bg-amber-500/20 dark:text-amber-300';
    if (normalized === 'not started') return 'bg-slate-100 text-slate-700 dark:bg-white/10 dark:text-slate-300';
    if (normalized === 'not signed') return 'bg-rose-100 text-rose-800 dark:bg-rose-500/20 dark:text-rose-300';
    return 'bg-slate-100 text-slate-700 dark:bg-white/10 dark:text-slate-300';
};
</script>

<template>
    <div class="overflow-x-auto rounded-lg border border-slate-100 dark:border-white/5">
        <table class="w-full table-fixed divide-y divide-slate-200 text-[11px] dark:divide-white/5">
            <colgroup>
                <col class="w-[8%]">
                <col class="w-[14%]">
                <col class="w-[18%]">
                <col class="w-[10%]">
                <col class="w-[12%]">
                <col class="w-[12%]">
                <col class="w-[26%]">
            </colgroup>
            <thead class="bg-slate-50 dark:bg-white/[0.03]">
                <tr>
                    <th class="px-2 py-1.5 text-left text-[9px] font-semibold uppercase tracking-wider text-slate-400">Code</th>
                    <th class="px-2 py-1.5 text-left text-[9px] font-semibold uppercase tracking-wider text-slate-400">Building Blok</th>
                    <th class="px-2 py-1.5 text-left text-[9px] font-semibold uppercase tracking-wider text-slate-400">Inisiatif</th>
                    <th class="px-2 py-1.5 text-left text-[9px] font-semibold uppercase tracking-wider text-slate-400">Status</th>
                    <th class="px-2 py-1.5 text-left text-[9px] font-semibold uppercase tracking-wider text-slate-400">Bulan/Tahun</th>
                    <th class="px-2 py-1.5 text-left text-[9px] font-semibold uppercase tracking-wider text-slate-400">Review</th>
                    <th class="px-2 py-1.5 text-left text-[9px] font-semibold uppercase tracking-wider text-slate-400">Implementasi</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-[#1a1a1a]">
                <tr v-if="project">
                    <td class="px-2 py-2 text-[11px] font-semibold text-slate-700 dark:text-slate-200">{{ project.code || '-' }}</td>
                    <td class="px-2 py-2">
                        <span class="inline-flex rounded-full bg-blue-100 px-2 py-0.5 text-[9px] font-semibold text-blue-800 dark:bg-blue-500/20 dark:text-blue-300">
                            {{ architectureBlockLabel }}
                        </span>
                    </td>
                    <td class="px-2 py-2 text-[11px] font-medium text-slate-700 break-words dark:text-slate-200">{{ project.name || '-' }}</td>
                    <td class="px-2 py-2">
                        <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[9px] font-medium capitalize" :class="statusBadgeClassById(project.status)">
                            {{ statusLabelFromOptions(project.status, statusOptions) }}
                        </span>
                    </td>
                    <td class="px-2 py-2 text-[10px] font-medium text-slate-600 dark:text-slate-300">{{ latestImplementationMonthYear || '-' }}</td>
                    <td class="px-2 py-2">
                        <span v-if="latestReviewStatus" class="inline-flex rounded-md px-1.5 py-0.5 text-[9px] font-medium" :class="reviewStatusBadgeClass(latestReviewStatus)">{{ latestReviewStatus }}</span>
                        <span v-else class="text-[10px] italic text-slate-400">-</span>
                    </td>
                    <td class="px-2 py-2">
                        <span v-if="latestImplementationStatus" class="inline-flex rounded-md bg-slate-100 px-1.5 py-0.5 text-[9px] font-medium text-slate-700 dark:bg-white/10 dark:text-slate-300">{{ latestImplementationStatus }}</span>
                        <span v-else class="text-[10px] italic text-slate-400">-</span>
                    </td>
                </tr>
                <tr v-else>
                    <td colspan="7" class="px-4 py-6 text-center text-xs text-slate-500 dark:text-slate-400">
                        Data status implementasi belum tersedia untuk initiative ini.
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
