<script setup>
import { ClockIcon, DocumentTextIcon, Squares2X2Icon } from '@heroicons/vue/24/outline';

const props = defineProps({
    initiative: { type: Object, required: true },
    categoryLabel: { type: Function, required: true },
});

const lineItems = (value) =>
    String(value || '')
        .split(/\r?\n/)
        .map((line) => line.trim())
        .filter(Boolean);

const backgroundLines = () => {
    const desc = props.initiative.useCase_description;
    if (desc) return lineItems(desc);
    const ref = props.initiative.planning_or_implementation?.useCase_description;
    if (ref) return lineItems(ref);
    return [];
};

const companyOwner = () => {
    const orgs = props.initiative.organizations || [];
    return orgs.length ? orgs.map((o) => o.name).join(', ') : '-';
};

const durationLabel = () => {
    const src = props.initiative.source;
    if (!src) return '-';
    const year = src.year;
    const month = src.month;
    if (!year) return '-';
    if (month) {
        const m = Number(month);
        const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        return `${months[m - 1] || ''} ${year}`.trim();
    }
    return String(year);
};
</script>

<template>
    <article
        class="charter-sheet mx-auto w-full max-w-[1200px] border border-slate-300 bg-[#e9e9e9] p-5 text-slate-900 shadow-sm dark:border-white/20 dark:bg-[#d7d7d7]">
        <header class="charter-block border-b-2 border-[#2e6ea2] pb-3">
            <h1 class="text-2xl font-extrabold leading-tight tracking-tight text-[#0f63b5]">
                Digital Initiative Charter: {{ initiative.use_case?.name || `#${initiative.id}` }}
            </h1>
        </header>

        <section class="charter-block mt-4 grid gap-5 lg:grid-cols-3">
            <div class="meta-item">
                <span class="meta-icon">
                    <Squares2X2Icon class="h-5 w-5" />
                </span>
                <div class="meta-label">Category</div>
                <div class="meta-value">
                    <span class="font-semibold">{{ categoryLabel(initiative.category_fase) || '-' }}</span>
                </div>
            </div>

            <div class="meta-item">
                <span class="meta-icon">
                    <ClockIcon class="h-5 w-5" />
                </span>
                <div class="meta-label">CoE</div>
                <div class="meta-value font-semibold">
                    {{ initiative.use_case.coe.name }}
                </div>
            </div>

            <div class="meta-item">
                <span class="meta-icon">
                    <DocumentTextIcon class="h-5 w-5" />
                </span>
                <div class="meta-label">Source</div>
                <div class="meta-value font-semibold">
                    {{ initiative.source.name }}
                </div>
            </div>
        </section>

        <section class="charter-block mt-4">
            <div class="bar-main">Digital Initiative Information</div>

            <div class="mt-2 grid gap-3 lg:grid-cols-2">
                <article class="panel">
                    <div class="bar-sub">Use Case Description</div>
                    <div class="panel-body">
                        <p>{{ initiative.useCase_description }}</p>
                    </div>
                </article>

                    <div class="mt-2 grid gap-2 md:grid-cols-3">
                        <div class="panel">
                            <div class="bar-sub !py-1 !px-2 !text-[13px]">Value and Urgency</div>
                            <div class="panel-body min-h-[40px] !p-1.5 text-sm leading-snug">
                                <p><strong>Value:</strong> {{ initiative.value != null ? (initiative.value === 4 ? 'TBC'
                                    : ['', 'Low', 'Medium', 'High'][initiative.value]) : '-' }}</p>
                                <p><strong>Urgency:</strong> {{ initiative.urgency != null ? (initiative.urgency === 4 ?
                                    'TBC' : ['', 'Low', 'Medium', 'High'][initiative.urgency]) : '-' }}</p>
                            </div>
                        </div>

                        <div class="panel">
                            <div class="bar-sub !py-1 !px-2 !text-[13px]">Organizations</div>
                            <div class="panel-body min-h-[40px] !p-1.5">
                                <ul v-if="(initiative.organizations || []).length" class="bullet-list">
                                    <li v-for="org in initiative.organizations" :key="org.id">{{ org.name }}</li>
                                </ul>
                                <p v-else class="empty">-</p>
                            </div>
                        </div>

                        <div class="panel">
                            <div class="bar-sub !py-1 !px-2 !text-[13px]">RJPP Themes</div>
                            <div class="panel-body min-h-[40px] !p-1.5">
                                <ul v-if="(initiative.rjpps || []).length" class="bullet-list">
                                    <li v-for="rjpp in initiative.rjpps" :key="rjpp.id">{{ rjpp.name }}</li>
                                </ul>
                                <p v-else class="empty">-</p>
                            </div>
                        </div>
                    </div>
            </div>
        </section>
    </article>
</template>

<style scoped>
.charter-sheet {
    font-family: "Segoe UI", Inter, Arial, sans-serif;
    border-radius: 0;
}

.charter-block {
    break-inside: avoid;
    page-break-inside: avoid;
}

.meta-item {
    display: flex;
    align-items: center;
}

.meta-icon {
    display: inline-flex;
    height: 48px;
    width: 48px;
    align-items: center;
    justify-content: center;
    border-radius: 9999px;
    background: #0f63b5;
    color: #fff;
    border: 3px solid #fff;
    box-shadow: 0 0 0 1px #e2e8f0;
    z-index: 10;
    flex-shrink: 0;
}

.meta-label {
    height: 32px;
    display: flex;
    align-items: center;
    padding: 0 12px 0 24px;
    margin-left: -18px;
    background: #0f63b5;
    color: #fff;
    font-size: 15px;
    font-weight: 700;
    border-radius: 0 4px 4px 0;
    z-index: 1;
}

.meta-value {
    flex: 1;
    margin-left: 12px;
    font-size: 15px;
    line-height: 1.25;
    font-weight: 600;
}

.bar-main {
    background: #0f63b5;
    color: #fff;
    padding: 8px 12px;
    font-size: 18px;
    font-weight: 700;
    line-height: 1.2;
}

.bar-sub {
    background: #3f6f9f;
    color: #fff;
    padding: 6px 12px;
    font-size: 15px;
    font-weight: 700;
    line-height: 1.2;
}

.panel {
    border: 2px solid #2e6ea2;
    border-radius: 0;
    background: transparent;
}

.panel-body {
    padding: 10px;
    background: #e9e9e9;
}

.bullet-list {
    margin: 0;
    padding-left: 0;
    list-style: none;
    font-size: 14px;
    line-height: 1.4;
}

.bullet-list li {
    position: relative;
    padding-left: 1rem;
}

.bullet-list li::before {
    content: "•";
    position: absolute;
    left: 0;
    font-weight: bold;
    color: #0f63b5;
}

.bullet-list li+li {
    margin-top: 2px;
}

.empty {
    color: #6b7280;
    font-size: 14px;
}

@media print {
    @page {
        size: A4 landscape;
        margin: 8mm;
    }

    .charter-sheet {
        width: 100%;
        max-width: none;
        margin: 0;
        padding: 0;
        border: 0;
        background: #ffffff !important;
        box-shadow: none;
    }

    .panel-body {
        background: #ffffff !important;
    }
}
</style>
