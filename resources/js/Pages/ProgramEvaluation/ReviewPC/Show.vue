<template>
    <UserLayout title="Detail Review PC">
        <div class="animate-fade-in-up">
            <div class="mx-auto mb-3 flex w-full max-w-[1080px] flex-wrap items-center gap-2 print:hidden">
                <Link
                    href="/program-evalution"
                    class="inline-flex items-center rounded-lg border border-slate-300 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 transition hover:border-sky-300 hover:bg-sky-50 hover:text-sky-700 dark:border-white/20 dark:bg-[#1f1f1f] dark:text-slate-200 dark:hover:border-sky-500/60 dark:hover:bg-sky-900/30 dark:hover:text-sky-200"
                >
                    Kembali
                </Link>
                <button
                    type="button"
                    class="inline-flex items-center rounded-lg border border-slate-300 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 transition hover:border-sky-300 hover:bg-sky-50 hover:text-sky-700 dark:border-white/20 dark:bg-[#1f1f1f] dark:text-slate-200 dark:hover:border-sky-500/60 dark:hover:bg-sky-900/30 dark:hover:text-sky-200"
                    @click="printPage"
                >
                    Print
                </button>
            </div>
            <div class="mx-auto w-full max-w-[1080px] space-y-5 border border-slate-300 bg-[#e9e9e9] p-5 text-slate-900 shadow-sm dark:border-white/20 dark:bg-[#d7d7d7]">
                <header class="space-y-2">
                    <h1 class="text-2xl font-extrabold tracking-tight text-slate-900 dark:text-white">
                        {{ initiativeName }}
                    </h1>
                    <div class="mt-3 h-[2px] w-full bg-sky-400/70"></div>
                </header>

                <section class="space-y-0">
                    <div class="bg-[#1661ad] px-4 py-1.5 text-[11px] font-semibold text-white">
                        Kesimpulan / Hasil Review
                    </div>
                    <article class="border border-[#9ec6e7] bg-white px-5 py-4 dark:border-sky-600/40 dark:bg-[#171717]">
                        <h2 class="text-xl font-extrabold leading-tight text-slate-900 dark:text-white">
                            {{ review.kesimpulan || '-' }}
                        </h2>
                        <p class="mt-2 text-xs font-medium text-slate-700 dark:text-slate-200">
                            {{ review.detail_kesimpulan || '-' }}
                        </p>
                        <ul v-if="penjelasanItems.length" class="mt-3 list-disc space-y-2 pl-5 text-xs leading-relaxed text-slate-800 dark:text-slate-200">
                            <li v-for="(item, index) in penjelasanItems" :key="`penjelasan-${index}`">{{ item }}</li>
                        </ul>
                        <p v-else class="mt-3 whitespace-pre-line text-xs leading-relaxed text-slate-800 dark:text-slate-200">
                            {{ review.penjelasan || '-' }}
                        </p>
                    </article>
                </section>

                <section class="space-y-1">
                    <div class="bg-[#1661ad] px-4 py-1.5 text-[11px] font-semibold text-white">
                        Informasi Proyek
                    </div>
                    <div class="grid grid-cols-1 gap-2 lg:grid-cols-3">
                        <article class="border border-[#b7daf1] bg-white dark:border-sky-700/40 dark:bg-[#171717]">
                            <header class="bg-[#a8d0ed] px-4 py-2 text-base font-semibold text-slate-900 dark:bg-sky-900/40 dark:text-slate-100">
                                Why
                            </header>
                            <div class="px-4 py-4">
                                <ul v-if="whyItems.length" class="list-disc space-y-2 pl-5 text-xs leading-relaxed text-slate-800 dark:text-slate-200">
                                    <li v-for="(item, index) in whyItems" :key="`why-${index}`">{{ item }}</li>
                                </ul>
                                <p v-else class="whitespace-pre-line text-xs leading-relaxed text-slate-800 dark:text-slate-200">
                                    {{ review.why || '-' }}
                                </p>
                            </div>
                        </article>

                        <article class="border border-[#b7daf1] bg-white dark:border-sky-700/40 dark:bg-[#171717]">
                            <header class="bg-[#a8d0ed] px-4 py-2 text-base font-semibold text-slate-900 dark:bg-sky-900/40 dark:text-slate-100">
                                What
                            </header>
                            <div class="px-4 py-4">
                                <p class="whitespace-pre-line text-xs leading-relaxed text-slate-800 dark:text-slate-200">
                                    {{ review.what || '-' }}
                                </p>
                            </div>
                        </article>

                        <article class="border border-[#b7daf1] bg-white dark:border-sky-700/40 dark:bg-[#171717]">
                            <header class="bg-[#a8d0ed] px-4 py-2 text-base font-semibold text-slate-900 dark:bg-sky-900/40 dark:text-slate-100">
                                How
                            </header>
                            <div class="px-4 py-4">
                                <p v-if="howParsed.intro" class="mb-2 text-xs leading-relaxed text-slate-800 dark:text-slate-200">
                                    {{ howParsed.intro }}
                                </p>
                                <ol v-if="howParsed.steps.length" class="list-decimal space-y-2 pl-5 text-xs leading-relaxed text-slate-800 dark:text-slate-200">
                                    <li v-for="(item, index) in howParsed.steps" :key="`how-${index}`">{{ item }}</li>
                                </ol>
                                <p v-else class="whitespace-pre-line text-xs leading-relaxed text-slate-800 dark:text-slate-200">
                                    {{ review.how || '-' }}
                                </p>
                            </div>
                        </article>
                    </div>
                </section>

                <section class="space-y-4">
                    <div class="bg-[#1661ad] px-4 py-1.5 text-[11px] font-semibold text-white">
                        Perubahan Project Charter
                    </div>
                    <div class="grid grid-cols-1 gap-4 lg:grid-cols-3 lg:gap-0 lg:divide-x-2 lg:divide-[#1661ad]">
                        <article class="px-4 py-2">
                            <header class="mb-3 flex items-center gap-2">
                                <span class="inline-flex h-7 w-7 items-center justify-center rounded-full bg-[#1661ad] text-sm font-bold text-white">1</span>
                                <h3 class="text-lg font-extrabold text-slate-900 dark:text-white">Project Profile</h3>
                            </header>
                            <ul v-if="projectProfileItems.length" class="list-disc space-y-2 pl-5 text-xs leading-relaxed text-slate-800 dark:text-slate-200">
                                <li v-for="(item, index) in projectProfileItems" :key="`profile-${index}`">{{ item }}</li>
                            </ul>
                            <p v-else class="whitespace-pre-line text-xs leading-relaxed text-slate-800 dark:text-slate-200">
                                {{ review.project_profile || '-' }}
                            </p>
                        </article>

                        <article class="px-4 py-2">
                            <header class="mb-3 flex items-center gap-2">
                                <span class="inline-flex h-7 w-7 items-center justify-center rounded-full bg-[#1661ad] text-sm font-bold text-white">2</span>
                                <h3 class="text-lg font-extrabold text-slate-900 dark:text-white">Key Milestone</h3>
                            </header>
                            <p class="whitespace-pre-line text-xs leading-relaxed text-slate-800 dark:text-slate-200">
                                {{ review.key_milestone || '-' }}
                            </p>
                        </article>

                        <article class="px-4 py-2">
                            <header class="mb-3 flex items-center gap-2">
                                <span class="inline-flex h-7 w-7 items-center justify-center rounded-full bg-[#1661ad] text-sm font-bold text-white">3</span>
                                <h3 class="text-lg font-extrabold text-slate-900 dark:text-white">Risk &amp; Impact Value</h3>
                            </header>
                            <p class="whitespace-pre-line text-xs leading-relaxed text-slate-800 dark:text-slate-200">
                                {{ review.risk_impact || '-' }}
                            </p>
                        </article>
                    </div>
                </section>
            </div>
        </div>
    </UserLayout>
