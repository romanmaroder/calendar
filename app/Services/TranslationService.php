<?php

namespace App\Services;

class TranslationService
{
    public static function forWelcomePage(): array
    {
        return [
            'login' => __('login.login'),
            'dashboard' => __('dashboard.dashboard_title'),
            'welcome' => __('welcome.welcome'),
            'register_button' => __('register.register_button'),
        ];
    }

    public static function forLoginPage(): array
    {
        return [
            'login_title' => __('login.login_title'),
            'login_description' => __('login.login_description'),
            'login' => __('login.login'),
            'forgot_password' => __('login.forgot_password'),
            'remember_me' => __('login.remember_me'),
            'password' => __('label.password'),
        ];
    }

    public static function forForgotPasswordPage(): array
    {
        return [
            'forgot_title' => __('forgot.forgot_title'),
            'forgot_description' => __('forgot.forgot_description'),
            'forgot_reset_link_button' => __('forgot.forgot_reset_link_button'),
            'forgot_back' => __('forgot.forgot_back'),
            'forgot_status' => __('forgot.forgot_status'),
            'email' => __('label.email'),
            'logIn' => __('label.logIn'),
        ];
    }

    public static function forResetPasswordPage(): array
    {
        return [
            'reset_title' => __('reset.reset_title'),
            'reset_description' => __('reset.reset_description'),
            'email' => __('label.email'),
            'password' => __('label.password'),
            'password_confirmation' => __('label.password_confirmation'),
            'reset_password' => __('button.reset_password'),
        ];
    }

    public static function forDashboardPage(): array
    {
        return [
            'dashboard' => __('dashboard.dashboard_title'),
        ];
    }

    public static function forRegistrationPage(): array
    {
        return [
            'register_title' => __('register.register_title'),
            'register_description' => __('register.register_description'),
            'register_create' => __('register.register_create'),
            'register_button' => __('register.register_button'),
            'register_have_account' => __('register.register_have_account'),
            'name' => __('label.name'),
            'email' => __('label.email'),
            'password' => __('label.password'),
            'password_confirmation' => __('label.password_confirmation'),
            'logIn' => __('label.logIn'),
        ];
    }

    public static function forSettingsProfilePage(): array
    {
        return [
            'profile_settings' => __('settings.profile_settings'),
            'profile_info' => __('settings.profile_info'),
            'profile_update' => __('settings.profile_update'),
            'profile_email_unverified' => __('settings.profile_email_unverified'),
            'profile_email_verification' => __('settings.profile_email_verification'),
            'profile_verification_link' => __('settings.profile_verification_link'),
            'profile_layout_title' => __('settings.profile_layout_title'),
            'profile_layout_description' => __('settings.profile_layout_description'),
            'name' => __('label.name'),
            'email' => __('label.email'),
            'save' => __('button.save'),
            'saved' => __('button.saved'),
            'layout' => [
                'profile' => __('label.profile'),
                'password' => __('label.password'),
                'appearance' => __('label.appearance'),
                'title' => __('settingsLayout.layout_title'),
                'description' => __('settingsLayout.layout_description'),
            ],
            'dialog' => [
                'dialog_title' => __('dialog.dialog_title'),
                'dialog_description' => __('dialog.dialog_description'),
                'profile_delete_account_title' => __('dialog.profile_delete_account_title'),
                'profile_delete_account_description' => __('dialog.profile_delete_account_description'),
                'profile_warning' => __('dialog.profile_warning'),
                'profile_warning_text' => __('dialog.profile_warning_text'),
                'delete_account' => __('button.delete_account'),
                'password' => __('label.password'),
                'cancel' => __('button.cancel'),
            ]
        ];
    }

    public static function forSettingsPasswordPage(): array
    {
        return [
            'password_new' => __('label.password_new'),
            'password_confirmation' => __('label.password_confirmation'),
            'password_current' => __('label.password_current'),
            'save_password' => __('button.save_password'),
            'saved' => __('button.saved'),
            'password_settings' => __('settings.password_settings'),
            'password_info' => __('settings.password_info'),
            'password_description' => __('settings.password_description'),
            'layout' => [
                'profile' => __('label.profile'),
                'password' => __('label.password'),
                'appearance' => __('label.appearance'),
                'title' => __('settingsLayout.layout_title'),
                'description' => __('settingsLayout.layout_description'),
            ],
        ];
    }

