<template>
        <div class="flex flex-initial rounded px-2  py-1 bg-gray-50 dark:bg-dark-eval-2 gap-y-3.5 items-center justify-between">
            <Button variant="primary" type="button" class="justify-center w-36" :disabled="isProcessing" v-slot="{iconSizeClasses}"  @click="adaugaFactura" size="sm">
                    <PlusIcon aria-hidden="true" :class="iconSizeClasses" />
                    <span class="text-xs">Adaugă factură</span>
            </Button>
            <Button variant="primary" type="button" class="justify-center w-36" :disabled="isProcessing" v-slot="{iconSizeClasses}"  @click="confirmareSold" size="sm">
                    <TableIcon aria-hidden="true" :class="iconSizeClasses" />
                    <span class="text-xs">Confirmare sold</span>
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
</template>

<script setup>

import Button from '@/Components/Button'
import { XIcon, PlusIcon, TableIcon} from '@heroicons/vue/outline'
import { defineProps, defineEmits, ref, computed, onMounted, //reactive,
} from 'vue'

import {
//    handleScroll,
    isDark,
//    scrolling,
//    toggleDarkMode,
//    sidebarState,
} from '@/Composables'


const props = defineProps({
 //   client: Object,
 //   editMode: Boolean,
    facturi: Object,
 //   message: String,
})

const isProcessing = ref(false)

const emit = defineEmits(['viewfactura', 'adaugafactura', 'confirmaresold'])


onMounted(()=>{
 //   console.log('ClientView on Mounted message',  props.message)
        // Close modal with 'esc' key
//     document.addEventListener("keydown", (e) => {
//         if (e.keyCode == 27) {
//  //           this.$emit('close');
//             emit('close')
//         }
//     });
})

// const edit = async () => {
//     isProcessing.value = true

//         emit('edit', )

//     isProcessing.value = false
// }


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

const confirmareSold = async () => {
    isProcessing.value = true
    emit('confirmaresold')
    isProcessing.value = false
}


</script>
