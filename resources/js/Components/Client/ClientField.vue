<template>
    <div class="flex-auto pr-4">
        <div class="flex items-end pb-0.5">
            <Label class="flex-none" :for="props.name" :value="props.label" />
        </div>
        <InputIconWrapper>
            <template v-if="!$slots.icon" #icon>
                <PencilAltIcon aria-hidden="true" class="w-5 h-5" />
            </template>
            <template v-else #icon>
                 <slot name="icon" />
            </template>
            <Field
                :name="props.name" :id="props.name" type="text" :placeholder="props.label" :rules="props.rules"
                :class="[
                    'block w-full',
                    'py-2 border-gray-400 rounded-md',
                    'focus:border-gray-400 focus:ring focus:ring-purple-500 focus:ring-offset-2 focus:ring-offset-white',
                    'dark:border-gray-600 dark:bg-dark-eval-1 dark:text-gray-300 dark:focus:ring-offset-dark-eval-1',
                    ' placeholder:italic placeholder:text-slate-400 placeholder:text-xs',
                    'pl-11 pr-4',
                ]"
                v-model="modelValue"
                @input="$emit('update:modelValue', $event.target.value)"
                ref="input"
            />
        </InputIconWrapper>
        <ErrorMessage class="text-sm text-red-700" :name="props.name" />
    </div>
</template>

<script setup>
import { defineProps } from 'vue'

import Label from '@/Components/Label'
import InputIconWrapper from '@/Components/InputIconWrapper'
import { PencilAltIcon, } from '@heroicons/vue/outline'
import { Field, ErrorMessage } from 'vee-validate';

const props = defineProps({
    modelValue: {String, Number},
    name: String,
    label: String,
    rules: Function,
})


</script>
