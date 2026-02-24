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
                            v-if="!isCharterOpen"
                            type="button"
                            class="rounded-lg bg-indigo-600 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-indigo-700"
                            @click="openCharter"
                        >
                            Open Digital Charter
                        </button>

                        <button
                            v-else
                            type="button"
                            class="rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-medium text-slate-700 transition hover:bg-slate-50 dark:border-white/10 dark:text-slate-300 dark:hover:bg-white/5"
                            @click="closeCharter"
                        >
                            Close Digital Charter
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
                v-if="isCharterOpen"
                class="print:hidden rounded-2xl border border-slate-200 bg-white p-3 shadow-sm dark:border-white/10 dark:bg-[#171717]"
            >
                <div class="flex flex-col gap-3 lg:flex-row lg:items-end lg:justify-between">
                    <div class="w-full lg:max-w-sm">
                        <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">
                            Charter Version
                        </label>
                        <select
                            disabled
                            class="w-full rounded-lg border border-slate-300 bg-slate-50 px-3 py-2 text-sm text-slate-900 dark:border-white/10 dark:bg-white/5 dark:text-slate-100"
                        >
                            <option>v1 - {{ currentDateLabel }}</option>
                        </select>
                    </div>

                    <div class="flex flex-wrap items-center gap-2">
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
                    </div>
                </div>

                <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">
                    Save akan membuat versi charter baru. Versi lama tetap tersimpan sebagai histori.
                </p>
            </section>

            <section
                v-if="!isCharterOpen"
                class="print:hidden rounded-2xl border border-dashed border-slate-300 bg-white p-10 text-center dark:border-white/15 dark:bg-[#171717]"
            >
                <p class="text-sm font-medium text-slate-600 dark:text-slate-300">
                    Buka digital charter untuk lihat detail inisiatif.
                </p>
            </section>

            <main v-if="isCharterOpen" class="print:m-0 print:p-0">
                <CharterDocument
                    :initiative="initiative"
                    :category-label="categoryLabel"
                />
            </main>
        </div>
    </UserLayout>
</template>

<script setup>
import { computed, ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import UserLayout from '@/Layouts/UserLayout.vue';
import CharterDocument from './Partials/CharterDocument.vue';

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

const isCharterOpen = ref(false);

const currentDateLabel = computed(() => {
    const d = new Date();
    return d.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });
});

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

const openCharter = () => {
    isCharterOpen.value = true;
};

const closeCharter = () => {
    isCharterOpen.value = false;
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
