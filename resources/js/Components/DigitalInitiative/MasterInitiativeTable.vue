<template>
    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white dark:border-white/5 dark:bg-[#1a1a1a]">
        <div class="border-b border-slate-200 px-4 py-3 dark:border-white/10">
            <p class="text-xs font-semibold uppercase tracking-[0.08em] text-slate-500 dark:text-slate-400">
                Data Sumber `mst_initiative` (tipe_initiative = 1)
            </p>
        </div>

        <div class="overflow-x-hidden">
            <table class="w-full table-fixed divide-y divide-slate-200 text-[11px] dark:divide-white/5">
                <colgroup>
                    <col class="w-[3%]">
                    <col class="w-[4%]">
                    <col class="w-[11%]">
                    <col class="w-[11%]">
                    <col class="w-[15%]">
                    <col class="w-[10%]">
                    <col class="w-[10%]">
                    <col class="w-[12%]">
                    <col class="w-[12%]">
                    <col class="w-[12%]">
                </colgroup>
                <thead class="bg-slate-50 dark:bg-white/5">
                    <tr>
                        <th class="px-3 py-2 text-left text-[10px] font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">No</th>
                        <th class="px-3 py-2 text-left text-[10px] font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Code</th>
                        <th class="px-3 py-2 text-left text-[10px] font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Centre of Excellence (CoE)</th>
                        <th class="px-3 py-2 text-left text-[10px] font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Nama Inisiatif</th>
                        <th class="px-3 py-2 text-left text-[10px] font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Deskripsi</th>
                        <th class="px-3 py-2 text-left text-[10px] font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Holding / Sub Holding</th>
                        <th class="px-3 py-2 text-left text-[10px] font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Organisasi</th>
                        <th class="px-3 py-2 text-left text-[10px] font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Status Timeline</th>
                        <th class="px-3 py-2 text-left text-[10px] font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Action</th>
                        <th class="px-3 py-2 text-left text-[10px] font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Sumber</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white dark:divide-white/5 dark:bg-[#1a1a1a]">
                    <tr v-for="(item, index) in items" :key="`mst-digital-${item.id}`" class="transition-colors hover:bg-slate-50 dark:hover:bg-white/5">
                        <td class="px-3 py-3 text-[11px] font-medium text-slate-600 dark:text-slate-400">{{ index + 1 }}</td>
                        <td class="px-3 py-3 text-[11px] font-medium text-slate-700 dark:text-slate-200">{{ item.code ?? '-' }}</td>
                        <td class="px-3 py-3 text-[11px] text-slate-700 dark:text-slate-200">{{ coeName(item) }}</td>
                        <td class="px-3 py-3 text-[11px] text-slate-700 dark:text-slate-200">
                            <p class="font-medium break-words">{{ item.name ?? '-' }}</p>
                        </td>
                        <td class="px-3 py-3 text-[11px] text-slate-700 dark:text-slate-200">{{ descriptionText(item) }}</td>
                        <td class="px-3 py-3 text-[11px] text-slate-700 dark:text-slate-200">{{ groubName(item) }}</td>
                        <td class="px-3 py-3 text-[11px] text-slate-700 dark:text-slate-200">{{ organizationName(item) }}</td>
                        <td class="px-3 py-3 text-[11px] text-slate-700 dark:text-slate-200">
                            <span v-if="scStatus(item) !== '-'" class="inline-flex items-center rounded-full bg-blue-50 px-2 py-0.5 text-[10px] font-medium text-blue-700 dark:bg-blue-500/10 dark:text-blue-300">
                                {{ scStatus(item) }}
                            </span>
                            <span v-else>-</span>
                        </td>
                        <td class="px-3 py-3 text-[10px] font-medium">
                            <div class="flex flex-col items-start gap-1">
                                <Link
                                    :href="initiativeHref(item)"
                                    :class="actionCellClass(hasLinkedInitiative(item))"
                                    title="View Scope Charter"
                                >
                                    Scope Charter
                                </Link>
                                <Link
                                    :href="editHref(item)"
                                    class="inline-flex items-center rounded-full px-2 py-0.5 text-[9px] font-semibold bg-amber-100 text-amber-800 hover:bg-amber-200 dark:bg-amber-500/20 dark:text-amber-300 dark:hover:bg-amber-500/30 transition-colors cursor-pointer"
                                    title="Edit Digital Initiative"
                                >
                                    Edit
                                </Link>
                            </div>
                        </td>
                        <td class="px-3 py-3 text-[11px] text-slate-700 dark:text-slate-200">
                            <span class="inline-flex items-center rounded-full bg-slate-100 px-2 py-0.5 text-[10px] font-medium text-slate-700 dark:bg-white/10 dark:text-slate-300">
                                {{ statusName(item) }}
                            </span>
                        </td>
                    </tr>

                    <tr v-if="items.length === 0">
                        <td colspan="10" class="px-6 py-8 text-center text-xs text-slate-500 dark:text-slate-400">
                            Tidak ada data `mst_initiative` tipe 1.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    items: {
        type: Array,
        default: () => [],
    },
    initiativeItems: {
        type: Array,
        default: () => [],
    },
});

