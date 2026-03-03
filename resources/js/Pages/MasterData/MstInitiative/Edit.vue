<template>
    <UserLayout title="Master Data — Edit Initiative">
        <div class="animate-fade-in-up space-y-4">
            <!-- Header -->
            <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-white/10 dark:bg-[#171717]">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Edit Master Initiative</h1>
                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                            {{ initiative.code ?? '' }} — {{ initiative.name }}
                        </p>
                    </div>
                    <a href="/master-data/master-initiatives"
                       class="inline-flex items-center gap-1 rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-medium text-slate-600 hover:bg-slate-50 dark:border-white/10 dark:text-slate-300 dark:hover:bg-white/5">
                        ← Kembali
                    </a>
                </div>
            </section>

            <!-- ═══ MASTER DATA — TABLE STYLE ═══ -->
            <div class="overflow-hidden rounded-xl border border-slate-200 bg-white dark:border-white/5 dark:bg-[#1a1a1a]">
                <div class="flex items-center justify-between border-b border-slate-200 px-4 py-2.5 dark:border-white/10">
                    <h2 class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Data Initiative</h2>
                    <button type="button" @click="submitMaster" :disabled="masterForm.processing"
                        class="inline-flex items-center gap-1 rounded-lg bg-[#0f63b5] px-4 py-1.5 text-xs font-semibold text-white shadow-sm hover:bg-[#0c4e8f] disabled:opacity-50">
                        Simpan
                    </button>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full table-fixed divide-y divide-slate-200 text-[11px] dark:divide-white/5">
                        <colgroup>
                            <col class="w-[8%]"><col class="w-[10%]"><col class="w-[14%]">
                            <col class="w-[14%]"><col class="w-[22%]"><col class="w-[32%]">
                        </colgroup>
                        <thead class="bg-slate-50 dark:bg-white/5">
                            <tr>
                                <th v-for="h in masterHeaders" :key="h" class="px-3 py-2 text-left text-[10px] font-semibold uppercase tracking-wider text-slate-500">{{ h }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 bg-white dark:divide-white/5 dark:bg-[#1a1a1a]">
                            <tr class="transition-colors hover:bg-slate-50 dark:hover:bg-white/5">
                                <td class="px-3 py-2.5">
                                    <input v-model="masterForm.code" type="text" placeholder="DI-001" class="form-input-sm" />
                                </td>
                                <td class="px-3 py-2.5">
                                    <select v-model="masterForm.tipe_initiative" class="form-input-sm">
                                        <option value="">—</option>
                                        <option v-for="t in tipeOptions" :key="t.id" :value="t.id">{{ t.label }}</option>
                                    </select>
                                </td>
                                <td class="px-3 py-2.5">
                                    <select v-model="masterForm.coe_id" class="form-input-sm">
                                        <option :value="null">—</option>
                                        <option v-for="opt in coeOptions" :key="opt.id" :value="opt.id">{{ opt.name }}</option>
                                    </select>
                                </td>
                                <td class="px-3 py-2.5">
                                    <select v-model="masterForm.business_unit" class="form-input-sm">
                                        <option :value="null">—</option>
                                        <option v-for="opt in organizationOptions" :key="opt.id" :value="opt.id">
                                            {{ opt.groub ? `${opt.groub} — ` : '' }}{{ opt.name }}
                                        </option>
                                    </select>
                                </td>
                                <td class="px-3 py-2.5">
                                    <input v-model="masterForm.name" type="text" placeholder="Nama initiative" class="form-input-sm" />
                                </td>
                                <td class="px-3 py-2.5">
                                    <input v-model="masterForm.description" type="text" placeholder="Deskripsi singkat..." class="form-input-sm" />
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div v-if="Object.keys(masterForm.errors).length" class="border-t border-slate-200 px-4 py-2 dark:border-white/10">
                    <p v-for="(msg, field) in masterForm.errors" :key="field" class="text-[10px] text-rose-500">{{ field }}: {{ msg }}</p>
                </div>
            </div>

            <!-- ═══ STATUS HISTORY — READ-ONLY TABLE ═══ -->
            <div class="overflow-hidden rounded-xl border border-slate-200 bg-white dark:border-white/5 dark:bg-[#1a1a1a]">
                <div class="flex items-center justify-between border-b border-slate-200 px-4 py-2.5 dark:border-white/10">
                    <h2 class="text-[11px] font-bold uppercase tracking-wider text-slate-500">
                        Status History
                        <span class="ml-1 font-normal text-slate-400">({{ initiative.status_history?.length ?? 0 }})</span>
                    </h2>
                    <button v-if="!showNewRow" type="button" @click="openNewRow"
                        class="inline-flex items-center gap-1 rounded-lg bg-[#0f63b5] px-4 py-1.5 text-xs font-semibold text-white shadow-sm hover:bg-[#0c4e8f]">
                        + Tambah
                    </button>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full table-fixed divide-y divide-slate-200 text-[11px] dark:divide-white/5">
                        <colgroup>
                            <col class="w-[6%]"><col class="w-[28%]"><col class="w-[22%]"><col class="w-[44%]">
                        </colgroup>
                        <thead class="bg-slate-50 dark:bg-white/5">
                            <tr>
                                <th v-for="h in statusHeaders" :key="h" class="px-3 py-2 text-left text-[10px] font-semibold uppercase tracking-wider text-slate-500">{{ h }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 bg-white dark:divide-white/5 dark:bg-[#1a1a1a]">
                            <tr v-for="(entry, idx) in initiative.status_history" :key="entry.id"
                                class="transition-colors hover:bg-slate-50 dark:hover:bg-white/5">
                                <td class="px-3 py-2.5 text-slate-500">{{ idx + 1 }}</td>
                                <td class="px-3 py-2.5">
                                    <span class="inline-flex rounded-full px-2 py-0.5 text-[10px] font-medium"
                                          :class="idx === 0 ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-300' : 'bg-slate-100 text-slate-600 dark:bg-white/10 dark:text-slate-300'">
                                        {{ entry.status }}
                                    </span>
                                </td>
                                <td class="px-3 py-2.5 text-slate-600 dark:text-slate-300">{{ formatDate(entry.tanggal) }}</td>
                                <td class="px-3 py-2.5 text-slate-600 dark:text-slate-300">{{ entry.notes ?? '-' }}</td>
                            </tr>

                            <tr v-if="!initiative.status_history?.length && !showNewRow">
                                <td colspan="4" class="px-6 py-8 text-center text-xs text-slate-500">Belum ada riwayat status.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Add new status form (below table) -->
                <div v-if="showNewRow" class="border-t border-slate-200 px-4 py-3 dark:border-white/10">
                    <div class="flex items-end gap-2">
                        <div class="flex-1">
                            <label class="mb-0.5 block text-[10px] font-semibold uppercase tracking-wider text-slate-500">Status *</label>
                            <input v-model="newRow.status" type="text" placeholder="Status" class="form-input-sm" />
                            <p v-if="newRowErrors.status" class="mt-0.5 text-[10px] text-rose-500">{{ newRowErrors.status }}</p>
                        </div>
                        <div class="w-40">
                            <label class="mb-0.5 block text-[10px] font-semibold uppercase tracking-wider text-slate-500">Tanggal</label>
                            <input v-model="newRow.tanggal" type="date" class="form-input-sm" />
                        </div>
                        <div class="flex-1">
                            <label class="mb-0.5 block text-[10px] font-semibold uppercase tracking-wider text-slate-500">Notes</label>
                            <input v-model="newRow.notes" type="text" placeholder="Catatan..." class="form-input-sm" />
                        </div>
                        <button type="button" @click="saveNewRow" :disabled="newRowProcessing"
                            class="inline-flex rounded-lg bg-emerald-500 px-3 py-1.5 text-[11px] font-semibold text-white hover:bg-emerald-600 disabled:opacity-50">
                            Save
                        </button>
                        <button type="button" @click="showNewRow = false"
                            class="inline-flex rounded-lg border border-slate-200 px-3 py-1.5 text-[11px] font-semibold text-slate-600 hover:bg-slate-50 dark:border-white/10 dark:text-slate-300 dark:hover:bg-white/5">
                            Batal
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </UserLayout>
</template>

<script setup>
import { ref, reactive } from 'vue';
import { router, useForm } from '@inertiajs/vue3';
import UserLayout from '@/Layouts/UserLayout.vue';

const props = defineProps({
    initiative:          { type: Object, required: true },
    coeOptions:          { type: Array, default: () => [] },
    organizationOptions: { type: Array, default: () => [] },
    tipeOptions:         { type: Array, default: () => [] },
});

const masterHeaders = ['Code', 'Tipe', 'CoE', 'Organisasi', 'Nama', 'Deskripsi'];
const statusHeaders = ['No', 'Status', 'Tanggal', 'Notes'];

// ── Master form ──
const masterForm = useForm({
    code:            props.initiative.code ?? '',
    name:            props.initiative.name ?? '',
    description:     props.initiative.description ?? '',
    tipe_initiative: props.initiative.tipe_initiative ?? '',
    coe_id:          props.initiative.coe_id ?? null,
    business_unit:   props.initiative.business_unit ?? null,
});

const submitMaster = () => {
    masterForm.put(`/master-data/master-initiatives/${props.initiative.id}`);
};

// ── Add new status ──
const showNewRow = ref(false);
const newRow = reactive({ status: '', tanggal: '', notes: '' });
const newRowErrors = reactive({ status: '' });
const newRowProcessing = ref(false);

const openNewRow = () => {
    showNewRow.value = true;
    newRow.status = ''; newRow.tanggal = ''; newRow.notes = '';
    newRowErrors.status = '';
};

const saveNewRow = () => {
    newRowErrors.status = '';
    if (!newRow.status.trim()) { newRowErrors.status = 'Status wajib diisi.'; return; }
    newRowProcessing.value = true;
    router.post(`/master-data/master-initiatives/${props.initiative.id}/status`, {
        status: newRow.status,
        tanggal: newRow.tanggal || null,
        notes: newRow.notes || null,
    }, {
        preserveScroll: true,
        onFinish: () => { newRowProcessing.value = false; showNewRow.value = false; },
    });
};

// ── Helpers ──
const formatDate = (d) => {
    if (!d) return '-';
    try { return new Date(d).toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' }); }
    catch { return d; }
};
</script>

<style scoped>
@reference "tailwindcss";
.form-input-sm {
    @apply w-full rounded border-slate-300 bg-white px-2 py-1 text-[11px] shadow-sm focus:border-[#0f63b5] focus:ring-[#0f63b5] dark:border-white/10 dark:bg-[#131313] dark:text-slate-100;
}
</style>
