<script setup lang="ts">
import AddUser from '@/components/AddUser.vue';
import Layout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
const breadcrumbs: BreadcrumbItem[] = [{ title: 'Users', href: '/users' }];

const props = defineProps({
    users: {
        type: Object,
        required: true,
    },
    count:{
        type: Number,
    }
});

const open = ref(false);
/*const anchor = ref({
    x: 0,
    y: 0,
});

const position = computed(() => ({
    getBoundingClientRect: () =>
        ({
            width: 0,
            height: 0,
            left: anchor.value.x,
            right: anchor.value.x,
            top: anchor.value.y,
            bottom: anchor.value.y,
            ...anchor.value,
        }) as DOMRect,
}));*/
</script>

<template>
    <Layout :breadcrumbs="breadcrumbs">
        <Head title="Index" />
        <div class="flex min-h-full w-full items-start justify-center p-2">
            <div class="container max-w-8xl">
                <div class="flex flex-wrap items-center justify-between gap-2">
                    Filter
                    <div class="client-cards-description">
                        Карточки клиентов на базе номеров мобильных телефонов. Всего карточек: {{count}}
                    </div>
                    <!--                    Добавление нового клиента-->
                    <div><AddUser /></div>
                </div>
<!--                <PopoverRoot>-->
<!--                    <PopoverTrigger class="rounded-md bg-white">-->
<!--                        <heading-small title="Popover Reka-ui" />-->
<!--                    </PopoverTrigger>-->
<!--                    <PopoverPortal>-->
<!--                        <PopoverContent class="relative z-10 flex w-[260px] justify-between rounded-md bg-[#1abc9c] p-5 text-white">-->
<!--                            <text-link href="https://reka-ui.com/docs/overview/getting-started">Reka -ui </text-link>-->
<!--                            <PopoverClose class="absolute top-1 right-3 text-red-600">x</PopoverClose>-->
<!--                            <PopoverArrow class="bg-white" />-->
<!--                        </PopoverContent>-->
<!--                    </PopoverPortal>-->
<!--                </PopoverRoot>-->

<!--                <TooltipProvider :delay-duration="0">-->
<!--                    <div-->
<!--                        class="border-muted-foreground/30 dark:text-foreground flex h-32 w-48 items-center justify-center rounded-lg border border-dashed text-sm font-medium"-->
<!--                        @pointerenter="open = true"-->
<!--                        @pointerleave="open = false"-->
<!--                        @pointermove="-->
<!--                            (ev) => {-->
<!--                                anchor.x = ev.clientX;-->
<!--                                anchor.y = ev.clientY;-->
<!--                            }-->
<!--                        "-->
<!--                    >-->
<!--                        Container-->
<!--                    </div>-->
<!--                    <TooltipRoot :open="open">-->
<!--                        <TooltipTrigger :reference="position" />-->
<!--                        <TooltipPortal>-->
<!--                            <TooltipContent-->
<!--                                side="top"-->
<!--                                :side-offset="12"-->
<!--                                class="bg-card border-muted-foreground/30 rounded-md border px-4 py-2 text-sm"-->
<!--                                update-position-strategy="always"-->
<!--                            >-->
<!--                                Following-->

