<template>
    <div>
        <label class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-300">{{ label }}</label>
        <p v-if="hint" class="mb-2 text-xs text-slate-500 dark:text-slate-400">{{ hint }}</p>

        <div class="max-h-48 overflow-y-auto rounded-lg border border-slate-200 bg-slate-50 p-2 dark:border-white/10 dark:bg-[#131313]">
            <label
                v-for="item in items"
                :key="item.id"
                class="flex cursor-pointer items-center gap-2 rounded-md px-2 py-1.5 text-sm text-slate-700 transition hover:bg-slate-100 dark:text-slate-200 dark:hover:bg-white/5"
            >
                <input
                    :checked="modelValue.includes(item.id)"
                    type="checkbox"
                    class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 dark:border-white/20"
                    @change="toggle(item.id, $event.target.checked)"
                />
                <span>{{ item.name }}</span>
            </label>

            <p v-if="items.length === 0" class="px-2 py-3 text-xs text-slate-500 dark:text-slate-400">No data available.</p>
        </div>
    </div>
</template>

<script setup>
const props = defineProps({
    modelValue: {
        type: Array,
        default: () => [],
    },
    items: {
        type: Array,
        default: () => [],
    },
    label: {
        type: String,
        required: true,
    },
    hint: {
        type: String,
        default: '',
    },
});

const emit = defineEmits(['update:modelValue']);

const toggle = (value, checked) => {
    const next = [...props.modelValue];
    const index = next.indexOf(value);

    if (checked && index === -1) {
        next.push(value);
    }

    if (!checked && index !== -1) {
        next.splice(index, 1);
    }

    emit('update:modelValue', next);
};
</script>
