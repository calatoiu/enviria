<template>
    <div class="flex-auto pr-4">
       <div class="flex items-end pb-0.5">
            <Label class="flex-none" :for="props.name" :value="props.label" />
        </div>
        <div
            class="relative text-gray-500 focus-within:text-gray-900 dark:focus-within:text-gray-400"
        >
            <div
                aria-hidden="true"
                class="absolute inset-y-0 flex items-center px-4 pointer-events-none "
            >
                <slot name="icon" />
            </div>
            <slot />
        </div>
        <ErrorMessage v-if="!novalidate" class="text-sm text-red-700" :name="props.name" />
    </div>
</template>

<script setup>
import { defineProps } from 'vue'

import Label from '@/Components/Label'
import { ErrorMessage } from 'vee-validate';

const props = defineProps({
    name: String,
    label: String,
    novalidate: Boolean
})


</script>

