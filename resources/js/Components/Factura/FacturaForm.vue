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
            <h3 class="pb-4 text-center">Actualizare factură - {{props.factura.SerieNumar}} - {{props.client.Denumire}} </h3>
            <div class="flex flex-wrap">
<!-- Furnizor -->
                    <div class="pr-4">
                        <div class="flex items-end pb-0.5">
                            <Label class="flex-none" for="furnizor" value="Furnizor" />
                        </div>
                        <InputIconWrapper>
                            <template #icon>
                                <SelectorIcon aria-hidden="true" class="w-5 h-5" />
                            </template>
                            <Select withIcon name="furnizor" id="furnizor" class="block w-full" placeholder="Furnizor"
                                v-model="props.factura.Furnizor"
                                :items="props.furnizori"
                            />
                        </InputIconWrapper>
                    </div>
<!--  Furnizor end -->
<!-- Data -->
                    <div class="pr-4">
                        <div class="flex items-end pb-0.5">
                            <Label class="flex-none" for="datafactura" value="Data" />
                        </div>
                        <Datepicker
                            name="datafactura" id="datafactura"
                            v-model="props.factura.Data"
                            locale="ro-RO" cancelText="renunță" selectText="selectează"
                            :format-locale="ro"
                        minDate="2010-01-01"
                        :maxDate = "new Date()"
                        :enableTimePicker="false"
                        :dark="isDark"
                        format="yyyy-MM-dd"
                        placeholder="Data facturii"
                        autoApply
                        @update:modelValue="setDate"
                        ></Datepicker>
                    </div>
<!--  Data end -->
<!--
                    <div>{{props.factura.Data}}</div>
                    <div>{{SerieFactura}}</div>
                    <div>{{serieFactura}}</div>
-->

<!-- Serie Factura
                <div class="flex-auto pr-4 basis-1/2">
                    <div class="flex items-end pb-0.5">
                        <Label class="flex-none" for="seriefactura" value="Serie" />
                    </div>
                    <InputIconWrapper>
                        <template #icon>
                            <DocumentTextIcon aria-hidden="true" class="w-5 h-5" />
                        </template>
                        <InputVee withIcon
                            name="serieFactura" id="serieFactura"
                            type="text" class="block w-full" placeholder="Număr factura"
                            v-model="serieFactura" :rules="validateSerieFactura"/>
                    </InputIconWrapper>
                    <ErrorMessage class="text-sm text-red-700" name="note" />
                </div>
  Serie Factura end -->
<!-- Nr factura -->
                    <div class="pr-4">
                        <div class="flex items-end pb-0.5">
                            <Label class="flex-none" for="nrfactura" value="Număr" />
                        </div>
                        <InputIconWrapper>
                            <template #icon>
                                {{SerieFactura}}
                            </template>
                            <InputVee withText
                                name="nrfactura" id="nrfactura"
                                type="text" class="block w-full" placeholder="Număr factura"
                                v-model="numarFactura" :rules="validateNrFactura"/>
                        </InputIconWrapper>
                        <ErrorMessage class="text-sm text-red-700" name="nrfactura" />
                    </div>
<!--  Nr factura end -->
<!-- Luna Ini -->
                    <div class="pr-4">
                        <div class="flex items-end pb-0.5">
                            <Label class="flex-none" for="lunaini" value="Luna inițială" />
                        </div>
                        <Datepicker
                            name="lunaini" id="lunaini"
                            v-model="lunaIni"
                            locale="ro-RO" cancelText="renunță" selectText="selectează"
                            :format-locale="ro"
                        minDate="2010-01-01"
                        :maxDate = "new Date()"
                        :enableTimePicker="false"
                          monthPicker
                        :dark="isDark"
                        format="MMM yy"
                        placeholder="Luna început"
                        autoApply
                        ></Datepicker>
                    </div>
<!--  Luna Ini end -->


<!-- Luna Fin -->
                    <div class="pr-4">
                        <div class="flex items-end pb-0.5">
                            <Label class="flex-none" for="lunafin" value="Luna finală" />
                        </div>
                        <Datepicker
                            name="lunafin" id="lunafin"
                            v-model="lunaFin"
                            locale="ro-RO" cancelText="renunță" selectText="selectează"
                            :format-locale="ro"
                        minDate="2010-01-01"
                        :maxDate = "new Date()"
                        :enableTimePicker="false"
                          monthPicker
                        :dark="isDark"
                        format="MMM yy"
                        placeholder="Luna finală"
                        autoApply
                        ></Datepicker>
                    </div>
