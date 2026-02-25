<script setup>
import { computed } from 'vue';

const props = defineProps({
    project:   { type: Object, required: true },
    form:      { type: Object, required: true },
    sequence:  { type: [Number, String], default: null },
    yearStart: { type: Number, default: 2025 },
    yearEnd:   { type: Number, default: 2029 },
    milestoneTypeOptions: { type: Array, default: () => [] },
});

/* ── Year / Quarter grid ─────────────────────────────── */

const yStart = computed(() => Number(props.yearStart) || 2025);
const yEnd   = computed(() => {
    const e = Number(props.yearEnd) || 2029;
    return e >= yStart.value ? e : yStart.value;
});

const years = computed(() =>
    Array.from({ length: yEnd.value - yStart.value + 1 }, (_, i) => yStart.value + i)
);

const quarterCells = computed(() =>
    years.value.flatMap((year) => [1, 2, 3, 4].map((quarter) => ({ year, quarter })))
);

const totalCells = computed(() => quarterCells.value.length);

const fallbackMilestoneTypeOptions = [
    { value: 1, label: '1', timeline_style: 'block' },
    { value: 2, label: '2', timeline_style: 'dashed' },
    { value: 3, label: '3', timeline_style: 'block' },
    { value: 4, label: '4', timeline_style: 'dashed' },
    { value: 5, label: '5', timeline_style: 'block' },
];

const milestoneTypeOptionsMap = computed(() => {
    const source = Array.isArray(props.milestoneTypeOptions) && props.milestoneTypeOptions.length > 0
        ? props.milestoneTypeOptions
        : fallbackMilestoneTypeOptions;

    return source.reduce((accumulator, option) => {
        const code = Number(option.value);

        if (Number.isFinite(code) && code > 0) {
            accumulator.set(code, {
                value: code,
                label: String(option.label ?? code),
                timelineStyle: String(option.timeline_style ?? 'block'),
            });
        }

        return accumulator;
    }, new Map());
});

const defaultMilestoneType = 1;

const normalizeMilestoneType = (value) => {
    const normalized = Number(value);

    if (milestoneTypeOptionsMap.value.has(normalized)) {
        return normalized;
    }

    return defaultMilestoneType;
};

const timelineStyleByType = (typeCode) => {
    const normalized = normalizeMilestoneType(typeCode);
    return milestoneTypeOptionsMap.value.get(normalized)?.timelineStyle === 'dashed'
        ? 'dashed'
        : 'block';
};

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
    if (!meta || totalCells.value === 0) return null;
    const raw = (meta.year - yStart.value) * 4 + Math.floor(meta.monthIndex / 3);
    return Math.max(0, Math.min(raw, totalCells.value - 1));
};

const toLineItems = (value) =>
    String(value || '').split(/\r?\n/).map((l) => l.trim()).filter(Boolean);

/* ── Milestones → rows ───────────────────────────────── */

const objectives = computed(() =>
    String(props.form?.objectives || '').split(/\r?\n/).map((l) => l.trim()).filter(Boolean)
);

const sortedMilestones = computed(() =>
    [...(props.project?.milestones ?? [])].sort((a, b) => {
        const od = (Number(a.order ?? 0)) - (Number(b.order ?? 0));
        return od !== 0 ? od : String(a.start_date ?? '').localeCompare(String(b.start_date ?? ''));
    })
);

const buildRowsFromMilestones = () => {
    const sections = new Map();
    for (const ms of sortedMilestones.value) {
        const sectionLabel = String(ms.type || 'Roadmap Activity').trim() || 'Roadmap Activity';
        const typeCode = normalizeMilestoneType(ms.milestone_type ?? ms.type);
        const si     = toQuarterIndex(ms.start_date ?? ms.end_date);
        const ei     = toQuarterIndex(ms.end_date   ?? ms.start_date);
        const hasTL  = Boolean(ms.start_date || ms.end_date) && si !== null && ei !== null;
        if (!sections.has(sectionLabel)) sections.set(sectionLabel, []);
        sections.get(sectionLabel).push({
            activity:    ms.title || '-',
            output:      toLineItems(ms.output),
            hasTimeline: hasTL,
            start:       hasTL ? Math.min(si, ei) : null,
            end:         hasTL ? Math.max(si, ei) : null,
            timelineStyle: timelineStyleByType(typeCode),
        });
    }
    return [...sections.entries()].map(([label, rows]) => ({ label, rows }));
};

