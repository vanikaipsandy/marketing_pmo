<template>
    <UserLayout title="Program Definition IT Initiatives">
        <div class="animate-fade-in">
            <div class="mb-4">
                <h2 class="text-2xl font-bold text-slate-900 dark:text-white">
                    Program Definition IT Initiatives
                </h2>
            </div>

            <section class="mb-4 grid grid-cols-1 gap-4 lg:grid-cols-3">
                <SummaryCard :total="totalItInitiatives" @create="showCreateModal = true" />

                <div class="lg:col-span-2">
                    <TimelineFlow
                        :status-options="statusOptions"
                        :status-counts="statusCounts"
                    />
                </div>
            </section>

            <MasterInitiativeTable :items="masterItList" />
        </div>

        <CreateInitiativeModal
            :show="showCreateModal"
            :tipe-initiative="2"
            :coe-options="coeOptions"
            :organization-options="organizationOptions"
            @close="showCreateModal = false"
        />
    </UserLayout>
</template>

<script setup>
import { computed, ref } from 'vue';
import UserLayout from '@/Layouts/UserLayout.vue';
import SummaryCard from '@/Components/ITInitiative/SummaryCard.vue';
import TimelineFlow from '@/Components/ITInitiative/TimelineFlow.vue';
import MasterInitiativeTable from '@/Components/ITInitiative/MasterInitiativeTable.vue';
import CreateInitiativeModal from '@/Components/Initiative/CreateInitiativeModal.vue';

const props = defineProps({
    totalItInitiatives: {
        type: Number,
        default: 0,
    },
    statusOptions: {
        type: Array,
        default: () => [],
    },
    statusCounts: {
        type: Object,
        default: () => ({}),
    },
    masterItInitiatives: {
        type: Array,
        default: () => [],
    },
    coeOptions: {
        type: Array,
        default: () => [],
    },
    organizationOptions: {
        type: Array,
        default: () => [],
    },
});

const showCreateModal = ref(false);

const masterItList = computed(() => {
    return Array.isArray(props.masterItInitiatives) ? props.masterItInitiatives : [];
});
</script>
