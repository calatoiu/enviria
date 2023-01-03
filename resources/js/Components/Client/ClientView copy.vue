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
            <ViewField :fvalue='props.client.Denumire' :class="['font-bold']" />
            <ViewField :fvalue='props.client.CIF' :class="['text-xs']" />
            <ViewField :fvalue='props.client.NrRegCom' :class="['text-xs']" />
            <ViewField :fvalue='props.client.Sediu' :class="['text-xs']" />
            <ViewField :fvalue='props.client.Judet' :class="['text-xs']" />
            <ViewField :fvalue='props.client.ContBancar' :class="['text-xs']" />
            <ViewField :fvalue="props.client.Furnizor + '-' + props.client.NrContract + '/' + props.client.DataContract" :class="['text-xs']" />
            <ViewField :fvalue='props.client.Valoare' :class="['text-xs']" />
            <ViewField :fvalue='props.client.Note' :class="['text-xs']" />
            <Button variant="secondary" type="button" class="justify-center w-32" :disabled="isProcessing" v-slot="{iconSizeClasses}"  @click="edit" size="sm">
                <PencilIcon aria-hidden="true" :class="iconSizeClasses" />
                <span class="text-xs">Modifică client</span>
            </Button>
        </div>
        <ul class="flex flex-wrap border-b border-gray-200 dark:border-gray-700">
            <li class="mr-2">
                <a href="#" aria-current="page" class="inline-block px-4 py-4 text-sm font-medium text-center text-blue-600 bg-gray-100 rounded-t-lg active dark:bg-gray-800 dark:text-blue-500">Facturi</a>
            </li>
            <li class="mr-2">
                <a href="#"                     class="inline-block px-4 py-4 text-sm font-medium text-center text-gray-500 rounded-t-lg hover:text-gray-600 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-gray-300">Puncte de lucru</a>
            </li>
        </ul>
        <div class="flex flex-initial rounded px-2  py-1 bg-gray-50 dark:bg-dark-eval-2 gap-y-3.5 items-center justify-between">
            <Button variant="primary" type="button" class="justify-center w-36" :disabled="isProcessing" v-slot="{iconSizeClasses}"  @click="adaugaFactura" size="sm">
                    <PlusIcon aria-hidden="true" :class="iconSizeClasses" />
                    <span class="text-xs">Adaugă factură</span>
            </Button>

            <Button variant="secondary" type="button" class="fixed top-0 right-0" :disabled="isProcessing" v-slot="{iconSizeClasses}"  @click="closeModal" size="sm">
                <XIcon aria-hidden="true" :class="iconSizeClasses" />
                <span class="text-xs"></span>
            </Button>
        </div>
        <div class="flex-auto overflow-auto shadow-lg h-5/6">
            <div class="w-full text-xs">
                <div class="flex w-full py-3 my-2 rounded shadow-md cursor-pointer hover:shadow-xl hover:bg-gray-200 dark:hover:bg-gray-600" v-for="factura in props.facturi" :key="factura.CUI" @click="viewFactura(factura.SerieNumar)">
                    <div class="w-[90px] px-1">{{factura.SerieNumar}}</div>
                    <div class="w-[85px] px-1">{{factura.Data}}</div>
                    <div class="w-20 px-1 text-right">
                        <div v-if="factura.Valoare"> {{factura.Valoare}}lei </div>
                        <div v-else> &nbsp; </div>
                    </div>
                    <div class="w-20 px-1 text-right">
                        <div v-if="factura.Sold != 0" class="text-red-700"> {{factura.Sold}}lei </div>
                        <div v-else> &nbsp; </div>
                    </div>
                    <div class="px-1 w-36">{{factura.Interval}}</div>
                </div>
            </div>
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
    client: Object,
    editMode: Boolean,
    facturi: Object,
    message: String,
})

const isProcessing = ref(false)

const emit = defineEmits(['close', 'edit', 'viewfactura', 'adaugafactura'])

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


const viewFactura = async (SerieNumar) => {
        isProcessing.value = true

        emit('viewfactura', SerieNumar)

    isProcessing.value = false

}
const adaugaFactura = async () => {
        isProcessing.value = true

        emit('adaugafactura')

    isProcessing.value = false

}


</script>
