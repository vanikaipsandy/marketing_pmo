<template>
    <UserLayout title="Master Data — Master Initiative">
        <div class="animate-fade-in-up space-y-4">
            <!-- Header -->
            <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-white/10 dark:bg-[#171717]">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Master Initiative</h1>
                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                            Kelola data master initiative (Digital &amp; IT).
                        </p>
                    </div>
                    <a href="/master-data"
                       class="inline-flex items-center gap-1 rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-medium text-slate-600 hover:bg-slate-50 dark:border-white/10 dark:text-slate-300 dark:hover:bg-white/5">
                        ← Kembali ke Master Data
                    </a>
                </div>
            </section>

            <!-- Stat cards -->
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                <article class="rounded-2xl border border-[#0f63b5] bg-[#0f63b5] p-4 shadow-md">
                    <p class="text-[10px] font-semibold uppercase tracking-[0.08em] text-blue-100">Total Initiative</p>
                    <p class="mt-1 text-3xl font-bold text-white">{{ mstInitiatives.length }}</p>
                </article>
                <article class="rounded-2xl border border-blue-500 bg-blue-500 p-4 shadow-md">
                    <p class="text-[10px] font-semibold uppercase tracking-[0.08em] text-blue-100">Digital</p>
                    <p class="mt-1 text-3xl font-bold text-white">{{ digitalCount }}</p>
                </article>
                <article class="rounded-2xl border border-violet-500 bg-violet-500 p-4 shadow-md">
                    <p class="text-[10px] font-semibold uppercase tracking-[0.08em] text-violet-100">IT</p>
                    <p class="mt-1 text-3xl font-bold text-white">{{ itCount }}</p>
                </article>
            </div>

            <!-- Filter + Add button -->
            <div class="overflow-hidden rounded-xl border border-slate-200 bg-white dark:border-white/5 dark:bg-[#1a1a1a]">
                <div class="flex flex-wrap items-center gap-2 border-b border-slate-200 px-4 py-2.5 dark:border-white/10">
                    <div class="flex items-center gap-1.5">
                        <label class="text-[10px] font-semibold uppercase tracking-wider text-slate-500">Tipe</label>
                        <select v-model="filterTipe" class="rounded border border-slate-200 bg-white px-2 py-0.5 text-[10px] text-slate-700 shadow-sm focus:outline-none dark:border-white/10 dark:bg-[#1f1f1f] dark:text-slate-200">
                            <option value="">All</option>
                            <option value="1">Digital</option>
                            <option value="2">IT</option>
                        </select>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <label class="text-[10px] font-semibold uppercase tracking-wider text-slate-500">CoE</label>
                        <select v-model="filterCoe" class="rounded border border-slate-200 bg-white px-2 py-0.5 text-[10px] text-slate-700 shadow-sm focus:outline-none dark:border-white/10 dark:bg-[#1f1f1f] dark:text-slate-200">
                            <option value="">All</option>
                            <option v-for="opt in coeFilterOptions" :key="opt" :value="opt">{{ opt }}</option>
                        </select>
                    </div>
                    <div class="ml-auto flex items-center gap-2">
                        <input v-model="search" type="text" placeholder="Cari nama / kode..."
                            class="rounded border border-slate-200 bg-white px-2.5 py-1 text-[11px] text-slate-700 shadow-sm focus:border-blue-400 focus:outline-none dark:border-white/10 dark:bg-[#1f1f1f] dark:text-slate-200" />
                        <button v-if="filterTipe || filterCoe || search" type="button"
                            class="rounded border border-slate-200 px-2 py-0.5 text-[10px] font-medium text-slate-500 hover:bg-slate-100 dark:border-white/10 dark:hover:bg-white/5"
                            @click="resetFilters">Reset</button>
                        <button type="button"
                            class="rounded-lg bg-[#0f63b5] px-3 py-1 text-[11px] font-semibold text-white shadow-sm hover:bg-[#0c4e8f]"
                            @click="openCreateModal">+ Tambah</button>
                    </div>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="w-full table-fixed divide-y divide-slate-200 text-[11px] dark:divide-white/5">
                        <colgroup>
                            <col class="w-[3%]"><col class="w-[6%]"><col class="w-[8%]"><col class="w-[12%]">
                            <col class="w-[20%]"><col class="w-[10%]"><col class="w-[10%]"><col class="w-[7%]">
                            <col class="w-[14%]"><col class="w-[10%]">
                        </colgroup>
                        <thead class="bg-slate-50 dark:bg-white/5">
                            <tr>
                                <th v-for="h in headers" :key="h" class="px-3 py-2 text-left text-[10px] font-semibold uppercase tracking-wider text-slate-500">{{ h }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 bg-white dark:divide-white/5 dark:bg-[#1a1a1a]">
                            <tr v-for="(item, idx) in filteredInitiatives" :key="item.id" class="transition-colors hover:bg-slate-50 dark:hover:bg-white/5">
                                <td class="px-3 py-2.5 text-slate-500">{{ idx + 1 }}</td>
                                <td class="px-3 py-2.5 font-medium text-slate-700 dark:text-slate-200">{{ item.code ?? '-' }}</td>
                                <td class="px-3 py-2.5">
                                    <span class="inline-flex rounded-full px-2 py-0.5 text-[10px] font-medium"
                                        :class="item.tipe_initiative === 1
                                            ? 'bg-blue-50 text-blue-700 dark:bg-blue-500/10 dark:text-blue-300'
                                            : 'bg-violet-50 text-violet-700 dark:bg-violet-500/10 dark:text-violet-300'">
                                        {{ item.tipe_initiative === 1 ? 'Digital' : 'IT' }}
                                    </span>
                                </td>
                                <td class="px-3 py-2.5 text-slate-700 dark:text-slate-200">{{ item.coe?.name ?? '-' }}</td>
                                <td class="px-3 py-2.5 font-medium text-slate-700 dark:text-slate-200"><p class="break-words">{{ item.name ?? '-' }}</p></td>
                                <td class="px-3 py-2.5 text-slate-600 dark:text-slate-300">{{ item.organization?.groub?.name ?? '-' }}</td>
                                <td class="px-3 py-2.5 text-slate-600 dark:text-slate-300">{{ item.organization?.name ?? '-' }}</td>
                                <td class="px-3 py-2.5">
                                    <span v-if="item.status" class="inline-flex rounded-full bg-slate-100 px-2 py-0.5 text-[10px] font-medium text-slate-600 dark:bg-white/10 dark:text-slate-300">{{ item.status }}</span>
                                    <span v-else class="text-slate-400">-</span>
                                </td>
                                <td class="px-3 py-2.5 text-slate-600 dark:text-slate-300"><p class="line-clamp-2 break-words">{{ item.description ?? '-' }}</p></td>
                                <td class="px-3 py-2.5">
                                    <div class="flex items-center gap-1">
                                        <button type="button" @click="openEditModal(item)"
                                            class="inline-flex rounded-full bg-amber-100 px-2 py-0.5 text-[9px] font-semibold text-amber-800 hover:bg-amber-200 dark:bg-amber-500/20 dark:text-amber-300">Edit</button>
                                        <button type="button" @click="confirmDelete(item)"
                                            class="inline-flex rounded-full bg-rose-100 px-2 py-0.5 text-[9px] font-semibold text-rose-700 hover:bg-rose-200 dark:bg-rose-500/20 dark:text-rose-300">Hapus</button>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="filteredInitiatives.length === 0">
                                <td colspan="10" class="px-6 py-8 text-center text-xs text-slate-500">Tidak ada data.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- ═══ CREATE / EDIT MODAL ═══ -->
        <div v-if="showFormModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm" @click.self="closeFormModal">
            <div class="w-full max-w-2xl rounded-xl border border-slate-200 bg-white shadow-xl dark:border-white/10 dark:bg-[#1a1a1a]">
                <div class="rounded-t-xl bg-[#0f63b5] px-6 py-4">
                    <h2 class="text-lg font-bold text-white">{{ editingItem ? 'Edit' : 'Tambah' }} Master Initiative</h2>
                    <p v-if="editingItem" class="text-sm text-blue-100">{{ editingItem.code ?? editingItem.name }}</p>
                </div>
                <form class="space-y-5 p-6" @submit.prevent="submitForm">
                    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                        <FormField label="Code" :error="form.errors.code">
                            <input v-model="form.code" type="text" placeholder="e.g. DI-001" class="form-input" />
                        </FormField>
                        <FormField label="Tipe Initiative" :error="form.errors.tipe_initiative" required>
                            <select v-model="form.tipe_initiative" class="form-input">
                                <option value="">Pilih Tipe</option>
                                <option v-for="t in tipeOptions" :key="t.id" :value="t.id">{{ t.label }}</option>
                            </select>
                        </FormField>
                        <FormField label="CoE" :error="form.errors.coe_id">
                            <select v-model="form.coe_id" class="form-input">
                                <option :value="null">— Pilih CoE —</option>
                                <option v-for="opt in coeOptions" :key="opt.id" :value="opt.id">{{ opt.name }}</option>
                            </select>
                        </FormField>
                        <FormField label="Organisasi" :error="form.errors.business_unit">
                            <select v-model="form.business_unit" class="form-input">
                                <option :value="null">— Pilih Organisasi —</option>
                                <option v-for="opt in organizationOptions" :key="opt.id" :value="opt.id">
                                    {{ opt.groub ? `${opt.groub} — ` : '' }}{{ opt.name }}
                                </option>
                            </select>
                        </FormField>
                    </div>
                    <FormField label="Nama Initiative" :error="form.errors.name" required>
                        <input v-model="form.name" type="text" placeholder="Nama initiative" class="form-input" />
                    </FormField>
                    <FormField label="Deskripsi" :error="form.errors.description">
                        <textarea v-model="form.description" rows="3" placeholder="Deskripsi singkat..." class="form-input"></textarea>
                    </FormField>
                    <FormField label="Status" :error="form.errors.status">
                        <input v-model="form.status" type="text" placeholder="e.g. Active" class="form-input" />
                    </FormField>
                    <div class="flex items-center justify-end gap-3 border-t border-slate-100 pt-5 dark:border-white/5">
                        <button type="button" @click="closeFormModal"
                            class="rounded-lg px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-white/5">Batal</button>
                        <button type="submit" :disabled="form.processing"
                            class="rounded-lg bg-[#0f63b5] px-6 py-2 text-sm font-medium text-white shadow-sm hover:bg-[#0c4e8f] disabled:opacity-50">
                            {{ editingItem ? 'Simpan Perubahan' : 'Simpan' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- ═══ DELETE MODAL ═══ -->
        <ConfirmDeleteModal v-if="deleteTarget" :name="deleteTarget.name" @cancel="deleteTarget = null" @confirm="doDelete" />
    </UserLayout>
</template>

<script setup>
import { computed, ref } from 'vue';
import { router, useForm } from '@inertiajs/vue3';
import UserLayout from '@/Layouts/UserLayout.vue';
import FormField from '@/Components/MasterData/FormField.vue';
import ConfirmDeleteModal from '@/Components/MasterData/ConfirmDeleteModal.vue';

const props = defineProps({
    mstInitiatives:      { type: Array, default: () => [] },
    coeOptions:          { type: Array, default: () => [] },
    organizationOptions: { type: Array, default: () => [] },
    tipeOptions:         { type: Array, default: () => [] },
});

const headers = ['No', 'Code', 'Tipe', 'CoE', 'Nama', 'Holding', 'Organisasi', 'Status', 'Deskripsi', 'Action'];

// ── Filters ──
const filterTipe = ref('');
const filterCoe = ref('');
const search = ref('');
const resetFilters = () => { filterTipe.value = ''; filterCoe.value = ''; search.value = ''; };

const digitalCount = computed(() => props.mstInitiatives.filter(i => i.tipe_initiative === 1).length);
const itCount = computed(() => props.mstInitiatives.filter(i => i.tipe_initiative === 2).length);

const coeFilterOptions = computed(() => {
    const names = props.mstInitiatives.map(i => i.coe?.name).filter(Boolean);
    return [...new Set(names)].sort();
});

const filteredInitiatives = computed(() => {
    return props.mstInitiatives.filter(item => {
        if (filterTipe.value && String(item.tipe_initiative) !== filterTipe.value) return false;
        if (filterCoe.value && (item.coe?.name ?? '') !== filterCoe.value) return false;
        if (search.value) {
            const q = search.value.toLowerCase();
            if (!(item.name ?? '').toLowerCase().includes(q) && !(item.code ?? '').toLowerCase().includes(q)) return false;
        }
        return true;
    });
});

// ── Create / Edit ──
const showFormModal = ref(false);
const editingItem = ref(null);

const form = useForm({
    code: '', name: '', description: '', tipe_initiative: '', coe_id: null, business_unit: null, status: '',
});

const openCreateModal = () => { editingItem.value = null; form.reset(); form.clearErrors(); showFormModal.value = true; };

const openEditModal = (item) => {
    editingItem.value = item;
    Object.assign(form, {
        code: item.code ?? '', name: item.name ?? '', description: item.description ?? '',
        tipe_initiative: item.tipe_initiative ?? '', coe_id: item.coe_id ?? null,
        business_unit: item.business_unit ?? null, status: item.status ?? '',
    });
    form.clearErrors();
    showFormModal.value = true;
};

const closeFormModal = () => { showFormModal.value = false; editingItem.value = null; };

const submitForm = () => {
    const opts = { onSuccess: () => closeFormModal() };
    if (editingItem.value) {
        form.put(`/master-data/master-initiatives/${editingItem.value.id}`, opts);
    } else {
        form.post('/master-data/master-initiatives', opts);
    }
};

// ── Delete ──
const deleteTarget = ref(null);
const confirmDelete = (item) => { deleteTarget.value = item; };
const doDelete = () => {
    if (!deleteTarget.value) return;
    router.delete(`/master-data/master-initiatives/${deleteTarget.value.id}`, {
        onFinish: () => { deleteTarget.value = null; },
    });
};
</script>

<style scoped>
@reference "tailwindcss";
.form-input {
    @apply w-full rounded-md border-slate-300 bg-slate-50 text-sm shadow-sm focus:border-[#0f63b5] focus:ring-[#0f63b5] dark:border-white/10 dark:bg-[#131313] dark:text-slate-100;
}
</style>
