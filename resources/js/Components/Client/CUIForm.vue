<template>
<div @click.self="closeModal" class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
    <div class="fixed inset-0 transition-opacity">
        <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
    </div>
<!-- This element is to trick the browser into centering the modal contents. -->
    <!-- <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span> -->
    <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>
    ​
    <div class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white dark:bg-dark-eval-1 rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-[70%] sm:w-full" role="dialog" aria-modal="true" aria-labelledby="modal-headline">
    <div class="mt-2 mb-6 text-sm text-red-600" v-if="props.errors !== ''">
    {{ props.errors }}
    </div>
    <Form class="bg-white dark:bg-dark-eval-1">
        <div class="px-4 pt-5 pb-4 bg-white dark:bg-dark-eval-1 sm:p-6 sm:pb-4">
            <h3 class="pb-4 text-center">Adăugare client</h3>
            <div class="flex gap-y-3.5 justify-items-start flex-row flex-wrap items-end justify-between">
                <!-- CUI -->
                <div class="flex-auto pr-4 basis-1/3">
                    <div class="flex items-end pb-0.5">
                        <Label class="flex-none" for="CUI" value="CUI" />
                    </div>
                    <InputIconWrapper>
                        <template #icon>
                            <PencilAltIcon aria-hidden="true" class="w-5 h-5" />
                        </template>
                        <InputVee withIcon name="CUI" id="CUI" type="text" class="block w-full" placeholder="CUI" v-model="CUI" :rules="validateRomanianCIF"/>
                    </InputIconWrapper>
                    <ErrorMessage class="text-sm text-red-700" name="CUI" />
                </div>  <!--  CUI end -->
            </div>
        </div>

        <div class="flex px-4 px-6 py-3 bg-gray-50 dark:bg-dark-eval-1 gap-y-3.5 flex-row flex-wrap items-end justify-between">
            <Button variant="primary" type="button" class="justify-center w-32 w-full gap-2" :disabled="isProcessing" v-slot="{iconSizeClasses}"  @click="adauga">
                    <SaveIcon aria-hidden="true" :class="iconSizeClasses" />
                    <span>Adaugă</span>
            </Button>

            <Button variant="secondary" type="button" class="justify-center w-32 w-full gap-2" :disabled="isProcessing" v-slot="{iconSizeClasses}" @click="inchide">
                    <XIcon aria-hidden="true" :class="iconSizeClasses" />
                    <span>Închide</span>
            </Button>
        </div>
    </Form>
    </div>
</div>
</template>

<script setup>
//import AuthenticatedLayout from '@/Layouts/Authenticated'
import Button from '@/Components/Button'
import Input from '@/Components/Input'
import InputVee from '@/Components/InputVee'
import Label from '@/Components/Label'
import InputIconWrapper from '@/Components/InputIconWrapper'
import { DocumentTextIcon, SelectorIcon, PlusIcon, PlusCircleIcon, SearchIcon, PencilAltIcon, SaveIcon, XIcon, ChevronDoubleDownIcon, CurrencyDollarIcon} from '@heroicons/vue/outline'

import { defineProps, defineEmits, ref, computed,
//reactive,
onMounted, onBeforeMount
} from 'vue'

import {
    isDark,
} from '@/Composables'
import { Field, Form, ErrorMessage } from 'vee-validate';

const props = defineProps({

})

onMounted(()=>{

    document.addEventListener("keydown", (e) => {
        if (e.keyCode == 27) {
            emit('close')
        }
    });
})

const isProcessing = ref(false)
const CUI = ref('')

const emit = defineEmits(['close', 'add'])

const inchide = () => {
    emit('close')
}


const adauga = async () => {
    isProcessing.value = true

        emit('add', CUI.value)

    isProcessing.value = false
}


/**
   * Validates if a string conforms to a valid Romanian Fiscal Code.
   *
   * See Romanian Law no. 359 from 8 September 2004.
   * @param v {string} input string to be validated
   * @returns {string|boolean} `true` if the input is a valid CIF or an error message,
   * describing where validation failed
   */
const  validateRomanianCIF = (v) => {
    if (typeof v !== 'string') {
      return 'Nu este un șir de caractere!'
    }

    let cif = v.toUpperCase()

    // remove RO from cif:
    const indexOfRo = cif.indexOf('RO')

    if (indexOfRo > -1) {
      cif = cif.substr(0, indexOfRo) + cif.substr(indexOfRo + 2)
    }

    // remove spaces:
    cif = cif.replace(' ', '')

    // validate character length:
    if (cif.length < 2 || cif.length > 10) {
      return 'Lungimea corectă fără RO, este între 2 și 10 caractere!'
    }

    // validate that so far the resulting CIF looks like an integer value:
    if (Number.isNaN(parseInt(cif))) {
      return 'Nu este număr!'
    }

    // begin testing:
    const testKey = '753217532'
    const controlNumber = parseInt(cif.substr(cif.length - 1))

    // remove control number:
    cif = cif.substr(0, cif.length - 1)

    // pad left with zeros to reach testKey length:
    while (cif.length !== testKey.length) {
      cif = '0' + cif
    }

    let sum = 0
    let i = cif.length

    while (i--) {
      sum = sum + (cif.charAt(i) * testKey.charAt(i))
    }

    let calculatedControlNumber = sum * 10 % 11

    if (calculatedControlNumber === 10) {
      calculatedControlNumber = 0
    }

    return controlNumber === calculatedControlNumber ||
      `CIF invalid! Cifra de control calculată (${calculatedControlNumber}) diferă de cea introdusă (${controlNumber})!`
  }

</script>
