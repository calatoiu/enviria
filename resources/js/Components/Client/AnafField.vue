<template>
    <div class="flex-auto pr-4">
        <div class="flex items-end pb-0.5">
            <Label class="flex-none" :for="props.name" :value="props.label" />
            <div class="flex-initial pl-3" >
                <fieldset v-if="modelValue != avalue" class="flex border border-gray-200 border-solid rounded dark:border-gray-600">
                    <legend class="text-xs font-thin text-gray-700 dark:text-gray-300">
                        <div class="px-0.5 italic" > ANAF </div>
                    </legend>
                    <div class="px-2 text-xs font-normal text-gray-500 dark:text-gray-300"> {{props.avalue}} </div>
                    <Button
                        iconOnly variant="secondary" size="xs" type="button"
                        @click="copyField"
                        v-slot="{ iconSizeClasses }" :disabled="modelValue == avalue"
                        >
                        <ChevronDoubleDownIcon
                            aria-hidden="true"
                            :class="iconSizeClasses"
                        />
                    </Button>
                </fieldset>
            </div>
        </div>
        <InputIconWrapper>
            <template #icon>
                <PencilAltIcon aria-hidden="true" class="w-5 h-5" />
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
import { defineProps, defineEmits } from 'vue'

import Button from '@/Components/Button'
import Label from '@/Components/Label'
import InputIconWrapper from '@/Components/InputIconWrapper'
import { PencilAltIcon,  ChevronDoubleDownIcon} from '@heroicons/vue/outline'
import { Field, ErrorMessage } from 'vee-validate';

const emit = defineEmits(['update:modelValue'])
const props = defineProps({
    avalue: {String, Number},
    modelValue: {String, Number},
    name: String,
    label: String,
    rules: Function,
})

const copyField = () => {
    emit('update:modelValue', props.avalue)
}
</script>
