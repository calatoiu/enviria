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
            <h3 v-if="props.editMode" class="pb-4 text-center">Modificare client - {{props.client.CIF}} </h3>
            <h3 v-else class="pb-4 text-center">Adaugare client - {{props.client.CIF}} </h3>
            <div class="flex gap-y-3.5 justify-items-start flex-row flex-wrap items-end justify-between">
                <AnafField name="denumire" label="Denumire" v-model="props.client.Denumire" :avalue="props.anaf.Denumire" :rules="validateDenumire" :class="['basis-2/3']"/>
                <AnafField name="judet" label="Județ" v-model="props.client.Judet" :avalue="props.anaf.Judet" :rules="validateJudet" :class="['basis-1/3']"/>
                <AnafField name="sediu" label="Sediu" v-model="props.client.Sediu" :avalue="props.anaf.Sediu" :rules="validateJudet" :class="['basis-2/3']"/>
                <AnafField name="nrregcom" label="Reg com" v-model="props.client.NrRegCom" :avalue="props.anaf.NrRegCom" :rules="validateJudet"  :class="['basis-1/3']"/>
                <ClientField name="banca" label="Banca" v-model="props.client.Banca" :rules="validateBanca"  :class="['basis-1/3']"/>
                <ClientField name="contbancar" label="Cont bancar" v-model="props.client.ContBancar" :rules="validateContBancar"  :class="['basis-2/3']"/>
            </div>
        </div>
       <div class="px-4 pt-5 pb-4 bg-white dark:bg-dark-eval-1 sm:p-6 sm:pb-4">
            <fieldset class="flex border-t-2 border-gray-400 border-solid dark:border-gray-600">
                <legend class="text-sm font-bold text-gray-700 dark:text-gray-300">
                    <div class="px-0.5"  > Contract </div>
                </legend>
                <div class="flex gap-y-3.5 justify-items-start flex-row flex-wrap items-end justify-between">
<!-- Nr Contract -->
                    <div class="flex-auto pr-4 basis-1/3">
                        <div class="flex items-end pb-0.5">
                            <Label class="flex-none" for="nrcontract" value="Număr" />
                        </div>
                        <InputIconWrapper>
                            <template #icon>
                                <PencilAltIcon aria-hidden="true" class="w-5 h-5" />
                            </template>
                            <InputVee withIcon name="nrcontract" id="nrcontract" type="text" class="block w-full" placeholder="Număr contract" v-model="props.client.NrContract" :rules="validateNrContract"/>
                        </InputIconWrapper>
                        <ErrorMessage class="text-sm text-red-700" name="nrcontract" />
                    </div>  <!--  Nr Contract end -->

<!-- Data Contract -->
                    <div class="flex-auto pr-4 basis-2/3">
                        <div class="flex items-end pb-0.5">
                            <Label class="flex-none" for="datacontract" value="Data contract" />
                        </div>
                        <Datepicker
                            name="datacontract" id="datacontract"
                            v-model="props.client.DataContract"
                            locale="ro-RO" cancelText="renunță" selectText="selectează"
                        minDate="2010-01-01"
                        :maxDate = "new Date()"
                        :enableTimePicker="false"
                        :dark="isDark"
                        format="yyyy-MM-dd"
                        inputClassName="placeholder:italic placeholder:text-slate-400 placeholder:text-xs"
                        placeholder="Data contract"
                        autoApply
                        ></Datepicker>
                    </div> <!--  Data Contract end -->

<!-- Valoare Contract -->
                    <div class="flex-auto pr-4 basis-1/2">
                        <div class="flex items-end pb-0.5">
                            <Label class="flex-none" for="valoarecontract" value="Valoare" />
                        </div>
                        <InputIconWrapper>
                            <template #icon>
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        class="w-6 h-6">
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="1"
                                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                        </path>
                                    </svg>
                            </template>
                            <InputVee withIcon name="valoarecontract" id="valoarecontract" type="text" class="block w-full" placeholder="Valoare contract" v-model="props.client.Valoare" :rules="validateValoareContract"/>
                        </InputIconWrapper>
                        <ErrorMessage class="text-sm text-red-700" name="valoarecontract" />
                    </div>  <!--  Valoare Contract end -->
<!-- Furnizor -->
                    <div class="flex-auto pr-4 basis-1/2">
                        <div class="flex items-end pb-0.5">
                            <Label class="flex-none" for="furnizor" value="Furnizor" />
                        </div>
                        <InputIconWrapper>
                            <template #icon>
                                <SelectorIcon aria-hidden="true" class="w-5 h-5" />
                            </template>
                            <Select withIcon name="furnizor" id="furnizor" class="block w-full" placeholder="Furnizor"
                                v-model="props.client.Furnizor"
                                :items="props.furnizori"
                            />
                        </InputIconWrapper>
                    </div>  <!--  Furnizor end -->