    public static function forSettingsAppearancePage(): array
    {
        return [
            'appearance_settings' => __('settings.appearance_settings'),
            'appearance_description' => __('settings.appearance_description'),
            'layout' => [
                'profile' => __('label.profile'),
                'password' => __('label.password'),
                'appearance' => __('label.appearance'),
                'title' => __('settingsLayout.layout_title'),
                'description' => __('settingsLayout.layout_description'),
            ],
            'tabs' => [
                'light' => __('tabs.light'),
                'dark' => __('tabs.dark'),
                'system' => __('tabs.system'),
            ]
        ];
    }

    public static function forRolesPage(): array
    {
        return [
            'title' => __('roles.index.title'),
            'table' => [
                'id' => __('roles.index.table.id'),
                'name' => __('roles.index.table.name'),
                'permissions' => __('roles.index.table.permissions'),
                'actions' => __('roles.index.table.actions'),
            ],
            'button' => [
                'create' => __('button.create'),
                'save' => __('button.save'),
                'cancel' => __('button.cancel'),
                'confirm' => __('button.confirm')
            ],
            'dialog' => [
                'roles_permission_question' => __('dialog.question'),
                'roles_permission_text' => __('dialog.delete_forever_text'),
                'roles_text' => __('dialog.roles_text'),
            ],
        ];
    }

    public static function forRolesCreatePage(): array
    {
        return [
            'title' => __('roles.create.title'),
            'roles' => __('roles.index.title'),
            'button' => [
                'save' => __('button.save'),
                'cancel' => __('button.cancel'),
            ],
            'placeholder' => [
                'name' => __('roles.create.placeholder.name'),
                'permissions' => __('roles.create.placeholder.permissions'),
            ]
        ];
    }

    public static function forRolesUpdatePage(): array
    {
        return [
            'title' => __('roles.update.title'),
            'roles' => __('roles.index.title'),
            'button' => [
                'save' => __('button.save'),
                'cancel' => __('button.cancel'),
            ],
        ];
    }

    public static function forPermissionsPage(): array
    {
        return [
            'title' => __('permissions.index.title'),
            'message' => __('permissions.index.message'),
            'table' => [
                'id' => __('permissions.index.table.id'),
                'name' => __('permissions.index.table.name'),
                'guard' => __('permissions.index.table.guard'),
                'actions' => __('permissions.index.table.actions'),
            ],
            'button' => [
                'create' => __('button.create'),
                'save' => __('button.save'),
                'cancel' => __('button.cancel'),
                'confirm' => __('button.confirm')
            ],
            'dialog' => [
                'roles_permission_question' => __('dialog.question'),
                'roles_permission_text' => __('dialog.delete_forever_text'),
                'permissions_text' => __('dialog.permissions_text'),
            ],
        ];
    }

    public static function forPermissionsCreatePage(): array
    {
        return [
            'title' => __('permissions.create.title'),
            'permissions' => __('permissions.index.title'),
            'message' => __('permissions.create.message'),
            'button' => [
                'save' => __('button.save'),
                'cancel' => __('button.cancel'),
            ],
            'placeholder' => [
                'name' => __('permissions.create.placeholder.name'),
            ],
            'toast' => [
                'create' => __('toast.create'),
                'update' => __('toast.update'),
            ]
        ];
    }

    public static function forPermissionsUpdatePage(): array
    {
        return [
            'title' => __('permissions.update.title'),
            'permissions' => __('permissions.index.title'),
            'message' => __('permissions.update.message'),
            'button' => [
                'cancel' => __('button.cancel'),
                'update' => __('button.update'),
            ],
            'toast' => [
                'create' => __('toast.create'),
                'update' => __('toast.update'),
            ]
        ];
    }

