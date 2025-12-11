import type { PageProps } from '@inertiajs/core';
import type { LucideIcon } from 'lucide-vue-next';
import type { Config } from 'ziggy-js';

export interface Auth {
    user: User;
}

export interface BreadcrumbItem {
    title: string;
    href: string;
}

export interface NavItem {
    title: string;
    href: string;
    icon?: LucideIcon;
    isActive?: boolean;
}

export interface SharedData extends PageProps {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
    ziggy: Config & { location: string };
    sidebarOpen: boolean;
}

export interface User {
    id: string | number;
    name: string;
    surname?: string;
    middleName?: string;
    phone?: string;
    comment?:  string;
    birthday?: string;
    email: string;
    avatar?: string;
    branch_id?: string | number;
    branch?: Branch;
    email_verified_at?: string | null;
    created_at?: string;
    updated_at?: string;
    deleted_at?: string;
    [key: string]: any;
}
export interface Client {
    id: number;
    name: string;
    surname?: string;
    middleName?: string;
    phone?: string;
    comment?:  string;
    email: string;
    avatar?: string;
    blacklist?: boolean;
    prepayment?: boolean;
    discount?: number;
    records?: number;
    total?: number;
    source?: string;
    created_at: string;
    updated_at: string;
    deleted_at?: string;
}

export interface Branch {
    id: number;
    name: string;
    description: string;
    contact: string;
    logo: null | string;
    active: number; // или boolean, если возможно
    created_at: string;
    updated_at: string;
}

export type BreadcrumbItemType = BreadcrumbItem;
