<template>
    <AuthenticatedLayout title="Dashboard">
        <template #header>
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <h2 class="text-xl font-semibold leading-tight">
                    Users
                </h2>
            </div>
        </template>

        <div class="p-6 overflow-hidden bg-white rounded-md shadow-md dark:bg-dark-eval-1">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="px-4 py-4 overflow-hidden bg-white shadow-xl sm:rounded-lg">
                    <div class="px-4 py-3 my-3 text-teal-900 bg-teal-100 border-t-4 border-teal-500 rounded-b shadow-md" role="alert" v-if="$page.props.flash.message">
                      <div class="flex">
                        <div>
                          <p class="text-sm">{{ $page.props.flash.message }}</p>
                        </div>
                      </div>
                    </div>
                    <button @click="openModal()" class="px-4 py-2 my-3 font-bold text-white bg-blue-500 rounded hover:bg-blue-700">Create New User</button>
                    <table class="w-full table-fixed">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="px-4 py-2">Name</th>
                                <th class="px-4 py-2">Email</th>
                                <th class="px-4 py-2">Roles</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="user in props.users.data" :key="user.id">
                                <td class="px-4 py-2 border">{{ user.name }}</td>
                                <td class="px-4 py-2 border">{{ user.email }}</td>
                                <td class="px-4 py-2 border">roles</td>
                                <td class="px-4 py-2 border">
                                    <button @click="editUser(user)" class="px-4 py-2 font-bold text-white bg-blue-500 rounded hover:bg-blue-700">Edit</button>
                                    <button @click="deleteUser(user)" class="px-4 py-2 font-bold text-white bg-red-500 rounded hover:bg-red-700">Delete</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="fixed inset-0 z-10 overflow-y-auto ease-out duration-400" v-if="isOpen">
                        <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                            <div class="fixed inset-0 transition-opacity">
                                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                            </div>
<!-- This element is to trick the browser into centering the modal contents. -->
                            <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>​
                            <div class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full" role="dialog" aria-modal="true" aria-labelledby="modal-headline">
                                <form>
                                    <div class="px-4 pt-5 pb-4 bg-white sm:p-6 sm:pb-4">
                                        <div class="">
                                            <div class="mb-4">
                                                <label for="exampleFormControlInput1" class="block mb-2 text-sm font-bold text-gray-700">Name:</label>
                                                <input type="text" class="w-full px-3 py-2 leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline" id="exampleFormControlInput1" placeholder="Enter Name" v-model="form.name">
                                                <div v-if="$page.props.errors.title" class="text-red-500">{{ $page.props.errors.title[0] }}</div>
                                            </div>
                                            <div class="mb-4">
                                                <label for="exampleFormControlInput2" class="block mb-2 text-sm font-bold text-gray-700">email:</label>
                                                <textarea class="w-full px-3 py-2 leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline" id="exampleFormControlInput2" v-model="form.email" placeholder="Enter Email"></textarea>
                                                <div v-if="$page.props.errors.body" class="text-red-500">{{ $page.props.errors.body[0] }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="px-4 py-3 bg-gray-50 sm:px-6 sm:flex sm:flex-row-reverse">
                                        <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                                        <button wire:click.prevent="store()" type="button" class="inline-flex justify-center w-full px-4 py-2 text-base font-medium leading-6 text-white transition duration-150 ease-in-out bg-green-600 border border-transparent rounded-md shadow-sm hover:bg-green-500 focus:outline-none focus:border-green-700 focus:shadow-outline-green sm:text-sm sm:leading-5" v-show="!editMode" @click="save(form)">
                                            Save
                                        </button>
                                        </span>
                                        <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                                        <button wire:click.prevent="store()" type="button" class="inline-flex justify-center w-full px-4 py-2 text-base font-medium leading-6 text-white transition duration-150 ease-in-out bg-green-600 border border-transparent rounded-md shadow-sm hover:bg-green-500 focus:outline-none focus:border-green-700 focus:shadow-outline-green sm:text-sm sm:leading-5" v-show="editMode" @click="update()">
                                            Update
                                        </button>
                                        </span>
                                        <span class="flex w-full mt-3 rounded-md shadow-sm sm:mt-0 sm:w-auto">

                                        <button @click="closeModal()" type="button" class="inline-flex justify-center w-full px-4 py-2 text-base font-medium leading-6 text-gray-700 transition duration-150 ease-in-out bg-white border border-gray-300 rounded-md shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue sm:text-sm sm:leading-5">
                                            Cancel
                                        </button>
                                        </span>
                                    </div>
                                </form>
                            </div>
                      </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/Authenticated'
import Button from '@/Components/Button'
import { GithubIcon } from '@/Components/Icons/brands'
import { defineProps, reactive, onMounted, ref  } from 'vue'
import { usePage } from '@inertiajs/inertia-vue3'
import { useForm } from '@inertiajs/inertia-vue3';

const props = defineProps({
    users: Object,
    errors: Object
})


const editMode = ref(false)
const isOpen = ref(false)
const form = useForm({
    name: '',
    email: '',
    id: '',
})

onMounted(() => {
    console.log(usePage().props.value.flash)
})

const openModal = () => {
        isOpen.value = true
    }
const closeModal = () => {
    isOpen.value = false
    reset()
    editMode.value = false;
}

const reset = () => {
    form.reset()
}

const save = (form) => {
    form.post('/users')
    reset();
    closeModal();
    editMode.value = false;
}

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    })
}

const editUser = (user) => {
    form.name = user.name
    form.email = user.email
    form.id = user.id
    editMode.value = true;
    openModal();
}
const update = () => {
 //   form.put('/users/'+ form.id)
    reset()
    closeModal()
}

const deleteUser = (user) => {
    if (!confirm('Are you sure want to remove?')) return;
    form.delete('/users/' + user.id )
  //  reset()
  //  closeModal()

}
const store = () => {}
</script>
