<template>
    <UserLayout title="Roadmap">
        <div class="space-y-6 print:space-y-0">
            <section class="print:hidden rounded-2xl border border-slate-200 bg-white p-4 shadow-sm dark:border-white/10 dark:bg-[#171717]">
                <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
                    <div>
                        <h1 class="text-xl font-bold text-slate-900 dark:text-white">Roadmap {{ yearStart }}-{{ yearEnd }}</h1>
                        <p class="text-sm text-slate-500 dark:text-slate-400">
                            View seluruh project dari atas ke bawah.
                        </p>
                    </div>

                    <Link
                        href="/roadmap/edit"
                        class="inline-flex items-center rounded-lg bg-[#0B2A8A] px-3 py-2 text-xs font-semibold text-white transition hover:bg-[#102f95]"
                    >
                        Buka Proses Input / Edit
                    </Link>
                </div>
            </section>

            <section v-if="projects.length > 0" class="space-y-0">
                <ProjectRoadmap
                    v-for="(project, projectIndex) in projects"
                    :key="`roadmap-${project.id}`"
                    :project="project"
                    :form="{
                        objectives: project.charter?.objectives ?? '',
                        duration: project.charter?.duration ?? '',
                    }"
                    :sequence="projectIndex + 1"
                    :year-start="yearStart"
                    :year-end="yearEnd"
                />
            </section>

            <section
                v-else
                class="rounded-2xl border border-dashed border-slate-300 bg-white p-10 text-center dark:border-white/15 dark:bg-[#171717]"
            >
                <p class="text-sm font-medium text-slate-600 dark:text-slate-300">
                    Belum ada data project untuk ditampilkan.
                </p>
            </section>

        </div>
    </UserLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import UserLayout from '@/Layouts/UserLayout.vue';
import ProjectRoadmap from '@/Components/Roadmap/ProjectRoadmap.vue';

defineProps({
    projects: {
        type: Array,
        default: () => [],
    },
    yearStart: {
        type: Number,
        default: 2025,
    },
    yearEnd: {
        type: Number,
        default: 2029,
    },
});
</script>
