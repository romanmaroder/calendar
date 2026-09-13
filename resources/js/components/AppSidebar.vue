<script setup lang="ts">
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import { BookOpen, Building2Icon, Folder, MapPinHouse, Shield, ShieldAlert, Users2, UsersIcon } from 'lucide-vue-next';
import AppLogo from './AppLogo.vue';

const page = usePage();
const userPermissions = page.props.auth?.user?.permissions ?? [];
const translations = page.props.appSidebarTranslations as Record<string, string>

const hasPermission = (perm: string): boolean => userPermissions.includes(perm);

const mainNavItemCompany: NavItem[] = [
    {
        title: translations.nav_company,
        href: '/company',
        icon: Building2Icon,
    },
    {
        title: translations.nav_branches,
        href: '/branch',
        icon: MapPinHouse,
    },
];
const mainNavItemsUser: NavItem[] = [
    {
        title: translations.nav_users,
        href: '/users',
        icon: UsersIcon,
    },
];
const mainNavItemsClient: NavItem[] = [
    {
        title: translations.nav_clients,
        href: '/clients',
        icon: Users2,
    },
];
const mainNavItemsRoles: NavItem[] = [
    {
        title: translations.nav_roles,
        href: route('admin.roles.index'),
        icon: Shield,
    },
];

const mainNavItemsPermissions: NavItem[] = [
    {
        title: translations.nav_permissions,
        href: route('admin.permissions.index'),
        icon: ShieldAlert,
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
            <template v-if="hasPermission('companies.view')">
                <NavMain :items="mainNavItemCompany" :group-label="translations.group_company" />
            </template>

            <template v-if="hasPermission('users.view')">
                <NavMain :items="mainNavItemsUser" :group-label="translations.group_users" />
            </template>
            <template v-if="hasPermission('clients.view')">
                <NavMain :items="mainNavItemsClient" :group-label="translations.group_clients" />
            </template>
            <!-- Показываем только при наличии разрешения roles.view -->
            <template v-if="hasPermission('roles.view')">
                <NavMain :items="mainNavItemsRoles" :group-label="translations.group_roles" />
            </template>

            <!-- Показываем только при наличии разрешения permissions.view -->
            <template v-if="hasPermission('permissions.view')">
                <NavMain :items="mainNavItemsPermissions" :group-label="translations.group_permissions" />
            </template>
        </SidebarContent>

        <SidebarFooter>
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
