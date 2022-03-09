<template>
    <select
        :class="[
            'py-2 border-gray-400 rounded-md',
            'focus:border-gray-400 focus:ring focus:ring-purple-500 focus:ring-offset-2 focus:ring-offset-white',
            'dark:border-gray-600 dark:bg-dark-eval-1 dark:text-gray-300 dark:focus:ring-offset-dark-eval-1',
            {
                'px-4': !withIcon,
                'pl-11 pr-4': withIcon,
            },
        ]"
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
        ref="input"    >
        <option v-for="item in props.items" :key="item.id" :selected= "item.id == modelValue" :value="item.id">
            {{item.Denumire}}
        </option>
    </select>
</template>

<script setup>
import { ref, onMounted } from 'vue'

const props = defineProps({
    modelValue: String,
    items: Object,
    withIcon: {
        type: Boolean,
        default: false,
    }
})

const input = ref(null)

onMounted(()=>{
  console.log('Select onMounted props.items', props.items)
//  console.log('Select onMounted props.items[0]', props.items[0])
})

const emit = defineEmits(['update:modelValue'])

// const focus = () => {
//     console.log('select:focus:modelValue.value: ', modelValue.value)
//     select.value.focus()
// }
</script>
