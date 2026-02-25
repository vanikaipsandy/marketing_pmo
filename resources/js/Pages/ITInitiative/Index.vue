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
                    class="relative flex cursor-pointer flex-col justify-center rounded-2xl border border-[#A7C942] bg-[#A7C942] p-5 shadow-[0_4px_16px_rgba(167,201,66,0.3)]"
                    role="button"
                    tabindex="0"
                    @click="showMasterItInitiatives"
                    @keydown.enter.prevent="showMasterItInitiatives"
                    @keydown.space.prevent="showMasterItInitiatives"
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
                        <span>{{ totalItInitiatives }}</span>
                    </p>
                </article>

                <article class="space-y-4 rounded-2xl border border-slate-200 bg-white px-5 py-3 shadow-[0_4px_16px_rgba(0,0,0,0.05)] dark:border-white/10 dark:bg-[#171717] lg:col-span-2">
                    <div>
                        <div class="mb-2 flex items-center justify-between gap-2">
                            <h2 class="text-[10px] font-semibold uppercase tracking-[0.08em] text-slate-500 dark:text-slate-400">Scope Charter IT Initiative Timeline</h2>
                        </div>

                        <div>
                            <div class="grid" :style="gridStyle(scopeSteps)">
                                <div
                                    v-for="(step, index) in scopeSteps"
                                    :key="`scope-step-${step.key}`"
                                    class="group relative flex cursor-pointer justify-center"
                                    @click="handleFlowFilter(step.statusId)"
                                >
                                    <span
                                        class="inline-flex h-6 w-6 shrink-0 items-center justify-center rounded-full border text-[10px] font-bold transition-all group-hover:ring-2 group-hover:ring-offset-1 group-hover:ring-slate-300"
                                        :class="[
                                            step.circleClass,
                                            activeFlowFilter === step.statusId && tableMode === TABLE_MODE.FLOW ? 'ring-2 ring-offset-2 ring-blue-500 shadow-md transform scale-110' : ''
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

                            <div class="mt-2 grid gap-1 text-center" :style="gridStyle(scopeSteps)">
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
                            <div class="grid" :style="gridStyle(digitalSteps)">
                                <div
                                    v-for="(step, index) in digitalSteps"
                                    :key="`step-${step.key}`"
                                    class="group relative flex cursor-pointer justify-center"
                                    @click="handleFlowFilter(step.statusId)"
                                >
                                    <span
                                        class="inline-flex h-6 w-6 shrink-0 items-center justify-center rounded-full border text-[10px] font-bold transition-all group-hover:ring-2 group-hover:ring-offset-1 group-hover:ring-slate-300"
                                        :class="[
                                            step.circleClass,
                                            activeFlowFilter === step.statusId && tableMode === TABLE_MODE.FLOW ? 'ring-2 ring-offset-2 ring-blue-500 shadow-md transform scale-110' : ''
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

                            <div class="mt-2 grid gap-1 text-center" :style="gridStyle(digitalSteps)">
                                <div v-for="step in digitalSteps" :key="`label-${step.key}`">
                                    <p class="text-[9px] font-semibold text-slate-700 dark:text-slate-200">{{ step.label }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </article>
            </section>

            <FlowStatusTable
                v-if="hasTableSelection && tableMode === TABLE_MODE.FLOW"
                :items="flowItems"
                :status-options="statusOptions"
                :active-flow-filter="activeFlowFilter"
            />

            <MasterInitiativeTable
                v-else-if="hasTableSelection"
                :items="masterItems"
            />
        </div>
    </UserLayout>
</template>

<script setup>
import { computed, ref } from 'vue';
import UserLayout from '@/Layouts/UserLayout.vue';
import { statusFlowClassByIndex } from '@/Composables/initiativeStatus';
import { useFlowFilter } from '@/Composables/useFlowFilter';
import FlowStatusTable from '@/Components/ITInitiative/FlowStatusTable.vue';
import MasterInitiativeTable from '@/Components/ITInitiative/MasterInitiativeTable.vue';

const props = defineProps({
    itInitiatives: {
        type: Array,
        default: () => [],
    },
    masterItInitiatives: {
        type: Array,
        default: () => [],
    },
    filters: {
        type: Object,
        default: () => ({}),
    },
    statusOptions: {
        type: Array,
        default: () => [],
    },
    totalItInitiatives: {
        type: Number,
        default: 0,
    },
    statusCounts: {
        type: Object,
        default: () => ({}),
    },
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

const { activeFlowFilter, filteredItems, toggleFilter } = useFlowFilter(
    () => asList(props.itInitiatives),
    (item) => item.status
);

const TABLE_MODE = {
    FLOW: 'flow',
    MASTER: 'master',
};

const tableMode = ref(TABLE_MODE.FLOW);
const hasTableSelection = ref(false);

const showMasterItInitiatives = () => {
    hasTableSelection.value = true;
    tableMode.value = TABLE_MODE.MASTER;
    activeFlowFilter.value = null;
};

const handleFlowFilter = (statusId) => {
    hasTableSelection.value = true;
    tableMode.value = TABLE_MODE.FLOW;
    toggleFilter(statusId);
};

const flowItems = computed(() => filteredItems.value);

const masterItems = computed(() => {
    return asList(props.masterItInitiatives);
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
</script>
