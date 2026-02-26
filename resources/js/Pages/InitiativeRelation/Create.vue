<template>
    <UserLayout title="Create Initiative Relation">
        <div class="mx-auto max-w-5xl space-y-6 animate-fade-in-up">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Create Initiative Relation</h1>
                    <p class="text-sm text-slate-500 dark:text-slate-400">
                        Tambahkan relasi antar initiative.
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
                    <p class="text-sm text-slate-500 dark:text-slate-400">Lengkapi data relasi di bawah.</p>
                </div>

                <form class="space-y-6 p-6" @submit.prevent="submit">
                    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                        <div class="space-y-4">
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

                            <div>
                                <label class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-300">Tipe Relasi</label>
                                <select
                                    v-model.number="form.type_relation"
                                    class="h-12 w-full rounded-md border-slate-300 bg-slate-50 px-4 text-base shadow-sm focus:border-slate-400 focus:ring-slate-300 dark:border-white/10 dark:bg-[#131313] dark:text-slate-200"
                                >
                                    <option :value="null">Pilih tipe relasi</option>
                                    <option v-for="option in typeRelationOptions" :key="option.value" :value="option.value">
                                        {{ option.label }}
                                    </option>
                                </select>
                                <p v-if="form.errors.type_relation" class="mt-1 text-xs text-red-500">{{ form.errors.type_relation }}</p>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <label class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-300">Initiative Predecessor</label>
                                <select
                                    v-model="form.initiative_code_row"
                                    class="h-12 w-full rounded-md border-slate-300 bg-slate-50 px-4 text-base shadow-sm focus:border-slate-400 focus:ring-slate-300 dark:border-white/10 dark:bg-[#131313] dark:text-slate-200"
                                >
                                    <option value="">Pilih initiative</option>
                                    <option v-for="initiative in initiativeOptions" :key="initiative.id" :value="initiative.id">
                                        {{ initiativeLabel(initiative) }}
                                    </option>
                                </select>
                                <p v-if="form.errors.initiative_code_row" class="mt-1 text-xs text-red-500">{{ form.errors.initiative_code_row }}</p>
                            </div>

                            <div>
                                <label class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-300">Initiative Successor</label>
                                <select
                                    v-model="form.initiative_code_column"
                                    class="h-12 w-full rounded-md border-slate-300 bg-slate-50 px-4 text-base shadow-sm focus:border-slate-400 focus:ring-slate-300 dark:border-white/10 dark:bg-[#131313] dark:text-slate-200"
                                >
                                    <option value="">Pilih initiative</option>
                                    <option v-for="initiative in initiativeOptions" :key="initiative.id" :value="initiative.id">
                                        {{ initiativeLabel(initiative) }}
                                    </option>
                                </select>
                                <p v-if="form.errors.initiative_code_column" class="mt-1 text-xs text-red-500">{{ form.errors.initiative_code_column }}</p>
                            </div>
                        </div>
                    </div>

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
                            Create Relation
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </UserLayout>
</template>

<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import UserLayout from '@/Layouts/UserLayout.vue';

const props = defineProps({
    initiativeOptions: {
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
    model_relasi: '',
    type_relation: null,
    initiative_code_row: '',
    initiative_code_column: '',
    justifikasi: '',
});

const initiativeLabel = (initiative) => {
    const code = initiative?.code ?? initiative?.id ?? '-';
    const name = initiative?.name ?? '';
    return name ? `${code} - ${name}` : code;
};

const submit = () => {
    form.post('/program-implementation/initiative-relation');
};
</script>
