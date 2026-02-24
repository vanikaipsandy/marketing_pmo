<template>
    <UserLayout title="Digital Initiatives">
        <div class="animate-fade-in">
            <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-slate-900 dark:text-white">
                        Digital Initiatives
                    </h2>
                </div>
            </div>

            <section class="mb-6 grid grid-cols-1 gap-4 lg:grid-cols-3">
                <article
                    class="relative flex cursor-pointer flex-col justify-center rounded-2xl border bg-[#1C75BC] border-[#1C75BC] p-5 shadow-[0_4px_16px_rgba(28,117,188,0.3)]"
                    role="button"
                    tabindex="0"
                    @click="showMasterDigitalInitiatives"
                    @keydown.enter.prevent="showMasterDigitalInitiatives"
                    @keydown.space.prevent="showMasterDigitalInitiatives"
                >
                    <p
                        class="text-xs font-semibold uppercase tracking-[0.08em] text-white"
                        style="text-shadow: 0 1px 3px rgba(0,0,0,0.3);"
                    >
                        Total Digital Inisiatif 
                    </p>
                    <p
                        class="mt-2 flex items-center justify-between text-3xl font-bold text-white"
                        style="text-shadow: 0 2px 6px rgba(0,0,0,0.35);"
                    >
                        <span>{{ totalDigitalInitiatives }}</span>
                    </p>
                </article>

                <article class="flex flex-col justify-center rounded-2xl border border-slate-200 bg-white px-5 py-3 shadow-[0_4px_16px_rgba(0,0,0,0.05)] dark:border-white/10 dark:bg-[#171717] lg:col-span-2 space-y-4">
                    <div>
                        <div class="mb-2 flex items-center justify-between gap-2">
                            <h2 class="text-[10px] font-semibold uppercase tracking-[0.08em] text-slate-500 dark:text-slate-400">Scope Charter Digital Initiative Timeline</h2>
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
                                    @click="handleFlowFilter(step.statusId)"
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
                            <h2 class="text-[10px] font-semibold uppercase tracking-[0.08em] text-slate-500 dark:text-slate-400">Project Charter Digital Initiative Timeline</h2>
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
                                    @click="handleFlowFilter(step.statusId)"
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

            <!-- Digital Initiatives Table -->
            <div v-if="tableMode === TABLE_MODE.FLOW" class="overflow-hidden rounded-xl border border-slate-200 bg-white dark:border-white/5 dark:bg-[#1a1a1a]">
                <div class="overflow-x-hidden">
                    <table class="w-full table-fixed divide-y divide-slate-200 text-[11px] dark:divide-white/5">
                        <colgroup>
                            <col class="w-[4%]">
                            <col class="w-[7%]">
                            <col class="w-[9%]">
                            <col class="w-[12%]">
                            <col class="w-[17%]">
                            <col class="w-[10%]">
                            <col class="w-[7%]">
                            <col class="w-[7%]">
                            <col class="w-[9%]">
                            <col class="w-[8%]">
                            <col class="w-[10%]">
                        </colgroup>
                        <thead class="bg-slate-50 dark:bg-white/5">
                            <tr>
                                <th class="px-3 py-2 text-left text-[10px] font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">No</th>
                                <th class="px-3 py-2 text-left text-[10px] font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Type</th>
                                <th class="px-3 py-2 text-left text-[10px] font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Project Owner</th>
                                <th class="px-3 py-2 text-left text-[10px] font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Use Case</th>
                                <th class="px-3 py-2 text-left text-[10px] font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Desc</th>
                                <th class="px-3 py-2 text-left text-[10px] font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Value</th>
                                <th class="px-3 py-2 text-left text-[10px] font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Urgency</th>
                                <th class="px-3 py-2 text-left text-[10px] font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Rjjp</th>
                                <th class="px-3 py-2 text-left text-[10px] font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Coe</th>
                                <th class="px-3 py-2 text-left text-[10px] font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Status</th>
                                <th class="px-3 py-2 text-left text-[10px] font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 bg-white dark:divide-white/5 dark:bg-[#1a1a1a]">
                            <tr v-for="item in filteredItems" :key="`digital-${item.id}`" class="group transition-colors hover:bg-slate-50 dark:hover:bg-white/5">
                                <td class="px-3 py-3 text-[11px] font-medium text-slate-600 dark:text-slate-400">{{ cellVal(item, 'no') }}</td>
                                <td class="px-3 py-3 text-[11px] font-medium text-slate-700 dark:text-slate-200">{{ cellVal(item, 'type') }}</td>
                                <td class="px-3 py-3 text-[11px] text-slate-700 dark:text-slate-200">{{ cellVal(item, 'projectOwner', 'project_owner') }}</td>
                                <td class="px-3 py-3 text-[11px] text-slate-700 dark:text-slate-200">
                                    <span class="break-words">{{ cellVal(item, 'useCase', 'use_case') }}</span>
                                </td>
                                <td class="px-3 py-3 text-[11px] text-slate-700 dark:text-slate-200">
                                    <span class="break-words" :title="cellVal(item, 'desc', 'description')">{{ cellVal(item, 'desc', 'description') }}</span>
                                </td>
                                <td class="px-3 py-3 text-[11px] text-slate-700 dark:text-slate-200">
                                    <span class="line-clamp-2" :title="cellVal(item, 'value')">{{ cellVal(item, 'value') }}</span>
                                </td>
                                <td class="px-3 py-3 text-[11px] text-slate-700 dark:text-slate-200">{{ cellVal(item, 'urgency') }}</td>
                                <td class="px-3 py-3 text-[11px] text-slate-700 dark:text-slate-200">{{ cellVal(item, 'rjjp') }}</td>
                                <td class="px-3 py-3 text-[11px] text-slate-700 dark:text-slate-200">{{ cellVal(item, 'coe') }}</td>
                                <td class="px-3 py-3">
                                    <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[10px] font-medium capitalize" :class="statusBadgeClassById(item.status)">
                                        {{ statusLabelFromOptions(item.status, statusOptions) }}
                                    </span>
                                </td>
                                <td class="px-3 py-3 text-[10px] font-medium">
                                    <div class="flex flex-col items-start gap-1">
                                        <Link
                                            :href="`/digital-initiatives/${item.id}`"
                                            :class="actionCellClass(hasScopeCharter(item))"
                                            title="View Scope Charter"
                                        >
                                            Scope Charter
                                        </Link>
                                        <Link
                                            :href="`/digital-initiatives/${item.id}`"
                                            :class="actionCellClass(hasProjectCharter(item))"
                                            title="View Project Charter"
                                        >
                                            Project Charter
                                        </Link>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="filteredItems.length === 0">
                                <td colspan="11" class="px-6 py-8 text-center text-xs text-slate-500 dark:text-slate-400">
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
                v-if="tableMode === TABLE_MODE.FLOW && filteredItems.length === 0"
                class="mt-6 rounded-xl border border-slate-200 bg-white py-12 text-center dark:border-white/5 dark:bg-[#1a1a1a]"
            >
                <p class="text-slate-500 dark:text-slate-400">
                    {{ activeFlowFilter === null ? 'Pilih status di atas untuk melihat data.' : 'Tidak ada inisiatif digital ditemukan.' }}
                </p>
                <p class="mt-2 text-sm text-slate-400 dark:text-slate-500" v-if="activeFlowFilter !== null">
                    Status yang dipilih belum memiliki data inisiatif.
                </p>
            </div>

            <!-- Master Digital Initiatives Table -->
            <div v-if="tableMode === TABLE_MODE.MASTER" class="overflow-hidden rounded-xl border border-slate-200 bg-white dark:border-white/5 dark:bg-[#1a1a1a]">
                <div class="border-b border-slate-200 px-4 py-3 dark:border-white/10">
                    <p class="text-xs font-semibold uppercase tracking-[0.08em] text-slate-500 dark:text-slate-400">
                        Data Sumber `mst_initiative` (tipe_initiative = 1)
                    </p>
                </div>

                <div class="overflow-x-hidden">
                    <table class="w-full table-fixed divide-y divide-slate-200 text-[11px] dark:divide-white/5">
                        <colgroup>
                            <col class="w-[4%]">
                            <col class="w-[7%]">
                            <col class="w-[20%]">
                            <col class="w-[20%]">
                            <col class="w-[22%]">
                            <col class="w-[14%]">
                            <col class="w-[14%]">
                        </colgroup>
                        <thead class="bg-slate-50 dark:bg-white/5">
                            <tr>
                                <th class="px-3 py-2 text-left text-[10px] font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">No</th>
                                <th class="px-3 py-2 text-left text-[10px] font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Code</th>
                                <th class="px-3 py-2 text-left text-[10px] font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Centre of Excellence (CoE)</th>
                                <th class="px-3 py-2 text-left text-[10px] font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Nama Inisiatif</th>
                                <th class="px-3 py-2 text-left text-[10px] font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Deskripsi</th>
                                <th class="px-3 py-2 text-left text-[10px] font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Organisasi</th>
                                <th class="px-3 py-2 text-left text-[10px] font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Sumber</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 bg-white dark:divide-white/5 dark:bg-[#1a1a1a]">
                            <tr v-for="(item, index) in mstInitiativesList" :key="`mst-digital-${item.id}`" class="transition-colors hover:bg-slate-50 dark:hover:bg-white/5">
                                <td class="px-3 py-3 text-[11px] font-medium text-slate-600 dark:text-slate-400">{{ index + 1 }}</td>
                                <td class="px-3 py-3 text-[11px] font-medium text-slate-700 dark:text-slate-200">{{ item.code ?? '-' }}</td>
                                <td class="px-3 py-3 text-[11px] text-slate-700 dark:text-slate-200">{{ coeName(item) }}</td>
                                <td class="px-3 py-3 text-[11px] text-slate-700 dark:text-slate-200">
                                    <p class="font-medium break-words">{{ item.name ?? '-' }}</p>
                                </td>
                                <td class="px-3 py-3 text-[11px] text-slate-700 dark:text-slate-200">{{ descriptionText(item) }}</td>
                                <td class="px-3 py-3 text-[11px] text-slate-700 dark:text-slate-200">{{ organizationWithGroup(item) }}</td>
                                <td class="px-3 py-3 text-[11px] text-slate-700 dark:text-slate-200">
                                    <span class="inline-flex items-center rounded-full bg-slate-100 px-2 py-0.5 text-[10px] font-medium text-slate-700 dark:bg-white/10 dark:text-slate-300">
                                        {{ statusName(item) }}
                                    </span>
                                </td>
                            </tr>

                            <tr v-if="mstInitiativesList.length === 0">
                                <td colspan="7" class="px-6 py-8 text-center text-xs text-slate-500 dark:text-slate-400">
                                    Tidak ada data `mst_initiative` tipe 1.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
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
    initiatives: Object,
    mstDigitalInitiatives: {
        type: Array,
        default: () => [],
    },
    filters: Object,
    statusOptions: {
        type: Array,
        default: () => [],
    },
    completedStatusId: {
        type: Number,
        default: 5,
    },
    totalDigitalInitiatives: {
        type: Number,
        default: 0,
    },
    statusCounts: {
        type: Object,
        default: () => ({}),
    }
});