const roadmapSections = computed(() => {
    if (sortedMilestones.value.length > 0) return buildRowsFromMilestones();
    const rows = objectives.value.length
        ? objectives.value.map((obj) => ({ activity: obj, output: [obj], hasTimeline: false, start: null, end: null, timelineStyle: 'block' }))
        : [{ activity: 'Belum ada activity roadmap', output: [], hasTimeline: false, start: null, end: null, timelineStyle: 'block' }];
    return [{ label: objectives.value.length ? 'Objectives' : 'Roadmap Activity', rows }];
});

const isActive = (row, idx) => row.hasTimeline && idx >= row.start && idx <= row.end;

const timelineCellClass = (row, quarterIndex, cell) => ({
    'cell-tl-active-block': isActive(row, quarterIndex) && row.timelineStyle !== 'dashed',
    'cell-tl-active-dashed': isActive(row, quarterIndex) && row.timelineStyle === 'dashed',
    'border-r-blue': cell.quarter === 4,
});
</script>

<template>
    <div class="roadmap-wrap overflow-x-auto">
        <table class="roadmap-table" :style="{ '--qcount': Math.max(totalCells, 1) }">
            <colgroup>
                <col class="col-no">
                <col class="col-initiative">
                <col v-for="(_, i) in quarterCells" :key="`cq-${i}`" class="col-quarter">
                <col class="col-output">
            </colgroup>

            <!-- ── HEAD ── -->
            <thead>
                <tr>
                    <th rowspan="2" class="th">No</th>
                    <th rowspan="2" class="th th-left">IT Initiative Roadmap {{ yStart }}–{{ yEnd }}</th>
                    <th v-for="year in years" :key="`y-${year}`" colspan="4" class="th th-year">{{ year }}</th>
                    <th rowspan="2" class="th">Output</th>
                </tr>
                <tr>
                    <th
                        v-for="(cell, i) in quarterCells"
                        :key="`qh-${i}`"
                        class="th-q"
                        :class="{ 'border-r-blue': cell.quarter === 4 }"
                    >Q{{ cell.quarter }}</th>
                </tr>
            </thead>

            <!-- ── BODY ── -->
            <tbody>
                <!-- Project name row -->
                <tr class="row-project">
                    <td class="cell-no">{{ sequence ?? '' }}</td>
                    <td class="cell-project-name">{{ project.name || '-' }}</td>
                    <td
                        v-for="(cell, i) in quarterCells" :key="`pg-${i}`"
                        class="cell-tl"
                        :class="{ 'border-r-blue': cell.quarter === 4 }"
                    ></td>
                    <td class="cell-output">–</td>
                </tr>

                <!-- Sections & activities -->
                <template v-for="(section, si) in roadmapSections" :key="`sec-${si}`">
                    <!-- Section header row -->
                    <tr class="row-section">
                        <td class="cell-no"></td>
                        <td class="cell-section">{{ section.label }}</td>
                        <td
                            v-for="(cell, i) in quarterCells" :key="`sg-${si}-${i}`"
                            class="cell-section-gap"
                            :class="{ 'border-r-blue': cell.quarter === 4 }"
                        ></td>
                        <td class="cell-section-gap"></td>
                    </tr>

                    <!-- Activity rows -->
                    <tr v-for="(row, ri) in section.rows" :key="`row-${si}-${ri}`" class="row-data">
                        <td class="cell-no"></td>
                        <td class="cell-activity">{{ row.activity }}</td>
                        <td
                            v-for="(cell, i) in quarterCells" :key="`tl-${si}-${ri}-${i}`"
                            class="cell-tl"
                            :class="timelineCellClass(row, i, cell)"
                        ></td>
                        <td class="cell-output">
                            <ul v-if="row.output.length" class="output-list">
                                <li v-for="(item, oi) in row.output" :key="`out-${si}-${ri}-${oi}`">{{ item }}</li>
                            </ul>
                            <span v-else>–</span>
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>
</template>

