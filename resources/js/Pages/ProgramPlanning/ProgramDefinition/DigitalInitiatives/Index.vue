<template>
    <UserLayout title="Program Definition Digital Initiatives">
        <div class="animate-fade-in">
            <div class="mb-4">
                <h2 class="text-2xl font-bold text-slate-900 dark:text-white">
                    Program Definition Digital Initiatives
                </h2>
            </div>

            <section class="mb-4 grid grid-cols-1 gap-4 lg:grid-cols-3">
                <div class="flex h-full flex-col gap-3">
                    <article
                        class="relative flex min-h-[220px] flex-1 flex-col justify-center rounded-2xl border border-[#1C75BC] bg-[#1C75BC] p-5 shadow-[0_4px_16px_rgba(28,117,188,0.3)]"
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

                    <div class="flex flex-wrap gap-2">
                        <Link
                            href="/digital-initiatives/create"
                            class="inline-flex items-center rounded-full border border-[#1C75BC]/45 bg-[#1C75BC]/10 px-3 py-1.5 text-xs font-semibold text-[#1C75BC] transition hover:bg-[#1C75BC]/20 dark:text-[#7FC0F2]"
                        >
                            New Initiative
                        </Link>
                        <Link
                            href="/digital-initiatives"
                            class="inline-flex items-center rounded-full border border-[#1C75BC]/45 bg-[#1C75BC]/10 px-3 py-1.5 text-xs font-semibold text-[#1C75BC] transition hover:bg-[#1C75BC]/20 dark:text-[#7FC0F2]"
                        >
                            Digital Initiative
                        </Link>
                        <Link
                            href="/digital-initiatives"
                            class="inline-flex items-center rounded-full border border-[#1C75BC]/45 bg-[#1C75BC]/10 px-3 py-1.5 text-xs font-semibold text-[#1C75BC] transition hover:bg-[#1C75BC]/20 dark:text-[#7FC0F2]"
                        >
                            Compedium List
                        </Link>
                        <Link
                            href="/digital-initiatives"
                            class="inline-flex items-center rounded-full border border-[#A7C942]/80 bg-[#A7C942]/15 px-3 py-1.5 text-xs font-semibold text-[#4F6B0F] transition hover:bg-[#A7C942]/25 dark:text-[#C7E67A]"
                        >
                            Appendix List
                        </Link>
                    </div>
                </div>

                <article class="h-full min-h-[220px] rounded-2xl border border-slate-200 bg-white px-5 py-4 shadow-[0_4px_16px_rgba(0,0,0,0.05)] dark:border-white/10 dark:bg-[#171717] lg:col-span-2">
                    <div class="mb-4 flex items-center justify-between gap-2">
                        <h2 class="text-[10px] font-semibold uppercase tracking-[0.08em] text-slate-500 dark:text-slate-400">
                            Digital Initiative Timeline
                        </h2>
                    </div>

                    <!-- GitHub-style Branch Flow (SVG) -->
                    <div ref="branchContainer" class="relative" :style="{ minHeight: postponeCount > 0 ? '130px' : '60px' }">
                        <svg class="absolute inset-0 h-full w-full" preserveAspectRatio="none">
                            <defs>
                                <linearGradient id="mainGrad" x1="0%" y1="0%" x2="100%" y2="0%">
                                    <stop offset="0%"   stop-color="#94a3b8" />
                                    <stop offset="25%"  stop-color="#60a5fa" />
                                    <stop offset="50%"  stop-color="#fbbf24" />
                                    <stop offset="100%" stop-color="#10b981" />
                                </linearGradient>
                            </defs>
                            <!-- Main branch line -->
                            <line :x1="nodeX(0)" y1="28" :x2="nodeX(4)" y2="28"
                                  stroke="url(#mainGrad)" stroke-width="2.5" stroke-linecap="round" />
                            <!-- Postpone smooth curve branch -->
                            <path v-if="postponeCount > 0"
                                  :d="postponeCurvePath"
                                  fill="none" stroke="#fb7185" stroke-width="2.5" stroke-linecap="round" />
                        </svg>

                        <!-- Main branch nodes -->
                        <div class="relative flex items-start justify-between px-4">
                            <div v-for="(step, index) in mainSteps" :key="step.key"
                                 class="relative z-10 flex flex-col items-center" style="min-width: 56px;">
                                <div class="flex h-[56px] w-[56px] items-center justify-center">
                                    <div class="flex h-8 w-8 items-center justify-center rounded-full border-[2.5px] text-[11px] font-bold shadow-sm"
                                         :class="step.nodeClass">
                                        {{ step.count }}
                                    </div>
                                </div>
                                <span class="mt-0.5 text-[9px] font-semibold" :class="step.labelClass">{{ step.label }}</span>
                            </div>
                        </div>

                        <!-- Postpone node (positioned below curve endpoint) -->
                        <div v-if="postponeCount > 0"
                             class="absolute z-10 flex flex-col items-center"
                             :style="{ left: `${postponeNodeLeft}px`, top: '82px', transform: 'translateX(-50%)' }">
                            <div class="flex h-8 w-8 items-center justify-center rounded-full border-[2.5px] border-rose-400 bg-rose-500 text-[11px] font-bold text-white shadow-sm">
                                {{ postponeCount }}
                            </div>
                            <span class="mt-0.5 whitespace-nowrap text-[9px] font-semibold text-rose-600 dark:text-rose-400">Postpone</span>
                        </div>
                    </div>
                </article>
            </section>

            <DigitalMasterInitiativeTable
                :items="masterDigitalList"
                :initiative-items="initiativeItemsList"
            />
        </div>
    </UserLayout>
