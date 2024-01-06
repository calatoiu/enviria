<template>
        <div class="flex flex-initial rounded px-2  py-1 bg-gray-50 dark:bg-dark-eval-2 gap-y-3.5 items-center justify-between">
            <Button variant="primary" type="button" class="justify-center w-36" :disabled="isProcessing" v-slot="{iconSizeClasses}"  @click="adaugaPunctLucru" size="sm">
                    <PlusIcon aria-hidden="true" :class="iconSizeClasses" />
                    <span class="text-xs">Adaugă punct de lucru</span>
            </Button>
        </div>
        <div class="flex-auto overflow-auto shadow-lg h-5/6">
            <div class="w-full text-xs">
                <div class="flex w-full py-3 my-2 rounded shadow-md cursor-pointer hover:shadow-xl hover:bg-gray-200 dark:hover:bg-gray-600" v-for="punctlucru in props.punctelucru" :key="punctlucru.CIF" @click="viewPunctLucru(punctlucru.PunctLucruID)">
                    <div class="w-[120px] px-1">{{punctlucru.PunctLucruID}}</div>
                    <div class="w-[85px] px-1">{{punctlucru.Denumire}}</div>
                    <div class="w-[85px] px-1">{{punctlucru.Pass}}</div>
                    <div v-if="punctlucru.Nr" class="w-[100px] px-1">{{punctlucru.Nr}}/{{punctlucru.Data}} </div>
                    <div v-else> &nbsp; </div>
                </div>
            </div>
        </div>
</template>

<script setup>

import Button from '@/Components/Button'
import { PlusIcon} from '@heroicons/vue/outline'
import { defineProps, defineEmits, ref, onMounted, //reactive,
} from 'vue'

import {
//    handleScroll,
    isDark,
//    scrolling,
//    toggleDarkMode,
//    sidebarState,
} from '@/Composables'


const props = defineProps({
    punctelucru: Object,
})

const isProcessing = ref(false)

const emit = defineEmits(['viewpunctlucru', 'adaugapunctlucru'])


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


const viewPunctLucru = async (SerieNumar) => {
    isProcessing.value = true

        emit('viewpunctlucru', SerieNumar)

    isProcessing.value = false

}

const adaugaPunctLucru = async () => {
    isProcessing.value = true

        emit('adaugapunctlucru')

    isProcessing.value = false

}


</script>