</template>

<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import UserLayout from '@/Layouts/UserLayout.vue';

const props = defineProps({
    trsReviewPC: {
        type: Object,
        required: true,
    },
});

const review = computed(() => ({
    ...props.trsReviewPC,
    detail_kesimpulan: props.trsReviewPC?.detail_kesimpulan ?? props.trsReviewPC?.detail_penjelasan ?? '',
}));

const initiativeName = computed(() => review.value?.initiative?.name ?? '-');

const normalizeText = (value) => String(value ?? '').replace(/\u200B/g, '').trim();

const splitToItems = (value) => {
    const text = normalizeText(value);
    if (!text) return [];

    let items = text
        .split(/\r?\n/)
        .map((line) => line.trim())
        .filter(Boolean);

    if (items.length === 1 && text.includes(' - ')) {
        items = text.split(/\s+-\s+/).map((line) => line.trim()).filter(Boolean);
    }

    return items.map((item) => item.replace(/^[-*]\s*/, '').trim()).filter(Boolean);
};

const parseHow = (value) => {
    const text = normalizeText(value);
    if (!text) {
        return { intro: '', steps: [] };
    }

    const stepMatches = Array.from(text.matchAll(/(?:^|[;\n])\s*\d+\.\s*([^;\n]+)/g));
    if (stepMatches.length) {
        const firstIndex = stepMatches[0].index ?? 0;
        const intro = text.slice(0, firstIndex).trim();
        const steps = stepMatches.map((match) => match[1].trim()).filter(Boolean);
        return { intro, steps };
    }

    return { intro: '', steps: splitToItems(text) };
};

const penjelasanItems = computed(() => splitToItems(review.value?.penjelasan));
const whyItems = computed(() => splitToItems(review.value?.why));
const projectProfileItems = computed(() => splitToItems(review.value?.project_profile));
const howParsed = computed(() => parseHow(review.value?.how));

const printPage = () => {
    window.print();
};
</script>
