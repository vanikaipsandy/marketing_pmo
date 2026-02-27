<template>
    <UserLayout :title="`IT Initiative - ${itInitiative.name}`">
        <div class="space-y-4 print:space-y-0">
            <!-- Top Bar -->
            <section class="print:hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-white/10 dark:bg-[#171717]">
                <div class="flex flex-wrap items-center gap-2 px-3 py-2.5">
                    <Link
                        href="/it-initiatives"
                        class="inline-flex items-center gap-1 rounded-md px-2 py-1 text-xs font-medium text-slate-500 dark:text-slate-400"
                    >
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m15 19-7-7 7-7" />
                        </svg>
                        Back
                    </Link>

                    <span class="text-slate-300 dark:text-slate-600">|</span>

                    <select
                        v-model="selectedProjectId"
                        class="max-w-xs rounded-md border border-slate-200 bg-white px-2 py-1 text-xs text-slate-700 focus:border-[#1C75BC] focus:outline-none dark:border-white/10 dark:bg-[#101826] dark:text-slate-100 lg:max-w-md"
                    >
                        <option
                            v-for="option in projectSelectOptions"
                            :key="`bar-${option.id}`"
                            :value="String(option.id)"
                        >
                            {{ projectOptionLabel(option) }}
                        </option>
                    </select>

                    <div class="ml-auto flex items-center gap-1.5">
                        <button
                            v-for="tab in tabs"
                            :key="tab.key"
                            type="button"
                            class="rounded-md px-2.5 py-1 text-[10px] font-semibold"
                            :class="activeTab === tab.key ? 'bg-slate-800 text-white dark:bg-slate-200 dark:text-slate-900' : 'text-slate-500 dark:text-slate-400'"
                            @click="toggleTab(tab.key)"
                        >
                            {{ tab.label }}
                        </button>
                    </div>
                </div>

                <!-- Detail panel (Status Implementation) -->
                <div v-if="activeTab === 'detail'" class="border-t border-slate-100 dark:border-white/5">
                    <div class="px-3 py-3">
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
                                    <tr>
                                        <td class="px-2 py-2 text-[11px] font-semibold text-slate-700 dark:text-slate-200">{{ itInitiative.code || '-' }}</td>
                                        <td class="px-2 py-2">
                                            <span class="inline-flex rounded-full bg-blue-100 px-2 py-0.5 text-[9px] font-semibold text-blue-800 dark:bg-blue-500/20 dark:text-blue-300">
                                                {{ architectureBlockLabel }}
                                            </span>
                                        </td>
                                        <td class="px-2 py-2 text-[11px] font-medium text-slate-700 break-words dark:text-slate-200">{{ itInitiative.name || '-' }}</td>
                                        <td class="px-2 py-2">
                                            <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[9px] font-medium capitalize" :class="statusBadgeClassById(itInitiative.status)">
                                                {{ statusLabelFromOptions(itInitiative.status, statusOptions) }}
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
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Project Charter Section -->
            <template v-if="activeTab === 'charter'">
                <section class="print:hidden rounded-2xl border border-slate-200 bg-white p-3 shadow-sm dark:border-white/10 dark:bg-[#171717]">
                    <div class="flex flex-col gap-3 lg:flex-row lg:items-end lg:justify-between">
                        <div class="w-full lg:max-w-sm">
                            <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">
                                Charter Version
                            </label>
                            <select
                                v-model="selectedCharterId"
                                :disabled="charterVersions.length === 0 || isEditing"
                                class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 focus:border-indigo-500 focus:ring-indigo-500 disabled:cursor-not-allowed disabled:bg-slate-100 dark:border-white/10 dark:bg-[#131313] dark:text-slate-100 dark:disabled:bg-white/5"
                            >
                                <option v-if="charterVersions.length === 0" value="">No saved version yet</option>
                                <option
                                    v-for="charter in charterVersions"
                                    :key="charter.id"
                                    :value="String(charter.id)"
                                >
                                    {{ charterOptionLabel(charter) }}
                                </option>
                            </select>
                        </div>

                        <div class="flex flex-wrap items-center gap-2">
                            <button type="button" class="rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-medium text-slate-700 dark:border-white/10 dark:text-slate-300" @click="startNewVersion">
                                New Version
                            </button>
                            <button v-if="!isEditing" type="button" class="rounded-lg bg-indigo-600 px-3 py-1.5 text-xs font-semibold text-white" @click="startEdit">
                                Edit Charter
                            </button>
                            <template v-else>
                                <button type="button" class="rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-medium text-slate-700 dark:border-white/10 dark:text-slate-300" @click="cancelEdit">Cancel</button>
                                <button type="button" :disabled="form.processing" class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-3 py-1.5 text-xs font-semibold text-white disabled:opacity-70" @click="saveCharter">
                                    <svg v-if="form.processing" class="h-3 w-3 animate-spin text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 0 1 8-8V0C5.37 0 0 5.37 0 12h4Zm2 5.29A7.95 7.95 0 0 1 4 12H0c0 3.04 1.14 5.82 3 7.94l3-2.65Z"></path></svg>
                                    Save as Version
                                </button>
                            </template>
                            <button type="button" class="rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-medium text-slate-700 dark:border-white/10 dark:text-slate-300" @click="printCharter">Print</button>
                        </div>
                    </div>

                    <div v-if="isEditing" class="mt-3">
                        <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Version Label</label>
                        <input v-model="form.version_label" type="text" class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 focus:border-indigo-500 focus:ring-indigo-500 dark:border-white/10 dark:bg-[#131313] dark:text-slate-100" placeholder="e.g., v2 - reviewed" />
                        <p v-if="form.errors.version_label" class="mt-1 text-xs text-red-500">{{ form.errors.version_label }}</p>
                    </div>
                </section>

                <main class="print:m-0 print:p-0">
                    <CharterDocument :it-initiative="itInitiative" :form="form" :editable="isEditing" />
                </main>
            </template>

            <!-- Roadmap Section -->
            <section v-if="activeTab === 'roadmap'" class="print:hidden">
                <ProjectRoadmap
                    :project="itInitiative"
                    :form="{ objectives: itInitiative.charter?.objectives ?? '', duration: itInitiative.charter?.duration ?? '' }"
                    :sequence="1"
                    :year-start="2025"
                    :year-end="2029"
                />
            </section>
        </div>
    </UserLayout>
</template>

<script setup>
import { computed, ref, watch } from 'vue';
import { Link, router, useForm, usePage } from '@inertiajs/vue3';
import UserLayout from '@/Layouts/UserLayout.vue';
import CharterDocument from './Partials/CharterDocument.vue';
import ProjectRoadmap from '@/Components/Roadmap/ProjectRoadmap.vue';
import { statusBadgeClassById, statusLabelFromOptions } from '@/Composables/initiativeStatus';

const props = defineProps({
    itInitiative: Object,
    projectOptions: {
        type: Array,
        default: () => [],
    },
});
const page = usePage();

// --- Tabs ---
const tabs = [
    { key: 'detail', label: 'Status Implementation' },
    { key: 'charter', label: 'Project Charter' },
    { key: 'roadmap', label: 'Roadmap' },
];
const parseTabFromUrl = () => {
    const query = String(page.url ?? '').split('?')[1] ?? '';
    const params = new URLSearchParams(query);
    const tab = String(params.get('tab') ?? '').trim().toLowerCase();

    if (['detail', 'scope', 'scope-charter', 'scope_charter'].includes(tab)) {
        return 'detail';
    }

    if (['charter', 'project-charter', 'project_charter'].includes(tab)) {
        return 'charter';
    }

    if (tab === 'roadmap') {
        return 'roadmap';
    }

    return 'charter';
};

const activeTab = ref(parseTabFromUrl());
const toggleTab = (key) => {
    activeTab.value = activeTab.value === key ? null : key;
};

// --- Status options ---
const statusOptions = computed(() => {
    const defaultOptions = [
        { id: 1, name: 'drafting', label: 'Drafting' },
        { id: 2, name: 'propose', label: 'Propose' },
        { id: 3, name: 'review', label: 'Review' },
        { id: 4, name: 'approve', label: 'Approve' },
        { id: 5, name: 'baseline', label: 'Baseline' },
    ];

    if (!props.itInitiative?.status_ref?.name) {
        return defaultOptions;
    }

    return defaultOptions.map((option) =>
        option.id === Number(props.itInitiative.status)
            ? {
                ...option,
                name: props.itInitiative.status_ref.name,
                label: String(props.itInitiative.status_ref.name)
                    .replace(/_/g, ' ')
                    .replace(/\b\w/g, (char) => char.toUpperCase()),
            }
            : option
    );
});

// --- Detail panel computed ---
const architectureBlockLabel = computed(() => {
    const text = String(props.itInitiative?.charter?.category ?? '').trim();
    return text.length > 0 ? text : '-';
});

const implementationHistory = computed(() => {
    const source = props.itInitiative?.pc_status_implementations ?? props.itInitiative?.pcStatusImplementations ?? [];
    return Array.isArray(source) ? source : [];
});

const latestImplementationLog = computed(() => {
    return implementationHistory.value.length > 0 ? implementationHistory.value[0] : null;
});

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

// --- Project selector ---
const selectedProjectId = ref(String(props.itInitiative?.id ?? ''));

const projectSelectOptions = computed(() => {
    const source = Array.isArray(props.projectOptions) ? props.projectOptions : [];
    if (source.length > 0) return source;
    return [{
        id: Number(props.itInitiative?.id ?? 0),
        code: props.itInitiative?.code,
        name: props.itInitiative?.name,
        category: props.itInitiative?.charter?.category,
    }];
});

const projectOptionLabel = (option) => {
    const code = String(option?.code ?? '-').trim() || '-';
    const category = String(option?.category ?? '-').trim() || '-';
    const name = String(option?.name ?? '-').trim() || '-';
    return `${code} | ${category} | ${name}`;
};

watch(selectedProjectId, (nextValue, previousValue) => {
    if (!nextValue || nextValue === previousValue) return;
    const selectedId = Number(nextValue);
    if (!Number.isFinite(selectedId) || selectedId <= 0 || selectedId === Number(props.itInitiative?.id)) return;

    const payload = activeTab.value ? { tab: activeTab.value } : {};

    router.get(`/it-initiatives/${selectedId}`, payload, { preserveScroll: true });
});

watch(
    () => props.itInitiative?.id,
    (id) => { selectedProjectId.value = String(id ?? ''); }
);

// --- Charter ---
const CHARTER_FIELDS = [
    'category', 'duration', 'background', 'objectives', 'scope',
    'impact_value', 'key_personnel', 'key_items', 'budget',
    'risks_identified', 'risk_mitigation',
];

const mapCharterToForm = (charter = null) => {
    const payload = { version_label: charter?.version_label ?? '' };
    for (const field of CHARTER_FIELDS) {
        payload[field] = charter?.[field] ?? '';
    }
    return payload;
};

const charterVersions = computed(() => {
    const items = Array.isArray(props.itInitiative?.charters) ? props.itInitiative.charters : [];
    return items.map((charter, index) => ({
        ...charter,
        resolved_version_label: String(charter?.version_label || '').trim() || `v${items.length - index}`,
    }));
});

const defaultCharter = computed(() => props.itInitiative?.charter ?? charterVersions.value[0] ?? null);
const selectedCharterId = ref(defaultCharter.value ? String(defaultCharter.value.id) : '');
const isEditing = ref(false);
const form = useForm(mapCharterToForm(defaultCharter.value));

const selectedCharter = computed(() => {
    if (!selectedCharterId.value) return null;
    return charterVersions.value.find((c) => String(c.id) === String(selectedCharterId.value)) || null;
});

const setFormValues = (charter) => {
    const values = mapCharterToForm(charter);
    form.defaults(values);
    form.reset();
    form.clearErrors();
};

watch(() => selectedCharterId.value, () => {
    if (isEditing.value) return;
    setFormValues(selectedCharter.value ?? defaultCharter.value);
});

watch(() => charterVersions.value[0]?.id ?? null, (latestId) => {
    if (!latestId) return;
    const hasSelected = charterVersions.value.some((c) => String(c.id) === String(selectedCharterId.value));
    if (hasSelected) return;
    selectedCharterId.value = String(latestId);
    if (!isEditing.value) setFormValues(charterVersions.value[0]);
});

const startEdit = () => {
    setFormValues(selectedCharter.value ?? defaultCharter.value);
    isEditing.value = true;
};

const startNewVersion = () => {
    const base = selectedCharter.value ?? defaultCharter.value;
    const values = mapCharterToForm(base);
    values.version_label = '';
    form.defaults(values);
    form.reset();
    form.clearErrors();
    isEditing.value = true;
};

const saveCharter = () => {
    form.post(`/it-initiatives/${props.itInitiative.id}/charter`, {
        preserveScroll: true,
        onSuccess: () => {
            isEditing.value = false;
            selectedCharterId.value = '';
        },
    });
};

const cancelEdit = () => {
    isEditing.value = false;
    setFormValues(selectedCharter.value ?? defaultCharter.value);
};

const printCharter = () => window.print();

const charterOptionLabel = (charter) => {
    const createdAt = charter?.created_at ? new Date(charter.created_at) : null;
    const createdLabel = createdAt && !Number.isNaN(createdAt.getTime())
        ? createdAt.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' })
        : '-';
    return `${charter.resolved_version_label} - ${createdLabel}`;
};
</script>