    public static function forCompanyPage(): array
    {
        return [
            'title' => __('company.index.title'),
            'label' => [
                'archive' => __('label.archive'),
                'name' => __('label.name'),
                'contact' => __('label.contact'),
                'info' => __('label.info'),
                'country' => __('label.country'),
                'phone' => __('label.phone'),
                'main' => __('label.main'),
                'description' => __('label.description'),
                'branches' => __('label.branches'),
            ],
            'toolbar' => [
                'archive' => __('toolbar.archive'),
                'search' => __('toolbar.search'),
            ],
            'table' => [
                'id' => __('table.id'),
                'avatar' => __('table.avatar'),
                'name_' => __('table.name_'),
                'phone_country' => __('table.phone_country'),
                'info' => __('table.info'),
                'contact' => __('table.contact'),
                'description' => __('table.description'),
                'actions' => __('table.actions'),
                'empty_text' => __('table.empty_text'),
                'loading' => __('table.loading'),
            ],
            'button' => [
                'create' => __('button.create'),
                'restore' => __('button.restore'),
                'confirm' => __('button.confirm'),
                'cancel' => __('button.cancel'),
                'save' => __('button.save'),
                'saving' => __('button.saving'),
                'edit' => __('button.edit'),
            ],
            'dialog' => [
                'question' => __('dialog.question'),
                'delete_forever_text' => __('dialog.delete_forever_text'),
                'delete_to_basket_text' => __('dialog.delete_to_basket_text')
            ],
            'toast' => [
                'info_message' => __('toast.info_message'),
                'error_message' => __('toast.error_message'),
            ],

        ];
    }

    public static function forCompanyCreatePage(): array
    {
        return [
            'title' => __('company.create.title'),
            'companies' => __('company.index.title'),
            'button' => [
                'save' => __('button.save'),
                'saving' => __('button.saving'),
                'cancel' => __('button.cancel'),
            ],
            'toast' => [
                'create' => __('toast.create'),
                'update' => __('toast.update'),
            ],
            'label' => [
                'name' => __('label.name'),
                'contact' => __('label.contact'),
                'info' => __('label.info'),
                'country' => __('label.country'),
                'phone' => __('label.phone'),
                'main' => __('label.main'),
                'description' => __('label.description'),
            ]
        ];
    }

    public static function forCompanyUpdatePage(): array
    {
        return [
            'title' => __('company.update.title'),
            'companies' => __('company.index.title'),
            'button' => [
                'save' => __('button.save'),
                'saving' => __('button.saving'),
                'cancel' => __('button.cancel'),
            ],
            'toast' => [
                'create' => __('toast.create'),
                'update' => __('toast.update'),
            ],
            'label' => [
                'name' => __('label.name'),
                'contact' => __('label.contact'),
                'info' => __('label.info'),
                'country' => __('label.country'),
                'phone' => __('label.phone'),
                'main' => __('label.main'),
                'description' => __('label.description'),
            ]
        ];
    }

    public static function forCompanyShowPage(): array
    {
        return [
            'companies' => __('company.index.title'),
            'button' => [
                'edit' => __('button.edit'),
            ],
            'label' => [
                'archive' => __('label.archive'),
                'name' => __('label.name'),
                'contact' => __('label.contact'),
                'info' => __('label.info'),
                'country' => __('label.country'),
                'phone' => __('label.phone'),
                'main' => __('label.main'),
                'description' => __('label.description'),
                'branches' => __('label.branches'),
            ],
            'toast' => [
                'route_not_found' => __('toast.route_not_found')
            ],
        ];
    }

    public static function forBranchPage(): array
    {
        return [
            'title' => __('branch.index.title'),
            'label' => [
                'archive' => __('label.archive'),
                'name' => __('label.name'),
                'contact' => __('label.contact'),
                'info' => __('label.info'),
                'country' => __('label.country'),
                'phone' => __('label.phone'),
                'main' => __('label.main'),
                'description' => __('label.description'),
            ],
            'toolbar' => [
                'archive' => __('toolbar.archive'),
                'search' => __('toolbar.search'),
            ],
            'table' => [
                'id' => __('table.id'),
                'avatar' => __('table.avatar'),
                'name_' => __('table.name_'),
                'info' => __('table.info'),
                'phone' => __('table.phone'),
                'status_users' => __('table.status_users'),
                'contact' => __('table.contact'),
                'actions' => __('table.actions'),
                'empty_text' => __('table.empty_text'),
                'loading' => __('table.loading'),
            ],
            'button' => [
                'create' => __('button.create'),
                'restore' => __('button.restore'),
                'confirm' => __('button.confirm'),
                'cancel' => __('button.cancel'),
                'save' => __('button.save'),
                'saving' => __('button.saving'),
                'edit' => __('button.edit'),
            ],
            'dialog' => [
                'question' => __('dialog.question'),
                'delete_forever_text' => __('dialog.delete_forever_text'),
                'delete_to_basket_text' => __('dialog.delete_to_basket_text'),
                'branches_warning_text_dialog' => __('dialog.branches_warning_text_dialog'),
            ],
            'toast' => [
                'info_message' => __('toast.info_message'),
                'error_message' => __('toast.error_message'),
                'failed_to_load_users'=>__('toast.failed_to_load_users'),
            ],

        ];
    }