const asList = (value) => {
    if (Array.isArray(value)) {
        return value;
    }

    if (value && typeof value === 'object') {
        return Object.values(value);
    }

    return [];
};

// const { activeFlowFilter, filteredItems, toggleFilter } = useFlowFilter(
//     () => asList(props.itInitiatives),
//     (item) => item.status
// );

const TABLE_MODE = {
    FLOW: 'flow',
    MASTER: 'master',
};

const tableMode = ref(TABLE_MODE.FLOW);

const showMasterDigitalInitiatives = () => {
    tableMode.value = TABLE_MODE.MASTER;
    activeFlowFilter.value = null;
};

const backToDigitalInitiatives = () => {
    tableMode.value = TABLE_MODE.FLOW;
    activeFlowFilter.value = null;
};

const handleFlowFilter = (statusId) => {
    tableMode.value = TABLE_MODE.FLOW;
    toggleFilter(statusId);
};

const { activeFlowFilter, filteredItems, toggleFilter } = useFlowFilter(
    () => props.initiatives,
    (item) => item.status
);

const mstInitiativesList = computed(() => {
    return Array.isArray(props.mstDigitalInitiatives) ? props.mstDigitalInitiatives : [];
});

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

const digitalSteps = computed(() => {
    // Currently defaulting to 0 as Project Charter does not have specific charter status counts
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

const gridStyle = (steps = []) => ({
    gridTemplateColumns: `repeat(${Math.max(steps.length, 1)}, minmax(0, 1fr))`,
});

const filters = ref({
    search: props.filters?.search || '',
    type: props.filters?.type || '',
    status: completedStatusId.value,
});

const typeBadgeClass = (type) => {
    const t = String(type || '').toLowerCase();
    if (t === 'strategic') return 'bg-purple-100 text-purple-700 dark:bg-purple-500/10 dark:text-purple-400';
    if (t === 'operational') return 'bg-teal-100 text-teal-700 dark:bg-teal-500/10 dark:text-teal-400';
    if (t === 'tactical') return 'bg-amber-100 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400';
    return 'bg-slate-100 text-slate-700 dark:bg-white/10 dark:text-slate-300';
};

let timeout = null;
const debouncedSearch = () => {
    clearTimeout(timeout);
    timeout = setTimeout(applyFilters, 300);
};

const applyFilters = () => {
    router.get('/digital-initiatives', filters.value, { preserveState: true, replace: true });
};

/** Ambil nilai dari item, coba beberapa key (camelCase / snake_case). */
function cellVal(item, ...keys) {
    if (!item || typeof item !== 'object') return '-';
    for (const key of keys) {
        const v = item[key];
        if (v !== undefined && v !== null && String(v).trim() !== '') return v;
    }
    return '-';
}

function hasFilled(val) {
    return val !== null && val !== undefined && String(val).trim() !== '' && String(val) !== '-';
}

function hasScopeCharter(item) {
    return hasFilled(cellVal(item, 'useCase', 'use_case')) && hasFilled(cellVal(item, 'desc', 'description'));
}

function hasProjectCharter(item) {
    return [
        cellVal(item, 'projectOwner', 'project_owner'),
        cellVal(item, 'value'),
        cellVal(item, 'urgency'),
        cellVal(item, 'rjjp'),
        cellVal(item, 'coe'),
    ].every(hasFilled);
}

function actionCellClass(isReady) {
    if (isReady) {
        return 'inline-flex items-center rounded-full px-2 py-0.5 text-[9px] font-semibold bg-emerald-100 text-emerald-800 hover:bg-emerald-200 dark:bg-emerald-500/20 dark:text-emerald-300 dark:hover:bg-emerald-500/30 transition-colors cursor-pointer';
    }

    return 'inline-flex items-center rounded-full px-2 py-0.5 text-[9px] font-semibold bg-rose-100 text-rose-800 hover:bg-rose-200 dark:bg-rose-500/20 dark:text-rose-300 dark:hover:bg-rose-500/30 transition-colors cursor-pointer';
}

const normalizeText = (value) => {
    const text = String(value ?? '').trim();
    return text.length > 0 ? text : '-';
};

const coeName = (item) => normalizeText(item?.coe?.name ?? item?.coe_name ?? item?.coe_id);

const organizationName = (item) => normalizeText(item?.organization?.name ?? item?.business_unit_name ?? item?.business_unit);

const groubName = (item) => normalizeText(item?.organization?.groub?.name);

const organizationWithGroup = (item) => {
    const org = organizationName(item);
    const group = groubName(item);

    if (org === '-' && group === '-') {
        return '-';
    }

    if (group === '-') {
        return org;
    }

    if (org === '-') {
        return group;
    }

    return `${org} - ${group}`;
};

const statusName = (item) => normalizeText(item?.status);

const descriptionText = (item) => normalizeText(item?.description);
</script>
