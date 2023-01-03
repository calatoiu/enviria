<template>
    <PerfrectScrollbar
        tagname="nav"
        aria-label="main"
        class="relative flex flex-col flex-1 max-h-full gap-4 px-3"
    >
        <SidebarLink
            title="Clienți"
            :href="route('clienti.index')"
            :active="route().current('clienti.index')"
        >
            <template #icon>
                <ClientsListIcon
                    class="flex-shrink-0 w-6 h-6"
                    aria-hidden="true"
                />
            </template>
        </SidebarLink>

        <SidebarLink
            title="Facturi"
            :href="route('facturi.index')"
            :active="route().current('facturi.index')"
        >
            <template #icon>
                <FacturiListIcon
                    class="flex-shrink-0 w-6 h-6"
                    aria-hidden="true"
                />
            </template>
        </SidebarLink>
        <SidebarCollapsible title="Security">
            <SidebarCollapsibleItem :href="route('users.index')" title="Users" :active="route().current('users.index')" />
            <SidebarCollapsibleItem href="#" title="Link 2" :active="true" />
            <SidebarCollapsibleItem href="#" title="Link 3" :active="false" />
        </SidebarCollapsible>

        <SidebarLink
            :title=  $page.props.auth.user.name
            :href="route('logout')"
            method="post"
            as="button"
        >
            <template #icon>
                <UserIcon
                    class="flex-shrink-0 w-6 h-6"
                    aria-hidden="true"
                />
            </template>
        </SidebarLink>

        <!-- Examples -->
        <!--
        => External link example
        <SidebarLink
            title="Github"
            href="https://github.com/kamona-wd/kui-laravel-breeze"
            external
            target="_blank"
        >
        </SidebarLink>

        => Collapsible examples
        <SidebarCollapsible title="Users" :active="$page.url.startsWith('/users')">
            <SidebarCollapsibleItem :href="route('users.index')" title="List" :active="$page.url === '/users/index'" />
            <SidebarCollapsibleItem :href="route('users.create')" title="Create" :active="$page.url === '/users/create'" />
        </SidebarCollapsible>

        <SidebarCollapsible title="Users" :active="usePage().url.value.startsWith('/users')">
            <template #icon>
                <UserIcon
                    class="flex-shrink-0 w-6 h-6"
                    aria-hidden="true"
                />
            </template>

            <SidebarCollapsibleItem :href="route('users.index')" title="List" :active="route().current('users.index')" />
            <SidebarCollapsibleItem :href="route('users.create')" title="Create" :active="route().current('users.create')" />
        </SidebarCollapsible> -->
    </PerfrectScrollbar>
</template>

<script setup>
import PerfrectScrollbar from '@/Components/PerfectScrollbar'
import SidebarLink from '@/Components/Sidebar/SidebarLink'
import {FacturiListIcon, ClientsListIcon, UserIcon } from '@/Components/Icons/outline'
import SidebarCollapsible from '@/Components/Sidebar/SidebarCollapsible'
import SidebarCollapsibleItem from '@/Components/Sidebar/SidebarCollapsibleItem'
</script>
