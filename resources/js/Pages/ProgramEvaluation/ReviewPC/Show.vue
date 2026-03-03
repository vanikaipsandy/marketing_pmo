<template>
    <UserLayout title="Detail Review PC">
        <div class="animate-fade-in-up">
            <section
                class="mx-auto mb-3 w-full max-w-[1200px] rounded-2xl border border-slate-200 bg-white shadow-sm print:hidden dark:border-white/10 dark:bg-[#171717]">
                <div class="flex flex-wrap items-center gap-2 px-3 py-2.5">
                    <Link href="/program-evalution"
                        class="inline-flex items-center gap-1 rounded-md px-2 py-1 text-xs font-medium text-slate-500 dark:text-slate-400">
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m15 19-7-7 7-7" />
                        </svg>
                        Kembali
                    </Link>
                    <span class="text-slate-300 dark:text-slate-600">|</span>
                    <label for="review" class="text-xs font-medium text-slate-700 dark:text-slate-200">Review</label>
                    <select v-if="reviewOptions.length" v-model="selectedReviewId"
                        class="max-w-xs rounded-md border border-slate-200 bg-white px-2 py-1 text-xs text-slate-700 focus:border-[#1C75BC] focus:outline-none dark:border-white/10 dark:bg-[#101826] dark:text-slate-100">
                        <option v-for="option in reviewOptions" :key="`review-opt-${option.id}`"
                            :value="String(option.id)">
                            {{ formatReviewLabel(option) }}
                        </option>
                    </select>
                    <div class="ml-auto flex items-center gap-1.5">
                        <button type="button" class="rounded-md px-2.5 py-1 text-[10px] font-semibold"
                            :class="activeNav === 'project-charter' ? 'bg-slate-800 text-white dark:bg-slate-200 dark:text-slate-900' : 'text-slate-500 dark:text-slate-400'"
                            @click="setActiveNav('project-charter')">
                            Project Charter
                        </button>
                        <button type="button" class="rounded-md px-2.5 py-1 text-[10px] font-semibold"
                            :class="activeNav === 'initiative-relation' ? 'bg-slate-800 text-white dark:bg-slate-200 dark:text-slate-900' : 'text-slate-500 dark:text-slate-400'"
                            @click="setActiveNav('initiative-relation')">
                            Initiative Relation
                        </button>
                        <button type="button" class="rounded-md px-2.5 py-1 text-[10px] font-semibold"
                            :class="activeNav === 'roadmap' ? 'bg-slate-800 text-white dark:bg-slate-200 dark:text-slate-900' : 'text-slate-500 dark:text-slate-400'"
                            @click="setActiveNav('roadmap')">
                            Roadmap
                        </button>
                        <button type="button" class="rounded-md px-2.5 py-1 text-[10px] font-semibold"
                            :class="activeNav === 'review' ? 'bg-slate-800 text-white dark:bg-slate-200 dark:text-slate-900' : 'text-slate-500 dark:text-slate-400'"
                            @click="setActiveNav('review')">
                            Review
                        </button>
                    </div>
                    <Link v-if="activeNav === 'review'" :href="`/program-evalution/review?edit=${trsReviewPC.id}`"
                        class="inline-flex items-center gap-2 rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-medium text-slate-700 transition hover:bg-slate-50 dark:border-white/10 dark:text-slate-300 dark:hover:bg-white/5">
                        Edit
                    </Link>
                    <button type="button"
                        class="inline-flex items-center gap-2 rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-medium text-slate-700 transition hover:bg-slate-50 dark:border-white/10 dark:text-slate-300 dark:hover:bg-white/5"
                        @click="printPage">
                        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 9V4h12v5M6 17h12v3H6v-3Zm-2-2h16a2 2 0 0 0 2-2v-2a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v2a2 2 0 0 0 2 2Z" />
                        </svg>
                        Print
                    </button>
                </div>
            </section>
            <div id="review"
                class="mx-auto w-full max-w-[1200px] space-y-0 border border-slate-300 bg-[#e9e9e9] p-0 text-slate-900 shadow-sm dark:border-white/20 dark:bg-[#d7d7d7]">
                <header class="space-y-2 px-4 pt-3 pb-3">
                    <h1 class="text-2xl font-extrabold tracking-tight text-slate-900 dark:text-white">
                        Review Project Charter: {{ initiativeName }}
                    </h1>
                </header>

                <section v-if="activeNav === 'review'" id="review" class="space-y-0">
                    <div class="px-3 py-3">
                        <StatusImplementationTable :project="mappedProject" />
                    </div>
                    <ReviewContent :review="review" :penjelasan-items="penjelasanItems" :why-items="whyItems"
                        :how-parsed="howParsed" :project-profile-items="projectProfileItems" />
                </section>

                <section v-if="activeNav === 'project-charter'" id="project-charter" class="space-y-0">
                    <div v-if="mappedProject"
                        class="border border-slate-200 bg-white shadow-sm dark:border-white/10 dark:bg-[#171717]">
                        <div class="px-3 py-3">
                            <StatusImplementationTable :project="mappedProject" />
                        </div>
                        <ItCharterDocument :it-initiative="mappedProject" :form="charterForm" :editable="false" />
                    </div>
                    <div v-else
                        class="border border-slate-200 bg-white px-4 py-6 text-center text-xs text-slate-500 dark:border-white/10 dark:bg-[#171717] dark:text-slate-400">
                        Data project charter belum tersedia untuk initiative ini.
                    </div>
                </section>

                <section v-if="activeNav === 'initiative-relation'" id="initiative-relation" class="space-y-0">
                    <div v-if="mappedProject"
                        class="overflow-hidden border border-slate-200 bg-white shadow-sm dark:border-white/10 dark:bg-[#171717] p-6">
                        <div class="px-3 py-3">
                            <StatusImplementationTable :project="mappedProject" />
                        </div>
                        <InitiativeDetailsWithRelations :initiative="mappedProject" :relations="initiativeRelations"
                            variant="emerald" status-label="Source" relations-title="Initiative Relations"
                            column-a-label="Predecessor" column-b-label="Successor" />
                    </div>
                    <div v-else
                        class="border border-slate-200 bg-white px-4 py-6 text-center text-xs text-slate-500 dark:border-white/10 dark:bg-[#171717] dark:text-slate-400">
                        Data initiative relation belum tersedia untuk initiative ini.
                    </div>
                </section>

                <section v-if="activeNav === 'roadmap'" id="roadmap" class="print:hidden">
                    <div class="px-3 py-3">
                        <StatusImplementationTable :project="mappedProject" />
                    </div>
                    <ProjectRoadmap v-if="mappedProject" :project="mappedProject"
                        :form="{ objectives: mappedProject?.charter?.objectives ?? '', duration: mappedProject?.charter?.duration ?? '' }"
                        :sequence="1" :year-start="2025" :year-end="2029" />
                    <div v-else
                        class="border border-slate-200 bg-white px-4 py-6 text-center text-xs text-slate-500 dark:border-white/10 dark:bg-[#171717] dark:text-slate-400">
                        Data roadmap belum tersedia untuk initiative ini.
                    </div>
                </section>
            </div>
        </div>
    </UserLayout>