    public static function forBranchCreatePage(): array
    {
        return [
            'title' => __('branch.create.title'),
            'branches' => __('branch.index.title'),
            'button' => [
                'save' => __('button.save'),
                'saving' => __('button.saving'),
                'cancel' => __('button.cancel'),
            ],
            'toast' => [
                'create' => __('toast.create'),
                'update' => __('toast.update'),
            ],
            'label' => [
                'name' => __('label.name'),
                'contact' => __('label.contact'),
                'info' => __('label.info'),
                'country' => __('label.country'),
                'phone' => __('label.phone'),
                'main' => __('label.main'),
                'description' => __('label.description'),
                'company' => __('label.company'),
                'status' => __('label.status'),
            ]
        ];
    }

    public static function forBranchUpdatePage(): array
    {
        return [
            'title' => __('branch.update.title'),
            'branches' => __('branch.index.title'),
            'button' => [
                'save' => __('button.save'),
                'saving' => __('button.saving'),
                'cancel' => __('button.cancel'),
            ],
            'toast' => [
                'create' => __('toast.create'),
                'update' => __('toast.update'),
            ],
            'label' => [
                'name' => __('label.name'),
                'contact' => __('label.contact'),
                'info' => __('label.info'),
                'country' => __('label.country'),
                'phone' => __('label.phone'),
                'main' => __('label.main'),
                'description' => __('label.description'),
                'company' => __('label.company'),
                'status' => __('label.status'),
            ]
        ];
    }

    public static function forBranchShowPage(): array
    {
        return [
            'branches' => __('branch.index.title'),
            'button' => [
                'edit' => __('button.edit'),
                'cancel' => __('button.cancel'),
                'unsubscribe' => __('button.unsubscribe'),
            ],
            'label' => [
                'archive' => __('label.archive'),
                'name' => __('label.name'),
                'contact' => __('label.contact'),
                'info' => __('label.info'),
                'country' => __('label.country'),
                'phone' => __('label.phone'),
                'main' => __('label.main'),
                'description' => __('label.description'),
                'branches' => __('label.branches'),
                'company' => __('label.company'),
                'status' => __('label.status'),
            ],
            'table' => [
                'name' => __('table.name'),
                'phone' => __('table.phone'),
                'actions' => __('table.actions'),
                'empty_text' => __('table.empty_text'),
                'loading' => __('table.loading'),
            ],
            'dialog' => [
                'question' => __('dialog.question'),
                'unsubscribe' => __('dialog.unsubscribe'),
            ],
            'toast' => [
                'route_not_found' => __('toast.route_not_found'),
                'info_message' => __('toast.info_message'),
                'error_message' => __('toast.error_message'),
                'failed_to_load_users'=>__('toast.failed_to_load_users'),
            ],
        ];
    }

    public static function forUserPage(): array
    {
        return [
            'title' => __('user.index.title'),
            'label' => [
                'archive' => __('label.archive'),
                'name' => __('label.name'),
                'surname' => __('label.surname'),
                'middlename' => __('label.middlename'),
                'branches' => __('label.branches'),
                'contact' => __('label.contact'),
                'info' => __('label.info'),
                'country' => __('label.country'),
                'phone' => __('label.phone'),
                'main' => __('label.main'),
                'description' => __('label.description'),
            ],
            'toolbar' => [
                'archive' => __('toolbar.archive'),
                'search' => __('toolbar.search'),
            ],
            'table' => [
                'id' => __('table.id'),
                'avatar' => __('table.avatar'),
                'name' => __('table.name'),
                'info' => __('table.info'),
                'phone_email' => __('table.phone_email'),
                'email' => __('table.email'),
                'birthday' => __('table.birthday'),
                'comment' => __('table.comment'),
                'actions' => __('table.actions'),
                'empty_text' => __('table.empty_text'),
                'loading' => __('table.loading'),
                'roles' => __('table.roles'),
                'no_roles' => __('table.no_roles'),
            ],
            'button' => [
                'create' => __('button.create'),
                'restore' => __('button.restore'),
                'confirm' => __('button.confirm'),
                'cancel' => __('button.cancel'),
                'save' => __('button.save'),
                'saving' => __('button.saving'),
                'edit' => __('button.edit'),
            ],
            'dialog' => [
                'question' => __('dialog.question'),
                'delete_forever_text' => __('dialog.delete_forever_text'),
                'delete_to_basket_text' => __('dialog.delete_to_basket_text'),
            ],
            'toast' => [
                'info_message' => __('toast.info_message'),
                'error_message' => __('toast.error_message'),
            ],

        ];
    }