<style scoped>
/* ── Variables ─────────────────────────────────── */
.roadmap-wrap {
    --blue:   #1c75bc;
    --blue-lt: #e2f0fb;
    --grid:   #b9d1e8;
    --text:   #0f172a;
    --text-sm: #111827;
    --bg:     #ffffff;
    --bg-row: #f9fbff;
    --active: #000000;
    font-family: "Segoe UI", Arial, sans-serif;
}

/* ── Table shell ────────────────────────────────── */
.roadmap-table {
    width: 100%;
    table-layout: fixed;
    border-collapse: collapse;
    border: 2px solid var(--blue);
    background: var(--bg);
}

.roadmap-table th,
.roadmap-table td {
    border: 1px solid var(--grid);
    overflow: hidden;
}

/* ── Column widths ──────────────────────────────── */
.col-no         { width: 3%; }
.col-initiative { width: 28%; }
.col-quarter    { width: calc(44% / var(--qcount)); }
.col-output     { width: 25%; }

/* ── Header cells ───────────────────────────────── */
.th {
    background: var(--blue);
    color: #fff;
    font-size: 10px;
    font-weight: 700;
    padding: 5px 6px;
    text-align: center;
    vertical-align: middle;
    line-height: 1.2;
}
.th-left { text-align: left; }
.th-year { text-align: center; }

.th-q {
    background: var(--blue-lt);
    color: var(--blue);
    font-size: 9px;
    font-weight: 700;
    text-align: center;
    padding: 2px 0;
    border-top: 1px solid var(--grid);
}

/* ── Year-end divider ───────────────────────────── */
.border-r-blue { border-right: 2px solid var(--blue) !important; }

/* ── Row types ──────────────────────────────────── */
.row-project  { background: var(--bg); }
.row-section  { background: var(--blue-lt); }
.row-data     { background: var(--bg-row); }

/* ── Data cells ─────────────────────────────────── */
.cell-no {
    font-size: 9px;
    font-weight: 700;
    text-align: center;
    color: var(--text-sm);
    padding: 3px 4px;
    vertical-align: top;
}

.cell-project-name {
    font-size: 12px;
    font-weight: 800;
    color: var(--text);
    padding: 5px 8px;
    vertical-align: middle;
    line-height: 1.3;
}

.cell-section {
    font-size: 10px;
    font-weight: 700;
    color: #000000;
    padding: 3px 8px 3px 16px;
    border-left: 3px solid #000000;
    line-height: 1.2;
    vertical-align: middle;
}

.cell-activity {
    font-size: 11px;
    color: #000000;
    padding: 3px 8px 3px 24px;
    line-height: 1.3;
    vertical-align: top;
}

.cell-section-gap { height: 14px; padding: 0; }

/* ── Timeline cells ─────────────────────────────── */
.cell-tl {
    height: 20px;
    padding: 0;
    background: var(--bg-row);
}
.cell-tl-active-block { background: var(--active); }

.cell-tl-active-dashed {
    background-color: var(--bg-row);
    background-image: repeating-linear-gradient(
        90deg,
        #000000 0 6px,
        transparent 6px 10px
    );
    background-position: center;
    background-repeat: no-repeat;
    background-size: 100% 2px;
}

/* ── Output cell ────────────────────────────────── */
.cell-output {
    font-size: 10px;
    color: #000000;
    padding: 4px 6px;
    vertical-align: top;
    line-height: 1.3;
    background: var(--bg-row);
}

.output-list {
    margin: 0;
    padding-left: 14px;
}
.output-list li + li { margin-top: 2px; }
</style>
