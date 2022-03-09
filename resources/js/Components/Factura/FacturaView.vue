<template>
<div @click.self="closeModal" class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
    <div class="fixed inset-0 transition-opacity">
        <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
    </div>
<!-- This element is to trick the browser into centering the modal contents. -->
    <!-- <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span> -->
    <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>
    ​
    <div
    class="
        inline-block
        overflow-hidden
        text-left
        align-bottom
        transition-all
        transform
        bg-white
        dark:bg-dark-eval-1
        rounded-lg
        shadow-xl
        sm:my-8
        sm:align-middle
        sm:max-w-[70%]
        sm:w-full"
    role="dialog"
    aria-modal="true"
    aria-labelledby="modal-headline">
        <div class="justify-center p-4 max-h-[600px] overflow-auto text-gray-500 dark:text-gray-300" ref="facturaContainer">
           <span v-html="props.facturahtml"></span>
        </div>
        <div class="flex px-6 py-3 bg-gray-50 dark:bg-dark-eval-1 gap-y-3.5 flex-row flex-wrap items-end justify-between">
            <Button variant="primary" type="button" class="justify-center w-32 gap-2" :disabled="isProcessing" v-slot="{iconSizeClasses}"  @click="downloadPDF">
                <DocumentIcon aria-hidden="true" :class="iconSizeClasses" />
                <span>PDF</span>
            </Button>
            <Button variant="secondary" type="button" class="justify-center w-32 gap-2" :disabled="isProcessing" v-slot="{iconSizeClasses}"  @click="edit">
                    <PencilIcon aria-hidden="true" :class="iconSizeClasses" />
                    <span>Editare</span>
            </Button>
            <Button variant="secondary" type="button" class="justify-center w-32 gap-2" :disabled="isProcessing" v-slot="{iconSizeClasses}"  @click="closeModal">
                <XIcon aria-hidden="true" :class="iconSizeClasses" />
                <span>Anulare</span>
            </Button>
        </div>
    </div>
</div>
</template>

<script setup>

import Button from '@/Components/Button'
import {DocumentIcon,  PencilIcon, XIcon} from '@heroicons/vue/outline'

import { defineProps, defineEmits, ref, computed , onMounted
//reactive,
} from 'vue'

import { saveAs } from 'file-saver'

const props = defineProps({
    facturahtml: String,
    SerieNumar: String
})

const isProcessing = ref(false)

const emit = defineEmits(['close', 'edit'])

const closeModal = () => {
    emit('close')
}

const edit = async () => {
    isProcessing.value = true

        emit('edit')

    isProcessing.value = false
}

const downloadPDF = async () => {
    isProcessing.value = true
    axios({
        url: '/api/facturapdf/' + props.SerieNumar,
        method: 'GET',
        responseType: 'blob', // important
    }).then((response) => {
        let filename = response.headers['content-disposition'].split(';')[1].split('=')[1].replaceAll('"','')
        console.log(filename)
        saveAs(response.data, filename);
    });

    isProcessing.value = false
}

const facturaContainer = ref(null)

onMounted(() => {
    facturaContainer.value.scrollTop = facturaContainer.value.scrollHeight;
    facturaContainer.value.scrollLeft = facturaContainer.value.scrollWidth;
})
</script>
