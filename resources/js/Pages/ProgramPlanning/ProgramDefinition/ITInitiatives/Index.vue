<template>
    <UserLayout title="Program Definition IT Initiatives">
        <div class="animate-fade-in">
            <div class="mb-4">
                <h2 class="text-2xl font-bold text-slate-900 dark:text-white">
                    Program Definition IT Initiatives
                </h2>
            </div>

            <section class="mb-4 grid grid-cols-1 gap-4 lg:grid-cols-3">
                <div class="flex h-full flex-col gap-3">
                    <article
                        class="relative flex min-h-[220px] flex-1 flex-col justify-center rounded-2xl border border-[#A7C942] bg-[#A7C942] p-5 shadow-[0_4px_16px_rgba(167,201,66,0.3)]"
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

                    <div class="flex flex-wrap gap-2">
                        <Link
                            href="/it-initiatives/create"
                            class="inline-flex items-center rounded-full border border-[#1C75BC]/45 bg-[#1C75BC]/10 px-3 py-1.5 text-xs font-semibold text-[#1C75BC] transition hover:bg-[#1C75BC]/20 dark:text-[#7FC0F2]"
                        >
                            New Initiative
                        </Link>
                    </div>
                </div>

                <article class="h-full min-h-[220px] rounded-2xl border border-slate-200 bg-white px-5 py-4 shadow-[0_4px_16px_rgba(0,0,0,0.05)] dark:border-white/10 dark:bg-[#171717] lg:col-span-2">
                    <div class="mb-2 flex items-center justify-between gap-2">
                        <h2 class="text-[10px] font-semibold uppercase tracking-[0.08em] text-slate-500 dark:text-slate-400">
                            Scope Charter IT Initiative Timeline
                        </h2>
                    </div>

                    <div>
                        <div class="grid" :style="gridStyle(scopeSteps)">
                            <div
                                v-for="(step, index) in scopeSteps"
                                :key="`scope-step-${step.key}`"
                                class="relative flex justify-center"
                            >
                                <span
                                    class="inline-flex h-6 w-6 shrink-0 items-center justify-center rounded-full border text-[10px] font-bold"
                                    :class="step.circleClass"
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
                </article>
            </section>

            <ItMasterInitiativeTable :items="masterItList" />
        </div>
    </UserLayout>
</template>

<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import UserLayout from '@/Layouts/UserLayout.vue';
import { statusFlowClassByIndex } from '@/Composables/initiativeStatus';
import ItMasterInitiativeTable from '@/Components/ITInitiative/MasterInitiativeTable.vue';

const props = defineProps({
    totalItInitiatives: {
        type: Number,
        default: 0,
    },
    statusOptions: {
        type: Array,
        default: () => [],
    },
    statusCounts: {
        type: Object,
        default: () => ({}),
    },
    masterItInitiatives: {
        type: Array,
        default: () => [],
    },
});

const masterItList = computed(() => {
    return Array.isArray(props.masterItInitiatives) ? props.masterItInitiatives : [];
});

const scopeStatusOrder = ['drafting', 'propose', 'review', 'approved'];
const normalizeStatusName = (value) => String(value ?? '').trim().toLowerCase();

const scopeStatusOptions = computed(() => {
    const sourceOptions = Array.isArray(props.statusOptions) ? props.statusOptions : [];
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
        { id: 4, name: 'approved', label: 'Approved' },
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