<!--  Luna Fin end -->

<!-- Interval >
                    <div class="self-center flex-auto pr-4">
                        <p>{{IntervalLuni}} </p>
                    </div>
<  Interval end -->

<!-- Fara contract -->
                    <label class="flex items-center pt-2 pr-4">
                        <Checkbox name="FaraContract" v-model:checked="FaraContract" />
                        <span class="ml-2 text-sm text-gray-600">Fără contract</span>
                    </label>
<!-- Fara contract end -->
<!-- Nota text -->
                    <label class="flex items-center pt-2 pr-4">
                        <Checkbox name="NotaText" v-model:checked="NotaText" />
                        <span class="ml-2 text-sm text-gray-600">Notă text</span>
                    </label>
<!-- Nota text end -->

<!-- Nota -->

                <InputLabelIconWrapper name="nota" label="Nota" :class="['']">
                    <template #icon>
                        <DocumentTextIcon aria-hidden="true" class="w-5 h-5" />
                    </template>
                    <InputVee as="textarea" withIcon name="nota" id="nota" class="block w-full" placeholder="Nota..." v-model="props.factura.Nota" />
                </InputLabelIconWrapper>


<!-- Nota end -->
            </div>
        </div>
        <div class="flex px-4 py-3 bg-gray-50 dark:bg-dark-eval-1 gap-y-3.5 flex-row flex-wrap items-end justify-between">
            <Button variant="primary" type="button" class="justify-center w-32 gap-2" :disabled="isProcessing" v-slot="{iconSizeClasses}"  @click="save">
                    <SaveIcon aria-hidden="true" :class="iconSizeClasses" />
                    <span>Salvare</span>
            </Button>


            <Button variant="secondary" type="button" class="justify-center w-32 gap-2" :disabled="isProcessing" v-slot="{iconSizeClasses}" @click="closeModal">
                    <XIcon aria-hidden="true" :class="iconSizeClasses" />
                    <span>Anulare</span>
            </Button>
            <Button variant="secondary" type="button" class="justify-center w-32 gap-2" :disabled="isProcessing" v-slot="{iconSizeClasses}" @click="verifica">
                    <XIcon aria-hidden="true" :class="iconSizeClasses" />
                    <span>Verifica</span>
            </Button>
        </div>
    </Form>
    </div>
</div>
</template>

<script setup>


import { ro } from 'date-fns/locale';
import moment from 'moment';
import 'moment/locale/ro';

import Button from '@/Components/Button'
import Input from '@/Components/Input'
import Select from '@/Components/Select'
import Textarea from '@/Components/Textarea'
import InputVee from '@/Components/InputVee'
import Label from '@/Components/Label'
import Checkbox from '@/Components/Checkbox'
import InputIconWrapper from '@/Components/InputIconWrapper'
import InputLabelIconWrapper from '@/Components/InputLabelIconWrapper'
import { DocumentTextIcon, SelectorIcon, PlusIcon, PlusCircleIcon, SearchIcon, PencilAltIcon, SaveIcon, XIcon, ChevronDoubleDownIcon, CurrencyDollarIcon} from '@heroicons/vue/outline'
import { isValidIBAN } from "ibantools"

import Datepicker from 'vue3-date-time-picker'
import 'vue3-date-time-picker/dist/main.css'

import { defineProps, defineEmits, ref, computed,  onMounted, watch
//reactive,
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
    factura: Object,
    SerieNumar: String,
    textFactura: String,
    furnizori: Object,
    errors: String,
    editMode: Boolean
})


//const serieFactura = ref('FFZC00');



onMounted(() => {
 //  lunaIni.value.month = moment(props.factura.LunaIni, 'YYYYMM').month()
 //  lunaIni.value.year = moment(props.factura.LunaIni, 'YYYYMM').year()
//   lunaFin.value.month = moment(props.factura.LunaFin, 'YYYYMM').month()
 //  lunaFin.value.year = moment(props.factura.LunaFin, 'YYYYMM').year()
 //  serieFactura.value = props.factura.SerieNumar.substr(0,6)
 //  numarFactura.value = props.factura.SerieNumar.substr(7,4)
 //  console.log(props.factura)
})


