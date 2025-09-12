import '../css/app.css';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import type { DefineComponent } from 'vue';
import { createApp, h } from 'vue';
import { ZiggyVue } from 'ziggy-js';
import { initializeTheme } from './composables/useAppearance';
import { definePreset } from '@primeuix/themes';
import Aura from '@primeuix/themes/aura';
import PrimeVue from 'primevue/config';
import ToastService from 'primevue/toastservice';
import { en } from "primelocale/js/en.js";
import { ru } from "primelocale/js/ru.js";
import.meta.glob(['../img/**']);

const MyPreset = definePreset(Aura, {
    semantic: {
        primary: {
            50: '{blue.50}',
            100: '{blue.100}',
            200: '{blue.200}',
            300: '{blue.300}',
            400: '{blue.400}',
            500: '{blue.500}',
            600: '{blue.600}',
            700: '{blue.700}',
            800: '{blue.800}',
            900: '{blue.900}',
            950: '{blue.950}',
        },
        warning: {
            50: '{orange.50}',
            100: '{orange.100}',
            200: '{orange.200}',
            300: '{orange.300}',
            400: '{orange.400}',
            500: '{orange.500}',
            600: '{orange.600}',
            700: '{orange.700}',
            800: '{orange.800}',
            900: '{orange.900}',
            950: '{orange.950}',
        },
        /*formField: {
            paddingX:"0.5rem",
            paddingY:"0.5rem"
        },
        myButton: {
            paddingX: '0.3rem',
            paddingY: '0.9rem',
        },*/
        colorScheme: {
            light: {
                formField: {
                    hoverBorderColor: '{primary.color}',
                },
            },
            dark: {},
        },
    },
    components: {
        button: {
            root: {
                paddingX: '.75rem',
                paddingY: '.5rem',
                borderRadius: '4px',
                sm: {
                    fontSize: '0.875rem',
                },
                lg: {
                    fontSize: '1rem',
                },
            },
            colorScheme: {
                light: {
                    root: {
                        primary: {
                            background: 'rgb(68, 114, 194)',
                            hoverBackground: 'rgba(63, 92, 136, 1);',
                            borderColor: 'rgb(68, 114, 194)',
                            hoverBorderColor: 'rgba(63, 92, 136, 1);',
                            hoverColor: 'rgba(34, 34, 34,1)',
                            focusRing: {
                                color: 'rgb(68, 114, 194)',
                            },
                        },
                        secondary: {
                            background: '{stone.200}',
                            hoverBackground: '{stone.300}',
                            activeBackground: '{stone.400}',
                            borderColor: '{stone.200}',
                            hoverBorderColor: '{stone.300}',
                            activeBorderColor: '{stone.400}',
                            color: '{stone.600}',
                            hoverColor: '{stone.700}',
                            activeColor: '{stone.800}',
                            focusRing: { color: '{stone.600}', shadow: 'none' }
                        },
                        danger: {
                            background: 'rgba(181, 50, 50, 1)',
                            hoverBackground: 'rgba(103, 22, 22, 1)',
                            activeBackground: '{red.600}',
                            borderColor: 'rgba(181, 50, 50, 1)',
                            hoverBorderColor: 'rgba(103, 22, 22, 1)',
                            activeBorderColor: '{red.600}',
                            color: 'rgba(255, 255, 255, 1)',
                            hoverColor: 'rgba(255, 255, 255, 1)',
                            activeColor: '{red.950}',
                            focusRing: { color: 'rgba(181, 50, 50, 1)', shadow: 'none' },
                        },
                        success: {
                            background: 'rgba(52, 145, 54, 1)',
                            hoverBackground: 'rgba(49, 119, 51, 1)',
                            activeBackground: 'rgba(52, 145, 54, .8)',
                            borderColor: 'rgba(52, 145, 54, 1)',
                            hoverBorderColor: 'rgba(49, 119, 51, 1)',
                            activeBorderColor: 'rgba(52, 145, 54, .8)',
                            color: 'rgba(255, 255, 255, 1)',
                            hoverColor: 'rgba(255, 255, 255, 1)',
                            activeColor: 'rgba(255, 255, 255, 1)',
                            focusRing: { color: 'rgba(52, 145, 54, 1)', shadow: 'none' },
                        },
                    },
                },
                dark: {
                    root: {
                        primary: {
                            background: 'rgb(68, 114, 194)',
                            hoverBackground: '#3f5c88',
                            borderColor: 'rgb(68, 114, 194)',
                            hoverBorderColor: '#3f5c88',
                            color: 'rgba(255, 255, 255, 1)',
                            focusRing: {
                                color: 'rgb(68, 114, 194)',
                            },
                        },
                        secondary: {
                            background: '{stone.700}',
                            hoverBackground: '{stone.600}',
                            activeBackground: '{stone.500}',
                            borderColor: '{stone.700}',
                            hoverBorderColor: '{stone.600}',
                            activeBorderColor: '{stone.500}',
                            color: '{stone.300}',
                            hoverColor: '{stone.200}',
                            activeColor: '{stone.100}',
                            focusRing: { color: '{stone.300}', shadow: 'none' }
                        },
                        danger: {
                            background: 'rgba(181, 50, 50, 1)',
                            hoverBackground: 'rgba(103, 22, 22, 1)',
                            activeBackground: '{red.200}',
                            borderColor: 'rgba(181, 50, 50, 1)',
                            hoverBorderColor: 'rgba(103, 22, 22, 1)',
                            activeBorderColor: '{red.200}',
                            color: 'rgba(255, 255, 255, 1)',
                            hoverColor: 'rgba(255, 255, 255, 1)',
                            activeColor: '{red.950}',
                            focusRing: { color: 'rgba(181, 50, 50, 1)', shadow: 'none' },
                        },
                        success: {
                            background: 'rgba(52, 145, 54, 1)',
                            hoverBackground: 'rgba(49, 119, 51, 1)',
                            activeBackground: 'rgba(52, 145, 54, .6)',
                            borderColor: 'rgba(52, 145, 54, 1)',
                            hoverBorderColor: 'rgba(49, 119, 51, 1)',
                            activeBorderColor: 'rgba(52, 145, 54, .6)',
                            color: 'rgba(255, 255, 255, 1)',
                            hoverColor: 'rgba(255, 255, 255, 1)',
                            activeColor: 'rgba(255, 255, 255, 1)',
                            focusRing: { color: 'rgba(52, 145, 54, 1)', shadow: 'none' },
                        },
                    },
                },
            },
        },
        splitbutton: {
            root: {
                borderRadius: 'rounded-sm',
            },
        },
    },
});

// Extend ImportMeta interface for Vite...
declare module 'vite/client' {
    interface ImportMetaEnv {
        readonly VITE_APP_NAME: string;

        [key: string]: string | boolean | undefined;
    }

    interface ImportMeta {
        readonly env: ImportMetaEnv;
        readonly glob: <T>(pattern: string) => Record<string, () => Promise<T>>;
    }
}

//const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    //title: (title) => `${title} - ${appName}`,
    title: (title) => `${title}`,
    resolve: (name) => resolvePageComponent(`./pages/${name}.vue`, import.meta.glob<DefineComponent>('./pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .use(ToastService)
            .use(PrimeVue, {
                locale:
                    Object.assign(
                        {},
                        en, // fallback, an object like { emptyFilterMessage: 'Empty', emptyMessage: 'empty...' }
                        ru, // locale, an object like { emptyFilterMessage: 'Leer' }
                    ),
                    //...
                ripple: true,
                theme: {
                    preset: MyPreset,
                    options: {
                        prefix: 'p',
                        //darkModeSelector: 'system',
                        cssLayer: false,
                    },
                },
            })
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});

// This will set light / dark mode on page load...
initializeTheme();
