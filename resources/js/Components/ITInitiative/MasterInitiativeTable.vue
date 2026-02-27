<template>
    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white dark:border-white/5 dark:bg-[#1a1a1a]">
        <div class="border-b border-slate-200 px-4 py-3 dark:border-white/10">
            <p class="text-xs font-semibold uppercase tracking-[0.08em] text-slate-500 dark:text-slate-400">
                Data Sumber `mst_initiative` (tipe_initiative = 2)
            </p>
        </div>

        <div class="overflow-x-hidden">
            <table class="w-full table-fixed divide-y divide-slate-200 text-[11px] dark:divide-white/5">
                <colgroup>
                    <col class="w-[4%]">
                    <col class="w-[7%]">
                    <col class="w-[17%]">
                    <col class="w-[16%]">
                    <col class="w-[22%]">
                    <col class="w-[12%]">
                    <col class="w-[10%]">
                    <col class="w-[12%]">
                </colgroup>
                <thead class="bg-slate-50 dark:bg-white/5">
                    <tr>
                        <th class="px-3 py-2 text-left text-[10px] font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">No</th>
                        <th class="px-3 py-2 text-left text-[10px] font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Code</th>
                        <th class="px-3 py-2 text-left text-[10px] font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">IT Arsitektur Building Blok</th>
                        <th class="px-3 py-2 text-left text-[10px] font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Nama Inisiatif</th>
                        <th class="px-3 py-2 text-left text-[10px] font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Deskripsi</th>
                        <th class="px-3 py-2 text-left text-[10px] font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Organisasi</th>
                        <th class="px-3 py-2 text-left text-[10px] font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Sumber</th>
                        <th class="px-3 py-2 text-left text-[10px] font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white dark:divide-white/5 dark:bg-[#1a1a1a]">
                    <tr v-for="(item, index) in items" :key="`mst-it-${item.id}`" class="transition-colors hover:bg-slate-50 dark:hover:bg-white/5">
                        <td class="px-3 py-3 text-[11px] font-medium text-slate-600 dark:text-slate-400">{{ index + 1 }}</td>
                        <td class="px-3 py-3 text-[11px] font-medium text-slate-700 dark:text-slate-200">{{ item.code ?? '-' }}</td>
                        <td class="px-3 py-3 text-[11px] text-slate-700 dark:text-slate-200">{{ coeName(item) }}</td>
                        <td class="px-3 py-3 text-[11px] text-slate-700 dark:text-slate-200">
                            <p class="font-medium break-words">{{ item.name ?? '-' }}</p>
                        </td>
                        <td class="px-3 py-3 text-[11px] text-slate-700 dark:text-slate-200">{{ descriptionText(item) }}</td>
                        <td class="px-3 py-3 text-[11px] text-slate-700 dark:text-slate-200">{{ organizationWithGroup(item) }}</td>
                        <td class="px-3 py-3 text-[11px] text-slate-700 dark:text-slate-200">
                            <span class="inline-flex items-center rounded-full bg-slate-100 px-2 py-0.5 text-[10px] font-medium text-slate-700 dark:bg-white/10 dark:text-slate-300">
                                {{ statusName(item) }}
                            </span>
                        </td>
                        <td class="px-3 py-3 text-[10px] font-medium">
                            <div class="flex flex-col items-start gap-1">
                                <Link
                                    :href="scopeCharterHref(item)"
                                    :class="actionCellClass(hasLinkedProject(item))"
                                    title="View Scope Charter"
                                >
                                    Scope Charter
                                </Link>
                                <Link
                                    :href="projectCharterHref(item)"
                                    :class="actionCellClass(hasLinkedProject(item))"
                                    title="View Project Charter"
                                >
                                    Project Charter
                                </Link>
                                <Link
                                    :href="roadmapHref(item)"
                                    class="inline-flex items-center rounded-full px-2 py-0.5 text-[9px] font-semibold bg-sky-100 text-sky-700 transition-colors hover:bg-sky-200 dark:bg-sky-500/20 dark:text-sky-300 dark:hover:bg-sky-500/30"
                                    title="Open Roadmap"
                                >
                                    Roadmap
                                </Link>
                            </div>
                        </td>
                    </tr>

                    <tr v-if="items.length === 0">
                        <td colspan="8" class="px-6 py-8 text-center text-xs text-slate-500 dark:text-slate-400">
                            Tidak ada data `mst_initiative` tipe 2.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    items: {
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

const hasLinkedProject = (item) => Boolean(Number(item?.project_id));

const scopeCharterHref = (item) => {
    const projectId = Number(item?.project_id);
    return projectId > 0 ? `/it-initiatives/${projectId}?tab=detail` : '/it-initiatives';
};

const projectCharterHref = (item) => {
    const projectId = Number(item?.project_id);
    return projectId > 0 ? `/it-initiatives/${projectId}?tab=charter` : '/it-initiatives';
};

const roadmapHref = (item) => {
    const projectId = Number(item?.project_id);
    return projectId > 0 ? `/roadmap?project_id=${projectId}` : '/roadmap';
};

const actionCellClass = (isReady) => {
    if (isReady) {
        return 'inline-flex items-center rounded-full px-2 py-0.5 text-[9px] font-semibold bg-emerald-100 text-emerald-800 hover:bg-emerald-200 dark:bg-emerald-500/20 dark:text-emerald-300 dark:hover:bg-emerald-500/30 transition-colors cursor-pointer';
    }

    return 'inline-flex items-center rounded-full px-2 py-0.5 text-[9px] font-semibold bg-rose-100 text-rose-800 hover:bg-rose-200 dark:bg-rose-500/20 dark:text-rose-300 dark:hover:bg-rose-500/30 transition-colors cursor-pointer';
};
</script>