const IntervalLuni = computed(() => {

var m1 = moment([lunaIni.value.year, lunaIni.value.month]);
var m2 = moment([lunaFin.value.year, lunaFin.value.month]);
var luni = m2.diff(m1, 'months');
    if (luni == 0)
        return 'o lună';

    return (luni + 1) + ' luni';
})


 watch(
  () => props.factura.Furnizor,
  () => {
 //   console.log(props.factura.Furnizor)
    var prefixFurnizor = props.furnizori.find((furnizor) => { return furnizor.id == props.factura.Furnizor }).prefix;
    props.factura.SerieNumar = 'FF' + prefixFurnizor + props.factura.Data.substr(2,2) + '-' + props.factura.SerieNumar.substr(7,4)
  }
)
 watch(
  () => props.factura.Data,
  () => {
 //   console.log(props.factura.Data)
    var prefixFurnizor = props.furnizori.find((furnizor) => { return furnizor.id == props.factura.Furnizor }).prefix;
    props.factura.SerieNumar = 'FF' + prefixFurnizor + props.factura.Data.substr(2,2) + '-' + props.factura.SerieNumar.substr(7,4)
  }
)

const SerieFactura = computed({
    get () {
        return props.factura.SerieNumar.substring(0, 6)
    }
    // ,
    // set(value) {
    //     var prefixFurnizor = props.furnizori.find((furnizor) => { return furnizor.id == props.factura.Furnizor }).prefix;
    //     props.factura.SerieNumar = 'FF' + prefixFurnizor + props.factura.Data.substr(2,2) + ''
    // }
})


const numarFactura = computed({
    get () {
        return props.factura.SerieNumar.substr(7,4)
    },
    set(value) {
        props.factura.SerieNumar = props.factura.SerieNumar.substring(0, 6) + '-' + String(parseInt(value)).padStart(4, '0')
    }
})

//const lunaIni = ref({month: 0, year: 2000});
//const lunaFin = ref({month: 0, year: 2000});

const lunaIni = computed({
     get () {
        return {month: moment(props.factura.LunaIni, 'YYYYMM').month(), year: moment(props.factura.LunaIni, 'YYYYMM').year()}
    },
    set (value) {
        props.factura.LunaIni = String(value.year).padStart(4, '0')  + String(value.month + 1).padStart(2, '0')
    }
})

const lunaFin = computed({
     get () {
        return {month: moment(props.factura.LunaFin, 'YYYYMM').month(), year: moment(props.factura.LunaFin, 'YYYYMM').year()}
    },
    set (value) {
        props.factura.LunaFin = String(value.year).padStart(4, '0')  + String(value.month + 1).padStart(2, '0')
    }
})

const FaraContract = computed({
     get () {
        return props.factura.FaraContract !=  0
    },
    set (value) {
        props.factura.FaraContract = (value ?  1 : 0)
    }
})


const NotaText = computed({
    get () {
        return props.factura.NotaText !=  0
    },
    set (value) {
        props.factura.NotaText = (value ?  1 : 0)

    }
})

const isProcessing = ref(false)

const emit = defineEmits(['close', 'save'])

const closeModal = () => {
    emit('close')
}

const validateSerieFactura = (value) => {

}
const validateNrFactura = (value) => {
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



const save = async () => {
    isProcessing.value = true
  //      props.factura.LunaIni = moment('' + lunaIni.value.year + '-' + (lunaIni.value.month + 1)).format('YYYYMM')
  //      props.factura.LunaFin = moment('' + lunaFin.value.year + '-' + (lunaFin.value.month + 1)).format('YYYYMM')
        console.log(props.factura)
        emit('save')

    isProcessing.value = false
}



const setDate = (value) => {
    props.factura.Data = moment(value).format('YYYY-MM-DD')

}

const verifica = async () => {
    var sn = SerieFactura.value + '-' + numarFactura.value
    var fact = await getFactura( sn )


    console.log(fact, Object.keys(fact).length)
}


const getFactura = async (SerieNumar) => {
    let response = await axios.get('/api/factura/' + SerieNumar)
    return response.data.data
}
</script>
