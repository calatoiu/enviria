<template>
    <div class="relative flex flex-col gap-4 px-3">
        <!-- Toggle Dark Mode -->
        <Button
            iconOnly
            variant="secondary"
            type="button"
            @click="toggleDarkMode"
            v-slot="{ iconSizeClasses }"
            srText="Toggle dark mode"
        >
            <MoonIcon
                v-show="!isDark"
                aria-hidden="true"
                :class="iconSizeClasses"
            />
            <SunIcon
                v-show="isDark"
                aria-hidden="true"
                :class="iconSizeClasses"
            />
        </Button>

        <!-- Close / Open Side Bar -->
        <Button
            :class="['lg:hidden']"
            iconOnly
            variant="secondary"
            type="button"
            v-slot="{ iconSizeClasses }"
            @click="sidebarState.isOpen = !sidebarState.isOpen"
            :srText="sidebarState.isOpen ? 'Close sidebar' : 'Open sidebar'"
        >
            <MenuFoldLineLeftIcon
                aria-hidden="true"
                v-show="sidebarState.isOpen"
                :class="[iconSizeClasses]" />

            <MenuFoldLineRightIcon
                aria-hidden="true"
                v-show="!sidebarState.isOpen"
                :class="[iconSizeClasses]" />
        </Button>
    </div>
</template>

<script setup>
import Button from '@/Components/Button'
import { MenuFoldLineLeftIcon, MenuFoldLineRightIcon,
} from '@/Components/Icons/outline'
import {
    SunIcon,
    MoonIcon,
} from '@heroicons/vue/outline'
import { sidebarState, toggleDarkMode, isDark, } from '@/Composables'
</script>
