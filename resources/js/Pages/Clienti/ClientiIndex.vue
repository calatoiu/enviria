<template>
    <AuthenticatedLayout title="Clienți">
        <div class="p-2 overflow-hidden bg-white rounded-md shadow-md dark:bg-dark-eval-1">
            <div class="flex items-center justify-between px-3 pb-2">
                <div class="flex-1 w-auto pr-4">
                    <InputIconWrapper>
                        <template #icon>
                            <SearchIcon aria-hidden="true" class="w-5 h-5" />
                        </template>
                        <Input withIcon name="search" :value='searchQuery.toUpperCase()' @input='evt=>searchQuery=evt.target.value.toUpperCase()' id="search" type="search" class="w-full" placeholder="Caută"  autofocus autocomplete="cauta.client" />
                    </InputIconWrapper>
                </div>
                <div class="flex-none w-14">
                    <Button
                        iconOnly
                        variant="primary"
                        type="button"
                        @click="adaugaClient()"
                        v-slot="{ iconSizeClasses }"
                        srText="Adaugă un nou client"
                        size="lg"
                    >
                        <PlusCircleIcon
                            aria-hidden="true"
                            :class="iconSizeClasses"
                        />
                    </Button>
                </div>
            </div>
            <div class="max-h-[400px] overflow-auto">
                <table class="w-full table-fixed">
                    <tbody>
                        <tr v-for="client in clientiFiltrati" :key="client.CIF">
                            <td class="px-4 py-2 border">
                                <Button
                                    variant="secondary"
                                    type="button"
                                    :disabled="isProcessing"
                                    @click="viewClient(client)"
                                    class="text-left"                            >
                                    {{ client.Denumire }}
                                </Button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="fixed inset-0 z-30 overflow-y-auto ease-out duration-400" v-if="isOpenClientForm">
                <ClientForm :client="currentClient" :furnizori="furnizori" :anaf="currentAnaf" :editMode="true" :errors="errors" @close="closeClientForm" @save="saveClient"/>
            </div>
            <div class="fixed inset-0 z-30 overflow-y-auto ease-out duration-400" v-if="isOpenClientView">
                <ClientView :client="currentClient" :facturi="currentFacturi" @close="closeClientView" @edit="editClient()"  @adaugafactura="adaugaFactura" @viewfactura="viewFactura" />
            </div>
            <div class="fixed inset-0 z-30 overflow-y-auto ease-out duration-400" v-if="isOpenFacturaView">
                <FacturaView :facturahtml="currentFacturaHTML" :SerieNumar = "currentSerieNumar" @edit="editFactura(currentSerieNumar)"   @close="closeFacturaView"  />
            </div>
            <div class="fixed inset-0 z-30 overflow-y-auto ease-out duration-400" v-if="isOpenFacturaForm">
                <FacturaForm :factura="currentFactura" :isadding="isAddingFactura" :furnizori="furnizori" :client="currentClient"  :SerieNumar = "currentSerieNumar" :textFactura="textfactura"  @close="closeFacturaForm" @save="saveFactura" />
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { defineProps, reactive, onMounted, ref, computed  } from 'vue'
import { usePage, useForm } from '@inertiajs/inertia-vue3'
import { PlusIcon, PlusCircleIcon, SearchIcon } from '@heroicons/vue/outline'

import Button              from '@/Components/Button'
import InputIconWrapper    from '@/Components/InputIconWrapper'
import Input               from '@/Components/Input'
import AuthenticatedLayout from '@/Layouts/Authenticated'

import ClientForm  from '@/Components/Client/ClientForm'
import ClientView  from '@/Components/Client/ClientView'
import FacturaView from '@/Components/Factura/FacturaView'
import FacturaForm from '@/Components/Factura/FacturaForm'


const props = defineProps({
    clienti: Object,
})

//=================
// Search
//=================
const searchQuery = ref('')
const clientiFiltrati = computed(() => {
    return props.clienti.filter(
        client => client.Denumire.toUpperCase().includes(searchQuery.value.toUpperCase())
    )
})
//==End Search=====


onMounted(async () => {
  //  console.log(usePage().props.value.flash)
  //  console.log(props.clienti)
    textfactura.value =  await getTextFactura()
   furnizori.value =  await getFurnizori()
})


const isProcessing = ref(false)

//=================
// Client
//=================

