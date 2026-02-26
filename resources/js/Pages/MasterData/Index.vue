<template>
    <UserLayout title="Master Data">
        <div class="space-y-4 animate-fade-in-up">
            <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-white/10 dark:bg-[#171717]">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Master Data Center</h1>
                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                            Halaman ini menampilkan data dari tabel <span class="font-semibold">mst_</span> dan <span class="font-semibold">trs_</span>.
                        </p>
                    </div>
                    <div class="w-full sm:w-80">
                        <label class="mb-1 block text-[10px] font-semibold uppercase tracking-[0.08em] text-slate-500 dark:text-slate-400">
                            Cari Nama Tabel
                        </label>
                        <input
                            v-model="search"
                            type="text"
                            placeholder="Contoh: initiative"
                            class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-xs text-slate-700 focus:border-[#1C75BC] focus:outline-none focus:ring-2 focus:ring-[#1C75BC]/20 dark:border-white/10 dark:bg-[#101826] dark:text-slate-100"
                        />
                    </div>
                </div>
            </section>

            <section class="grid grid-cols-1 gap-4 lg:grid-cols-[240px_minmax(0,1fr)]">
                <aside class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm dark:border-white/10 dark:bg-[#171717]">
                    <p class="text-[10px] font-semibold uppercase tracking-[0.08em] text-slate-500 dark:text-slate-400">
                        Menu
                    </p>
                    <div class="mt-3 space-y-2">
                        <button
                            type="button"
                            class="flex w-full items-center justify-between rounded-lg px-3 py-2 text-left text-sm font-semibold transition-colors"
                            :class="activeSection === 'master'
                                ? 'bg-[#1C75BC]/10 text-[#1C75BC] dark:bg-[#1C75BC]/20 dark:text-[#7FC0F2]'
                                : 'text-slate-700 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-white/5'"
                            @click="activeSection = 'master'"
                        >
                            <span>Data Master</span>
                            <span class="text-xs">{{ masterTables.length }}</span>
                        </button>
                        <button
                            type="button"
                            class="flex w-full items-center justify-between rounded-lg px-3 py-2 text-left text-sm font-semibold transition-colors"
                            :class="activeSection === 'transaction'
                                ? 'bg-[#A7C942]/20 text-[#4F6B0F] dark:bg-[#A7C942]/20 dark:text-[#C7E67A]'
                                : 'text-slate-700 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-white/5'"
                            @click="activeSection = 'transaction'"
                        >
                            <span>Data Transaksi</span>
                            <span class="text-xs">{{ transactionTables.length }}</span>
                        </button>
                    </div>

                    <div class="mt-4 rounded-xl border border-slate-200 p-3 text-xs dark:border-white/10">
                        <p class="font-semibold uppercase tracking-[0.08em] text-slate-500 dark:text-slate-400">Ringkasan</p>
                        <p class="mt-2 text-slate-600 dark:text-slate-300">MST: <span class="font-semibold">{{ masterTables.length }}</span> tabel</p>
                        <p class="text-slate-600 dark:text-slate-300">TRS: <span class="font-semibold">{{ transactionTables.length }}</span> tabel</p>
                    </div>
                </aside>

                <div class="space-y-4">
                    <section class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <article class="rounded-2xl border border-[#1C75BC] bg-[#1C75BC] p-4 shadow-[0_4px_16px_rgba(28,117,188,0.3)]">
                            <p class="text-[10px] font-semibold uppercase tracking-[0.08em] text-white">Tabel {{ sectionLabel }}</p>
                            <p class="mt-2 text-3xl font-bold text-white">{{ filteredTables.length }}</p>
                        </article>
                        <article class="rounded-2xl border border-slate-700 bg-slate-700 p-4 shadow-[0_4px_16px_rgba(51,65,85,0.35)]">
                            <p class="text-[10px] font-semibold uppercase tracking-[0.08em] text-white">Total Baris (Preview Scope)</p>
                            <p class="mt-2 text-3xl font-bold text-white">{{ selectedTotalRows }}</p>
                        </article>
                    </section>

                    <section
                        v-if="error"
                        class="rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700 dark:border-rose-500/30 dark:bg-rose-500/10 dark:text-rose-300"
                    >
                        {{ error }}
                    </section>

                    <section
                        v-if="!error && filteredTables.length === 0"
                        class="rounded-2xl border border-dashed border-slate-300 bg-white p-8 text-center text-sm text-slate-500 dark:border-white/10 dark:bg-[#171717] dark:text-slate-300"
                    >
                        Tidak ada tabel untuk kategori ini.
                    </section>

                    <section
                        v-for="table in filteredTables"
                        :key="table.name"
                        class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-white/10 dark:bg-[#171717]"
                    >
                        <div class="flex flex-col gap-1 border-b border-slate-200 px-4 py-3 dark:border-white/10 sm:flex-row sm:items-center sm:justify-between">
                            <h2 class="text-sm font-bold uppercase tracking-[0.06em] text-slate-800 dark:text-slate-100">
                                {{ table.name }}
                            </h2>
                            <p class="text-xs text-slate-500 dark:text-slate-400">
                                {{ table.total_rows }} baris | preview {{ previewLimit }} baris
                            </p>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-slate-200 text-[11px] dark:divide-white/10">
                                <thead class="bg-slate-50 dark:bg-white/5">
                                    <tr>
                                        <th
                                            v-for="column in table.columns"
                                            :key="`${table.name}-head-${column}`"
                                            class="whitespace-nowrap px-3 py-2 text-left text-[10px] font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400"
                                        >
                                            {{ column }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                                    <tr
                                        v-for="(row, rowIndex) in table.rows"
                                        :key="`${table.name}-row-${rowIndex}`"
                                        class="transition-colors hover:bg-slate-50 dark:hover:bg-white/5"
                                    >
                                        <td
                                            v-for="column in table.columns"
                                            :key="`${table.name}-${rowIndex}-${column}`"
                                            class="max-w-[240px] whitespace-nowrap px-3 py-2 text-slate-700 dark:text-slate-200"
                                            :title="fullCell(row[column])"
                                        >
                                            {{ shortCell(row[column]) }}
                                        </td>
                                    </tr>
                                    <tr v-if="table.rows.length === 0">
                                        <td
                                            :colspan="Math.max(table.columns.length, 1)"
                                            class="px-4 py-6 text-center text-xs text-slate-500 dark:text-slate-400"
                                        >
                                            Belum ada data.
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </section>
                </div>
            </section>
        </div>
    </UserLayout>
</template>

<script setup>
import { computed, ref } from 'vue';
import UserLayout from '@/Layouts/UserLayout.vue';

const props = defineProps({
    tables: {
        type: Array,
        default: () => [],
    },
    previewLimit: {
        type: Number,
        default: 10,
    },
    error: {
        type: String,
        default: null,
    },
});

const search = ref('');
const activeSection = ref('master');

const allTables = computed(() => {
    return Array.isArray(props.tables) ? props.tables : [];
});

const masterTables = computed(() => {
    return allTables.value.filter((table) => String(table?.name ?? '').toLowerCase().startsWith('mst_'));
});

const transactionTables = computed(() => {
    return allTables.value.filter((table) => String(table?.name ?? '').toLowerCase().startsWith('trs_'));
});

const sectionTables = computed(() => {
    return activeSection.value === 'transaction'
        ? transactionTables.value
        : masterTables.value;
});

const filteredTables = computed(() => {
    const keyword = String(search.value ?? '').trim().toLowerCase();

    if (!keyword) {
        return sectionTables.value;
    }

    return sectionTables.value.filter((table) =>
        String(table?.name ?? '').toLowerCase().includes(keyword)
    );
});

const sectionLabel = computed(() => {
    return activeSection.value === 'transaction' ? 'Transaksi' : 'Master';
});

const selectedTotalRows = computed(() => {
    return filteredTables.value.reduce((total, table) => {
        return total + Number(table?.total_rows ?? 0);
    }, 0);
});

const fullCell = (value) => {
    if (value === null || value === undefined) {
        return '-';
    }

    if (typeof value === 'object') {
        try {
            return JSON.stringify(value);
        } catch {
            return String(value);
        }
    }

    return String(value);
};

const shortCell = (value) => {
    const text = fullCell(value);

    if (text.length <= 80) {
        return text;
    }

    return `${text.slice(0, 80)}...`;
};
</script>

