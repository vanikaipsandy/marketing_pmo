<template>
    <UserLayout title="Create Roadmap">
        <div class="space-y-5 print:space-y-0">
            <section class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm dark:border-white/10 dark:bg-[#171717]">
                <div class="mb-4 flex flex-col gap-3 lg:flex-row lg:items-end lg:justify-between">
                    <div>
                        <Link href="/roadmap" class="mb-2 inline-flex text-sm font-medium text-[#0B2A8A] hover:underline dark:text-[#53BDE6]">
                            Kembali
                        </Link>
                        <h1 class="text-xl font-bold text-slate-900 dark:text-white">Create Roadmap</h1>
                    </div>

                    <Link
                        href="/roadmap"
                        class="inline-flex items-center rounded-lg border border-slate-300 bg-white px-3 py-2 text-xs font-semibold text-slate-700 transition hover:bg-slate-50 dark:border-white/10 dark:bg-transparent dark:text-slate-200 dark:hover:bg-white/5"
                    >
                        Lihat Roadmap
                    </Link>
                </div>

                <div class="grid max-w-4xl gap-3 md:grid-cols-3">
                    <div class="md:col-span-2">
                        <label class="mb-1 block text-xs font-semibold uppercase tracking-[0.08em] text-slate-500 dark:text-slate-400">
                            Pilih Project
                        </label>
                        <select
                            v-model="selectedProjectIdLocal"
                            class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-700 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 dark:border-white/10 dark:bg-[#101826] dark:text-slate-100"
                            @change="handleProjectChange"
                        >
                            <option :value="null">-- Pilih Project --</option>
                            <option v-for="proj in projects" :key="proj.id" :value="proj.id">
                                {{ proj.code ? `${proj.code} - ${proj.name}` : proj.name }}
                            </option>
                        </select>
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-semibold uppercase tracking-[0.08em] text-slate-500 dark:text-slate-400">
                            Versi Roadmap
                        </label>
                        <select
                            v-model="selectedRoadmapVersionIdLocal"
                            class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-700 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 disabled:bg-slate-100 disabled:text-slate-400 dark:border-white/10 dark:bg-[#101826] dark:text-slate-100 dark:disabled:bg-[#0f1623]"
                            :disabled="!activeProject"
                            @change="handleVersionChange"
                        >
                            <option :value="null">-- Pilih Versi --</option>
                            <option
                                v-for="version in activeRoadmapVersions"
                                :key="`roadmap-version-${version.id}`"
                                :value="version.id"
                            >
                                {{ version.version_label }}
                            </option>
                        </select>
                    </div>
                </div>
                <div class="mt-3">
                    <button
                        type="button"
                        :disabled="!activeProject"
                        class="inline-flex items-center rounded-lg bg-[#0B2A8A] px-3 py-2 text-xs font-semibold text-white transition hover:bg-[#102f95] disabled:cursor-not-allowed disabled:opacity-60"
                        @click="createRoadmapVersion"
                    >
                        Buat Versi Baru
                    </button>
                </div>
            </section>

            <main v-if="activeProject" class="space-y-5">
                <ActivityQuarterManager
                    :project="activeProject"
                    :selected-roadmap-version-id="selectedRoadmapVersionIdLocal"
                    :milestone-type-options="milestoneTypeOptionsDisplay"
                />
            </main>

            <section
                v-else
                class="rounded-2xl border border-dashed border-slate-300 bg-white p-10 text-center dark:border-white/15 dark:bg-[#171717]"
            >
                <p class="text-sm font-medium text-slate-600 dark:text-slate-300">
                    <template v-if="projects.length">
                        Pilih project di atas untuk mulai tambah activity roadmap.
                    </template>
                    <template v-else>
                        Belum ada data project untuk proses roadmap.
                    </template>
                </p>
            </section>
        </div>
    </UserLayout>
</template>

<script setup>
import { computed, ref, watch } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import UserLayout from '@/Layouts/UserLayout.vue';
import ActivityQuarterManager from '@/Components/Roadmap/ActivityQuarterManager.vue';

const props = defineProps({
    projects: { type: Array, default: () => [] },
    selectedProject: { type: Object, default: null },
    selectedProjectId: { type: Number, default: null },
    selectedRoadmapVersion: { type: String, default: null },
});

const selectedProjectIdLocal = ref(props.selectedProjectId ?? null);
const selectedRoadmapVersionIdLocal = ref(props.selectedRoadmapVersion ?? null);

const milestoneTypeOptionsDisplay = [
    { value: 1, label: 'Blok', timeline_style: 'block' },
    { value: 2, label: 'Garis', timeline_style: 'dashed' },
];

watch(() => props.selectedProjectId, (nextProjectId) => {
    selectedProjectIdLocal.value = nextProjectId ?? null;
}, { immediate: true });

watch(() => props.selectedRoadmapVersion, (nextVersionId) => {
    selectedRoadmapVersionIdLocal.value = nextVersionId ?? null;
}, { immediate: true });

const handleProjectChange = () => {
    router.get('/roadmap/add', {
        project_id: selectedProjectIdLocal.value || undefined,
    }, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
    });
};

const handleVersionChange = () => {
    if (!selectedProjectIdLocal.value) {
        return;
    }

    router.get('/roadmap/add', {
        project_id: selectedProjectIdLocal.value,
        version: selectedRoadmapVersionIdLocal.value || undefined,
    }, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
    });
};

const activeProject = computed(() =>
    props.projects.find((project) => project.id === selectedProjectIdLocal.value) ?? null
);

const activeRoadmapVersions = computed(() =>
    Array.isArray(activeProject.value?.roadmap_versions) ? activeProject.value.roadmap_versions : []
);

const createRoadmapVersion = () => {
    if (!selectedProjectIdLocal.value) {
        return;
    }

    router.post(`/it-initiatives/${selectedProjectIdLocal.value}/milestones/versions`, {
        redirect_to: 'add',
    }, {
        preserveScroll: true,
    });
};
</script>
