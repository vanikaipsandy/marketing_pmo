<template>
    <UserLayout title="Program Evaluation">
        <div class="space-y-6 animate-fade-in-up">
            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-[#171717]">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div>
                        <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Review Project Charter</h1>
                    </div>
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="inline-flex items-center rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-200">
                            Digital Initiative: {{ summaryCounts.digital }}
                        </span>
                        <span class="inline-flex items-center rounded-full bg-indigo-100 px-3 py-1 text-xs font-semibold text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-200">
                            IT Initiative: {{ summaryCounts.it }}
                        </span>
                        <span class="inline-flex items-center rounded-full bg-sky-100 px-3 py-1 text-xs font-semibold text-sky-700 dark:bg-sky-900/30 dark:text-sky-200">
                            Total: {{ filteredCount }}
                        </span>
                    </div>
                </div>
            </section>

            <section>
                <ReviewPCTable
                    :reviews="trsReviewPCs"
                    @count-change="onCountChange"
                />
            </section>
        </div>
    </UserLayout>
</template>

<script setup>
import { computed, ref } from 'vue';
import ReviewPCTable from '@/Components/ReviewPC/ReviewPCTable.vue';
import UserLayout from '@/Layouts/UserLayout.vue';

const props = defineProps({
    trsReviewPCs: {
        type: Array,
        default: () => [],
    },
});

const filteredCount = ref(props.trsReviewPCs.length);

const onCountChange = (count) => {
    filteredCount.value = Number(count);
};

const summaryCounts = computed(() => {
    return props.trsReviewPCs.reduce(
        (acc, review) => {
            const type = Number(review?.initiative?.tipe_initiative);
            if (type === 1) acc.digital += 1;
            if (type === 2) acc.it += 1;
            return acc;
        },
        { digital: 0, it: 0 },
    );
});

</script>
