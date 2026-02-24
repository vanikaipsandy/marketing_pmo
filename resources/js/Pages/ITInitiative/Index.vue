<template>
    <UserLayout title="IT Initiatives">
        <div class="animate-fade-in">
            <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-slate-900 dark:text-white">IT Initiatives</h2>
                </div>
            </div>

            <section class="mb-6 grid grid-cols-1 gap-4 lg:grid-cols-3">
                <article
                    class="relative flex flex-col justify-center rounded-2xl border p-5 shadow-[0_4px_16px_rgba(167,201,66,0.3)] bg-[#A7C942] border-[#A7C942]"
                >
                    <p
                        class="text-xs font-semibold uppercase tracking-[0.08em] text-white"
                        style="text-shadow: 0 1px 3px rgba(0,0,0,0.3);"
                    >
                        Total IT Inisiatif 
                    </p>
                    <p
                        class="mt-2 flex items-center justify-between text-3xl font-bold text-white"
                        style="text-shadow: 0 2px 6px rgba(0,0,0,0.35);"
                    >
                        <span>{{ totalApproved }}</span>
                    </p>
                </article>

                <article class="flex flex-col justify-center rounded-2xl border border-slate-200 bg-white px-5 py-3 shadow-[0_4px_16px_rgba(0,0,0,0.05)] dark:border-white/10 dark:bg-[#171717] lg:col-span-2 space-y-4">
                    <div>
                        <div class="mb-2 flex items-center justify-between gap-2">
                            <h2 class="text-[10px] font-semibold uppercase tracking-[0.08em] text-slate-500 dark:text-slate-400">Scope Charter IT Initiative Timeline</h2>
                        </div>

                        <div>
                            <div
                                class="grid"
                                :style="gridStyle(scopeSteps)"
                            >
                                <div
                                    v-for="(step, index) in scopeSteps"
                                    :key="`scope-step-${step.key}`"
                                    class="relative flex justify-center cursor-pointer group"
                                    @click="toggleFilter(step.statusId)"
                                >
                                    <span
                                        class="inline-flex h-6 w-6 shrink-0 items-center justify-center rounded-full border text-[10px] font-bold transition-all group-hover:ring-2 group-hover:ring-offset-1 group-hover:ring-slate-300"
                                        :class="[
                                            step.circleClass,
                                            activeFlowFilter === step.statusId ? 'ring-2 ring-offset-2 ring-blue-500 shadow-md transform scale-110' : ''
                                        ]"
                                    >
                                        {{ step.count }}
                                    </span>
                                    <span
                                        v-if="index < scopeSteps.length - 1"
                                        class="absolute left-1/2 top-1/2 ml-[0.75rem] h-0.5 w-[calc(100%_-_1.5rem)] -translate-y-1/2 rounded-full"
                                        :class="step.lineClass"
                                    ></span>
                                </div>
                            </div>

                            <div
                                class="mt-2 grid gap-1 text-center"
                                :style="gridStyle(scopeSteps)"
                            >
                                <div v-for="step in scopeSteps" :key="`scope-label-${step.key}`">
                                    <p class="text-[9px] font-semibold text-slate-700 dark:text-slate-200">{{ step.label }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-slate-100 pt-4 dark:border-white/5">
                        <div class="mb-2 flex items-center justify-between gap-2">
                            <h2 class="text-[10px] font-semibold uppercase tracking-[0.08em] text-slate-500 dark:text-slate-400">Project Charter IT Initiative Timeline</h2>
                        </div>

                        <div>
                            <div
                                class="grid"
                                :style="gridStyle(digitalSteps)"
                            >
                                <div
                                    v-for="(step, index) in digitalSteps"
                                    :key="`step-${step.key}`"
                                    class="relative flex justify-center cursor-pointer group"
                                    @click="toggleFilter(step.statusId)"
                                >
                                    <span
                                        class="inline-flex h-6 w-6 shrink-0 items-center justify-center rounded-full border text-[10px] font-bold transition-all group-hover:ring-2 group-hover:ring-offset-1 group-hover:ring-slate-300"
                                        :class="[
                                            step.circleClass,
                                            activeFlowFilter === step.statusId ? 'ring-2 ring-offset-2 ring-blue-500 shadow-md transform scale-110' : ''
                                        ]"
                                    >
                                        {{ step.count }}
                                    </span>
                                    <span
                                        v-if="index < digitalSteps.length - 1"
                                        class="absolute left-1/2 top-1/2 ml-[0.75rem] h-0.5 w-[calc(100%_-_1.5rem)] -translate-y-1/2 rounded-full"
                                        :class="step.lineClass"
                                    ></span>
                                </div>
                            </div>

                            <div
                                class="mt-2 grid gap-1 text-center"
                                :style="gridStyle(digitalSteps)"
                            >
                                <div v-for="step in digitalSteps" :key="`label-${step.key}`">
                                    <p class="text-[9px] font-semibold text-slate-700 dark:text-slate-200">{{ step.label }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </article>
            </section>
            <!-- FILTER -->
<!-- 
            <div class="mb-6 flex flex-col gap-3 rounded-xl border border-slate-200 bg-white p-4 dark:border-white/5 dark:bg-[#1a1a1a] sm:flex-row">
                <div class="relative flex-1">
                    <input
                        v-model="filters.search"
                        type="text"
                        placeholder="Search by name or code..."
                        class="w-full rounded-lg border border-slate-300 bg-white py-2 pl-10 pr-4 text-slate-900 placeholder-slate-400 focus:border-indigo-500 focus:ring-indigo-500 dark:border-white/10 dark:bg-[#131313] dark:text-slate-100 dark:placeholder-slate-500"
                        @input="debouncedSearch"
                    />
                    <svg class="absolute left-3 top-2.5 h-5 w-5 text-slate-400 dark:text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <select
                    v-model="filters.status"
                    disabled
                    class="w-full rounded-lg border-slate-300 bg-white py-1.5 text-sm text-slate-700 focus:border-indigo-500 focus:ring-indigo-500 dark:border-white/10 dark:bg-[#131313] dark:text-slate-200 sm:w-auto"
                >
                    <option :value="completedStatusId">{{ completedStatusLabel }}</option>
                </select>
                <select
                    v-model="filters.month"
                    class="w-full rounded-lg border-slate-300 bg-white py-1.5 text-sm text-slate-700 focus:border-indigo-500 focus:ring-indigo-500 dark:border-white/10 dark:bg-[#131313] dark:text-slate-200 sm:w-auto"
                    @change="applyFilters"
                >
                    <option value="">All Months</option>
                    <option v-for="month in months" :key="month.value" :value="month.value">
                        {{ month.label }}
                    </option>
                </select>
            </div> -->

            <div class="overflow-hidden rounded-xl border border-slate-200 bg-white dark:border-white/5 dark:bg-[#1a1a1a]">
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[1720px] divide-y divide-slate-200 dark:divide-white/5">
                        <thead class="bg-slate-50 dark:bg-white/5">
                            <tr>
                                <th scope="col" class="w-10 whitespace-nowrap px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500 dark:text-slate-400">No</th>
                                <th scope="col" class="w-16 whitespace-nowrap px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500 dark:text-slate-400">Code</th>
                                <th scope="col" class="whitespace-nowrap px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500 dark:text-slate-400">IT Arsitektur Building Blok</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500 dark:text-slate-400">Daftar Inisiatif</th>
                                <th scope="col" class="whitespace-nowrap px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500 dark:text-slate-400">Status Usulan</th>
                                <th scope="col" class="whitespace-nowrap px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500 dark:text-slate-400">Bulan / Tahun</th>
                                <th scope="col" class="w-[30rem] px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500 dark:text-slate-400">Status Review</th>
                                <th scope="col" class="w-[44rem] px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500 dark:text-slate-400">Status Implementasi</th>
                                <th scope="col" class="whitespace-nowrap p-0 text-xs font-medium uppercase tracking-wider text-slate-500 dark:text-slate-400">
                                    <div class="w-[270px] border-l border-slate-200 dark:border-white/10 float-right">
                                        <div class="border-b border-slate-200 px-2 py-1.5 text-center dark:border-white/10">Action</div>
                                        <div class="grid grid-cols-3 divide-x divide-slate-200 text-[10px] font-semibold normal-case dark:divide-white/10">
                                            <span class="px-2 py-1 text-center">Scope Charter</span>
                                            <span class="px-2 py-1 text-center">Project Charter</span>
                                            <span class="px-2 py-1 text-center">Roadmap</span>
                                        </div>
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 bg-white dark:divide-white/5 dark:bg-[#1a1a1a]">
                            <tr v-for="(project, index) in filteredItems" :key="project.id" class="group transition-colors hover:bg-slate-50 dark:hover:bg-white/5">
                                <td class="whitespace-nowrap px-3 py-4 text-xs font-medium text-slate-600 dark:text-slate-400">
                                    {{ index + 1 }}
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-xs font-medium text-slate-600 dark:text-slate-400">
                                    {{ project.code || '-' }}
                                </td>
                                <td 
                                    v-if="shouldShowCategory(index)"
                                    :rowspan="getCategoryRowspan(index)"
                                    class="whitespace-nowrap px-3 py-4 align-top border-r border-slate-100 dark:border-white/5 bg-slate-50/50 dark:bg-white/[0.02]"
                                >
                                    <span class="inline-flex rounded-full bg-blue-100 px-2 text-[10px] font-semibold leading-5 text-blue-800 dark:bg-blue-500/20 dark:text-blue-300">
                                        {{ project.charter?.category || 'Uncategorized' }}
                                    </span>
                                </td>
                                <td class="w-[30rem] px-6 py-4 text-xs text-slate-700 dark:text-slate-200">
                                    <span class="font-medium text-slate-700 dark:text-slate-200">{{ project.name || '-' }}</span>
                                </td>
                                <td class="whitespace-nowrap px-3 py-4">
                                    <span
                                        class="inline-flex items-center rounded-full px-2.5 py-0.5 text-[10px] font-medium capitalize"
                                        :class="statusBadgeClassById(project.status)"
                                    >
                                        {{ statusLabelFromOptions(project.status, statusOptions) }}
                                    </span>
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-xs font-medium text-slate-700 dark:text-slate-300">
                                    {{ latestItDate(project) || '-' }}
                                </td>
                                <td class="w-[44rem] px-6 py-4 text-xs text-slate-700 dark:text-slate-200">
                                    <span
                                        v-if="latestItReviewStatus(project)"
                                        class="inline-flex items-center rounded-md bg-amber-100 px-2 py-1 text-[11px] font-medium text-amber-800 dark:bg-amber-500/20 dark:text-amber-300 break-words whitespace-normal leading-relaxed"
                                        :title="latestItReviewStatus(project)"
                                    >
                                        {{ latestItReviewStatus(project) }}
                                    </span>
                                    <span v-else class="text-slate-400 dark:text-slate-500 italic text-xs">-</span>
                                </td>
                                <td class="px-6 py-4 text-xs text-slate-700 dark:text-slate-200">
                                    <span
                                        v-if="latestItStatus(project)"
                                        class="inline-flex w-full items-center rounded-md bg-slate-100 px-3 py-2 text-[11px] font-medium text-slate-700 capitalize dark:bg-white/10 dark:text-slate-300 break-words whitespace-normal leading-relaxed"
                                        :title="latestItStatus(project)"
                                    >
                                        {{ latestItStatus(project) }}
                                    </span>
                                    <span v-else class="text-slate-400 dark:text-slate-500 italic text-xs">-</span>
                                </td>
                                <td class="p-0 text-sm font-medium">
                                    <div class="grid w-[270px] h-full grid-cols-3 divide-x divide-slate-200 border-l border-slate-200 dark:divide-white/10 dark:border-white/10 float-right">
                                        <div class="flex items-center justify-center p-2">
                                            <Link
                                                :href="`/it-initiatives/${project.id}`"
                                                :class="actionCellClass(hasScopeCharter(project))"
                                                title="View Scope Charter"
                                            >
                                                View
                                            </Link>
                                        </div>
                                        <div class="flex items-center justify-center p-2">
                                            <Link
                                                :href="`/it-initiatives/${project.id}`"
                                                :class="actionCellClass(hasProjectCharter(project))"
                                                title="View Project Charter"
                                            >
                                                View
                                            </Link>
                                        </div>
                                        <div class="flex items-center justify-center p-2">
                                            <Link
                                                :href="`/roadmap?project_id=${project.id}`"
                                                class="inline-flex items-center rounded-full px-2.5 py-0.5 text-[10px] font-semibold bg-blue-100 text-blue-800 hover:bg-blue-200 dark:bg-blue-500/20 dark:text-blue-300 dark:hover:bg-blue-500/30 transition-colors cursor-pointer"
                                                title="View Roadmap"
                                            >
                                                Roadmap
                                            </Link>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="filteredItems.length === 0">
                                <td colspan="9" class="px-6 py-8 text-center text-xs text-slate-500 dark:text-slate-400">
                                    <span v-if="activeFlowFilter === null">
                                        Silakan klik salah satu status di atas untuk menampilkan data inisiatif.
                                    </span>
                                    <span v-else>
                                        Tidak ada data yang sesuai dengan filter opsi ini.
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div
                v-if="filteredItems.length === 0"
                class="mt-6 rounded-xl border border-slate-200 bg-white py-12 text-center dark:border-white/5 dark:bg-[#1a1a1a]"
            >
                <p class="text-slate-500 dark:text-slate-400">
                    {{ activeFlowFilter === null ? 'Pilih status di atas untuk melihat data.' : 'Tidak ada IT initiative ditemukan.' }}
                </p>
                <p class="mt-2 text-sm text-slate-400 dark:text-slate-500" v-if="activeFlowFilter !== null">
                    Status yang dipilih belum memiliki data inisiatif.
                </p>
            </div>


        </div>
    </UserLayout>
</template>

<script setup>
import { computed, ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import UserLayout from '@/Layouts/UserLayout.vue';
import { statusBadgeClassById, statusLabelFromOptions, statusFlowClassByIndex } from '@/Composables/initiativeStatus';
import { useFlowFilter } from '@/Composables/useFlowFilter';

const props = defineProps({
    itInitiatives: Object,
    filters: Object,
    statusOptions: {
        type: Array,
        default: () => [],
    },
    completedStatusId: {
        type: Number,
        default: 5,
    },
    totalApproved: {
        type: Number,
        default: 0,
    },
    statusCounts: {
        type: Object,
        default: () => ({}),
    }
});

const { activeFlowFilter, filteredItems, toggleFilter } = useFlowFilter(
    () => props.itInitiatives,
    (item) => item.status
);

const statusOptions = computed(() => {
    if (props.statusOptions.length > 0) {
        return props.statusOptions;
    }

    return [
        { id: 1, name: 'drafting', label: 'Drafting' },
        { id: 2, name: 'propose', label: 'Propose' },
        { id: 3, name: 'review', label: 'Review' },
        { id: 4, name: 'approve', label: 'Approve' },
        { id: 5, name: 'baseline', label: 'Baseline' },
    ];
});

const completedStatusId = computed(() => {
    return Number(props.completedStatusId || 5);
});

const completedStatusLabel = computed(() => {
    return statusLabelFromOptions(completedStatusId.value, statusOptions.value);
});

const scopeStatusOrder = ['drafting', 'propose', 'review', 'approve'];
const normalizeStatusName = (value) => String(value ?? '').trim().toLowerCase();

const scopeStatusOptions = computed(() => {
    const sourceOptions = Array.isArray(statusOptions.value) ? statusOptions.value : [];
    const mappedStatusByName = new Map();

    sourceOptions.forEach((status) => {
        const candidateNames = [normalizeStatusName(status?.name), normalizeStatusName(status?.label)];

        if (candidateNames.includes('baseline')) {
            return;
        }

        candidateNames.forEach((candidateName) => {
            if (scopeStatusOrder.includes(candidateName) && !mappedStatusByName.has(candidateName)) {
                mappedStatusByName.set(candidateName, status);
            }
        });
    });

    const fallbackStatusOptions = [
        { id: 1, name: 'drafting', label: 'Drafting' },
        { id: 2, name: 'propose', label: 'Propose' },
        { id: 3, name: 'review', label: 'Review' },
        { id: 4, name: 'approve', label: 'Approve' },
    ];

    return scopeStatusOrder.map((statusName, index) => {
        const matchedStatus = mappedStatusByName.get(statusName);
        const fallbackStatus = fallbackStatusOptions[index];

        return {
            id: Number(matchedStatus?.id ?? fallbackStatus.id),
            name: statusName,
            label: fallbackStatus.label,
        };
    });
});

const scopeSteps = computed(() => {
    // Currently defaulting to 0 as Scope Charter does not have specific charter status counts
    return scopeStatusOptions.value.map((status, index) => {
        const flowClass = statusFlowClassByIndex(index);
        const key = String(status.id);

        return {
            key,
            statusId: status.id,
            label: status.label,
            count: 0,
            circleClass: flowClass.circleClass,
            lineClass: flowClass.lineClass,
        };
    });
});

const digitalSteps = computed(() => {
    const counts = props.statusCounts || {};
    return scopeStatusOptions.value.map((status, index) => {
        const flowClass = statusFlowClassByIndex(index);
        const key = String(status.id);

        return {
            key,
            statusId: status.id,
            label: status.label,
            count: Number(counts?.[key] ?? 0),
            circleClass: flowClass.circleClass,
            lineClass: flowClass.lineClass,
        };
    });
});

const gridStyle = (steps = []) => ({
    gridTemplateColumns: `repeat(${Math.max(steps.length, 1)}, minmax(0, 1fr))`,
});

const filters = ref({
    search: props.filters.search || '',
    status: completedStatusId.value,
    month: props.filters.month || '',
});

const months = [
    { value: '01', label: 'January' },
    { value: '02', label: 'February' },
    { value: '03', label: 'March' },
    { value: '04', label: 'April' },
    { value: '05', label: 'May' },
    { value: '06', label: 'June' },
    { value: '07', label: 'July' },
    { value: '08', label: 'August' },
    { value: '09', label: 'September' },
    { value: '10', label: 'October' },
    { value: '11', label: 'November' },
    { value: '12', label: 'December' },
];

const shouldShowCategory = (index) => {
    if (index === 0) return true;
    const current = filteredItems.value[index].charter?.category || 'Uncategorized';
    const previous = filteredItems.value[index - 1].charter?.category || 'Uncategorized';
    return current !== previous;
};

const getCategoryRowspan = (index) => {
    let count = 1;
    const current = filteredItems.value[index].charter?.category || 'Uncategorized';
    for (let i = index + 1; i < filteredItems.value.length; i++) {
        if ((filteredItems.value[i].charter?.category || 'Uncategorized') === current) {
            count++;
        } else {
            break;
        }
    }
    return count;
};

let timeout = null;
const debouncedSearch = () => {
    clearTimeout(timeout);
    timeout = setTimeout(applyFilters, 300);
};

const applyFilters = () => {
    router.get('/it-initiatives', filters.value, { preserveState: true, replace: true });
};

function hasFilled(val) {
    return val !== null && val !== undefined && String(val).trim() !== '';
}

function hasScopeCharter(project) {
    return hasFilled(project?.code) && hasFilled(project?.name);
}

function hasProjectCharter(project) {
    const charter = project?.charter;

    if (!charter || typeof charter !== 'object') {
        return false;
    }

    return [
        charter.category,
        charter.duration,
        charter.background,
        charter.objectives,
        charter.scope,
        charter.impact_value,
    ].some(hasFilled);
}

function actionCellClass(isReady) {
    if (isReady) {
        return 'inline-flex items-center rounded-full px-2.5 py-0.5 text-[10px] font-semibold bg-emerald-100 text-emerald-800 hover:bg-emerald-200 dark:bg-emerald-500/20 dark:text-emerald-300 dark:hover:bg-emerald-500/30 transition-colors cursor-pointer';
    }

    return 'inline-flex items-center rounded-full px-2.5 py-0.5 text-[10px] font-semibold bg-rose-100 text-rose-800 hover:bg-rose-200 dark:bg-rose-500/20 dark:text-rose-300 dark:hover:bg-rose-500/30 transition-colors cursor-pointer';
}

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

const latestItReviewStatus = (item) => {
    const rawReviewStatus = item?.latest_implementation_status?.review_status
        ?? item?.latestImplementationStatus?.review_status
        ?? item?.latest_pc_status_implementation?.review_status
        ?? item?.latestPcStatusImplementation?.review_status
        ?? item?.pc_status_implementations?.[0]?.review_status
        ?? item?.pcStatusImplementations?.[0]?.review_status
        ?? null;

    const normalizedReviewStatus = String(rawReviewStatus ?? '').trim();

    return normalizedReviewStatus.length > 0 ? normalizedReviewStatus : null;
};

const latestItDate = (item) => {
    const rawDate = item?.latest_implementation_status?.date
        ?? item?.latestImplementationStatus?.date
        ?? item?.latest_pc_status_implementation?.date
        ?? item?.latestPcStatusImplementation?.date
        ?? item?.pc_status_implementations?.[0]?.date
        ?? item?.pcStatusImplementations?.[0]?.date
        ?? null;

    if (!rawDate) return null;
    
    const date = new Date(rawDate);
    if (isNaN(date.getTime())) return null;

    return date.toLocaleDateString('en-US', { year: 'numeric', month: 'long' });
};
</script>
