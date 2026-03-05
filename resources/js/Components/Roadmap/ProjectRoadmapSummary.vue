<script setup>
import { computed } from 'vue';
import { ChevronDownIcon, ChevronUpIcon } from '@heroicons/vue/24/outline';

const props = defineProps({
    project:   { type: Object, required: true },
    sequence:  { type: [Number, String], default: null },
    yearStart: { type: Number, default: 2025 },
    yearEnd:   { type: Number, default: 2029 },
    expanded:  { type: Boolean, default: false },
});

const emit = defineEmits(['toggle']);

/* ── Year / Quarter grid ─────────────────────────────── */

const yStart = computed(() => Number(props.yearStart) || 2025);
const yEnd   = computed(() => {
    const e = Number(props.yearEnd) || 2029;
    return e >= yStart.value ? e : yStart.value;
});

const years = computed(() =>
    Array.from({ length: yEnd.value - yStart.value + 1 }, (_, i) => yStart.value + i)
);

const totalQuarters = computed(() => years.value.length * 4);

/* ── Date helpers ────────────────────────────────────── */

const parseDateMeta = (value) => {
    if (!value) return null;
    const m = String(value).match(/^(\d{4})-(\d{2})-(\d{2})/);
    if (m) return { year: +m[1], monthIndex: +m[2] - 1 };
    const d = new Date(value);
    if (Number.isNaN(d.getTime())) return null;
    return { year: d.getUTCFullYear(), monthIndex: d.getUTCMonth() };
};

const toQuarterIndex = (dateValue) => {
    const meta = parseDateMeta(dateValue);
    if (!meta || totalQuarters.value === 0) return null;
    const raw = (meta.year - yStart.value) * 4 + Math.floor(meta.monthIndex / 3);
    return Math.max(0, Math.min(raw, totalQuarters.value - 1));
};

/* ── Compute combined milestone range ────────────────── */

const milestoneRange = computed(() => {
    const milestones = props.project?.milestones ?? [];
    if (milestones.length === 0) return null;

    let minIndex = totalQuarters.value;
    let maxIndex = -1;

    for (const ms of milestones) {
        const si = toQuarterIndex(ms.start_date ?? ms.end_date);
        const ei = toQuarterIndex(ms.end_date ?? ms.start_date);
        if (si !== null) minIndex = Math.min(minIndex, si);
        if (ei !== null) maxIndex = Math.max(maxIndex, ei);
    }

    if (minIndex > maxIndex) return null;
    return { start: minIndex, end: maxIndex };
});

const milestoneCount = computed(() => (props.project?.milestones ?? []).length);

/* ── Bar as percentage ──────────────────────────────── */

const barStyle = computed(() => {
    if (!milestoneRange.value || totalQuarters.value === 0) return null;
    const left = (milestoneRange.value.start / totalQuarters.value) * 100;
    const width = ((milestoneRange.value.end - milestoneRange.value.start + 1) / totalQuarters.value) * 100;
    return { left: `${left}%`, width: `${width}%` };
});
</script>

<template>
    <div
        class="summary-row"
        :class="{ 'summary-row--expanded': expanded }"
        role="button"
        tabindex="0"
        @click="emit('toggle')"
        @keydown.enter.prevent="emit('toggle')"
    >
        <!-- No -->
        <div class="col-no">
            <span class="seq-badge">{{ sequence ?? '' }}</span>
        </div>

        <!-- Name -->
        <div class="col-name">
            <span class="project-name">{{ project.name || '-' }}</span>
        </div>

        <!-- Timeline grid -->
        <div class="col-timeline">
            <!-- Year headers -->
            <div class="year-row">
                <div v-for="year in years" :key="`y-${year}`" class="year-cell">{{ year }}</div>
            </div>

            <!-- Bar track -->
            <div class="bar-track">
                <!-- Year divider lines -->
                <span
                    v-for="(year, yi) in years"
                    :key="`div-${year}`"
                    class="year-divider"
                    :style="{ left: `${((yi + 1) / years.length) * 100}%` }"
                ></span>

                <!-- The bar -->
                <div v-if="barStyle" class="bar-fill" :style="barStyle"></div>
            </div>
        </div>

        <!-- Toggle -->
        <div class="col-toggle">
            <span class="toggle-btn">
                <ChevronUpIcon v-if="expanded" class="toggle-icon" />
                <ChevronDownIcon v-else class="toggle-icon" />
            </span>
        </div>
    </div>
</template>

<style scoped>
.summary-row {
    display: flex;
    align-items: stretch;
    border: 1px solid #d0dce8;
    border-bottom: none;
    background: #ffffff;
    cursor: pointer;
    transition: background 0.12s ease;
    min-height: 38px;
    font-family: "Segoe UI", Arial, sans-serif;
}
.summary-row:last-child,
.summary-row--expanded {
    border-bottom: 1px solid #d0dce8;
}
.summary-row:first-child {
    border-radius: 6px 6px 0 0;
}
.summary-row:hover {
    background: #f0f7ff;
}
.summary-row--expanded {
    background: #eaf3fb;
    border-color: #1c75bc;
}

/* ── No column ─── */
.col-no {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 36px;
    flex-shrink: 0;
    border-right: 1px solid #d0dce8;
}
.seq-badge {
    font-size: 10px;
    font-weight: 700;
    color: #1c75bc;
}

/* ── Name column ─── */
.col-name {
    display: flex;
    align-items: center;
    width: 28%;
    flex-shrink: 0;
    padding: 0 12px;
    border-right: 1px solid #d0dce8;
    min-width: 0;
}
.project-name {
    font-size: 11px;
    font-weight: 600;
    color: #0f172a;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    line-height: 1.3;
}

/* ── Timeline column ─── */
.col-timeline {
    flex: 1 1 auto;
    display: flex;
    flex-direction: column;
    justify-content: center;
    padding: 4px 0;
    min-width: 0;
    overflow: hidden;
}

.year-row {
    display: flex;
    padding: 0 2px;
}
.year-cell {
    flex: 1;
    text-align: center;
    font-size: 9px;
    font-weight: 700;
    color: #64748b;
    line-height: 1;
    padding-bottom: 3px;
}

.bar-track {
    position: relative;
    height: 12px;
    margin: 0 2px;
    background: #f1f5f9;
    border-radius: 3px;
}

.year-divider {
    position: absolute;
    top: 0;
    bottom: 0;
    width: 1px;
    background: #cbd5e1;
    pointer-events: none;
}
.year-divider:last-of-type {
    display: none;
}

.bar-fill {
    position: absolute;
    top: 1px;
    bottom: 1px;
    border-radius: 2px;
    background: linear-gradient(90deg, #1c75bc, #2b87cc);
    transition: width 0.25s ease, left 0.25s ease;
}

/* ── Toggle column ─── */
.col-toggle {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 36px;
    flex-shrink: 0;
    border-left: 1px solid #d0dce8;
}
.toggle-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 22px;
    height: 22px;
    border-radius: 4px;
    color: #1c75bc;
    transition: background 0.12s ease;
}
.summary-row:hover .toggle-btn {
    background: #dbeafe;
}
.toggle-icon {
    width: 14px;
    height: 14px;
}
</style>
