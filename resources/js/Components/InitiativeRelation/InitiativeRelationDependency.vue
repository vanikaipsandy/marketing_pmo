<template>
    <section
        class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-white/10 dark:bg-[#171717]">
        <div
            class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-200 px-5 py-4 dark:border-white/10">
            <div>
                <h2 class="text-base font-semibold text-slate-900 dark:text-white">Initiative Relation Dependency</h2>
            </div>

            <div
                class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                <label for="initiative-type-filter" class="text-[11px]">Filter Type</label>
                <select id="initiative-type-filter" v-model="selectedType"
                    class="rounded-lg border border-slate-200 bg-white px-2.5 py-1 text-xs font-semibold text-slate-700 shadow-sm transition focus:border-slate-300 focus:outline-none dark:border-white/10 dark:bg-[#1f1f1f] dark:text-slate-200">
                    <option value="all">All</option>
                    <option value="digital">Digital</option>
                    <option value="it">IT</option>
                </select>
            </div>
        </div>

        <div class="max-h-[70vh] overflow-auto">
            <table class="w-full min-w-[1120px] border-collapse text-sm">
                <thead class="bg-slate-50 dark:bg-white/5">
                    <tr>
                        <th
                            class="sticky top-0 z-20 w-10 border border-slate-200 bg-slate-50 px-4 py-3 text-center align-middle text-xs font-semibold uppercase tracking-wider text-slate-500 dark:border-white/10 dark:bg-[#1f1f1f] dark:text-slate-400">
                            Code
                        </th>
                        <th
                            class="sticky top-0 z-20 w-56 border border-slate-200 bg-slate-50 px-4 py-3 text-center align-middle text-xs font-semibold uppercase tracking-wider text-slate-500 dark:border-white/10 dark:bg-[#1f1f1f] dark:text-slate-400">
                            Initiative
                        </th>
                        <th
                            class="sticky top-0 z-20 w-56 border border-slate-200 bg-slate-50 px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500 dark:border-white/10 dark:bg-[#1f1f1f] dark:text-slate-400">
                            Predecessor
                        </th>
                        <th
                            class="sticky top-0 z-20 w-56 border border-slate-200 bg-slate-50 px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500 dark:border-white/10 dark:bg-[#1f1f1f] dark:text-slate-400">
                            Successor
                        </th>
                        <th
                            class="sticky top-0 z-20 border border-slate-200 bg-slate-50 px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500 dark:border-white/10 dark:bg-[#1f1f1f] dark:text-slate-400">
                            Justifikasi
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-if="!filteredInitiatives.length">
                        <td colspan="6"
                            class="border border-slate-200 px-4 py-6 text-center text-sm text-slate-500 dark:border-white/10 dark:text-slate-400">
                            Belum ada data initiative.
                        </td>
                    </tr>
                    <tr v-for="row in initiativeTableRows" :key="row.key"
                        class="transition hover:bg-slate-50 dark:hover:bg-white/5">
                        <td v-if="row.isFirst" :rowspan="row.rowspan"
                            class="border border-slate-200 px-4 py-3 text-center align-middle text-sm text-slate-800 dark:border-white/10 dark:text-slate-200">
                            {{ row.initiative.code ?? row.initiative.id ?? '-' }}
                        </td>
                        <td v-if="row.isFirst" :rowspan="row.rowspan"
                            class="w-56 border border-slate-200 px-4 py-3 text-left align-middle text-sm text-slate-800 dark:border-white/10 dark:text-slate-200">
                            {{ row.initiative.name ?? '-' }}
                        </td>
                        <td
                            class="border border-slate-200 px-4 py-3 text-sm text-slate-800 dark:border-white/10 dark:text-slate-200">
                            <span v-if="row.relation"
                                class="inline-block border border-slate-300 bg-slate-100 px-2 py-0.5 rounded text-slate-700 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100">
                                {{ row.relation.predecessor }}
                            </span>
                            <span v-else class="text-slate-400">-</span>
                        </td>

                        <td
                            class="border border-slate-200 px-4 py-3 text-sm text-slate-800 dark:border-white/10 dark:text-slate-200">
                            <span v-if="row.relation" class="inline-block border border-emerald-300 bg-emerald-50 px-2 py-0.5 rounded text-emerald-700 dark:border-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-200">
                                {{ row.relation.successor }}
                            </span>
                            <span v-else class="text-slate-400">-</span>
                        </td>
                        <td
                            class="border border-slate-200 px-4 py-3 text-sm text-slate-800 dark:border-white/10 dark:text-slate-200">
                            {{ row.relation?.justifikasi ?? '-' }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>
</template>

<script setup>
import { computed, ref } from 'vue';

const props = defineProps({
    mstInitiatives: {
        type: Array,
        default: () => [],
    },
});

const selectedType = ref('all');

const filteredInitiatives = computed(() => {
    if (selectedType.value === 'all') {
        return props.mstInitiatives;
    }

    const expectedType = selectedType.value === 'digital' ? 1 : 2;
    return props.mstInitiatives.filter((initiative) => Number(initiative.tipe_initiative) === expectedType);
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
    const rows = [];
    const seen = new Set();

    const relationKey = (relation) => {
        if (relation?.id) {
            return `id-${relation.id}`;
        }

        return `row-${relation?.initiative_code_row}-col-${relation?.initiative_code_column}`;
    };

    initiativeRelationsRow.forEach((relation) => {
        const key = relationKey(relation);
        if (seen.has(key)) {
            return;
        }
        seen.add(key);

        rows.push({
            predecessor: formatInitiative(initiative, relation.initiative_code_row),
            successor: formatInitiative(
                findInitiativeById(relation.initiative_code_column),
                relation.initiative_code_column,
            ),
            justifikasi: justifikasiValue(relation),
        });
    });

    initiativeRelationsColumn.forEach((relation) => {
        const key = relationKey(relation);
        if (seen.has(key)) {
            return;
        }
        seen.add(key);

        rows.push({
            predecessor: formatInitiative(
                findInitiativeById(relation.initiative_code_row),
                relation.initiative_code_row,
            ),
            successor: formatInitiative(initiative, relation.initiative_code_column),
            justifikasi: justifikasiValue(relation),
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

const initiativeTableRows = computed(() => {
    const rows = [];

    filteredInitiatives.value.forEach((initiative) => {
        const relations = relationRowsByInitiative(initiative);

        if (!relations.length) {
            rows.push({
                key: `initiative-${initiative.id}-empty`,
                initiative,
                relation: null,
                isFirst: true,
                rowspan: 1,
            });
            return;
        }

        relations.forEach((relation, index) => {
            rows.push({
                key: `initiative-${initiative.id}-relation-${index}`,
                initiative,
                relation,
                isFirst: index === 0,
                rowspan: relations.length,
            });
        });
    });

    return rows;
});

</script>
