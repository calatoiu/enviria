<template>
    <GuestLayout title="Log in">
        <ValidationErrors class="mb-4" />

        <div v-if="status" class="mb-4 text-sm font-medium text-green-600">
            {{ status }}
        </div>

        <form @submit.prevent="submit">
            <div class="grid gap-6">
                <div class="space-y-2">
                    <Label for="email" value="Email" />
                    <InputIconWrapper>
                        <template #icon>
                            <MailIcon aria-hidden="true" class="w-5 h-5" />
                        </template>
                        <Input withIcon id="email" type="email" class="block w-full" placeholder="Email" v-model="form.email" required autofocus autocomplete="username" />
                    </InputIconWrapper>
                </div>

                <div class="space-y-2">
                    <Label for="password" value="Password" />
                    <InputIconWrapper>
                        <template #icon>
                            <LockClosedIcon aria-hidden="true" class="w-5 h-5" />
                        </template>
                        <Input withIcon id="password" type="password" class="block w-full" placeholder="Password" v-model="form.password" required autocomplete="current-password" />
                    </InputIconWrapper>
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center">
                        <Checkbox name="remember" v-model:checked="form.remember" />
                        <span class="ml-2 text-sm text-gray-600">Remember me</span>
                    </label>

                    <Link v-if="canResetPassword" :href="route('password.request')" class="text-sm text-blue-500 hover:underline">
                        Forgot your password?
                    </Link>
                </div>

                <div>
                    <Button class="justify-center w-full gap-2" :disabled="form.processing" v-slot="{iconSizeClasses}">
                        <LoginIcon aria-hidden="true" :class="iconSizeClasses" />
                        <span>Logg in</span>
                    </Button>
                </div>

                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Don't have an account?
                    <Link :href="route('register')" class="text-blue-500 hover:underline">
                        Register
                    </Link>
                </p>
            </div>
        </form>
    </GuestLayout>
</template>

<script setup>
import { Link, useForm } from '@inertiajs/inertia-vue3'
import { MailIcon, LockClosedIcon, LoginIcon } from '@heroicons/vue/outline'
import InputIconWrapper from '@/Components/InputIconWrapper'
import Button from '@/Components/Button'
import Checkbox from '@/Components/Checkbox'
import GuestLayout from '@/Layouts/Guest'
import Input from '@/Components/Input'
import Label from '@/Components/Label'
import ValidationErrors from '@/Components/ValidationErrors'

const props = defineProps({
    canResetPassword: Boolean,
    status: String,
})

const form = useForm({
    email: '',
    password: '',
    remember: false
})

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    })
}
</script>
