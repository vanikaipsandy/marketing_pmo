<template>
    <UserLayout title="Program Definition Digital Initiatives">
        <div class="animate-fade-in">
            <div class="mb-4">
                <h2 class="text-2xl font-bold text-slate-900 dark:text-white">
                    Program Definition Digital Initiatives
                </h2>
            </div>

            <section class="mb-4 grid grid-cols-1 gap-4 lg:grid-cols-3">
                <SummaryCard :total="totalDigitalInitiatives" @create="showCreateModal = true" />

                <div class="lg:col-span-2">
                    <TimelineFlow
                        :status-counts="statusCounts"
                        :postpone-from-counts="postponeFromCounts"
                    />
                </div>
            </section>

            <MasterInitiativeTable
                :items="masterDigitalList"
                :initiative-items="initiativeItemsList"
            />
        </div>

        <CreateInitiativeModal
            :show="showCreateModal"
            :tipe-initiative="1"
            :coe-options="coeOptions"
            :organization-options="organizationOptions"
            @close="showCreateModal = false"
        />
    </UserLayout>
</template>

<script setup>
import { computed, ref } from 'vue';
import UserLayout from '@/Layouts/UserLayout.vue';
import SummaryCard from '@/Components/DigitalInitiative/SummaryCard.vue';
import TimelineFlow from '@/Components/DigitalInitiative/TimelineFlow.vue';
import MasterInitiativeTable from '@/Components/DigitalInitiative/MasterInitiativeTable.vue';
import CreateInitiativeModal from '@/Components/Initiative/CreateInitiativeModal.vue';

const props = defineProps({
    totalDigitalInitiatives: { type: Number, default: 0 },
    statusOptions:           { type: Array,  default: () => [] },
    statusCounts:            { type: Object, default: () => ({}) },
    postponeFromCounts:      { type: Object, default: () => ({}) },
    masterDigitalInitiatives:{ type: Array,  default: () => [] },
    initiativeItems:         { type: Array,  default: () => [] },
    coeOptions:              { type: Array,  default: () => [] },
    organizationOptions:     { type: Array,  default: () => [] },
});

const showCreateModal     = ref(false);
const masterDigitalList   = computed(() => Array.isArray(props.masterDigitalInitiatives) ? props.masterDigitalInitiatives : []);
const initiativeItemsList = computed(() => Array.isArray(props.initiativeItems) ? props.initiativeItems : []);
</script>
