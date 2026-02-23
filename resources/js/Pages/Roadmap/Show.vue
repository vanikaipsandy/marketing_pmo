<template>
    <UserLayout title="Roadmap Details">
        <div class="space-y-5 print:space-y-0 max-w-7xl mx-auto">
            <section class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm dark:border-white/10 dark:bg-[#171717]">
                <div class="mb-4">
                    <div class="flex items-center justify-between mb-2">
                        <Link href="/roadmap" class="text-sm text-indigo-600 dark:text-indigo-400 font-medium hover:underline block">
                            &larr; Back to Roadmap List
                        </Link>
                        
                        <Link :href="`/roadmap/${roadmap.id}/edit`" class="text-sm bg-indigo-50 text-indigo-700 dark:bg-indigo-500/10 dark:text-indigo-400 px-3 py-1.5 rounded-lg font-medium hover:bg-indigo-100 dark:hover:bg-indigo-500/20 transition">
                            Edit
                        </Link>
                    </div>
                    <h1 class="text-xl font-bold text-slate-900 dark:text-white">Roadmap Details</h1>
                    <p class="text-sm text-slate-500 dark:text-slate-400">
                        Viewing specifics for this roadmap.
                    </p>
                </div>
                
                <div class="mt-6 border-t border-slate-200 dark:border-white/10 pt-4">
                    <!-- Roadmap details go here -->
                </div>
            </section>
        </div>
    </UserLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import UserLayout from '@/Layouts/UserLayout.vue';

const props = defineProps({
    roadmap: { type: Object, default: () => ({}) },
});
</script>