<!--                                <TooltipArrow class="fill-card stroke-muted-foreground/30 -translate-y-[1px]" />-->
<!--                            </TooltipContent>-->
<!--                        </TooltipPortal>-->
<!--                    </TooltipRoot>-->
<!--                </TooltipProvider>-->

                <div class="overflow-hidden rounded-xl bg-white shadow-md">
                    <!-- Table Header -->
                    <div class="border-b border-gray-200 p-6">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                            <div>
                                <h2 class="text-xl font-bold text-gray-800">Team Members</h2>
                                <p class="mt-1 text-gray-500">Manage your team members and their account permissions here.</p>
                            </div>
                            <div class="mt-4 md:mt-0">
                                <button
                                    class="rounded-lg bg-indigo-600 px-4 py-2 font-medium text-white transition duration-150 ease-in-out hover:bg-indigo-700"
                                >
                                    Add Member
                                </button>
                            </div>
                        </div>

                        <!-- Search and Filter -->
                        <div class="mt-6 flex flex-col gap-4 sm:flex-row">
                            <div class="relative flex-grow">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                    <svg
                                        class="h-5 w-5 text-gray-400"
                                        xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20"
                                        fill="currentColor"
                                        aria-hidden="true"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                </div>
                                <input type="text" class="w-full rounded-lg border border-gray-300 py-2 pr-4 pl-10" placeholder="Search members..." />
                            </div>
                            <div>
                                <select class="w-full rounded-lg border border-gray-300 px-4 py-2 sm:w-auto">
                                    <option value="">All Departments</option>
                                    <option value="engineering">Engineering</option>
                                    <option value="design">Design</option>
                                    <option value="marketing">Marketing</option>
                                    <option value="sales">Sales</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium tracking-wider text-gray-500 uppercase">Name</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium tracking-wider text-gray-500 uppercase">Email</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-center text-xs font-medium tracking-wider text-gray-500 uppercase">
                                        Phone
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-center text-xs font-medium tracking-wider text-gray-500 uppercase">
                                        Comment
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-center text-xs font-medium tracking-wider text-gray-500 uppercase">
                                        Created
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-center text-xs font-medium tracking-wider text-gray-500 uppercase">
                                        Blacklist
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-center text-xs font-medium tracking-wider text-gray-500 uppercase">
                                        Prepayment
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-center text-xs font-medium tracking-wider text-gray-500 uppercase">
                                        Discount
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-center text-xs font-medium tracking-wider text-gray-500 uppercase">
                                        Records
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-center text-xs font-medium tracking-wider text-gray-500 uppercase">
                                        Total
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-center text-xs font-medium tracking-wider text-gray-500 uppercase">Status</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium tracking-wider text-gray-500 uppercase">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                <!-- Row 1 -->
                                <tr class="transition-colors duration-150 hover:bg-gray-50" v-for="user in users" :key="user.id">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="h-10 w-10 flex-shrink-0">
                                                <img v-if="user.avatar" :src="user.avatar" :alt="user.id">
                                                <img v-else
                                                    class="h-10 w-10 rounded-full object-cover"
                                                    src="../../../../public/no_avatar_big.png"
                                                    alt=""
                                                />
                                            </div>
                                            <div class="ml-4">
                                                <div
                                                    class="text-wrap md:text-nowrap text-sm font-medium text-gray-900">{{
                                                        user.name }} {{ user.middleName}} {{ user.surname }}
                                                </div>
                                                <div class="text-xs font-normal text-gray-900">ID:{{ user.id }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-left text-gray-900">{{ user.email }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <div class="text-sm text-gray-900" v-if="user.phone">{{ user.phone }}</div>
                                        <div class="text-sm text-gray-900" v-else>-</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <div class="text-sm text-gray-900" >{{ user.comment }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <div class="text-sm text-gray-900" >{{ user.created_at }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <div class="text-sm text-gray-900" v-if="user.blacklist" >{{ user.blacklist
                                            }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <div class="text-sm text-gray-900" >{{ user.prepayment }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <div class="text-sm text-gray-900" >{{ user.discount }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <div class="text-sm text-gray-900" >{{ user.records }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <div class="text-sm text-gray-900" >{{ user.total }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex rounded-full bg-green-100 px-2 text-xs leading-5 font-semibold text-green-800">
                                            Active
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right text-sm font-medium whitespace-nowrap">
                                        <a href="#" class="mr-3 text-indigo-600 hover:text-indigo-900">Edit</a>
                                        <a href="#" class="text-red-600 hover:text-red-900">Delete</a>
                                    </td>
                                </tr>

                                <!-- Row 2
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 flex-shrink-0">
                                        <img class="h-10 w-10 rounded-full object-cover" src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-1.2.1&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="">
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">Sarah Johnson</div>
                                        <div class="text-sm text-gray-500">sarah.johnson@example.com</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">Product Designer</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">Design</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
              Active
            </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="#" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                                <a href="#" class="text-red-600 hover:text-red-900">Delete</a>
                            </td>
                        </tr>-->

                                <!-- Row 3
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 flex-shrink-0">
                                        <img class="h-10 w-10 rounded-full object-cover" src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?ixlib=rb-1.2.1&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="">
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">Michael Roberts</div>
                                        <div class="text-sm text-gray-500">michael.roberts@example.com</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">Marketing Manager</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">Marketing</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
              On Leave
            </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="#" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                                <a href="#" class="text-red-600 hover:text-red-900">Delete</a>
                            </td>
                        </tr>-->

                                <!-- Row 4
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 flex-shrink-0">
                                        <img class="h-10 w-10 rounded-full object-cover" src="https://images.unsplash.com/photo-1520813792240-56fc4a3765a7?ixlib=rb-1.2.1&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="">
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">Lisa Wang</div>
                                        <div class="text-sm text-gray-500">lisa.wang@example.com</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">Sales Director</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">Sales</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
              Active
            </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="#" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                                <a href="#" class="text-red-600 hover:text-red-900">Delete</a>
                            </td>
                        </tr> -->

                                <!-- Row 5
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 flex-shrink-0">
                                        <img class="h-10 w-10 rounded-full object-cover" src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?ixlib=rb-1.2.1&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="">
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">David Wilson</div>
                                        <div class="text-sm text-gray-500">david.wilson@example.com</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">Frontend Developer</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">Engineering</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
              Inactive
            </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="#" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                                <a href="#" class="text-red-600 hover:text-red-900">Delete</a>
                            </td>
                        </tr>-->
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="border-t border-gray-200 bg-white px-4 py-3 sm:px-6">
                        <div class="flex flex-col items-center justify-between sm:flex-row">
                            <div class="mb-4 sm:mb-0">
                                <p class="text-sm text-gray-700">
                                    Showing <span class="font-medium">1</span> to <span class="font-medium">5</span> of
                                    <span class="font-medium">24</span> results
                                </p>
                            </div>
                            <div>
                                <nav class="relative z-0 inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
                                    <a
                                        href="#"
                                        class="relative inline-flex items-center rounded-l-md border border-gray-300 bg-white px-2 py-2 text-sm font-medium text-gray-500 hover:bg-gray-50"
                                    >
                                        <span class="sr-only">Previous</span>
                                        <svg
                                            class="h-5 w-5"
                                            xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 20 20"
                                            fill="currentColor"
                                            aria-hidden="true"
                                        >
                                            <path
                                                fill-rule="evenodd"
                                                d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                                                clip-rule="evenodd"
                                            />
                                        </svg>
                                    </a>
                                    <a
                                        href="#"
                                        class="relative inline-flex items-center border border-gray-300 bg-indigo-50 px-4 py-2 text-sm font-medium text-indigo-600 hover:bg-indigo-100"
                                    >
                                        1
                                    </a>
                                    <a
                                        href="#"
                                        class="relative inline-flex items-center border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
                                    >
                                        2
                                    </a>
                                    <a
                                        href="#"
                                        class="relative inline-flex items-center border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
                                    >
                                        3
                                    </a>
                                    <span
                                        class="relative inline-flex items-center border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700"
                                    >
                                        ...
                                    </span>
                                    <a
                                        href="#"
                                        class="relative inline-flex items-center border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
                                    >
                                        8
                                    </a>
                                    <a
                                        href="#"
                                        class="relative inline-flex items-center border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
                                    >
                                        9
                                    </a>
                                    <a
                                        href="#"
                                        class="relative inline-flex items-center rounded-r-md border border-gray-300 bg-white px-2 py-2 text-sm font-medium text-gray-500 hover:bg-gray-50"
                                    >
                                        <span class="sr-only">Next</span>
                                        <svg
                                            class="h-5 w-5"
                                            xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 20 20"
                                            fill="currentColor"
                                            aria-hidden="true"
                                        >
                                            <path
                                                fill-rule="evenodd"
                                                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                                clip-rule="evenodd"
                                            />
                                        </svg>
                                    </a>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </Layout>
</template>
