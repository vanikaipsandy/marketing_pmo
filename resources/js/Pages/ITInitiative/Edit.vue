<template>
    <UserLayout title="Edit IT Initiative">
        <div class="mx-auto max-w-2xl animate-fade-in">
            <div class="mb-8">
                <Link href="/it-initiatives" class="mb-2 flex items-center gap-1 text-sm text-slate-500 hover:text-indigo-600 dark:text-slate-400 dark:hover:text-indigo-400">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Back to IT Initiatives
                </Link>
                <h2 class="text-2xl font-bold text-slate-900 dark:text-white">Edit IT Initiative</h2>
            </div>

            <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm dark:border-white/5 dark:bg-[#1a1a1a]">
                <form class="space-y-6" @submit.prevent="submit">
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-4">
                        <div class="md:col-span-1">
                            <label class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-300">IT Initiative Code</label>
                            <input
                                v-model="form.code"
                                type="text"
                                class="w-full rounded-lg border-slate-300 bg-white text-slate-900 focus:border-indigo-500 focus:ring-indigo-500 dark:border-white/10 dark:bg-[#131313] dark:text-slate-100"
                                placeholder="PRJ-001"
                            />
                            <p v-if="form.errors.code" class="mt-1 text-xs text-red-500">{{ form.errors.code }}</p>
                        </div>
                        <div class="md:col-span-3">
                            <label class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-300">IT Initiative Name</label>
                            <input
                                v-model="form.name"
                                type="text"
                                class="w-full rounded-lg border-slate-300 bg-white text-slate-900 focus:border-indigo-500 focus:ring-indigo-500 dark:border-white/10 dark:bg-[#131313] dark:text-slate-100"
                                placeholder="e.g., UI/UX Standardization"
                            />
                            <p v-if="form.errors.name" class="mt-1 text-xs text-red-500">{{ form.errors.name }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-300">Company Owner</label>
                            <input
                                v-model="form.owner_name"
                                type="text"
                                class="w-full rounded-lg border-slate-300 bg-white text-slate-900 focus:border-indigo-500 focus:ring-indigo-500 dark:border-white/10 dark:bg-[#131313] dark:text-slate-100"
                                placeholder="e.g., PT Example Indonesia"
                            />
                            <p v-if="form.errors.owner_name" class="mt-1 text-xs text-red-500">{{ form.errors.owner_name }}</p>
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-300">Status</label>
                            <select
                                v-model="form.status"
                                class="w-full rounded-lg border-slate-300 bg-white text-slate-700 focus:border-indigo-500 focus:ring-indigo-500 dark:border-white/10 dark:bg-[#131313] dark:text-slate-200"
                            >
                                <option v-for="statusOption in statusOptions" :key="statusOption.id" :value="statusOption.id">
                                    {{ statusOption.label }}
                                </option>
                            </select>
                            <p v-if="form.errors.status" class="mt-1 text-xs text-red-500">{{ form.errors.status }}</p>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 border-t border-slate-100 pt-4 dark:border-white/5">
                        <Link href="/it-initiatives" class="rounded-lg px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-white/5">
                            Cancel
                        </Link>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700 disabled:opacity-50"
                        >
                            Save IT Initiative
                        </button>
                    </div>
                </form>
            </div>

            <div class="mt-8 rounded-xl border border-slate-200 bg-white p-6 shadow-sm dark:border-white/5 dark:bg-[#1a1a1a]">
                <div class="mb-4 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Status Implementation History</h3>
                </div>
                
                <form @submit.prevent="submitHistory" class="mb-6 flex gap-3">
                    <input
                        v-model="historyForm.status"
                        type="text"
                        class="flex-1 rounded-lg border-slate-300 bg-white text-sm text-slate-900 focus:border-indigo-500 focus:ring-indigo-500 dark:border-white/10 dark:bg-[#131313] dark:text-slate-100"
                        placeholder="Enter new status (e.g. In Progress, Completed)"
                        required
                    />
                    <input
                        v-model="historyForm.month_year"
                        type="month"
                        class="w-48 rounded-lg border-slate-300 bg-white text-sm text-slate-900 focus:border-indigo-500 focus:ring-indigo-500 dark:border-white/10 dark:bg-[#131313] dark:text-slate-100"
                        required
                    />
                    <button
                        type="submit"
                        :disabled="historyForm.processing"
                        class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700 disabled:opacity-50"
                    >
                        Add Status
                    </button>
                </form>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 text-sm dark:divide-white/10">
                        <thead class="bg-slate-50 dark:bg-white/5">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Month / Year</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Status</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                            <tr v-for="log in itInitiative.pc_status_implementations" :key="log.id">
                                <td class="whitespace-nowrap px-4 py-3 font-medium text-slate-700 dark:text-slate-300">{{ formatMonthYear(log.date) }}</td>
                                <td class="whitespace-nowrap px-4 py-3 font-medium text-slate-900 dark:text-white capitalize">{{ log.status }}</td>
                                <td class="whitespace-nowrap px-4 py-3 text-right">
                                    <button @click="deleteHistory(log.id)" class="text-red-500 hover:text-red-700 text-sm font-medium">Delete</button>
                                </td>
                            </tr>
                            <tr v-if="!itInitiative.pc_status_implementations || itInitiative.pc_status_implementations.length === 0">
                                <td colspan="3" class="px-4 py-6 text-center text-xs text-slate-500 dark:text-slate-400">
                                    No status history found.
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
import { Link, useForm, router } from '@inertiajs/vue3';
import UserLayout from '@/Layouts/UserLayout.vue';

const props = defineProps({
    itInitiative: {
        type: Object,
        required: true,
    },
    statusOptions: {
        type: Array,
        default: () => [],
    },
    defaultStatusId: {
        type: Number,
        default: 1,
    },
});

const statusOptions = props.statusOptions.length > 0
    ? props.statusOptions
    : [{ id: 1, label: 'Drafting' }];

const resolvedDefaultStatusId = statusOptions.some((statusOption) => statusOption.id === props.defaultStatusId)
    ? props.defaultStatusId
    : statusOptions[0].id;

const form = useForm({
    code: props.itInitiative.code ?? '',
    name: props.itInitiative.name ?? '',
    owner_name: props.itInitiative.owner_name ?? '',
    status: props.itInitiative.status ?? resolvedDefaultStatusId,
});

const submit = () => {
    form.put(`/it-initiatives/${props.itInitiative.id}`);
};

const historyForm = useForm({
    status: '',
    month_year: new Date().toISOString().slice(0, 7),
});

const submitHistory = () => {
    historyForm.post(`/it-initiatives/${props.itInitiative.id}/implementation-status`, {
        preserveScroll: true,
        onSuccess: () => {
            historyForm.reset('status');
            historyForm.month_year = new Date().toISOString().slice(0, 7);
        },
    });
};

const deleteHistory = (id) => {
    if (confirm('Are you sure you want to delete this status entry?')) {
        router.delete(`/implementation-status/${id}`, {
            preserveScroll: true,
        });
    }
};

const formatMonthYear = (dateStr) => {
    if (!dateStr) return '-';
    // dateStr format is "YYYY-MM-DD"
    const date = new Date(dateStr);
    return date.toLocaleDateString('en-US', { year: 'numeric', month: 'long' });
};
</script>
