<template>
    <UserLayout title="Digital Initiatives">
        <div class="animate-fade-in">
            <div class="mb-6 rounded-xl border border-slate-200 bg-white p-4 dark:border-white/5 dark:bg-[#1a1a1a]">
                <div class="grid grid-cols-1 gap-3 md:grid-cols-3 xl:grid-cols-7">
                    <div class="md:col-span-3 xl:col-span-2">
                        <label class="mb-1 block text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Search</label>
                        <div class="relative">
                            <input
                                v-model="form.search"
                                type="text"
                                placeholder="Search by description or ID..."
                                class="w-full rounded-lg border border-slate-300 bg-white py-2 pl-10 pr-4 text-slate-900 placeholder-slate-400 focus:border-indigo-500 focus:ring-indigo-500 dark:border-white/10 dark:bg-[#131313] dark:text-slate-100 dark:placeholder-slate-500"
                                @input="debouncedApplyFilters"
                            />
                            <svg class="absolute left-3 top-2.5 h-5 w-5 text-slate-400 dark:text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>

                    <div>
                        <label class="mb-1 block text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Type</label>
                        <select
                            v-model="form.category_fase"
                            class="w-full rounded-lg border-slate-300 bg-white text-slate-700 focus:border-indigo-500 focus:ring-indigo-500 dark:border-white/10 dark:bg-[#131313] dark:text-slate-200"
                            @change="applyFilters"
                        >
                            <option value="">All Type</option>
                            <option v-for="category in categoryOptions" :key="category.id" :value="String(category.id)">
                                {{ category.label }}
                            </option>
                        </select>
                    </div>

                    <div>
                        <label class="mb-1 block text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Source</label>
                        <select
                            v-model="form.source_id"
                            class="w-full rounded-lg border-slate-300 bg-white text-slate-700 focus:border-indigo-500 focus:ring-indigo-500 dark:border-white/10 dark:bg-[#131313] dark:text-slate-200"
                            @change="applyFilters"
                        >
                            <option value="">All Sources</option>
                            <option v-for="source in options.sources" :key="source.id" :value="String(source.id)">
                                {{ source.name }}
                            </option>
                        </select>
                    </div>

                    <div>
                        <label class="mb-1 block text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Phase</label>
                        <select
                            v-model="form.phase_id"
                            class="w-full rounded-lg border-slate-300 bg-white text-slate-700 focus:border-indigo-500 focus:ring-indigo-500 dark:border-white/10 dark:bg-[#131313] dark:text-slate-200"
                            @change="applyFilters"
                        >
                            <option value="">All Phase</option>
                            <option v-for="phase in options.phases || []" :key="phase.id" :value="String(phase.id)">
                                {{ phase.name }}
                            </option>
                        </select>
                    </div>

                    <div>
                        <label class="mb-1 block text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Organization</label>
                        <select
                            v-model="form.organization_id"
                            class="w-full rounded-lg border-slate-300 bg-white text-slate-700 focus:border-indigo-500 focus:ring-indigo-500 dark:border-white/10 dark:bg-[#131313] dark:text-slate-200"
                            @change="applyFilters"
                        >
                            <option value="">All Organization</option>
                            <option v-for="organization in options.organizations || []" :key="organization.id" :value="String(organization.id)">
                                {{ organization.name }}
                            </option>
                        </select>
                    </div>

                    <div>
                        <label class="mb-1 block text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">COE</label>
                        <select
                            v-model="form.coe_id"
                            class="w-full rounded-lg border-slate-300 bg-white text-slate-700 focus:border-indigo-500 focus:ring-indigo-500 dark:border-white/10 dark:bg-[#131313] dark:text-slate-200"
                            @change="applyFilters"
                        >
                            <option value="">All COE</option>
                            <option v-for="coe in options.coes || []" :key="coe.id" :value="String(coe.id)">
                                {{ coe.name }}
                            </option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-white/10 dark:bg-[#171717]">
                <div class="overflow-x-auto overflow-y-visible">
                    <table class="min-w-max w-full divide-y divide-slate-200 text-sm dark:divide-white/10">
                        <thead class="bg-slate-50 dark:bg-white/5">
                            <tr>
                                <th class="whitespace-nowrap px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">No</th>
                                <th class="whitespace-nowrap px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Type</th>
                                <th class="whitespace-nowrap px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Project Owner</th>
                                <th class="min-w-[180px] px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Use Case</th>
                                <th class="min-w-[280px] px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Desc</th>
                                <th class="whitespace-nowrap px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Value</th>
                                <th class="whitespace-nowrap px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Urgency</th>
                                <th class="min-w-[180px] px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">RJPP</th>
                                <th class="whitespace-nowrap px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">COE</th>
                                <th class="min-w-[420px] px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Status</th>
                                <th class="sticky right-0 z-10 bg-slate-50 p-0 text-xs font-semibold uppercase tracking-wider text-slate-500 dark:bg-white/5 dark:text-slate-400">
                                    <div class="w-[180px] border-l border-slate-200 dark:border-white/10">
                                        <div class="border-b border-slate-200 px-2 py-1.5 text-center dark:border-white/10">Action</div>
                                        <div class="grid grid-cols-2 divide-x divide-slate-200 text-[10px] font-semibold normal-case dark:divide-white/10">
                                            <span class="px-2 py-1 text-center">Scope Charter</span>
                                            <span class="px-2 py-1 text-center">Project Charter</span>
                                        </div>
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                            <tr v-for="item in initiatives" :key="`digital-${item.id}`" class="transition-colors hover:bg-slate-50 dark:hover:bg-white/[0.02]">
                                <td class="whitespace-nowrap px-4 py-3 font-medium text-slate-900 dark:text-white">{{ item.id }}</td>
                                <td class="whitespace-nowrap px-4 py-3 text-slate-700 dark:text-slate-200">{{ categoryLabel(item.category_fase) }}</td>
                                <td class="max-w-[220px] px-4 py-3 text-slate-700 dark:text-slate-200">
                                    <span class="line-clamp-2">{{ organizationLabel(item.organizations) }}</span>
                                </td>
                                <td class="min-w-[180px] max-w-[320px] px-4 py-3 text-slate-700 dark:text-slate-200">
                                    <span class="whitespace-normal break-words">{{ item.use_case?.name || '-' }}</span>
                                </td>
                                <td class="min-w-[280px] max-w-[420px] px-4 py-3 text-slate-700 dark:text-slate-200">
                                    <span class="whitespace-normal break-words">{{ item.useCase_description || '-' }}</span>
                                </td>
                                <td class="whitespace-nowrap px-4 py-3 text-slate-700 dark:text-slate-200">{{ scoreLabel(item.value) }}</td>
                                <td class="whitespace-nowrap px-4 py-3 text-slate-700 dark:text-slate-200">{{ scoreLabel(item.urgency) }}</td>
                                <td class="max-w-[240px] px-4 py-3 text-slate-700 dark:text-slate-200">
                                    <span class="line-clamp-2">{{ rjppLabel(item.rjpps) }}</span>
                                </td>
                                <td class="whitespace-nowrap px-4 py-3 text-slate-700 dark:text-slate-200">{{ item.use_case?.coe?.name || '-' }}</td>
                                <td class="px-4 py-3 text-slate-700 dark:text-slate-200">
                                    <div class="min-w-[380px]">
                                        <div
                                            class="grid"
                                            :style="flowGridStyle"
                                        >
                                            <div
                                                v-for="(step, index) in buildStatusFlow(item.statuses)"
                                                :key="`flow-${item.id}-${step.id}`"
                                                class="relative flex justify-center"
                                            >
                                                <span
                                                    class="inline-flex h-7 w-7 shrink-0 items-center justify-center rounded-full text-[11px] font-bold text-white"
                                                    :class="step.circleClass"
                                                    :title="`${step.label}: ${step.count}`"
                                                >
                                                    {{ step.count }}
                                                </span>
                                                <span
                                                    v-if="index < buildStatusFlow(item.statuses).length - 1"
                                                    class="absolute left-1/2 top-1/2 ml-[1rem] h-0.5 w-[calc(100%_-_2rem)] -translate-y-1/2 rounded-full"
                                                    :class="step.lineClass"
                                                ></span>
                                            </div>
                                        </div>

                                        <div
                                            class="mt-1.5 grid gap-1 text-center"
                                            :style="flowGridStyle"
                                        >
                                            <div v-for="step in buildStatusFlow(item.statuses)" :key="`flow-label-${item.id}-${step.id}`">
                                                <p class="text-[11px] font-semibold text-slate-700 dark:text-slate-200">{{ step.label }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="sticky right-0 z-10 bg-white p-0 shadow-[-4px_0_8px_rgba(0,0,0,0.05)] dark:bg-[#171717] dark:shadow-[-4px_0_8px_rgba(0,0,0,0.2)]">
                                    <div class="grid w-[180px] grid-cols-2 divide-x divide-slate-200 border-l border-slate-200 dark:divide-white/10 dark:border-white/10">
                                        <Link
                                            :href="`/digital-initiatives/${item.id}`"
                                            class="block bg-emerald-500 px-2 py-1.5 text-center text-xs font-semibold text-white transition-colors hover:bg-emerald-600"
                                            title="View Scope Charter"
                                        >
                                            View
                                        </Link>
                                        <Link
                                            :href="`/digital-initiatives/${item.id}`"
                                            class="block bg-emerald-500 px-2 py-1.5 text-center text-xs font-semibold text-white transition-colors hover:bg-emerald-600"
                                            title="View Project Charter"
                                        >
                                            View
                                        </Link>
                                    </div>
                                </td>
                            </tr>

                            <tr v-if="initiatives.length === 0">
                                <td colspan="11" class="px-4 py-6 text-center text-xs text-slate-500 dark:text-slate-400">
                                    No digital initiatives found.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </UserLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import UserLayout from '@/Layouts/UserLayout.vue';

const props = defineProps({
    initiatives: {
        type: Array,
        default: () => [],
    },
    filters: {
        type: Object,
        default: () => ({}),
    },
    options: {
        type: Object,
        default: () => ({
            sources: [],
            statuses: [],
            phases: [],
            organizations: [],
            coes: [],
        }),
    },
    categoryOptions: {
        type: Array,
        default: () => [],
    },
});

const form = ref({
    search: props.filters.search || '',
    category_fase: props.filters.category_fase ? String(props.filters.category_fase) : '',
    source_id: props.filters.source_id ? String(props.filters.source_id) : '',
    phase_id: props.filters.phase_id ? String(props.filters.phase_id) : '',
    organization_id: props.filters.organization_id ? String(props.filters.organization_id) : '',
    coe_id: props.filters.coe_id ? String(props.filters.coe_id) : '',
});

let timeout = null;
const debouncedApplyFilters = () => {
    clearTimeout(timeout);
    timeout = setTimeout(applyFilters, 300);
};

const applyFilters = () => {
    router.get('/digital-initiatives', form.value, {
        preserveState: true,
        replace: true,
    });
};

const categoryLabel = (value) => {
    const found = props.categoryOptions.find((category) => Number(category.id) === Number(value));
    return found ? found.label : '-';
};

const scoreLabel = (value) => {
    const map = {
        1: 'Low',
        2: 'Medium',
        3: 'High',
        4: 'TBC',
    };

    return map[Number(value)] || '-';
};

const organizationLabel = (organizations = []) => {
    if (!organizations.length) {
        return '-';
    }

    return organizations.map((organization) => organization.name).join(', ');
};

const rjppLabel = (rjpps = []) => {
    if (!rjpps.length) {
        return '-';
    }

    return rjpps.map((rjpp) => rjpp.name).join(', ');
};

const capitalize = (value) => {
    if (!value) {
        return '-';
    }

    const raw = String(value).trim();
    return raw.charAt(0).toUpperCase() + raw.slice(1);
};

const phaseStyleByIndex = (index) => {
    const styles = [
        { circleClass: 'bg-slate-500', lineClass: 'bg-blue-300' },
        { circleClass: 'bg-blue-500', lineClass: 'bg-amber-300' },
        { circleClass: 'bg-amber-500', lineClass: 'bg-emerald-300' },
        { circleClass: 'bg-emerald-500', lineClass: 'bg-transparent' },
    ];

    return styles[index] || styles[styles.length - 1];
};

const buildStatusFlow = (statuses = []) => {
    const phases = Array.isArray(props.options?.phases) ? props.options.phases : [];
    const statusList = Array.isArray(statuses) ? statuses : [];

    return phases.map((phase, index) => {
        const count = statusList.filter((status) => Number(status.phase_id) === Number(phase.id)).length;
        const styles = phaseStyleByIndex(index);

        return {
            id: phase.id,
            label: capitalize(phase.name),
            count,
            circleClass: styles.circleClass,
            lineClass: styles.lineClass,
        };
    });
};

const flowGridStyle = {
    gridTemplateColumns: `repeat(${Math.max((props.options?.phases || []).length, 1)}, minmax(0, 1fr))`,
};
</script>