const normalizeText = (value) => {
    const text = String(value ?? '').trim();
    return text.length > 0 ? text : '-';
};

const coeName = (item) => normalizeText(item?.coe?.name ?? item?.coe_name ?? item?.coe_id);

const organizationName = (item) => normalizeText(item?.organization?.name ?? item?.business_unit_name ?? item?.business_unit);

const groubName = (item) => normalizeText(item?.organization?.groub?.name);

const organizationWithGroup = (item) => {
    const org = organizationName(item);
    const group = groubName(item);

    if (org === '-' && group === '-') {
        return '-';
    }

    if (group === '-') {
        return org;
    }

    if (org === '-') {
        return group;
    }

    return `${org} - ${group}`;
};

const statusName = (item) => normalizeText(item?.status);

const descriptionText = (item) => normalizeText(item?.description);

const scStatus = (item) => {
    const matched = resolveLinkedInitiative(item);
    return normalizeText(matched?.latest_sc_status_implementation?.status);
};

const scReviewStatus = (item) => {
    const matched = resolveLinkedInitiative(item);
    return normalizeText(matched?.latest_sc_status_implementation?.review_status);
};

const normalizeKey = (value) => String(value ?? '').trim().toLowerCase();

const resolveLinkedInitiative = (item) => {
    const sourceItems = Array.isArray(props.initiativeItems) ? props.initiativeItems : [];
    const codeKey = normalizeKey(item?.code);
    const nameKey = normalizeKey(item?.name);

    return sourceItems.find((initiative) =>
        normalizeKey(initiative?.no) === codeKey ||
        normalizeKey(initiative?.useCase) === nameKey
    ) || null;
};

const hasLinkedInitiative = (item) => Boolean(resolveLinkedInitiative(item)?.id);

const initiativeHref = (item) => {
    const matchedInitiative = resolveLinkedInitiative(item);
    return matchedInitiative?.id ? `/digital-initiatives/${matchedInitiative.id}` : '/digital-initiatives';
};

const editHref = (item) => {
    const matchedInitiative = resolveLinkedInitiative(item);
    return matchedInitiative?.id
        ? `/program-planning/program-definition/digital-initiatives/${matchedInitiative.id}/edit`
        : '/program-planning/program-definition/digital-initiatives';
};

const actionCellClass = (isReady) => {
    if (isReady) {
        return 'inline-flex items-center rounded-full px-2 py-0.5 text-[9px] font-semibold bg-emerald-100 text-emerald-800 hover:bg-emerald-200 dark:bg-emerald-500/20 dark:text-emerald-300 dark:hover:bg-emerald-500/30 transition-colors cursor-pointer';
    }

    return 'inline-flex items-center rounded-full px-2 py-0.5 text-[9px] font-semibold bg-rose-100 text-rose-800 hover:bg-rose-200 dark:bg-rose-500/20 dark:text-rose-300 dark:hover:bg-rose-500/30 transition-colors cursor-pointer';
};
</script>
