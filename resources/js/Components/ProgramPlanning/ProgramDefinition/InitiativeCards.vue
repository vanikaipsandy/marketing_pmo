<template>
    <section class="grid grid-cols-1 gap-4 md:grid-cols-2">
        <article
            role="button"
            tabindex="0"
            class="rounded-2xl border border-[#1C75BC]/30 bg-white p-5 shadow-[0_4px_16px_rgba(28,117,188,0.08)] transition hover:border-[#1C75BC]/60 dark:border-[#1C75BC]/30 dark:bg-[#171717]"
            @click="$emit('show-digital')"
            @keydown.enter.prevent="$emit('show-digital')"
            @keydown.space.prevent="$emit('show-digital')"
        >
            <p class="text-[10px] font-semibold uppercase tracking-[0.08em] text-slate-500 dark:text-slate-400">
                Mst Initiative Tipe 1
            </p>
            <p class="mt-2 text-3xl font-bold text-[#1C75BC] dark:text-[#7FC0F2]">
                {{ digitalTotal }}
            </p>
            <div class="mt-4">
                <Link
                    href="/digital-initiatives/create"
                    class="inline-flex items-center rounded-lg bg-[#1C75BC] px-3 py-2 text-xs font-semibold uppercase tracking-[0.06em] text-white transition hover:bg-[#0f63b5]"
                >
                    New Digital Initiatives
                </Link>
            </div>
        </article>

        <article
            role="button"
            tabindex="0"
            class="rounded-2xl border border-[#A7C942]/40 bg-white p-5 shadow-[0_4px_16px_rgba(167,201,66,0.12)] transition hover:border-[#A7C942] dark:border-[#A7C942]/30 dark:bg-[#171717]"
            @click="$emit('show-it')"
            @keydown.enter.prevent="$emit('show-it')"
            @keydown.space.prevent="$emit('show-it')"
        >
            <p class="text-[10px] font-semibold uppercase tracking-[0.08em] text-slate-500 dark:text-slate-400">
                Mst Initiative Tipe 2
            </p>
            <p class="mt-2 text-3xl font-bold text-[#4F6B0F] dark:text-[#C7E67A]">
                {{ itTotal }}
            </p>
            <div class="mt-4">
                <Link
                    href="/it-initiatives/create"
                    class="inline-flex items-center rounded-lg bg-[#A7C942] px-3 py-2 text-xs font-semibold uppercase tracking-[0.06em] text-white transition hover:bg-[#8fb237]"
                >
                    New IT Initiatives
                </Link>
            </div>
        </article>
    </section>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    digitalTotal: {
        type: Number,
        default: 0,
    },
    itTotal: {
        type: Number,
        default: 0,
    },
});

defineEmits(['show-digital', 'show-it']);
</script>

