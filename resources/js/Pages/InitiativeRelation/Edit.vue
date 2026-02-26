<template>
    <UserLayout title="Edit Initiative Relation">
        <div class="mx-auto max-w-5xl space-y-6 animate-fade-in-up">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Edit Initiative Relation</h1>
                    <p class="text-sm text-slate-500 dark:text-slate-400">
                        Update relasi antar initiative.
                    </p>
                </div>
                <Link
                    href="/program-implementation/initiative-relation"
                    class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 shadow-sm hover:bg-slate-50 dark:border-white/10 dark:bg-[#1f1f1f] dark:text-slate-200"
                >
                    Kembali
                </Link>
            </div>

            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-white/10 dark:bg-[#171717]">
                <div class="border-b border-slate-200 px-6 py-4 dark:border-white/10">
                    <h2 class="text-base font-semibold text-slate-900 dark:text-white">Detail Relation</h2>
                    <p class="text-sm text-slate-500 dark:text-slate-400">Perbarui data relasi di bawah.</p>
                </div>

                <form class="space-y-6 p-6" @submit.prevent="submit">
                    <!-- Work Package 1 Section -->
                    <div class="rounded-lg border border-slate-200 bg-slate-50 p-4 dark:border-white/10 dark:bg-[#131313]">
                        <label class="mb-3 block text-sm font-semibold text-slate-700 dark:text-slate-300">Work Package 1</label>
                        <select
                            v-model="form.initiative_code_row"
                            class="h-12 w-full rounded-md border-slate-300 bg-white px-4 text-sm shadow-sm focus:border-slate-400 focus:ring-slate-300 dark:border-white/10 dark:bg-[#1f1f1f] dark:text-slate-200"
                        >
                            <option value="">Pilih work package</option>
                            <option v-for="initiative in initiativeOptions" :key="initiative.id" :value="initiative.id">
                                {{ initiativeLabel(initiative) }}
                            </option>
                        </select>
                        <p v-if="form.errors.initiative_code_row" class="mt-2 text-xs text-red-500">{{ form.errors.initiative_code_row }}</p>
                    </div>

                    <!-- Work Package 1 Details -->
                    <div v-if="selectedInitiativeRow" class="rounded-lg border border-emerald-200 bg-emerald-50 overflow-hidden dark:border-emerald-900/30 dark:bg-emerald-900/10">
                        <div class="overflow-x-auto">
                            <table class="w-full table-fixed border-collapse text-xs">
                                <colgroup>
                                    <col class="w-[6%]">
                                    <col class="w-[22%]">
                                    <col class="w-[44%]">
                                    <col class="w-[12%]">
                                    <col class="w-[6%]">
                                    <col class="w-[10%]">
                                </colgroup>
                                <thead>
                                    <tr class="bg-emerald-100/70 dark:bg-emerald-900/30">
                                        <th class="border border-emerald-200 dark:border-emerald-900/30 px-2 py-1 text-left font-semibold text-emerald-900 dark:text-emerald-200 whitespace-nowrap">Code</th>
                                        <th class="border border-emerald-200 dark:border-emerald-900/30 px-2 py-1 text-left font-semibold text-emerald-900 dark:text-emerald-200 whitespace-nowrap">Name</th>
                                        <th class="border border-emerald-200 dark:border-emerald-900/30 px-2 py-1 text-left font-semibold text-emerald-900 dark:text-emerald-200 whitespace-nowrap">Description</th>
                                        <th class="border border-emerald-200 dark:border-emerald-900/30 px-2 py-1 text-left font-semibold text-emerald-900 dark:text-emerald-200 whitespace-nowrap">Status</th>
                                        <th class="border border-emerald-200 dark:border-emerald-900/30 px-2 py-1 text-left font-semibold text-emerald-900 dark:text-emerald-200 whitespace-nowrap">Type</th>
                                        <th class="border border-emerald-200 dark:border-emerald-900/30 px-2 py-1 text-left font-semibold text-emerald-900 dark:text-emerald-200 whitespace-nowrap">BU</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="hover:bg-emerald-50/50 dark:hover:bg-emerald-900/15">
                                        <td class="border border-emerald-200 dark:border-emerald-900/30 px-2 py-1 text-emerald-900 dark:text-emerald-100 whitespace-nowrap">{{ selectedInitiativeRow.code || '-' }}</td>
                                        <td class="border border-emerald-200 dark:border-emerald-900/30 px-2 py-1 text-emerald-900 dark:text-emerald-100 whitespace-normal break-words">{{ selectedInitiativeRow.name || '-' }}</td>
                                        <td class="border border-emerald-200 dark:border-emerald-900/30 px-2 py-1 text-emerald-900 dark:text-emerald-100 whitespace-normal break-words">{{ selectedInitiativeRow.description || '-' }}</td>
                                        <td class="border border-emerald-200 dark:border-emerald-900/30 px-2 py-1 text-emerald-900 dark:text-emerald-100 whitespace-normal break-words">{{ selectedInitiativeRow.status || '-' }}</td>
                                        <td class="border border-emerald-200 dark:border-emerald-900/30 px-2 py-1 text-emerald-900 dark:text-emerald-100 whitespace-nowrap">{{ formatInitiativeType(selectedInitiativeRow.tipe_initiative) }}</td>
                                        <td class="border border-emerald-200 dark:border-emerald-900/30 px-2 py-1 text-emerald-900 dark:text-emerald-100 whitespace-nowrap">{{ selectedInitiativeRow.business_unit_name || selectedInitiativeRow.business_unit || '-' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="border-t border-emerald-200 dark:border-emerald-900/30">
                            <div class="px-3 py-2 bg-emerald-100/70 dark:bg-emerald-900/30 text-[11px] font-semibold uppercase tracking-wide text-emerald-900 dark:text-emerald-200">
                                Relasi Initiative
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full table-fixed border-collapse text-[11px]">
                                    <colgroup>
                                        <col class="w-[22%]">
                                        <col class="w-[22%]">
                                        <col class="w-[34%]">
                                        <col class="w-[22%]">
                                    </colgroup>
                                    <thead>
                                        <tr class="bg-emerald-100/70 dark:bg-emerald-900/30">
                                            <th class="border border-emerald-200 dark:border-emerald-900/30 px-2 py-1 text-left font-semibold text-emerald-900 dark:text-emerald-200 whitespace-nowrap">Predecessor</th>
                                            <th class="border border-emerald-200 dark:border-emerald-900/30 px-2 py-1 text-left font-semibold text-emerald-900 dark:text-emerald-200 whitespace-nowrap">Successor</th>
                                            <th class="border border-emerald-200 dark:border-emerald-900/30 px-2 py-1 text-left font-semibold text-emerald-900 dark:text-emerald-200 whitespace-nowrap">Justifikasi</th>
                                            <th class="border border-emerald-200 dark:border-emerald-900/30 px-2 py-1 text-left font-semibold text-emerald-900 dark:text-emerald-200 whitespace-nowrap">Model Relasi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-if="!selectedRowRelations.length">
                                            <td colspan="4" class="border border-emerald-200 dark:border-emerald-900/30 px-2 py-2 text-center text-emerald-700 dark:text-emerald-200">
                                                Belum ada relasi
                                            </td>
                                        </tr>
                                        <tr v-for="relation in selectedRowRelations" :key="relation.id" class="hover:bg-emerald-50/50 dark:hover:bg-emerald-900/15">
                                            <td class="border border-emerald-200 dark:border-emerald-900/30 px-2 py-1 text-emerald-900 dark:text-emerald-100 whitespace-normal break-words">{{ relation.predecessorLabel }}</td>
                                            <td class="border border-emerald-200 dark:border-emerald-900/30 px-2 py-1 text-emerald-900 dark:text-emerald-100 whitespace-normal break-words">{{ relation.successorLabel }}</td>
                                            <td class="border border-emerald-200 dark:border-emerald-900/30 px-2 py-1 text-emerald-900 dark:text-emerald-100 whitespace-normal break-words">{{ relation.justifikasi }}</td>
                                            <td class="border border-emerald-200 dark:border-emerald-900/30 px-2 py-1 text-emerald-900 dark:text-emerald-100 whitespace-nowrap">{{ relation.modelRelasi }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Work Package 2 Section -->
                    <div class="rounded-lg border border-slate-200 bg-slate-50 p-4 dark:border-white/10 dark:bg-[#131313]">
                        <label class="mb-3 block text-sm font-semibold text-slate-700 dark:text-slate-300">Work Package 2</label>
                        <select
                            v-model="form.initiative_code_column"
                            class="h-12 w-full rounded-md border-slate-300 bg-white px-4 text-sm shadow-sm focus:border-slate-400 focus:ring-slate-300 dark:border-white/10 dark:bg-[#1f1f1f] dark:text-slate-200"
                        >
                            <option value="">Pilih work package</option>
                            <option v-for="initiative in initiativeOptions" :key="initiative.id" :value="initiative.id">
                                {{ initiativeLabel(initiative) }}
                            </option>
                        </select>
                        <p v-if="form.errors.initiative_code_column" class="mt-2 text-xs text-red-500">{{ form.errors.initiative_code_column }}</p>
                    </div>

                    <!-- Work Package 2 Details -->
                    <div v-if="selectedInitiativeColumn" class="rounded-lg border border-blue-200 bg-blue-50 overflow-hidden dark:border-blue-900/30 dark:bg-blue-900/10">
                        <div class="overflow-x-auto">
                            <table class="w-full table-fixed border-collapse text-xs">
                                <colgroup>
                                    <col class="w-[6%]">
                                    <col class="w-[22%]">
                                    <col class="w-[44%]">
                                    <col class="w-[12%]">
                                    <col class="w-[6%]">
                                    <col class="w-[10%]">
                                </colgroup>
                                <thead>
                                    <tr class="bg-blue-100/70 dark:bg-blue-900/30">
                                        <th class="border border-blue-200 dark:border-blue-900/30 px-2 py-1 text-left font-semibold text-blue-900 dark:text-blue-200 whitespace-nowrap">Code</th>
                                        <th class="border border-blue-200 dark:border-blue-900/30 px-2 py-1 text-left font-semibold text-blue-900 dark:text-blue-200 whitespace-nowrap">Name</th>
                                        <th class="border border-blue-200 dark:border-blue-900/30 px-2 py-1 text-left font-semibold text-blue-900 dark:text-blue-200 whitespace-nowrap">Description</th>
                                        <th class="border border-blue-200 dark:border-blue-900/30 px-2 py-1 text-left font-semibold text-blue-900 dark:text-blue-200 whitespace-nowrap">Status</th>
                                        <th class="border border-blue-200 dark:border-blue-900/30 px-2 py-1 text-left font-semibold text-blue-900 dark:text-blue-200 whitespace-nowrap">Type</th>
                                        <th class="border border-blue-200 dark:border-blue-900/30 px-2 py-1 text-left font-semibold text-blue-900 dark:text-blue-200 whitespace-nowrap">BU</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="hover:bg-blue-50/50 dark:hover:bg-blue-900/15">
                                        <td class="border border-blue-200 dark:border-blue-900/30 px-2 py-1 text-blue-900 dark:text-blue-100 whitespace-nowrap">{{ selectedInitiativeColumn.code || '-' }}</td>
                                        <td class="border border-blue-200 dark:border-blue-900/30 px-2 py-1 text-blue-900 dark:text-blue-100 whitespace-normal break-words">{{ selectedInitiativeColumn.name || '-' }}</td>
                                        <td class="border border-blue-200 dark:border-blue-900/30 px-2 py-1 text-blue-900 dark:text-blue-100 whitespace-normal break-words">{{ selectedInitiativeColumn.description || '-' }}</td>
                                        <td class="border border-blue-200 dark:border-blue-900/30 px-2 py-1 text-blue-900 dark:text-blue-100 whitespace-normal break-words">{{ selectedInitiativeColumn.status || '-' }}</td>
                                        <td class="border border-blue-200 dark:border-blue-900/30 px-2 py-1 text-blue-900 dark:text-blue-100 whitespace-nowrap">{{ formatInitiativeType(selectedInitiativeColumn.tipe_initiative) }}</td>
                                        <td class="border border-blue-200 dark:border-blue-900/30 px-2 py-1 text-blue-900 dark:text-blue-100 whitespace-nowrap">{{ selectedInitiativeColumn.business_unit_name || selectedInitiativeColumn.business_unit || '-' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="border-t border-blue-200 dark:border-blue-900/30">
                            <div class="px-3 py-2 bg-blue-100/70 dark:bg-blue-900/30 text-[11px] font-semibold uppercase tracking-wide text-blue-900 dark:text-blue-200">
                                Relasi Initiative
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full table-fixed border-collapse text-[11px]">
                                    <colgroup>
                                        <col class="w-[22%]">
                                        <col class="w-[22%]">
                                        <col class="w-[34%]">
                                        <col class="w-[22%]">
                                    </colgroup>
                                    <thead>
                                        <tr class="bg-blue-100/70 dark:bg-blue-900/30">
                                            <th class="border border-blue-200 dark:border-blue-900/30 px-2 py-1 text-left font-semibold text-blue-900 dark:text-blue-200 whitespace-nowrap">Predecessor</th>
                                            <th class="border border-blue-200 dark:border-blue-900/30 px-2 py-1 text-left font-semibold text-blue-900 dark:text-blue-200 whitespace-nowrap">Successor</th>
                                            <th class="border border-blue-200 dark:border-blue-900/30 px-2 py-1 text-left font-semibold text-blue-900 dark:text-blue-200 whitespace-nowrap">Justifikasi</th>
                                            <th class="border border-blue-200 dark:border-blue-900/30 px-2 py-1 text-left font-semibold text-blue-900 dark:text-blue-200 whitespace-nowrap">Model Relasi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-if="!selectedColumnRelations.length">
                                            <td colspan="4" class="border border-blue-200 dark:border-blue-900/30 px-2 py-2 text-center text-blue-700 dark:text-blue-200">
                                                Belum ada relasi
                                            </td>
                                        </tr>
                                        <tr v-for="relation in selectedColumnRelations" :key="relation.id" class="hover:bg-blue-50/50 dark:hover:bg-blue-900/15">
                                            <td class="border border-blue-200 dark:border-blue-900/30 px-2 py-1 text-blue-900 dark:text-blue-100 whitespace-normal break-words">{{ relation.predecessorLabel }}</td>
                                            <td class="border border-blue-200 dark:border-blue-900/30 px-2 py-1 text-blue-900 dark:text-blue-100 whitespace-normal break-words">{{ relation.successorLabel }}</td>
                                            <td class="border border-blue-200 dark:border-blue-900/30 px-2 py-1 text-blue-900 dark:text-blue-100 whitespace-normal break-words">{{ relation.justifikasi }}</td>
                                            <td class="border border-blue-200 dark:border-blue-900/30 px-2 py-1 text-blue-900 dark:text-blue-100 whitespace-nowrap">{{ relation.modelRelasi }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Relation Type and Display Row -->
                    <div class="rounded-lg border border-slate-200 bg-slate-50 p-4 dark:border-white/10 dark:bg-[#131313]">
                        <div class="grid grid-cols-3 gap-4 items-end">
                            <div>
                                <label class="mb-2 block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-400">Work Package 1 Terpilih</label>
                                <div class="flex items-center justify-center h-10 rounded-md border border-slate-300 bg-white px-3 text-sm text-slate-700 dark:border-white/10 dark:bg-[#1f1f1f] dark:text-slate-300">
                                    {{ getSelectedLabel('row') || '-' }}
                                </div>
                            </div>

                            <div>
                                <label class="mb-2 block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-400">Tipe Relasi</label>
                                <select
                                    v-model.number="form.type_relation"
                                    class="h-10 w-full rounded-md border-slate-300 bg-white px-4 text-sm shadow-sm focus:border-slate-400 focus:ring-slate-300 dark:border-white/10 dark:bg-[#1f1f1f] dark:text-slate-200"
                                >
                                    <option :value="null">Pilih</option>
                                    <option v-for="option in typeRelationOptions" :key="option.value" :value="option.value">
                                        {{ option.label }}
                                    </option>
                                </select>
                                <p v-if="form.errors.type_relation" class="mt-1 text-xs text-red-500">{{ form.errors.type_relation }}</p>
                            </div>

                            <div>
                                <label class="mb-2 block text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-400">Work Package 2 Terpilih</label>
                                <div class="flex items-center justify-center h-10 rounded-md border border-slate-300 bg-white px-3 text-sm text-slate-700 dark:border-white/10 dark:bg-[#1f1f1f] dark:text-slate-300">
                                    {{ getSelectedLabel('column') || '-' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Model Relasi -->
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-300">Model Relasi</label>
                        <input
                            v-model="form.model_relasi"
                            list="model-relasi-options"
                            type="text"
                            class="h-12 w-full rounded-md border-slate-300 bg-slate-50 px-4 text-base shadow-sm focus:border-slate-400 focus:ring-slate-300 dark:border-white/10 dark:bg-[#131313] dark:text-slate-100"
                            placeholder="e.g. initiative"
                        />
                        <datalist id="model-relasi-options">
                            <option v-for="option in modelRelationOptions" :key="option" :value="option" />
                        </datalist>
                        <p v-if="form.errors.model_relasi" class="mt-1 text-xs text-red-500">{{ form.errors.model_relasi }}</p>
                    </div>

                    <!-- Justifikasi -->
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-300">Justifikasi</label>
                        <textarea
                            v-model="form.justifikasi"
                            rows="4"
                            class="w-full rounded-md border-slate-300 bg-slate-50 shadow-sm focus:border-slate-400 focus:ring-slate-300 dark:border-white/10 dark:bg-[#131313] dark:text-slate-100 sm:text-sm"
                            placeholder="Tambahkan justifikasi relasi..."
                        ></textarea>
                        <p v-if="form.errors.justifikasi" class="mt-1 text-xs text-red-500">{{ form.errors.justifikasi }}</p>
                    </div>

                    <div class="flex items-center justify-end gap-3 border-t border-slate-100 pt-6 dark:border-white/5">
                        <Link
                            href="/program-implementation/initiative-relation"
                            class="rounded-lg px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-white/5"
                        >
                            Cancel
                        </Link>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="rounded-lg bg-slate-900 px-6 py-2 text-sm font-medium text-white shadow-sm hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2 disabled:opacity-50 dark:bg-white dark:text-slate-900"
                        >
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </UserLayout>
</template>

<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import { computed } from 'vue';
import UserLayout from '@/Layouts/UserLayout.vue';

const props = defineProps({
    initiativeRelation: {
        type: Object,
        required: true,
    },
    initiativeOptions: {
        type: Array,
        default: () => [],
    },
    initiativeRelations: {
        type: Array,
        default: () => [],
    },
    typeRelationOptions: {
        type: Array,
        default: () => [],
    },
    modelRelationOptions: {
        type: Array,
        default: () => [],
    },
});

const form = useForm({
    model_relasi: props.initiativeRelation?.model_relasi ?? '',
    type_relation: props.initiativeRelation?.type_relation ?? null,
    initiative_code_row: props.initiativeRelation?.initiative_code_row != null
        ? Number(props.initiativeRelation.initiative_code_row)
        : '',
    initiative_code_column: props.initiativeRelation?.initiative_code_column != null
        ? Number(props.initiativeRelation.initiative_code_column)
        : '',
    justifikasi: props.initiativeRelation?.justifikasi ?? props.initiativeRelation?.description ?? '',
});

const selectedInitiativeRow = computed(() => {
    if (!form.initiative_code_row) return null;
    return props.initiativeOptions.find(opt => opt.id == form.initiative_code_row);
});

const selectedInitiativeColumn = computed(() => {
    if (!form.initiative_code_column) return null;
    return props.initiativeOptions.find(opt => opt.id == form.initiative_code_column);
});

const initiativeLabel = (initiative) => {
    const code = initiative?.code ?? initiative?.id ?? '-';
    const name = initiative?.name ?? '';
    return name ? `${code} - ${name}` : code;
};

const buildRelations = (initiativeId) => {
    if (!initiativeId) return [];
    const id = Number(initiativeId);

    return props.initiativeRelations
        .filter(relation => (
            Number(relation.initiative_code_row) === id
            || Number(relation.initiative_code_column) === id
        ))
        .map((relation) => {
            const rowInitiative = relation.initiative_row;
            const columnInitiative = relation.initiative_column;
            const rowFallback = relation.initiative_code_row;
            const columnFallback = relation.initiative_code_column;
            const justifikasi = relation.justifikasi ?? relation.description ?? '-';

            return {
                id: relation.id ?? `${relation.initiative_code_row}-${relation.initiative_code_column}-${relation.type_relation}-${relation.model_relasi}`,
                predecessorLabel: rowInitiative ? initiativeLabel(rowInitiative) : (rowFallback ?? '-'),
                successorLabel: columnInitiative ? initiativeLabel(columnInitiative) : (columnFallback ?? '-'),
                modelRelasi: relation.model_relasi ?? '-',
                justifikasi,
            };
        });
};

const selectedRowRelations = computed(() => buildRelations(form.initiative_code_row));
const selectedColumnRelations = computed(() => buildRelations(form.initiative_code_column));

const getSelectedLabel = (type) => {
    const initiativeId = type === 'row' ? form.initiative_code_row : form.initiative_code_column;
    if (!initiativeId) return null;
    const initiative = props.initiativeOptions.find(opt => opt.id == initiativeId);
    return initiative ? initiativeLabel(initiative) : null;
};

const formatInitiativeType = (typeValue) => {
    const typeMap = {
        1: 'Digital',
        2: 'IT',
    };
    return typeMap[typeValue] || '-';
};

const submit = () => {
    form.put(`/program-implementation/initiative-relation/${props.initiativeRelation.id}`);
};
</script>
