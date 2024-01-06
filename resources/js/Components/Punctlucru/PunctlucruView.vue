<template>
<div @click.self="closeModal"   class="flex items-end justify-center h-screen">
    <div class="fixed inset-0 transition-opacity">
        <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
    </div>

    <div @keydown.esc="closeModal" tabindex="0" class="flex flex-col h-full p-2 overflow-hidden text-left align-top transition-all transform bg-white rounded-lg shadow-xl dark:bg-dark-eval-2" role="dialog" aria-modal="true" aria-labelledby="modal-headline">
        <div tabindex="0" class="flex flex-row flex-wrap items-end justify-start flex-initial text-sm font-normal text-gray-500 justify-items-start dark:text-gray-300">
            <div class="flex-auto p-2" v-if='props.message'>
                <span class="font-bold text-red-600"> {{props.message}} </span>
            </div>
            <ViewField :fvalue='props.punctlucru.PunctLucruID' :class="['font-bold']" />
            <ViewField :fvalue='props.punctlucru.Denumire' :class="['font-bold']" />
            <ViewField :fvalue='props.punctlucru.Pass' :class="['text-xs']" />
            <Button variant="secondary" type="button" class="justify-center w-32" :disabled="isProcessing" v-slot="{iconSizeClasses}"  @click="edit" size="sm">
                <PencilIcon aria-hidden="true" :class="iconSizeClasses" />
                <span class="text-xs">Modifică punct de lucru</span>
            </Button>
        </div>
    </div>
</div>
</template>

<script setup>

import Button from '@/Components/Button'


import {  PencilIcon, XIcon, PlusIcon} from '@heroicons/vue/outline'
import ViewField from './ViewField'
import { defineProps, defineEmits, ref, computed,
//reactive,
onMounted,
} from 'vue'

import {
//    handleScroll,
    isDark,
//    scrolling,
//    toggleDarkMode,
//    sidebarState,
} from '@/Composables'


const props = defineProps({
    punctlucru: Object,
    message: String,
})

const isProcessing = ref(false)


const emit = defineEmits(['close', 'edit'])

const closeModal = () => {
    emit('close')
}

onMounted(()=>{
    console.log('ClientView on Mounted message',  props.message)
        // Close modal with 'esc' key
    document.addEventListener("keydown", (e) => {
        if (e.keyCode == 27) {
 //           this.$emit('close');
            emit('close')
        }
    });
})

const edit = async () => {
    isProcessing.value = true

        emit('edit', )

    isProcessing.value = false
}

</script>