</template>

<script setup>
import { computed, onMounted, ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import UserLayout from '@/Layouts/UserLayout.vue';
import ItCharterDocument from '@/Components/ProjectCharter/ItCharterDocument.vue';
import ProjectRoadmap from '@/Components/Roadmap/ProjectRoadmap.vue';
import StatusImplementationTable from '@/Components/ITInitiative/StatusImplementationTable.vue';
import ReviewContent from '@/Components/ReviewPC/ReviewContent.vue';
import InitiativeDetailsWithRelations from '@/Components/InitiativeRelation/InitiativeDetailsWithRelations.vue';

const props = defineProps({
    trsReviewPC: {
        type: Object,
        required: true,
    },
    reviewOptions: {
        type: Array,
        default: () => [],
    },
    mappedProject: {
        type: Object,
        default: null,
    },
    mstInitiatives: {
        type: Array,
        default: () => [],
    },
});

const review = computed(() => ({
    ...props.trsReviewPC,
    detail_kesimpulan: props.trsReviewPC?.detail_kesimpulan ?? props.trsReviewPC?.detail_penjelasan ?? '',
}));

const initiativeName = computed(() => review.value?.initiative?.name ?? '-');

const normalizeText = (value) => String(value ?? '').replace(/\u200B/g, '').trim();

const splitToItems = (value) => {
    const text = normalizeText(value);
    if (!text) return [];

    let items = text
        .split(/\r?\n/)
        .map((line) => line.trim())
        .filter(Boolean);

    if (items.length === 1 && text.includes(' - ')) {
        items = text.split(/\s+-\s+/).map((line) => line.trim()).filter(Boolean);
    }

    return items.map((item) => item.replace(/^[-*]\s*/, '').trim()).filter(Boolean);
};

const parseHow = (value) => {
    const text = normalizeText(value);
    if (!text) {
        return { intro: '', steps: [] };
    }

    const stepMatches = Array.from(text.matchAll(/(?:^|[;\n])\s*\d+\.\s*([^;\n]+)/g));
    if (stepMatches.length) {
        const firstIndex = stepMatches[0].index ?? 0;
        const intro = text.slice(0, firstIndex).trim();
        const steps = stepMatches.map((match) => match[1].trim()).filter(Boolean);
        return { intro, steps };
    }

    return { intro: '', steps: splitToItems(text) };
};

const penjelasanItems = computed(() => splitToItems(review.value?.penjelasan));
const whyItems = computed(() => splitToItems(review.value?.why));
const projectProfileItems = computed(() => splitToItems(review.value?.project_profile));
const howParsed = computed(() => parseHow(review.value?.how));

const printPage = () => {
    window.print();
};

const activeNav = ref('review');

const setActiveNav = (value) => {
    activeNav.value = value;
};

onMounted(() => {
    const hash = String(window.location.hash || '').replace('#', '');
    if (hash) {
        activeNav.value = hash;
    }
});

const selectedReviewId = computed({
    get: () => String(props.trsReviewPC?.id ?? ''),
    set: (value) => {
        if (!value || String(value) === String(props.trsReviewPC?.id)) return;
        router.visit(`/program-evalution/review/${value}`);
    },
});

const formatReviewLabel = (option) => {
    const code = option?.initiative?.code ?? option?.initiative_id ?? option?.id ?? '-';
    const name = option?.initiative?.name ?? 'Initiative';
    return `${code} - ${name}`;
};

const charterForm = computed(() => {
    const charter = props.mappedProject?.charter ?? {};
    return {
        category: charter?.category ?? '',
        duration: charter?.duration ?? '',
        background: charter?.background ?? '',
        objectives: charter?.objectives ?? '',
        scope: charter?.scope ?? '',
        impact_value: charter?.impact_value ?? '',
        key_personnel: charter?.key_personnel ?? '',
        key_items: charter?.key_items ?? '',
        budget: charter?.budget ?? '',
        risks_identified: charter?.risks_identified ?? '',
        risk_mitigation: charter?.risk_mitigation ?? '',
    };
});

const initiativeLabel = (initiative) => {
    const code = initiative?.code ?? initiative?.id ?? '-';
    const name = initiative?.name ?? '';
    const baseLabel = name ? `${code} - ${name}` : code;
    const coeName = initiative?.coe_name ?? initiative?.coe?.name ?? '';
    return coeName ? `${baseLabel} (CoE: ${coeName})` : baseLabel;
};

const findInitiativeById = (initiativeId) => {
    if (!initiativeId || !props.mstInitiatives.length) return null;
    return props.mstInitiatives.find(opt => Number(opt.id) === Number(initiativeId));
};

const buildInitiativeRelations = () => {
    if (!props.mappedProject) return [];

    const projectId = Number(props.mappedProject?.id);
    const rows = [];
    const seen = new Set();

    // Get all relations from the mapped project
    const allRelations = [
        ...(props.mappedProject?.initiativeRelationsRow ?? props.mappedProject?.initiative_relations_row ?? []),
        ...(props.mappedProject?.initiativeRelationsColumn ?? props.mappedProject?.initiative_relations_column ?? []),
    ];

    const relationKey = (relation) => {
        if (relation?.id) {
            return `id-${relation.id}`;
        }
        return `row-${relation?.initiative_code_row}-col-${relation?.initiative_code_column}`;
    };

    allRelations.forEach((relation) => {
        const key = relationKey(relation);
        if (seen.has(key)) {
            return;
        }
        seen.add(key);

        const rowInitiative = relation.initiative_row;
        const columnInitiative = relation.initiative_column;
        const rowFallback = relation.initiative_code_row;
        const columnFallback = relation.initiative_code_column;
        const justifikasi = relation.justifikasi ?? relation.description ?? '-';

        rows.push({
            id: relation.id ?? `${relation.initiative_code_row}-${relation.initiative_code_column}`,
            predecessorLabel: rowInitiative ? initiativeLabel(rowInitiative) : (rowFallback ?? '-'),
            successorLabel: columnInitiative ? initiativeLabel(columnInitiative) : (columnFallback ?? '-'),
            modelRelasi: relation.model_relasi ?? '-',
            justifikasi,
        });
    });

    return rows;
};

const initiativeRelations = computed(() => buildInitiativeRelations());

</script>