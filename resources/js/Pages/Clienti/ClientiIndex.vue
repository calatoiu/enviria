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
                        @click="adaugaClientCUI"
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
            <div class="fixed inset-0 z-30 overflow-y-auto ease-out duration-400" v-if="isOpenCUIForm">
                <CUIForm  @close="closeCUIForm" @add="adaugaClient"/>
            </div>
            <div class="fixed inset-0 z-30 overflow-y-auto ease-out duration-400" v-if="isOpenClientForm">
                <ClientForm :client="currentEditClient" :furnizori="furnizori" :anaf="currentAnaf" :editMode="editMode" :errors="errors" @close="closeClientForm" @save="saveClient"/>
            </div>
            <div class="fixed inset-0 z-30 overflow-y-auto ease-out duration-400" v-if="isOpenClientView">
                <ClientView :message="viewFormMsg" :client="currentClient" :facturi="currentFacturi" @close="closeClientView" @edit="editClient()"  @adaugafactura="adaugaFactura" @viewfactura="viewFactura" />
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

import CUIForm  from '@/Components/Client/CUIForm'
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
const currentEditClient = ref({})
const currentAnaf = ref({})
const cClient = ref({})
const isOpenClientView = ref(false)
const currentFacturi = ref({})
const furnizori = ref({})
const viewFormMsg = ref('')

const viewClient = async (client, msg = '')=>{
    isProcessing.value = true
    cClient.value = client
    currentClient.value =  await getClient(client.CIF)
    var furnizor = furnizori.value.find((fr) => fr.id == currentClient.value.Furnizor)
    if (furnizor)
        currentClient.value.FurnizorDen = furnizor.Denumire
    currentFacturi.value =  await getFacturi(client.CIF)
    viewFormMsg.value = msg
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
    currentEditClient.value = await getClient(cClient.value.CIF)
    editMode.value = true;
    isOpenClientView.value = false
    isOpenClientForm.value = true
    isProcessing.value = false
}

const saveClient = ()=>{
    if (editMode.value) {
// Modificare
        updateClient().then(() => {
            if (!errors.value) {
                if (cClient.value.Denumire != currentEditClient.value.Denumire){
                    cClient.value.Denumire = currentEditClient.value.Denumire
                    props.clienti.sort((a,b) => {
                        if (a.Denumire < b.Denumire){ return -1}
                        if (a.Denumire > b.Denumire){ return 1}
                        return 0})
                    currentClient.value = currentEditClient.value
                }
                reset();
                editMode.value = false;
                closeClientForm();
            }
        })
    } else {
// Adaugare
        addClient().then(() => {
            if (!errors.value) {
                var newclient = {'CIF': currentEditClient.value.CIF, 'Denumire': currentEditClient.value.Denumire}
                props.clienti.push(newclient)
                props.clienti.sort((a,b) => {
                        if (a.Denumire < b.Denumire){ return -1}
                        if (a.Denumire > b.Denumire){ return 1}
                        return 0})
                reset();
                editMode.value = false;
                isOpenClientForm.value = false
                isOpenClientView.value = false
                isOpenCUIForm.value = false
            }
        })
    }
}

const errors = ref('')
const updateClient = async () => {
    errors.value = ''
    try {
        const res =await axios.put('/api/clienti/' + currentEditClient.value.CIF, currentEditClient.value)
    } catch (e) {
 //       console.log ('EROARE:', e)
         errors.value = 'Status:' + e.response.status + '; '
        if (e.response.status === 500) {
            errors.value += e.response.data.message
        }
        if (e.response.status === 422) {
            for (const key in e.response.data.errors) {
                errors.value += e.response.data.errors[key][0] + ' ';
            }
        }
    }
}

const addClient = async () => {
    errors.value = ''
    try {
        const res =await axios.put('/api/addclient', currentEditClient.value)
    } catch (e) {
 //       console.log ('EROARE:', e)
         errors.value = 'Status:' + e.response.status + '; '
        if (e.response.status === 500) {
            errors.value += e.response.data.message
        }
        if (e.response.status === 422) {
            for (const key in e.response.data.errors) {
                errors.value += e.response.data.errors[key][0] + ' ';
            }
        }
    }
}

const closeClientForm = ()=>{
    isOpenClientForm.value = false
    isOpenClientView.value = true
    reset()
}

const adaugaClient = async (CUI)=>{
    isProcessing.value = true
    console.log('adaugaClient' + CUI)
    var client = props.clienti.find((cl) => cl.CIF == CUI )
//    currentClient.value = await getClient(CUI)
    if (client){
        viewClient(client, 'Acest client există deja în baza de date')
    } else {
        currentAnaf.value =  await getAnaf(CUI)
        currentEditClient.value = await getClientFromAnaf(CUI)
        editMode.value = false;
        isOpenCUIForm.value = false
        isOpenClientView.value = false
        isOpenClientForm.value = true
        isProcessing.value = false
    }
}

const isOpenCUIForm = ref(false)
const adaugaClientCUI = ()=>{
    isOpenCUIForm.value = true
}

const closeCUIForm = ()=>{
    isOpenCUIForm.value = false
}


const getClientFromAnaf = async (CIF) => {
    let response = await axios.get('/api/newclientfromanaf/' + CIF)
    return response.data.data
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
