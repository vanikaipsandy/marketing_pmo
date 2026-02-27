<template>
    <section
        class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-white/10 dark:bg-[#171717]">
        <div
            class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-200 px-5 py-4 dark:border-white/10">
            <div
                class="flex flex-wrap items-center gap-3 text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                <div class="flex items-center gap-2">
                    <label for="initiative-type-filter" class="text-[11px]">Filter Type</label>
                    <select id="initiative-type-filter" v-model="selectedType"
                        class="rounded-lg border border-slate-200 bg-white px-2.5 py-1 text-xs font-semibold text-slate-700 shadow-sm transition focus:border-slate-300 focus:outline-none dark:border-white/10 dark:bg-[#1f1f1f] dark:text-slate-200">
                        <option value="all">All</option>
                        <option value="digital">Digital</option>
                        <option value="it">IT</option>
                    </select>
                </div>

                <div class="flex items-center gap-2">
                    <label for="model-relasi-filter" class="text-[11px]">Model Relasi</label>
                    <select id="model-relasi-filter" v-model="selectedModelRelasi"
                        class="rounded-lg border border-slate-200 bg-white px-2.5 py-1 text-xs font-semibold text-slate-700 shadow-sm transition focus:border-slate-300 focus:outline-none dark:border-white/10 dark:bg-[#1f1f1f] dark:text-slate-200">
                        <option value="all">All</option>
                        <option v-for="option in modelRelasiOptions" :key="option" :value="option">
                            {{ option }}
                        </option>
                    </select>
                </div>

                <div class="flex items-center gap-2">
                    <label for="initiative-filter" class="text-[11px]">Initiative</label>
                    <select id="initiative-filter" v-model="selectedInitiative"
                        class="w-48 max-w-full rounded-lg border border-slate-200 bg-white px-2.5 py-1 text-xs font-semibold text-slate-700 shadow-sm transition focus:border-slate-300 focus:outline-none dark:border-white/10 dark:bg-[#1f1f1f] dark:text-slate-200">
                        <option value="all">All</option>
                        <option v-for="option in filteredInitiativeOptions" :key="option.id" :value="option.id">
                            {{ option.label }}
                        </option>
                    </select>
                </div>
            </div>
        </div>

        <div>
            <table class="w-full min-w-[1120px] border-collapse text-sm">
                <tbody>
                    <tr v-if="!filteredInitiatives.length">
                        <td colspan="5"
                            class="border border-slate-200 px-4 py-6 text-center text-sm text-slate-500 dark:border-white/10 dark:text-slate-400">
                            Belum ada data initiative.
                        </td>
                    </tr>
                    <template v-for="initiative in filteredInitiatives" :key="initiative.id">
                        <!-- Initiative Header Row -->
                        <tr class="bg-slate-100 dark:bg-slate-900/30">
                            <td colspan="5"
                                class="border border-slate-200 px-4 py-3 text-sm font-semibold text-slate-800 dark:border-white/10 dark:text-slate-200">
                                <span class="text-slate-600 dark:text-slate-400">Code:</span>
                                {{ initiative.code ?? initiative.id ?? '-' }}
                                <span class="mx-2 text-slate-400">|</span>
                                <span class="text-slate-600 dark:text-slate-400">Initiative:</span>
                                {{ initiative.name ?? '-' }}
                            </td>
                        </tr>
                        <!-- Column Headers for each Initiative -->
                        <tr class="bg-slate-50 dark:bg-white/5">
                            <th
                                class="border border-slate-200 bg-slate-50 px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500 dark:border-white/10 dark:bg-[#1f1f1f] dark:text-slate-400">
                                Predecessor
                            </th>
                            <th
                                class="border border-slate-200 bg-slate-50 px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500 dark:border-white/10 dark:bg-[#1f1f1f] dark:text-slate-400">
                                Successor
                            </th>
                            <th
                                class="border border-slate-200 bg-slate-50 px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500 dark:border-white/10 dark:bg-[#1f1f1f] dark:text-slate-400">
                                Justifikasi
                            </th>
                            <th
                                class="border border-slate-200 bg-slate-50 px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider text-slate-500 dark:border-white/10 dark:bg-[#1f1f1f] dark:text-slate-400">
                                Model Relasi
                            </th>
                            <th
                                class="w-20 border border-slate-200 bg-slate-50 px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider text-slate-500 dark:border-white/10 dark:bg-[#1f1f1f] dark:text-slate-400">
                                Action
                            </th>
                        </tr>
                        <!-- Relation Rows -->
                        <tr v-for="(relation, index) in relationRowsByInitiative(initiative)" :key="`${initiative.id}-${index}`"
                            class="transition hover:bg-slate-50 dark:hover:bg-white/5">
                            <td
                                class="border border-slate-200 px-4 py-3 text-sm text-slate-800 dark:border-white/10 dark:text-slate-200">
                                <span v-if="relation"
                                    class="inline-block border border-slate-300 bg-slate-100 px-2 py-0.5 rounded text-slate-700 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100">
                                    {{ relation.predecessor }}
                                </span>
                                <span v-else class="text-slate-400">-</span>
                            </td>

                            <td
                                class="border border-slate-200 px-4 py-3 text-sm text-slate-800 dark:border-white/10 dark:text-slate-200">
                                <span v-if="relation" class="inline-block border border-emerald-300 bg-emerald-50 px-2 py-0.5 rounded text-emerald-700 dark:border-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-200">
                                    {{ relation.successor }}
                                </span>
                                <span v-else class="text-slate-400">-</span>
                            </td>
                            <td
                                class="border border-slate-200 px-4 py-3 text-sm text-slate-800 dark:border-white/10 dark:text-slate-200">
                                {{ relation?.justifikasi ?? '-' }}
                            </td>
                            <td
                                class="border border-slate-200 px-4 py-3 text-center text-sm text-slate-800 dark:border-white/10 dark:text-slate-200">
                                {{ relation?.model_relasi ?? '-' }}
                            </td>
                            <td
                                class="border border-slate-200 px-4 py-3 text-center align-middle dark:border-white/10">
                                <button
                                    v-if="relation"
                                    type="button"
                                    class="relative z-10 cursor-pointer rounded-full border border-blue-300 bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-600 transition hover:bg-blue-100 dark:border-blue-800 dark:bg-blue-900/30 dark:text-blue-400 dark:hover:bg-blue-900/50"
                                    @click="handleEditRelation(initiative, relation)"
                                >
                                    Edit
                                </button>
                                <span v-else class="text-slate-300 dark:text-slate-600">-</span>
                            </td>
                        </tr>
                        <!-- Empty row if no relations -->
                        <tr v-if="!relationRowsByInitiative(initiative).length"
                            class="transition hover:bg-slate-50 dark:hover:bg-white/5">
                            <td colspan="5"
                                class="border border-slate-200 px-4 py-3 text-center text-sm text-slate-400 dark:border-white/10 dark:text-slate-500">
                                Tidak ada relasi
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </section>
</template>

<script setup>
import { computed, ref } from 'vue';
import {
    QuestionMarkCircleIcon,
    CubeIcon,
    CircleStackIcon,
    ArrowTrendingUpIcon,
    ArrowUpIcon,
    LinkIcon,
} from '@heroicons/vue/24/outline';

const props = defineProps({
    mstInitiatives: {
        type: Array,
        default: () => [],
    },
    modelRelationOptions: {
        type: Array,
        default: () => [],
    },
});

const emit = defineEmits(['edit-relation']);

const selectedType = ref('all');
const selectedModelRelasi = ref('all');
const selectedInitiative = ref('all');

const handleEditRelation = (initiative, relation) => {
    emit('edit-relation', {
        initiative,
        relation,
    });
};

const relationTypeConfig = {
    1: { label: 'TBC', icon: QuestionMarkCircleIcon },
    2: { label: 'Composition', icon: CubeIcon },
    3: { label: 'Aggregation', icon: CircleStackIcon },
    4: { label: 'Flow', icon: ArrowTrendingUpIcon },
    5: { label: 'Specialization', icon: ArrowUpIcon },
    6: { label: 'Association', icon: LinkIcon },
};

const getRelationLabel = (typeRelation) => {
    const key = typeRelation != null ? Number(typeRelation) : null;
    return relationTypeConfig[key]?.label ?? 'Unknown';
};

const getRelationIconComponent = (typeRelation) => {
    const key = typeRelation != null ? Number(typeRelation) : null;
    return relationTypeConfig[key]?.icon ?? QuestionMarkCircleIcon;
};

const modelRelasiOptions = computed(() => {
    if (!props.modelRelationOptions?.length) {
        return [];
    }

    return props.modelRelationOptions.filter((option) => option);
});

const initiativeOptions = computed(() => {
    return props.mstInitiatives.map((initiative) => {
        const code = initiative.code ?? initiative.id ?? '-';
        const name = initiative.name ?? '-';
        const coeName = initiative?.coe?.name ?? initiative?.coe_name ?? '';
        const baseLabel = `${code} - ${name}`;
        const label = coeName ? `${baseLabel} (CoE: ${coeName})` : baseLabel;

        return {
            id: initiative.id,
            code,
            name,
            label,
            type: initiative.tipe_initiative != null ? Number(initiative.tipe_initiative) : null,
        };
    });
});

const filteredInitiativeOptions = computed(() => {
    if (selectedType.value === 'digital') {
        return initiativeOptions.value.filter((initiative) => initiative.type === 1);
    }
    if (selectedType.value === 'it') {
        return initiativeOptions.value.filter((initiative) => initiative.type === 2);
    }
    return initiativeOptions.value;
});

const filteredInitiatives = computed(() => {
    let initiatives = props.mstInitiatives;

    if (selectedType.value !== 'all') {
        const expectedType = selectedType.value === 'digital' ? 1 : 2;
        initiatives = initiatives.filter((initiative) => Number(initiative.tipe_initiative) === expectedType);
    }

    if (selectedInitiative.value !== 'all') {
        initiatives = initiatives.filter((initiative) => (
            Number(initiative.id) === Number(selectedInitiative.value)
        ));
    }

    // Filter by model relasi if selected
    if (selectedModelRelasi.value !== 'all') {
        initiatives = initiatives.filter((initiative) => extractRelations(initiative).length > 0);
    }

    return initiatives;
});

const initiativeById = computed(() => {
    const map = new Map();
    props.mstInitiatives.forEach((initiative) => {
        map.set(Number(initiative.id), initiative);
    });
    return map;
});

const findInitiativeById = (initiativeId) => initiativeById.value.get(Number(initiativeId)) ?? null;

const formatInitiative = (initiative, fallbackCode) => {
    const code = initiative?.code ?? initiative?.id ?? fallbackCode ?? '-';
    const name = initiative?.name;
    if (!name) {
        return code;
    }
    return `${code} - ${name}`;
};

const extractRelations = (initiative) => {
    const initiativeRelationsRow = initiative?.initiativeRelationsRow
        ?? initiative?.initiative_relations_row
        ?? [];
    const initiativeRelationsColumn = initiative?.initiativeRelationsColumn
        ?? initiative?.initiative_relations_column
        ?? [];

    const justifikasiValue = (relation) => relation.justifikasi ?? relation.description ?? '-';
    const shouldIncludeRelation = (relation) => {
        if (selectedModelRelasi.value === 'all') {
            return true;
        }

        return relation?.model_relasi === selectedModelRelasi.value;
    };
    const rows = [];
    const seen = new Set();

    const relationKey = (relation) => {
        if (relation?.id) {
            return `id-${relation.id}`;
        }

        return `row-${relation?.initiative_code_row}-col-${relation?.initiative_code_column}`;
    };

    initiativeRelationsRow.forEach((relation) => {
        if (!shouldIncludeRelation(relation)) {
            return;
        }

        const key = relationKey(relation);
        if (seen.has(key)) {
            return;
        }
        seen.add(key);

        rows.push({
            id: relation.id,
            predecessor: formatInitiative(initiative, relation.initiative_code_row),
            successor: formatInitiative(
                findInitiativeById(relation.initiative_code_column),
                relation.initiative_code_column,
            ),
            justifikasi: justifikasiValue(relation),
            model_relasi: relation.model_relasi ?? '-',
            type_relation: relation.type_relation != null ? Number(relation.type_relation) : null,
        });
    });

    initiativeRelationsColumn.forEach((relation) => {
        if (!shouldIncludeRelation(relation)) {
            return;
        }

        const key = relationKey(relation);
        if (seen.has(key)) {
            return;
        }
        seen.add(key);

        rows.push({
            id: relation.id,
            predecessor: formatInitiative(
                findInitiativeById(relation.initiative_code_row),
                relation.initiative_code_row,
            ),
            successor: formatInitiative(initiative, relation.initiative_code_column),
            justifikasi: justifikasiValue(relation),
            model_relasi: relation.model_relasi ?? '-',
            type_relation: relation.type_relation != null ? Number(relation.type_relation) : null,
        });
    });

    return rows;
};

const relationsByInitiativeId = computed(() => {
    const map = new Map();
    props.mstInitiatives.forEach((initiative) => {
        map.set(Number(initiative.id), extractRelations(initiative));
    });
    return map;
});

const relationRowsByInitiative = (initiative) => (
    relationsByInitiativeId.value.get(Number(initiative?.id)) ?? []
);

</script>
