<template>
    <UserLayout :title="`Digital Initiative - #${initiative.id}`">
        <div class="space-y-4 print:space-y-0">
            <section class="print:hidden rounded-2xl border border-slate-200 bg-white p-3 shadow-sm dark:border-white/10 dark:bg-[#171717]">
                <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
                    <div class="flex flex-wrap items-center gap-2">
                        <Link
                            href="/digital-initiatives"
                            class="inline-flex items-center gap-2 rounded-lg border border-slate-200 px-2.5 py-1.5 text-sm font-medium text-slate-600 transition hover:bg-slate-50 dark:border-white/10 dark:text-slate-300 dark:hover:bg-white/5"
                        >
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m15 19-7-7 7-7" />
                            </svg>
                            Back
                        </Link>

                        <h1 class="text-base font-bold text-slate-900 dark:text-white">
                            #{{ initiative.id }}
                        </h1>

                        <span class="text-xs text-slate-500 dark:text-slate-400">
                            {{ categoryLabel(initiative.category_fase) }}
                        </span>
                    </div>

                    <div class="flex flex-wrap items-center gap-2">
                        <button
                            v-if="!isDetailOpen"
                            type="button"
                            class="rounded-lg bg-indigo-600 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-indigo-700"
                            @click="openDetail"
                        >
                            Open Digital Charter
                        </button>

                        <button
                            v-else
                            type="button"
                            class="rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-medium text-slate-700 transition hover:bg-slate-50 dark:border-white/10 dark:text-slate-300 dark:hover:bg-white/5"
                            @click="closeDetail"
                        >
                            Close Digital Charter
                        </button>

                        <Link
                            :href="`/digital-initiatives/${initiative.id}/edit`"
                            class="rounded-lg bg-indigo-600 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-indigo-700"
                        >
                            Edit Charter
                        </Link>

                        <button
                            type="button"
                            class="inline-flex items-center gap-2 rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-medium text-slate-700 transition hover:bg-slate-50 dark:border-white/10 dark:text-slate-300 dark:hover:bg-white/5"
                            @click="printCharter"
                        >
                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 9V4h12v5M6 17h12v3H6v-3Zm-2-2h16a2 2 0 0 0 2-2v-2a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v2a2 2 0 0 0 2 2Z" />
                            </svg>
                            Print
                        </button>

                        <button
                            type="button"
                            class="rounded-lg border border-red-200 px-3 py-1.5 text-xs font-semibold text-red-600 transition hover:bg-red-50 dark:border-red-500/20 dark:text-red-400 dark:hover:bg-red-500/10"
                            @click="destroyItem"
                        >
                            Delete
                        </button>
                    </div>
                </div>

                <div class="mt-4 flex flex-wrap gap-2 text-xs text-slate-500 dark:text-slate-400">
                    <span class="inline-flex items-center rounded-md bg-slate-100 px-2 py-1 dark:bg-white/5">
                        Source: {{ initiative.source?.name || '-' }}
                    </span>
                    <span class="inline-flex items-center rounded-md bg-slate-100 px-2 py-1 dark:bg-white/5">
                        Value/Urgency: {{ scoreLabel(initiative.value) }} / {{ scoreLabel(initiative.urgency) }}
                    </span>
                </div>
            </section>

            <section
                v-if="!isDetailOpen"
                class="print:hidden rounded-2xl border border-dashed border-slate-300 bg-white p-10 text-center dark:border-white/15 dark:bg-[#171717]"
            >
                <p class="text-sm font-medium text-slate-600 dark:text-slate-300">
                    Buka digital charter untuk lihat detail inisiatif.
                </p>
            </section>

            <main v-if="isDetailOpen" class="print:m-0 print:p-0">
                <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-white/10 dark:bg-[#171717]">
                    <div class="grid grid-cols-1 gap-5 lg:grid-cols-2">
                        <article class="space-y-3">
                            <h2 class="text-sm font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Main Data</h2>
                            <dl class="space-y-2 text-sm">
                                <div>
                                    <dt class="text-slate-500 dark:text-slate-400">Use Case</dt>
                                    <dd class="font-medium text-slate-800 dark:text-slate-200">{{ initiative.use_case?.name || '-' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-slate-500 dark:text-slate-400">COE</dt>
                                    <dd class="font-medium text-slate-800 dark:text-slate-200">{{ initiative.use_case?.coe?.name || '-' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-slate-500 dark:text-slate-400">Description</dt>
                                    <dd class="font-medium text-slate-800 dark:text-slate-200">{{ initiative.useCase_description || '-' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-slate-500 dark:text-slate-400">Reference Planning</dt>
                                    <dd class="font-medium text-slate-800 dark:text-slate-200">{{ initiative.planning_or_implementation?.useCase_description || '-' }}</dd>
                                </div>
                            </dl>
                        </article>

                        <article class="space-y-3">
                            <h2 class="text-sm font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Relations</h2>

                            <div>
                                <p class="mb-1 text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Organizations</p>
                                <div class="flex flex-wrap gap-1.5">
                                    <span v-for="organization in initiative.organizations || []" :key="organization.id" class="inline-flex rounded-full bg-slate-100 px-2 py-0.5 text-xs font-semibold text-slate-700 dark:bg-white/10 dark:text-slate-200">
                                        {{ organization.name }}
                                    </span>
                                    <span v-if="(initiative.organizations || []).length === 0" class="text-sm text-slate-500 dark:text-slate-400">-</span>
                                </div>
                            </div>

                            <div>
                                <p class="mb-1 text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">RJPP Themes</p>
                                <div class="flex flex-wrap gap-1.5">
                                    <span v-for="rjpp in initiative.rjpps || []" :key="rjpp.id" class="inline-flex rounded-full bg-indigo-100 px-2 py-0.5 text-xs font-semibold text-indigo-700 dark:bg-indigo-500/15 dark:text-indigo-300">
                                        {{ rjpp.name }}
                                    </span>
                                    <span v-if="(initiative.rjpps || []).length === 0" class="text-sm text-slate-500 dark:text-slate-400">-</span>
                                </div>
                            </div>

                            <div>
                                <p class="mb-1 text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Statuses</p>
                                <div class="flex flex-wrap gap-1.5">
                                    <span v-for="status in initiative.statuses || []" :key="status.id" class="inline-flex rounded-full bg-emerald-100 px-2 py-0.5 text-xs font-semibold text-emerald-700 dark:bg-emerald-500/15 dark:text-emerald-300">
                                        {{ status.name }}
                                    </span>
                                    <span v-if="(initiative.statuses || []).length === 0" class="text-sm text-slate-500 dark:text-slate-400">-</span>
                                </div>
                            </div>
                        </article>
                    </div>
                </section>
            </main>
        </div>
    </UserLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import UserLayout from '@/Layouts/UserLayout.vue';

const props = defineProps({
    initiative: {
        type: Object,
        required: true,
    },
    categoryOptions: {
        type: Array,
        default: () => [],
    },
});

const isDetailOpen = ref(false);

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

const openDetail = () => {
    isDetailOpen.value = true;
};

const closeDetail = () => {
    isDetailOpen.value = false;
};

const destroyItem = () => {
    if (!confirm(`Delete digital initiative #${props.initiative.id}?`)) {
        return;
    }

    router.delete(`/digital-initiatives/${props.initiative.id}`);
};

const printCharter = () => {
    window.print();
};
</script>
