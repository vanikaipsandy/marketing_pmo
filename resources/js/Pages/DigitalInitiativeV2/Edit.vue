<template>
    <UserLayout title="Edit Digital Initiative">
        <div class="mx-auto max-w-6xl animate-fade-in">
            <div class="mb-6 flex items-center justify-between">
                <Link href="/digital-initiatives" class="text-sm font-medium text-slate-600 hover:text-slate-900 dark:text-slate-400 dark:hover:text-white">
                    ← Back to Digital Initiatives
                </Link>
            </div>

            <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm dark:border-white/5 dark:bg-[#1a1a1a]">
                <div class="bg-[#0f63b5] px-6 py-4">
                    <h2 class="text-xl font-bold text-white">Edit Digital Initiative #{{ initiative.id }}</h2>
                    <p class="text-sm text-blue-100">Update initiative and related entities.</p>
                </div>

                <form class="space-y-6 p-6" @submit.prevent="submit">
                    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                        <div class="space-y-4">
                            <h3 class="text-sm font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Main Data</h3>

                            <div>
                                <label class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-300">Category Phase</label>
                                <select v-model="form.category_fase" class="w-full rounded-md border-slate-300 bg-slate-50 dark:border-white/10 dark:bg-[#131313] dark:text-slate-100">
                                    <option v-for="category in categoryOptions" :key="category.id" :value="category.id">
                                        {{ category.label }}
                                    </option>
                                </select>
                                <p v-if="form.errors.category_fase" class="mt-1 text-xs text-red-500">{{ form.errors.category_fase }}</p>
                            </div>

                            <div v-if="Number(form.category_fase) === 2">
                                <label class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-300">Reference Planning</label>
                                <select v-model="form.ref_id" class="w-full rounded-md border-slate-300 bg-slate-50 dark:border-white/10 dark:bg-[#131313] dark:text-slate-100">
                                    <option :value="null">No Reference</option>
                                    <option v-for="item in planningReferences" :key="item.id" :value="item.id">
                                        #{{ item.id }} - {{ item.useCase_description }}
                                    </option>
                                </select>
                                <p v-if="form.errors.ref_id" class="mt-1 text-xs text-red-500">{{ form.errors.ref_id }}</p>
                            </div>

                            <div>
                                <label class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-300">Use Case</label>
                                <select v-model="form.useCase_id" class="w-full rounded-md border-slate-300 bg-slate-50 dark:border-white/10 dark:bg-[#131313] dark:text-slate-100">
                                    <option :value="null">Select use case</option>
                                    <option v-for="useCase in options.useCases" :key="useCase.id" :value="useCase.id">
                                        {{ useCase.name }}
                                    </option>
                                </select>
                                <p v-if="form.errors.useCase_id" class="mt-1 text-xs text-red-500">{{ form.errors.useCase_id }}</p>
                            </div>

                            <div>
                                <label class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-300">Use Case Description</label>
                                <textarea v-model="form.useCase_description" rows="4" class="w-full rounded-md border-slate-300 bg-slate-50 dark:border-white/10 dark:bg-[#131313] dark:text-slate-100"></textarea>
                                <p v-if="form.errors.useCase_description" class="mt-1 text-xs text-red-500">{{ form.errors.useCase_description }}</p>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-300">Value</label>
                                    <select v-model="form.value" class="w-full rounded-md border-slate-300 bg-slate-50 dark:border-white/10 dark:bg-[#131313] dark:text-slate-100">
                                        <option v-for="item in scoreOptions" :key="item.id" :value="item.id">{{ item.label }}</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-300">Urgency</label>
                                    <select v-model="form.urgency" class="w-full rounded-md border-slate-300 bg-slate-50 dark:border-white/10 dark:bg-[#131313] dark:text-slate-100">
                                        <option v-for="item in scoreOptions" :key="`urgency-${item.id}`" :value="item.id">{{ item.label }}</option>
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-300">Data Source</label>
                                <select v-model="form.source_id" class="w-full rounded-md border-slate-300 bg-slate-50 dark:border-white/10 dark:bg-[#131313] dark:text-slate-100">
                                    <option :value="null">Select source</option>
                                    <option v-for="source in options.sources" :key="source.id" :value="source.id">
                                        {{ source.name }}
                                    </option>
                                </select>
                                <p v-if="form.errors.source_id" class="mt-1 text-xs text-red-500">{{ form.errors.source_id }}</p>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <h3 class="text-sm font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Relations</h3>

                            <MultiCheckbox
                                v-model="form.organization_ids"
                                label="Organizations"
                                :items="options.organizations"
                                hint="Assign 1 or more organizations."
                            />

                            <MultiCheckbox
                                v-model="form.rjpp_ids"
                                label="RJPP Themes"
                                :items="options.rjpps"
                                hint="Select related strategic themes."
                            />

                            <MultiCheckbox
                                v-model="form.status_ids"
                                label="Statuses"
                                :items="statusItems"
                                hint="Multiple statuses are supported."
                            />
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 border-t border-slate-100 pt-6 dark:border-white/5">
                        <Link href="/digital-initiatives" class="rounded-lg px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-white/5">
                            Cancel
                        </Link>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="rounded-lg bg-[#0f63b5] px-5 py-2 text-sm font-semibold text-white transition hover:bg-[#0c4e8f] disabled:opacity-50"
                        >
                            Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </UserLayout>
</template>

<script setup>
import { computed } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';
import UserLayout from '@/Layouts/UserLayout.vue';
import MultiCheckbox from './Partials/MultiCheckbox.vue';

const props = defineProps({
    initiative: {
        type: Object,
        required: true,
    },
    options: {
        type: Object,
        required: true,
    },
    categoryOptions: {
        type: Array,
        default: () => [],
    },
    planningReferences: {
        type: Array,
        default: () => [],
    },
});

const scoreOptions = [
    { id: 1, label: 'Low' },
    { id: 2, label: 'Medium' },
    { id: 3, label: 'High' },
    { id: 4, label: 'TBC' },
];

const statusItems = computed(() => {
    return (props.options.statuses || []).map((status) => ({
        id: status.id,
        name: status.phase ? `${status.name} (${status.phase.name})` : status.name,
    }));
});

const form = useForm({
    category_fase: props.initiative.category_fase ?? 1,
    ref_id: props.initiative.ref_id ?? null,
    useCase_id: props.initiative.useCase_id ?? null,
    useCase_description: props.initiative.useCase_description ?? '',
    value: props.initiative.value ?? 4,
    urgency: props.initiative.urgency ?? 4,
    source_id: props.initiative.source_id ?? null,
    organization_ids: props.initiative.organization_ids ?? [],
    rjpp_ids: props.initiative.rjpp_ids ?? [],
    status_ids: props.initiative.status_ids ?? [],
});

const submit = () => {
    form.put(`/digital-initiatives/${props.initiative.id}`);
};
</script>
