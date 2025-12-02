<script setup lang="ts">
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/vue3';
import { BookOpen, Folder, UserRoundXIcon, Users2, UsersIcon, UserX } from 'lucide-vue-next';
import AppLogo from './AppLogo.vue';

const mainNavItemsUser: NavItem[] = [
    {
        title: 'Users',
        href: '/users',
        icon: UsersIcon,
    },
    {
        title: 'Users trash ',
        href: '/users/archive',
        icon: UserX,
    },
];
const mainNavItemsClient: NavItem[] = [
    {
        title: 'Clients',
        href: '/clients/index',
        icon: Users2,
    },
    {
        title: 'Clients trash ',
        href: '/archive',
        icon: UserRoundXIcon,
    },
];

const footerNavItems: NavItem[] = [
    {
        title: 'Github Repo',
        href: 'https://github.com/laravel/vue-starter-kit',
        icon: Folder,
    },
    {
        title: 'Documentation',
        href: 'https://laravel.com/docs/starter-kits',
        icon: BookOpen,
    },
];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="route('dashboard')">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>
        <SidebarContent>
            <NavMain :items="mainNavItemsUser" group-label="Users" />
            <NavMain :items="mainNavItemsClient" group-label="Clients" />
        </SidebarContent>

        <SidebarFooter>
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