    public static function forUserCreatePage(): array
    {
        return [
            'title' => __('user.create.title'),
            'users' => __('user.index.title'),
            'button' => [
                'save' => __('button.save'),
                'saving' => __('button.saving'),
                'cancel' => __('button.cancel'),
            ],
            'toast' => [
                'create' => __('toast.create'),
                'update' => __('toast.update'),
                'route_not_found' => __('toast.route_not_found')
            ],
            'label' => [
                'name' => __('label.name'),
                'surname' => __('label.surname'),
                'middlename' => __('label.middlename'),
                'branches' => __('label.branches'),
                'contact' => __('label.contact'),
                'info' => __('label.info'),
                'country' => __('label.country'),
                'phone' => __('label.phone'),
            ],
            'message' => __('user.message'),
            'table' => [
                'roles' => __('table.roles'),
                'birthday' => __('table.birthday'),
                'comment' => __('table.comment'),
            ],

        ];
    }

    public static function forUserUpdatePage(): array
    {
        return [
            'title' => __('user.update.title'),
            'users' => __('user.index.title'),
            'button' => [
                'save' => __('button.save'),
                'saving' => __('button.saving'),
                'cancel' => __('button.cancel'),
            ],
            'toast' => [
                'create' => __('toast.create'),
                'update' => __('toast.update'),
            ],
            'label' => [
                'name' => __('label.name'),
                'surname' => __('label.surname'),
                'middlename' => __('label.middlename'),
                'contact' => __('label.contact'),
                'info' => __('label.info'),
                'country' => __('label.country'),
                'phone' => __('label.phone'),
                'main' => __('label.main'),
                'description' => __('label.description'),
                'company' => __('label.company'),
                'status' => __('label.status'),
            ],
            'table' => [
                'roles' => __('table.roles'),
                'birthday' => __('table.birthday'),
                'comment' => __('table.comment'),
            ],

        ];
    }

    public static function forUserShowPage(): array
    {
        return [
            'users' => __('user.index.title'),
            'button' => [
                'edit' => __('button.edit'),
                'cancel' => __('button.cancel'),
                'unsubscribe' => __('button.unsubscribe'),
            ],
            'label' => [
                'archive' => __('label.archive'),
                'name' => __('label.name'),
                'contact' => __('label.contact'),
                'info' => __('label.info'),
                'country' => __('label.country'),
                'phone' => __('label.phone'),
                'main' => __('label.main'),
                'description' => __('label.description'),
                'branches' => __('label.branches'),
                'company' => __('label.company'),
                'status' => __('label.status'),
            ],
            'table' => [
                'name' => __('table.name'),
                'phone' => __('table.phone'),
                'actions' => __('table.actions'),
                'empty_text' => __('table.empty_text'),
                'loading' => __('table.loading'),
            ],
            'toast' => [
                'route_not_found' => __('toast.route_not_found')
            ],
        ];
    }

