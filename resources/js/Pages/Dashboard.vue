<template>
    <AuthenticatedLayout title="Dashboard">
        <template #header>
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <h2 class="text-xl font-semibold leading-tight">
                    Dashboard
                </h2>

                <Button external variant="black" target="_blank" class="items-center max-w-xs gap-2" v-slot="{ iconSizeClasses }" href="https://github.com/kamona-wd/kui-laravel-breeze">
                    <GithubIcon aria-hidden="true" :class="iconSizeClasses" />
                    <span>Star on Github</span>
                </Button>
            </div>
        </template>

        <div class="p-6 overflow-hidden bg-white rounded-md shadow-md dark:bg-dark-eval-1">
            You're logged in!!!
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/Authenticated'
import Button from '@/Components/Button'
import { GithubIcon } from '@/Components/Icons/brands'
</script>
