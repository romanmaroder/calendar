//Базовые поля на всех страницах
export interface BaseTableTranslations {
    id?: string;
    avatar?: string;
    name_?: string;
    name?: string;
    contact?: string;
    phone?: string;
    actions?: string;
    empty_text?: string;
    loading?: string;
    info?: string;
    email?: string;
    comment?:string;
    birthday?:string;
    created_at?: string;
}

export interface BasePageTranslations {
    title?: string;
}

export interface TabsTranslations {
    light: string;
    dark: string;
    system: string;
}

export interface MenuTranslations {
    profile: string;
    password: string;
    appearance: string;
}

/** Общие поля, которые есть на всех страницах настроек */
export interface BaseTranslations {
    menu: MenuTranslations;
    tabs: TabsTranslations;
}

/** Переводы страницы внешнего вида */
export interface AppearanceTranslations extends BaseTranslations {
    appearance_settings: string;
    appearance_description: string;
    layout: LayoutTranslations;
}

/** Переводы страницы пароля */
export interface PasswordTranslations extends BaseTranslations {
    password_settings: string;
    password_current: string;
    password_new: string;
    password_confirmation: string;
    password_info: string;
    password_description: string;
    save_password: string;
    saved: string;
    layout: LayoutTranslations;
}

/** Переводы страницы профиля */
export interface ProfileTranslations {
    profile_settings: string;
    profile_info: string;
    profile_update: string;
    profile_email_unverified: string;
    profile_email_verification: string;
    profile_verification_link: string;
    profile_layout_title: string;
    profile_layout_description: string;
    dialog_title: string;
    dialog_description: string;
    name: string;
    email: string;
    save: string;
    saved: string;
    layout: LayoutTranslations;
    dialog: DialogTranslations;
}

export interface DialogTranslations {
    dialog_title?: string;
    dialog_description?: string;

    // общие для удалений/подтверждений
    question?: string;
    delete_forever_text?: string;
    delete_to_basket_text?: string;

    // специфичные
    profile_delete_account_title?: string;
    profile_delete_account_description?: string;
    profile_warning?: string;
    profile_warning_text?: string;
    branches_warning_text_dialog?: string;
    unsubscribe: string;

    roles_permission_text?: string;
    roles_text?: string;
    permissions_text?: string;

    delete_account?: string;
    password?: string;
    cancel?: string;
}

// Для DeleteConfirmation
export interface DeleteDialogTranslations {
    dialog?: DialogTranslations;
    button?: Buttons;
    toast?:ToastTranslations;
}
export interface RestoreTranslations {
    toast?:ToastTranslations;
}

/** Лейаут — ему нужны menu + заголовки */
export interface LayoutTranslations {
    profile: string;
    password: string;
    appearance: string;
    title: string;
    description: string;
}

export interface RolesTableTranslations extends BaseTableTranslations {
    permissions?: string;
}

export interface RolesTranslations extends BasePageTranslations {
    table: RolesTableTranslations;
    button: Buttons;
    roles: string;
    placeholder: {
        name: string;
        permissions: string;
    };
    dialog: DialogTranslations;
}

export interface PermissionsTableTranslations extends BaseTableTranslations {
    guard?: string;
}

export interface PermissionTranslations {
    title: string;
    message: string;
    table: PermissionsTableTranslations;
    button: Buttons;
    permissions: string;
    placeholder: {
        name: string;
        permissions: string;
    };
    dialog: DialogTranslations;
    toast: ToastTranslations;
}

export interface CompanyTableTranslations extends BaseTableTranslations {
    phone_country?: string;
    description?: string;
}

export interface CompanyTranslations extends BasePageTranslations {
    title: string;
    companies: string;
    toolbar: ToolbarTranslations;
    button: Buttons;
    table: CompanyTableTranslations;
    dialog: DialogTranslations;
    toast: ToastTranslations;
    label: LabelTranslations;
}

export interface BranchTableTranslations extends BaseTableTranslations {
    status_users?: string;
}

export interface BranchTranslations extends BasePageTranslations {
    title: string;
    branches: string;
    toolbar: ToolbarTranslations;
    button: Buttons;
    table: BranchTableTranslations;
    dialog: DialogTranslations;
    toast: ToastTranslations;
    label: LabelTranslations;
}

export interface UserTableTranslations extends BaseTableTranslations {
    phone_email?: string;
    roles?: string;
    no_roles?:string;
}

export interface UserTranslations extends BasePageTranslations {
    title: string;
    users:string;
    message:string;
    toolbar: ToolbarTranslations;
    button: Buttons;
    table: UserTableTranslations;
    dialog: DialogTranslations;
    toast: ToastTranslations;
    label: LabelTranslations;
}

export interface ClientTableTranslations extends BaseTableTranslations {
    phone_email?: string;
    discount?:string;
    blacklist?:string;
    prepayment?:string;
    source?:string;
    total:string;
}
export interface ClientLabelTranslations extends LabelTranslations {
    average_check:string;
    calendar_entries:string;
}

export interface ClientTranslations extends BasePageTranslations {
    title: string;
    clients:string;
    message:string;
    toolbar: ToolbarTranslations;
    button: Buttons;
    table: ClientTableTranslations;
    dialog: DialogTranslations;
    toast: ToastTranslations;
    label: ClientLabelTranslations;
}

export interface LabelTranslations {
    password: string;
    password_new: string;
    password_confirmation: string;
    password_current: string;
    email: string;
    logIn: string;
    name: string;
    name_: string;
    middlename: string;
    surname: string;
    contact: string;
    info: string;
    country: string;
    phone: string;
    main: string;
    description: string;
    profile: string;
    appearance: string;
    branches: string;
    archive: string;
    company: string;
    status: string;
}

export interface ToolbarTranslations {
    archive: string;
    search: string;
}

export interface ToastTranslations {
    create?: string;
    update?: string;
    has_been_deleted?:string;
    move_to_the_basket?:string;
    has_been_restored?:string;
    route_not_found?:string;
    info_message?:string;
    error_message?:string;
    insufficient_rights_to_create_a_user?:string;
    failed_to_load_users?:string;
}

export interface Buttons {
    save?: string;
    saved?: string;
    saving?: string;
    save_password?: string;
    cancel?: string;
    delete?: string;
    delete_account?: string;
    edit?: string;
    update?: string;
    create?: string;
    create_account?: string;
    back?: string;
    confirm?: string;
    reset_password?: string;
    reset?: string;
    restore?: string;
    close?: string;
    unsubscribe?: string;
}
