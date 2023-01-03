<template>
    <!-- Mobile bottom bar -->
    <div
        :class="[
            'fixed inset-x-0 bottom-0 flex items-center justify-between px-4 py-4 sm:px-6 transition-transform duration-500 bg-white md:hidden dark:bg-dark-eval-1',
            {
                'translate-y-full': scrolling.down,
                'translate-y-0': scrolling.up,
            },
        ]"
    >
        <Link :href="route('clienti.index')">
            <ApplicationLogo class="w-10 h-10 text-green-400" />
            <span class="sr-only">K UI</span>
        </Link>

        <Button
            iconOnly
            variant="secondary"
            type="button"
            @click="toggleDarkMode"
            v-slot="{ iconSizeClasses }"
            class="md:inline-flex"
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

        <Button
            iconOnly
            variant="secondary"
            type="button"
            @click="sidebarState.isOpen = !sidebarState.isOpen"
            v-slot="{ iconSizeClasses }"
            class="md:hidden"
            srText="Search"
        >
            <MenuIcon
                v-show="!sidebarState.isOpen"
                aria-hidden="true"
                :class="iconSizeClasses"
            />
            <XIcon
                v-show="sidebarState.isOpen"
                aria-hidden="true"
                :class="iconSizeClasses"
            />
        </Button>
    </div>
</template>

<script setup>
import { onMounted, onUnmounted } from 'vue'
import { Link } from '@inertiajs/inertia-vue3'
import { useFullscreen } from '@vueuse/core'
import {
    SunIcon,
    MoonIcon,
    SearchIcon,
    MenuIcon,
    XIcon,
    ArrowsExpandIcon
} from '@heroicons/vue/outline'
import {
    handleScroll,
    isDark,
    scrolling,
    toggleDarkMode,
    sidebarState,
} from '@/Composables'
import Button from '@/Components/Button'
import ApplicationLogo from '@/Components/ApplicationLogo'
import BreezeDropdown from '@/Components/Dropdown'
import BreezeDropdownLink from '@/Components/DropdownLink'
import { ArrowsInnerIcon } from '@/Components/Icons/outline'

const { isFullscreen, toggle: toggleFullScreen } = useFullscreen()

onMounted(() => {
    document.addEventListener('scroll', handleScroll)
})

onUnmounted(() => {
    document.removeEventListener('scroll', handleScroll)
})
</script>