<!-- Note -->
                    <div class="flex-auto pr-4 basis-1/2">
                        <div class="flex items-end pb-0.5">
                            <Label class="flex-none" for="valoarecontract" value="Note" />
                        </div>
                        <InputIconWrapper>
                            <template #icon>
                                <DocumentTextIcon aria-hidden="true" class="w-5 h-5" />
                            </template>
                            <Textarea withIcon name="note" id="note" class="block w-full" placeholder="Note..." v-model="props.client.Note"/>
                        </InputIconWrapper>
                        <ErrorMessage class="text-sm text-red-700" name="note" />
                    </div>  <!--  Note end -->
                </div>
            </fieldset>
       </div>

        <div class="flex px-4 px-6 py-3 bg-gray-50 dark:bg-dark-eval-1 gap-y-3.5 flex-row flex-wrap items-end justify-between">
            <Button variant="primary" type="button" class="justify-center w-32 w-full gap-2" :disabled="isProcessing" v-slot="{iconSizeClasses}"  @click="save">
                    <SaveIcon aria-hidden="true" :class="iconSizeClasses" />
                    <span>Salvare</span>
            </Button>


            <Button variant="secondary" type="button" class="justify-center w-32 w-full gap-2" :disabled="isProcessing" v-slot="{iconSizeClasses}" v-show="props.editMode" @click="closeModal">
                    <XIcon aria-hidden="true" :class="iconSizeClasses" />
                    <span>Anulare</span>
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
import Select from '@/Components/Select'
import Textarea from '@/Components/Textarea'
import InputVee from '@/Components/InputVee'
import Label from '@/Components/Label'
import AnafField from './AnafField'
import ClientField from './ClientField'
import ViewField from './ViewField'
import InputIconWrapper from '@/Components/InputIconWrapper'
import { DocumentTextIcon, SelectorIcon, PlusIcon, PlusCircleIcon, SearchIcon, PencilAltIcon, SaveIcon, XIcon, ChevronDoubleDownIcon, CurrencyDollarIcon} from '@heroicons/vue/outline'
import { isValidIBAN } from "ibantools"

import Datepicker from 'vue3-date-time-picker'
import 'vue3-date-time-picker/dist/main.css'

import { defineProps, defineEmits, ref, computed,
//reactive,
onMounted, onBeforeMount
} from 'vue'

import {
//    handleScroll,
    isDark,
//    scrolling,
//    toggleDarkMode,
//    sidebarState,
} from '@/Composables'
import { Field, Form, ErrorMessage } from 'vee-validate';

const props = defineProps({
    client: Object,
    anaf: Object,
    furnizori: Object,
    errors: String,
    editMode: Boolean
})


onBeforeMount(() => {

 //   console.log('ClientForm onBeforeMount') //,furnizori.value)
})

const isProcessing = ref(false)

const emit = defineEmits(['close', 'save'])

const closeModal = () => {
    emit('close')
}

const update = () => {

}


const validateDenumire = (value) => {
       console.log('validateDenumire ',value)
      // if the field is empty
      if (!value) {
        return 'This field is required';
      }
      // if the field is not a valid email
    //   const regex = /^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i;
    //   if (!regex.test(value)) {
    //     return 'This field must be a valid email';
    //   }
      // All is good
      return true;
}
const validateDenumire1 = (value) => {
    console.log('validateDenumire1 ',value)
      // if the field is empty
      if (!value) {
        return 'This field is required';
      }
      // if the field is not a valid email
    //   const regex = /^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i;
    //   if (!regex.test(value)) {
    //     return 'This field must be a valid email';
    //   }
      // All is good
      return true;
}

const validateJudet = (value) => {
      // if the field is empty
      if (!value) {
        return 'This field is required';
      }
      // if the field is not a valid email
    //   const regex = /^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i;
    //   if (!regex.test(value)) {
    //     return 'This field must be a valid email';
    //   }
      // All is good
      return true;
}
const validateBanca = (value) => {
      // if the field is empty
      if (!value) {
        return 'This field is required';
      }
      // if the field is not a valid email
    //   const regex = /^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i;
    //   if (!regex.test(value)) {
    //     return 'This field must be a valid email';
    //   }
      // All is good
      return true;
}
const validateContBancar = (value) => {
      // if the field is empty
      if (value) {
          if (!isValidIBAN(value))
                return 'Introduceți un cont bancar valid';
      }
      return true;
}
const validateValoareContract = (value) => {
      return true;
}
const validateNrContract = (value) => {
      return true;
}

const copyDenumire = () => {
    console.log("copyDenumire")
    console.log("props.anaf.Denumire", props.anaf.Denumire)
    console.log("props.client.Denumire", props.client.Denumire)
   props.client.Denumire = props.anaf.Denumire
       console.log("props.anaf.Denumire", props.anaf.Denumire)
    console.log("props.client.Denumire", props.client.Denumire)
}

const save = async () => {
    isProcessing.value = true

        emit('save')

    isProcessing.value = false
}


</script>
