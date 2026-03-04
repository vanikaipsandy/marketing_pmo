<template>
    <div class="space-y-3">
        <!-- Tabel 1: Code & Status Implementasi -->
        <div class="overflow-x-auto rounded-lg border border-slate-100 dark:border-white/5">
            <table class="w-full table-fixed divide-y divide-slate-200 text-[11px] dark:divide-white/5">
                <colgroup>
                    <col class="w-[15%]">
                    <col class="w-[25%]">
                    <col class="w-[60%]">
                </colgroup>
                <thead class="bg-slate-50 dark:bg-white/[0.03]">
                    <tr>
                        <th
                            class="px-2 py-1.5 text-left text-[9px] font-semibold uppercase tracking-wider text-slate-400">
                            Code</th>
                        <th
                            class="px-2 py-1.5 text-left text-[9px] font-semibold uppercase tracking-wider text-slate-400">
                            Review Status</th>
                        <th
                            class="px-2 py-1.5 text-left text-[9px] font-semibold uppercase tracking-wider text-slate-400">
                            Keterangan</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-[#1a1a1a]">
                    <tr v-for="proj in projectList" :key="`t2-${proj.id}`">
                        <td class="px-2 py-2 text-[11px] font-semibold text-slate-700 dark:text-slate-200">
                            {{ proj.code || '-' }}
                        </td>
                        <td class="px-2 py-2">
                            <span v-if="getLatestReviewStatus(proj)"
                                class="inline-flex rounded-md px-1.5 py-0.5 text-[9px] font-medium"
                                :class="reviewStatusBadgeClass(getLatestReviewStatus(proj))">
                                {{ getLatestReviewStatus(proj) }}
                            </span>
                            <span v-else class="text-[10px] italic text-slate-400">-</span>
                        </td>
                        <td class="px-2 py-2">
                            <span v-if="getLatestImplementationStatus(proj)"
                                class="inline-flex rounded-md bg-slate-100 px-1.5 py-0.5 text-[9px] font-medium text-slate-700 dark:bg-white/10 dark:text-slate-300">
                                {{ getLatestImplementationStatus(proj) }}
                            </span>
                            <span v-else class="text-[10px] italic text-slate-400">-</span>
                        </td>
                    </tr>
                    <tr v-if="projectList.length === 0">
                        <td colspan="3" class="px-4 py-6 text-center text-xs text-slate-500 dark:text-slate-400">
                            Data status implementasi belum tersedia.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Tabel 2: Informasi Dasar & Timeline -->
        <div class="overflow-x-auto rounded-lg border border-slate-100 dark:border-white/5">
            <table class="w-full table-fixed divide-y divide-slate-200 text-[11px] dark:divide-white/5">
                <colgroup>
                    <col class="w-[20%]">
                    <col class="w-[20%]">
                    <col class="w-[20%]">
                    <col class="w-[20%]">
                    <col class="w-[20%]">
                </colgroup>
                <thead class="bg-slate-50 dark:bg-white/[0.03]">
                    <tr>
                        <th
                            class="px-2 py-1.5 text-left text-[9px] font-semibold uppercase tracking-wider text-slate-400">
                            Version</th>
                        <th
                            class="px-2 py-1.5 text-left text-[9px] font-semibold uppercase tracking-wider text-slate-400">
                            Building Blok</th>
                        <th
                            class="px-2 py-1.5 text-left text-[9px] font-semibold uppercase tracking-wider text-slate-400">
                            Inisiatif</th>
                        <th
                            class="px-2 py-1.5 text-left text-[9px] font-semibold uppercase tracking-wider text-slate-400">
                            Status Timeline</th>
                        <th
                            class="px-2 py-1.5 text-left text-[9px] font-semibold uppercase tracking-wider text-slate-400">
                            Bulan/Tahun</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-[#1a1a1a]">
                    <tr v-for="row in projectCharterRows" :key="row.key">
                        <td class="px-2 py-2 text-[11px] font-semibold text-slate-700 dark:text-slate-200">
                            {{ row.versionLabel }}
                        </td>
                        <td class="px-2 py-2 text-[11px] text-slate-700 dark:text-slate-200">
                            <span
                                class="inline-flex rounded-full bg-blue-100 px-2 py-0.5 text-[9px] font-semibold text-blue-800 dark:bg-blue-500/20 dark:text-blue-300">
                                {{ getArchitectureBlockLabel(row.charter) }}
                            </span>
                        </td>
                        <td class="px-2 py-2 text-[11px] font-medium text-slate-700 break-words dark:text-slate-200">
                            {{ row.project?.name || '-' }}
                        </td>
                        <td class="px-2 py-2">
                            <span
                                class="inline-flex items-center rounded-full px-2 py-0.5 text-[9px] font-medium capitalize"
                                :class="statusBadgeClassById(row.project?.status)">
                                {{ statusLabelFromOptions(row.project?.status, statusOptions) }}
                            </span>
                        </td>
                        <td class="px-2 py-2 text-[10px] font-medium text-slate-600 dark:text-slate-300">
                            {{ getLatestImplementationMonthYear(row.project) || '-' }}
                        </td>
                    </tr>
                    <tr v-if="projectCharterRows.length === 0">
                        <td colspan="5" class="px-4 py-6 text-center text-xs text-slate-500 dark:text-slate-400">
                            Data belum tersedia.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue';
import { statusBadgeClassById, statusLabelFromOptions } from '@/Composables/initiativeStatus';

const props = defineProps({
    project: {
        type: Object,
        default: null,
    },
    projects: {
        type: Array,
        default: () => [],
    },
});

// Normalize to array of projects
const projectList = computed(() => {
    if (Array.isArray(props.projects) && props.projects.length > 0) {
        return props.projects;
    }
    if (props.project) {
        return [props.project];
    }
    return [];
});

const statusOptions = computed(() => {
    const defaultOptions = [
        { id: 1, name: 'drafting', label: 'Drafting' },
        { id: 2, name: 'propose', label: 'Propose' },
        { id: 3, name: 'review', label: 'Review' },
        { id: 4, name: 'approved', label: 'Approved' },
        { id: 5, name: 'baseline', label: 'Baseline' },
    ];

    if (!props.projects?.[0]?.status_ref?.name && !props.project?.status_ref?.name) {
        return defaultOptions;
    }

    const currentProject = props.project || props.projects?.[0];
    return defaultOptions.map((option) =>
        option.id === Number(currentProject?.status)
            ? {
                ...option,
                name: currentProject.status_ref.name,
                label: String(currentProject.status_ref.name)
                    .replace(/_/g, ' ')
                    .replace(/\b\w/g, (char) => char.toUpperCase()),
            }
            : option
    );
});

const projectCharterRows = computed(() => {
    return projectList.value.flatMap((project, projectIndex) => {
        const charters = Array.isArray(project?.charters) && project.charters.length > 0
            ? project.charters
            : project?.charter
                ? [project.charter]
                : [];

        if (charters.length === 0) {
            return [{
                key: `proj-${project?.id ?? projectIndex}-charter-empty`,
                project,
                charter: null,
                versionLabel: '-',
            }];
        }

        return charters.map((charter, charterIndex) => ({
            key: `proj-${project?.id ?? projectIndex}-charter-${charter?.id ?? charterIndex}`,
            project,
            charter,
            versionLabel: getCharterVersionLabel(charter, charterIndex, charters.length),
        }));
    });
});

// Helper functions for each project row
const getCharterVersionLabel = (charter, index, total) => {
    const label = String(charter?.resolved_version_label ?? charter?.version_label ?? '').trim();
    if (label) return label;
    if (Number.isFinite(total) && total > 0) {
        return `v${total - index}`;
    }
    return '-';
};

const getArchitectureBlockLabel = (charter) => {
    const text = String(charter?.category ?? '').trim();
    return text.length > 0 ? text : '-';
};

const getImplementationHistory = (project) => {
    const source = project?.pc_status_implementations ?? project?.pcStatusImplementations ?? [];
    return Array.isArray(source) ? source : [];
};

const getLatestImplementationLog = (project) => {
    const history = getImplementationHistory(project);
    return history.length > 0 ? history[0] : null;
};

const getLatestReviewStatus = (project) => {
    const log = getLatestImplementationLog(project);
    const raw = String(log?.review_status ?? '').trim();
    return raw.length > 0 ? raw : null;
};

const getLatestImplementationStatus = (project) => {
    const log = getLatestImplementationLog(project);
    const raw = String(log?.status ?? '').trim();
    return raw.length > 0 ? raw : null;
};

const getLatestImplementationMonthYear = (project) => {
    const log = getLatestImplementationLog(project);
    const rawDate = log?.date ?? null;
    if (!rawDate) return null;
    const date = new Date(rawDate);
    if (Number.isNaN(date.getTime())) return null;
    return date.toLocaleDateString('en-US', { year: 'numeric', month: 'long' });
};

const reviewStatusBadgeClass = (reviewStatus) => {
    const normalized = String(reviewStatus ?? '').trim().toLowerCase();
    if (normalized === 'on track') return 'bg-emerald-100 text-emerald-800 dark:bg-emerald-500/20 dark:text-emerald-300';
    if (normalized === 'at risk') return 'bg-amber-100 text-amber-800 dark:bg-amber-500/20 dark:text-amber-300';
    if (normalized === 'not started') return 'bg-slate-100 text-slate-700 dark:bg-white/10 dark:text-slate-300';
    if (normalized === 'not signed') return 'bg-rose-100 text-rose-800 dark:bg-rose-500/20 dark:text-rose-300';
    return 'bg-slate-100 text-slate-700 dark:bg-white/10 dark:text-slate-300';
};
</script>
