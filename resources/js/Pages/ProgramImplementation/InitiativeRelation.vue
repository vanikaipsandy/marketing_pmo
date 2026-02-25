<template>
    <UserLayout title="Initiative Relation">
        <div class="space-y-6 animate-fade-in-up">
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Initiative Relation</h1>

            <InitiativeRelationTable />
        </div>
    </UserLayout>
</template>

<script setup>
import InitiativeRelationTable from '@/Components/InitiativeRelationTable.vue';
import UserLayout from '@/Layouts/UserLayout.vue';
</script>