    public static function forClientPage(): array
    {
        return [
            'title' => __('client.index.title'),
            'label' => [
                'archive' => __('label.archive'),
                'name' => __('label.name'),
                'surname' => __('label.surname'),
                'middlename' => __('label.middlename'),
                'info' => __('label.info'),
                'phone' => __('label.phone'),
            ],
            'toolbar' => [
                'archive' => __('toolbar.archive'),
                'search' => __('toolbar.search'),
            ],
            'table' => [
                'id' => __('table.id'),
                'avatar' => __('table.avatar'),
                'name' => __('table.name'),
                'info' => __('table.info'),
                'phone_email' => __('table.phone_email'),
                'email' => __('table.email'),
                'birthday' => __('table.birthday'),
                'comment' => __('table.comment'),
                'actions' => __('table.actions'),
                'empty_text' => __('table.empty_text'),
                'loading' => __('table.loading'),
                'blacklist' => __('table.blacklist'),
                'prepayment' => __('table.prepayment'),
                'total' => __('table.total'),
                'source' => __('table.source'),
                'discount' => __('table.discount'),
            ],
            'button' => [
                'create' => __('button.create'),
                'restore' => __('button.restore'),
                'confirm' => __('button.confirm'),
                'cancel' => __('button.cancel'),
                'save' => __('button.save'),
                'saving' => __('button.saving'),
                'edit' => __('button.edit'),
            ],
            'dialog' => [
                'question' => __('dialog.question'),
                'delete_forever_text' => __('dialog.delete_forever_text'),
                'delete_to_basket_text' => __('dialog.delete_to_basket_text'),
            ],
            'toast' => [
                'info_message' => __('toast.info_message'),
                'error_message' => __('toast.error_message'),
            ],
        ];
    }

    public static function forClientCreatePage(): array
    {
        return [
            'title' => __('client.create.title'),
            'clients' => __('client.index.title'),
            'button' => [
                'save' => __('button.save'),
                'saving' => __('button.saving'),
                'cancel' => __('button.cancel'),
            ],
            'toast' => [
                'create' => __('toast.create'),
                'update' => __('toast.update'),
            ],
            'label' => [
                'name' => __('label.name'),
                'surname' => __('label.surname'),
                'middlename' => __('label.middlename'),
                'phone' => __('label.phone'),
                'email' => __('label.email'),
            ],
            'table' => [
                'birthday' => __('table.birthday'),
                'comment' => __('table.comment'),
                'blacklist' => __('table.blacklist'),
                'prepayment' => __('table.prepayment'),
                'discount' => __('table.discount'),
                'source' => __('table.source'),
            ],
        ];
    }

    public static function forClientUpdatePage(): array
    {
        return [
            'title' => __('client.update.title'),
            'clients' => __('client.index.title'),
            'button' => [
                'save' => __('button.save'),
                'saving' => __('button.saving'),
                'cancel' => __('button.cancel'),
            ],
            'toast' => [
                'create' => __('toast.create'),
                'update' => __('toast.update'),
            ],
            'label' => [
                'name' => __('label.name'),
                'surname' => __('label.surname'),
                'middlename' => __('label.middlename'),
                'phone' => __('label.phone'),
                'email' => __('label.email'),
            ],
            'table' => [
                'birthday' => __('table.birthday'),
                'comment' => __('table.comment'),
                'blacklist' => __('table.blacklist'),
                'prepayment' => __('table.prepayment'),
                'discount' => __('table.discount'),
                'source' => __('table.source'),
            ],
        ];
    }

    public static function forClientShowPage(): array
    {
        return [
            'clients' => __('client.index.title'),
            'button' => [
                'edit' => __('button.edit'),
                'cancel' => __('button.cancel'),
            ],
            'label' => [
                'archive' => __('label.archive'),
                'phone' => __('label.phone'),
                'average_check' => __('label.average_check'),
                'calendar_entries' => __('label.calendar_entries')
            ],
            'table' => [
                'name' => __('table.name'),
                'phone' => __('table.phone'),
                'actions' => __('table.actions'),
                'empty_text' => __('table.empty_text'),
                'loading' => __('table.loading'),
                'birthday' => __('table.birthday'),
                'created_at' => __('table.created_at'),
                'comment' => __('table.comment'),
                'source' => __('table.source'),
                'total' => __('table.total'),
            ],
            'toast' => [
                'route_not_found' => __('toast.route_not_found')
            ],
        ];
    }

    public static function forSidebar(): array
    {
        return [
            'nav_company' => __('nav.company'),
            'nav_branches' => __('nav.branches'),
            'nav_users' => __('nav.users'),
            'nav_clients' => __('nav.clients'),
            'nav_roles' => __('nav.roles'),
            'nav_permissions' => __('nav.permissions'),
            'nav_settings' => __('nav.settings'),
            'nav_logout' => __('nav.logOut'),

            'group_company' => __('group.company'),
            'group_users' => __('group.users'),
            'group_clients' => __('group.clients'),
            'group_roles' => __('group.roles'),
            'group_permissions' => __('group.permissions'),
        ];
    }


}