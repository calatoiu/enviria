<template>
<div @click.self="closeModal" class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
    <div class="fixed inset-0 transition-opacity">
        <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
    </div>
<!-- This element is to trick the browser into centering the modal contents. -->
    <!-- <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span> -->
    <span class="hidden sm:inline-block sm:align-top sm:h-screen"></span>
    ​
    <div class="inline-block overflow-hidden text-left align-top transition-all transform bg-white dark:bg-dark-eval-1 rounded-lg shadow-xl sm:my-8 sm:align-top sm:max-w-[70%] sm:w-full" role="dialog" aria-modal="true" aria-labelledby="modal-headline">
        <div class="flex flex-row flex-wrap items-end justify-between text-sm font-normal text-gray-500 justify-items-start dark:text-gray-300">
            <div class="flex-auto px-4 pt-4 ">
                <span class="font-bold"> {{props.client.Denumire}} ({{props.client.CIF}} - {{props.client.NrRegCom}}) </span> - {{props.client.Sediu}} - {{props.client.Judet}}
            </div>
            <div class="flex-auto px-4 pt-2">
                {{props.client.ContBancar}} - {{props.client.Banca}}
            </div>
            <div class="flex-auto px-4 pt-2">
                Contract {{props.client.Furnizor}}: {{props.client.NrContract}} / {{props.client.DataContract}} - {{props.client.Valoare}} lei
            </div>
            <div class="flex-auto px-4 pt-2 ">
                {{props.client.Note}}
            </div>
        </div>
        <div class="overflow-auto shadow-lg h-[500px]">
            <div class="w-full p-1 text-xs">
                <div class="flex w-full py-3 my-2 rounded shadow-md cursor-pointer hover:shadow-xl hover:bg-gray-200 dark:hover:bg-gray-600" v-for="factura in props.facturi" :key="factura.CUI" @click="viewFactura(factura.SerieNumar)">
                    <div class="w-[90px] px-1">{{factura.SerieNumar}}</div>
                    <div class="w-[85px] px-1">{{factura.Data}}</div>
                    <div class="w-20 px-1 text-right">
                        <div v-if="factura.Valoare"> {{factura.Valoare}}lei </div>
                        <div v-else> &nbsp; </div>
                    </div>
                    <div class="w-20 px-1 text-right">
                        <div v-if="factura.Sold" class="text-red-700"> {{factura.Sold}}lei </div>
                        <div v-else> &nbsp; </div>
                    </div>
                    <div class="px-1 w-36">{{factura.Interval}}</div>
                </div>
            </div>
        </div>
        <div class="flex px-2  py-3 bg-gray-50 dark:bg-dark-eval-1 gap-y-3.5 items-center justify-between">
            <Button variant="primary" type="button" class="justify-center w-36" :disabled="isProcessing" v-slot="{iconSizeClasses}"  @click="adaugaFactura">
                    <PencilIcon aria-hidden="true" :class="iconSizeClasses" />
                    <span class="text-xs">Adaugă factură</span>
            </Button>
            <Button variant="secondary" type="button" class="justify-center w-32" :disabled="isProcessing" v-slot="{iconSizeClasses}"  @click="edit">
                    <PencilIcon aria-hidden="true" :class="iconSizeClasses" />
                    <span class="text-xs">Modifică client</span>
            </Button>
            <Button variant="secondary" type="button" class="justify-center w-32" :disabled="isProcessing" v-slot="{iconSizeClasses}"  @click="closeModal">
                <XIcon aria-hidden="true" :class="iconSizeClasses" />
                <span class="text-xs">Închide</span>
            </Button>
        </div>
    </div>
</div>
</template>

<script setup>

import Button from '@/Components/Button'
import {  PencilIcon, XIcon} from '@heroicons/vue/outline'

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
})

const isProcessing = ref(false)

const emit = defineEmits(['close', 'edit', 'viewfactura', 'adaugafactura'])

const closeModal = () => {
    emit('close')
}

onMounted(()=>{
    console.log('ClientView on Mounted facturi', props.facturi)
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