const currentClient = ref({})
const currentAnaf = ref({})
const cClient = ref({})
const isOpenClientView = ref(false)
const currentFacturi = ref({})
const furnizori = ref({})


const viewClient = async (client)=>{
    isProcessing.value = true
    cClient.value = client
    currentClient.value =  await getClient(client.CIF)
    currentFacturi.value =  await getFacturi(client.CIF)
    isOpenClientView.value = true
    isProcessing.value = false

}

const closeClientView = ()=>{
    isOpenClientView.value = false
}

const editMode = ref(false)
const isOpenClientForm = ref(false)

const editClient = async ()=>{
    isProcessing.value = true
    console.log('editClient:cClient ' + cClient.value.Denumire)
    currentAnaf.value =  await getAnaf(cClient.value.CIF)
    currentClient.value = await getClient(cClient.value.CIF)
    editMode.value = true;
    isOpenClientView.value = false
    isOpenClientForm.value = true
    isProcessing.value = false
}

const saveClient = ()=>{
    updateClient().then(() => {
        if (!errors.value) {
            if (cClient.value.Denumire != currentClient.value.Denumire){
                cClient.value.Denumire = currentClient.value.Denumire
            }
            reset();
            editMode.value = false;
            closeClientForm();
        }
    })
}

const errors = ref('')
const updateClient = async () => {
    errors.value = ''
    try {
        const res =await axios.put('/api/clienti/' + currentClient.value.CIF, currentClient.value)
    } catch (e) {
        console.log (e.response.data.errors)
        if (e.response.status === 422) {
            errors.value = e.response.data.errors
        }
    }
}


const closeClientForm = ()=>{
    isOpenClientForm.value = false
    isOpenClientView.value = true
    reset()
}

const adaugaClient = ()=>{
    isOpenClientForm.value = true
}


const getClient = async (CIF) => {
    let response = await axios.get('/api/clienti/' + CIF)
    return response.data.data
}

const getAnaf = async (CIF) => {
    let response = await axios.get('/api/anaf/' + CIF)
    return response.data.data
}

const getFacturi = async (CUI) => {
    let response = await axios.get('/api/facturi/' + CUI)
    return response.data.data
}

const getFurnizori = async () => {
    let response = await axios.get('/api/furnizori')
    return response.data.data
}



// const getFurnizori = () => {
//     axios.get('/api/furnizori').then((response) => {
//                 console.log('getFurnizori',response.data.data)
//                 return response.data.data
//             })

// }
//=====End Client========

const reset = () => {
    errors.value = ''
}


//====Factura =======
const currentFacturaHTML = ref({})
const currentFactura = ref({})
const currentSerieNumar = ref({})
const isOpenFacturaView = ref(false)
const isOpenFacturaForm = ref(false)
const isAddingFactura = ref(false)
const textfactura = ref('')

const getFacturahml = async (SerieNumar) => {
    let response = await axios.get('/api/facturahtml/' + SerieNumar)
    return response.data
}

const viewFactura = async (SerieNumar)=>{
    currentSerieNumar.value = SerieNumar
    currentFacturaHTML.value =  await getFacturahml(SerieNumar)
    isOpenClientView.value = false
    isOpenFacturaView.value =true
}


const closeFacturaView = async (SerieNumar)=>{
    isOpenFacturaView.value = false
    isOpenClientView.value = true
}

const getFactura = async (SerieNumar) => {
    let response = await axios.get('/api/factura/' + SerieNumar)
    return response.data.data
}
const getTextFactura = async () => {
    let response = await axios.get('/api/textfactura')
    console.log(response)
    return response.data
}

const getNextNumber = async () => {

}

const adaugaFactura = async ()=>{

    isOpenClientView.value = false
    isOpenFacturaView.value = false
    isOpenFacturaForm.value = true
    isAddingFactura.value = true
}

const editFactura = async (SerieNumar)=>{
    currentSerieNumar.value = SerieNumar
    currentFactura.value =  await getFactura(SerieNumar)
    isOpenClientView.value = false
    isOpenFacturaView.value = false
    isOpenFacturaForm.value = true
    isAddingFactura.value = false
}

const saveFactura = async ()=>{
}

const closeFacturaForm = async ()=>{
    isOpenFacturaView.value = true
    isOpenFacturaForm.value = false
}
//===End Factura


</script>
