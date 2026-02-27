<template>
    <UserLayout title="Create IT Initiative">
        <div class="max-w-2xl mx-auto animate-fade-in">
            <div class="mb-8">
                <Link href="/it-initiatives" class="text-slate-500 dark:text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 text-sm flex items-center gap-1 mb-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Back to IT Initiatives
                </Link>
                <h2 class="text-2xl font-bold text-slate-900 dark:text-white">New IT Initiative</h2>
                <p class="text-slate-500 dark:text-slate-400 text-sm">Create a new IT initiative entry to start drafting its charter.</p>
            </div>

            <div class="bg-white dark:bg-[#1a1a1a] rounded-xl border border-slate-200 dark:border-white/5 p-6 shadow-sm">
                <form @submit.prevent="submit" class="space-y-6">
                    <!-- Code & Name -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        <div class="md:col-span-1">
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">IT Initiative Code</label>
                            <input
                                v-model="form.code"
                                type="text"
                                class="w-full rounded-lg border-slate-300 dark:border-white/10 bg-white dark:bg-[#131313] text-slate-900 dark:text-slate-100 focus:ring-indigo-500 focus:border-indigo-500"
                                placeholder="PRJ-001"
                            />
                            <p v-if="form.errors.code" class="text-red-500 text-xs mt-1">{{ form.errors.code }}</p>
                        </div>
                        <div class="md:col-span-3">
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">IT Initiative Name</label>
                            <input
                                v-model="form.name"
                                type="text"
                                class="w-full rounded-lg border-slate-300 dark:border-white/10 bg-white dark:bg-[#131313] text-slate-900 dark:text-slate-100 focus:ring-indigo-500 focus:border-indigo-500"
                                placeholder="e.g., UI/UX Standardization"
                            />
                            <p v-if="form.errors.name" class="text-red-500 text-xs mt-1">{{ form.errors.name }}</p>
                        </div>
                    </div>

                    <!-- Owner & Status -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Company Owner</label>
                            <input
                                v-model="form.owner_name"
                                type="text"
                                class="w-full rounded-lg border-slate-300 dark:border-white/10 bg-white dark:bg-[#131313] text-slate-900 dark:text-slate-100 focus:ring-indigo-500 focus:border-indigo-500"
                                placeholder="e.g., PT Example Indonesia"
                            />
                            <p v-if="form.errors.owner_name" class="text-red-500 text-xs mt-1">{{ form.errors.owner_name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Status</label>
                            <select v-model="form.status" class="w-full rounded-lg border-slate-300 dark:border-white/10 bg-white dark:bg-[#131313] text-slate-700 dark:text-slate-200 focus:ring-indigo-500 focus:border-indigo-500">
                                <option v-for="statusOption in statusOptions" :key="statusOption.id" :value="statusOption.id">
                                    {{ statusOption.label }}
                                </option>
                            </select>
                        </div>
                    </div>

                    <!-- Mapping Planning IT Definition -->
                    <div>
                        <div class="mb-2 flex items-center justify-between">
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                                Mapping IT Definition (Planning)
                            </label>
                            <span class="text-xs font-medium text-slate-500 dark:text-slate-400">
                                Terpilih: {{ form.initiative_ids.length }}
                            </span>
                        </div>
                        <p class="mb-2 text-xs text-slate-500 dark:text-slate-400">
                            Checklist inisiatif dari master data `Program Planning / IT Definition` untuk dimapping ke project ini.
                        </p>

                        <div class="max-h-64 overflow-y-auto rounded-lg border border-slate-200 dark:border-white/10">
                            <label
                                v-for="initiative in planningDefinitions"
                                :key="`planning-it-${initiative.id}`"
                                class="flex cursor-pointer items-start gap-3 border-b border-slate-100 px-3 py-2 last:border-b-0 hover:bg-slate-50 dark:border-white/5 dark:hover:bg-white/5"
                            >
                                <input
                                    v-model="form.initiative_ids"
                                    type="checkbox"
                                    :value="Number(initiative.id)"
                                    class="mt-0.5 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 dark:border-white/20"
                                />
                                <span class="min-w-0">
                                    <span class="block text-sm font-medium text-slate-800 dark:text-slate-100">
                                        {{ initiativeLabel(initiative) }}
                                    </span>
                                    <span class="block text-xs text-slate-500 dark:text-slate-400">
                                        {{ initiative.description || '-' }}
                                    </span>
                                </span>
                            </label>

                            <div
                                v-if="planningDefinitions.length === 0"
                                class="px-3 py-5 text-center text-xs text-slate-500 dark:text-slate-400"
                            >
                                Belum ada data IT Definition (tipe 2) di `mst_initiative`.
                            </div>
                        </div>

                        <p v-if="form.errors.initiative_ids" class="text-red-500 text-xs mt-1">{{ form.errors.initiative_ids }}</p>
                        <p v-else-if="form.errors['initiative_ids.0']" class="text-red-500 text-xs mt-1">{{ form.errors['initiative_ids.0'] }}</p>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100 dark:border-white/5">
                        <Link href="/it-initiatives" class="px-4 py-2 text-sm font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5 rounded-lg">Cancel</Link>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg disabled:opacity-50"
                        >
                            Create IT Initiative
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </UserLayout>
</template>

<script setup>
import { useForm, Link } from '@inertiajs/vue3';
import UserLayout from '@/Layouts/UserLayout.vue';

const props = defineProps({
    statusOptions: {
        type: Array,
        default: () => [],
    },
    defaultStatusId: {
        type: Number,
        default: 1,
    },
    planningItDefinitions: {
        type: Array,
        default: () => [],
    },
});

const statusOptions = props.statusOptions.length > 0
    ? props.statusOptions
    : [{ id: 1, label: 'Drafting' }];
const planningDefinitions = Array.isArray(props.planningItDefinitions) ? props.planningItDefinitions : [];

const form = useForm({
    code: '',
    name: '',
    owner_name: '',
    status: statusOptions.some((statusOption) => statusOption.id === props.defaultStatusId)
        ? props.defaultStatusId
        : statusOptions[0].id,
    initiative_ids: [],
});

const initiativeLabel = (initiative) => {
    const code = String(initiative?.code ?? '').trim();
    const name = String(initiative?.name ?? '').trim();

    if (code && name) {
        return `${code} - ${name}`;
    }

    return name || code || '-';
};

const submit = () => {
    form.post('/it-initiatives');
};
</script>