</template>

<script setup>
import { computed, ref, onMounted, onUnmounted, nextTick } from 'vue';
import { Link } from '@inertiajs/vue3';
import UserLayout from '@/Layouts/UserLayout.vue';
import DigitalMasterInitiativeTable from '@/Components/DigitalInitiative/MasterInitiativeTable.vue';

const props = defineProps({
    totalDigitalInitiatives: {
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
    masterDigitalInitiatives: {
        type: Array,
        default: () => [],
    },
    initiativeItems: {
        type: Array,
        default: () => [],
    },
});

const masterDigitalList = computed(() => {
    return Array.isArray(props.masterDigitalInitiatives) ? props.masterDigitalInitiatives : [];
});

const initiativeItemsList = computed(() => {
    return Array.isArray(props.initiativeItems) ? props.initiativeItems : [];
});

// ── Branch flow config ──
const mainBranch = [
    { key: 'drafting',  label: 'Drafting', nodeClass: 'border-slate-400 bg-slate-500 text-white',   labelClass: 'text-slate-600 dark:text-slate-300' },
    { key: 'propose',   label: 'Propose',  nodeClass: 'border-blue-400 bg-blue-500 text-white',     labelClass: 'text-blue-600 dark:text-blue-300' },
    { key: 'review',    label: 'Review',   nodeClass: 'border-amber-400 bg-amber-500 text-white',   labelClass: 'text-amber-600 dark:text-amber-300' },
    { key: 'approve',   label: 'Approve',  nodeClass: 'border-emerald-400 bg-emerald-500 text-white', labelClass: 'text-emerald-600 dark:text-emerald-300' },
    { key: 'finish',    label: 'Finish',   nodeClass: 'border-emerald-500 bg-emerald-600 text-white', labelClass: 'text-emerald-700 dark:text-emerald-300' },
];

const mainSteps = computed(() => {
    const counts = props.statusCounts || {};
    return mainBranch.map((step) => ({
        ...step,
        count: Number(counts[step.key] ?? 0),
    }));
});

const postponeCount = computed(() => {
    const counts = props.statusCounts || {};
    return Number(counts['postpone'] ?? counts['Postpone'] ?? 0);
});

const postponeBranchFrom = computed(() => {
    const counts = props.statusCounts || {};
    let lastActive = 'drafting';
    for (const step of mainBranch) {
        if (Number(counts[step.key] ?? 0) > 0) {
            lastActive = step.key;
        }
    }
    return lastActive;
});

// ── SVG coordinate helpers ──
const branchContainer = ref(null);
const containerWidth = ref(400);

const updateWidth = () => {
    if (branchContainer.value) {
        containerWidth.value = branchContainer.value.offsetWidth;
    }
};

let resizeObserver = null;
onMounted(() => {
    nextTick(updateWidth);
    if (typeof ResizeObserver !== 'undefined' && branchContainer.value) {
        resizeObserver = new ResizeObserver(updateWidth);
        resizeObserver.observe(branchContainer.value);
    }
});
onUnmounted(() => {
    resizeObserver?.disconnect();
});

// Padding matches the px-4 (16px each side) on the node container
const PAD = 16;
const nodeX = (index) => {
    const usable = containerWidth.value - PAD * 2;
    return PAD + (usable / 4) * index;   // 5 nodes → 4 gaps
};

const postponeBranchIndex = computed(() => {
    const idx = mainBranch.findIndex((s) => s.key === postponeBranchFrom.value);
    return idx >= 0 ? idx : 0;
});

// The pixel left for the postpone node (one step to the right of the branch point)
const postponeNodeLeft = computed(() => {
    const idx = Math.min(postponeBranchIndex.value + 1, 4);
    return nodeX(idx);
});

// Smooth cubic bezier: starts at branch-from node, curves down to postpone node
const postponeCurvePath = computed(() => {
    const startX = nodeX(postponeBranchIndex.value);
    const startY = 28;  // main line Y
    const endX = postponeNodeLeft.value;
    const endY = 98;    // postpone node center Y
    // control points for a smooth S-curve like GitHub branches
    const cp1x = startX;
    const cp1y = startY + 40;
    const cp2x = endX;
    const cp2y = endY - 40;
    return `M ${startX} ${startY} C ${cp1x} ${cp1y}, ${cp2x} ${cp2y}, ${endX} ${endY}`;
});
</script>
