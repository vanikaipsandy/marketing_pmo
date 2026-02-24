<template>
    <UserLayout title="Create Roadmap">
        <div class="space-y-5 print:space-y-0 max-w-7xl mx-auto">
            <section class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm dark:border-white/10 dark:bg-[#171717]">
                <div class="mb-4">
                    <Link href="/roadmap" class="text-sm text-indigo-600 dark:text-indigo-400 font-medium hover:underline mb-2 block">
                        &larr; Back to Roadmap List
                    </Link>
                    <h1 class="text-xl font-bold text-slate-900 dark:text-white">Create New Roadmap</h1>
                    <p class="text-sm text-slate-500 dark:text-slate-400">
                        Add a new roadmap entry.
                    </p>
                </div>
                
                <form @submit.prevent="submit" class="space-y-4 max-w-2xl">
                    <!-- Form fields go here -->
                </form>
            </section>
        </div>
    </UserLayout>
</template>

<script setup>
import { useForm, Link } from '@inertiajs/vue3';
import UserLayout from '@/Layouts/UserLayout.vue';

const form = useForm({
    // Add fields
});

const submit = () => {
    // form.post('/roadmap');
};
</script>
