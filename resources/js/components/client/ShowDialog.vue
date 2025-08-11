<script setup lang="ts">
import Icon from '@/components/Icon.vue';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { useInitials } from '@/composables/useInitials';
import { usePhoneLink } from '@/composables/usePhoneLink';
import { useFullname } from '@/composables/useFullname';
import { computed, ref } from 'vue';

const props = defineProps({
    entity: {
        type: Object,
        default() {
            return {};
        },
    },
    route: {
        type: String,
    },
    iconName: {
        type: String,
        default: '',
    },
    label: {
        type: String,
        default: 'Edit',
    },
});

const open = ref(false);
// Compute whether we should show the avatar image
const showAvatar = computed(() => props.entity.avatar && props.entity.avatar !== '');

const { getFullname } = useFullname();
const { getInitials } = useInitials();
const { getPhone } = usePhoneLink();
const resize = () => {
    window.addEventListener('resize', () => {
        if (window.innerWidth <= 640) {
            open.value = false;
        }
    });
};
resize();

const getStatusLabel = (status: any) => {
    switch (status) {
        case true:
            return 'success';
        case false:
            return 'warn';
        default:
            return undefined;
    }
};
</script>

<template>
    <Button as="a" variant="link" @click="open = true">
        <Icon v-if="iconName" :name="iconName" class="mr-1 text-sky-600 hover:text-sky-900 focus:text-sky-900" />
    </Button>

    <Dialog v-model:visible="open" modal :header="entity.surname" class="hidden! w-[90vw]! sm:block! md:w-[50vw]! lg:w-[40vw]! xl:w-[30vw]!">
        <template #header>
            <div class="inline-flex items-center justify-center gap-2">
                <Avatar class="h-8 w-8 overflow-hidden rounded-lg">
                    <AvatarFallback class="rounded-lg text-black dark:text-white">
                        {{ getInitials(getFullname(entity.name, entity.middleName, entity.surname)) }}
                    </AvatarFallback>
                </Avatar>
                <span class="font-bold whitespace-nowrap">
                    {{ getFullname(entity.name, entity.middleName, entity.surname) }}
                    {{ resize() }}
                </span>
            </div>
        </template>
        <div class="relative">
            <div class="relative flex w-full items-center justify-center gap-2">
                <Avatar
                    class="max-h-56 min-h-40 max-w-56 min-w-40 overflow-hidden rounded-[50%] transition-all hover:relative hover:z-1 hover:scale-120"
                >
                    <AvatarImage v-if="showAvatar" :src="entity.avatar" :alt="entity.name" />
                    <AvatarFallback class="rounded-lg text-black dark:text-white">
                        {{ getInitials(getFullname(entity.name, entity.surname)) }}
                    </AvatarFallback>
                </Avatar>
                <div
                    class="absolute right-0 bottom-0 left-0 flex scale-80 flex-col items-center justify-center rounded-lg border border-2 bg-white p-2 transition-all hover:scale-100 dark:bg-[#18181B]"
                >
                    <div class="flex flex-col items-center justify-center">
                        <Button
                            class="text-md! p-0! font-bold! md:text-xl!"
                            as="a"
                            variant="link"
                            :label="entity.phone"
                            :href="'tel:' + getPhone(entity.phone)"
                            rel="noopener"
                        />
                        <Button
                            class="md:text-md! p-0! text-xs! font-light!"
                            as="a"
                            variant="link"
                            :label="entity.email"
                            :href="'mailto:' + entity.email"
                            rel="noopener"
                        />
                    </div>
                    <!--                    <div class="flex hidden w-1/2 items-center justify-end gap-x-10">
                        <div>
                            <Button
                                class="!hover:text-sky-800 px-0 !text-gray-800"
                                as="a"
                                variant="link"
                                :label="entity.phone"
                                :href="'tel:' + usePhoneLink(entity.phone)"
                                rel="noopener"
                            >
                                <Icon name="PhoneOutgoing" class="h-5 w-5" />
                            </Button>
                        </div>
                        <div>
                            <Button class="px-0" as="a" variant="link" :label="entity.phone" :href="'sms:' + usePhoneLink(entity.phone)" rel="noopener">
                                <Icon name="MessageSquareText" class="h-5 w-5" />
                            </Button>
                        </div>
                    </div>-->
                </div>
            </div>
            <Tabs value="0">
                <TabList>
                    <Tab value="0">History</Tab>
                    <Tab value="1" v-if="entity.comment">Comment</Tab>
                    <Tab value="2">Info</Tab>
                </TabList>
                <TabPanels>
                    <TabPanel value="0">
                        <p class="m-0 flex flex-row items-center justify-between">
                            <span class="flex items-center justify-between gap-x-2">
                                <Icon name="CalendarPlus2" />
                                Registration
                            </span>
                            {{ entity.created_at }}
                        </p>
                        <Divider v-if="entity.source" />

                        <p class="m-0 flex flex-row items-center justify-between" v-if="entity.source">
                            <span class="flex items-center justify-between gap-x-2">
                                <Icon name="MessageSquareDot" />
                                Source
                            </span>
                            {{ entity.source }}
                        </p>
                    </TabPanel>
                    <TabPanel value="1">
                        <p class="m-0 flex flex-row items-center justify-between" v-if="entity.comment">
                            <!--                            <span class="flex">
                                                            <Icon name="MessageSquareMore" />
                                                        </span>-->
                            {{ entity.comment }}
                        </p>
                    </TabPanel>
                    <TabPanel value="2">
                        <p class="m-0 flex flex-row items-center justify-between" v-if="entity.discount">
                            <span class="flex items-center justify-between gap-x-2">
                                <Icon name="Percent" />
                                Discount
                            </span>

                            {{ entity.discount }}
                        </p>
                        <Divider v-if="entity.blacklist" />

                        <p class="m-0 flex flex-row items-center justify-between" v-if="entity.blacklist">
                            <span class="flex items-center justify-between gap-x-2">
                                <Icon name="FileX" />
                                Blacklist
                            </span>
                            <Tag :value="entity.blacklist" :severity="getStatusLabel(entity.blacklist)" />
                        </p>
                        <Divider v-if="entity.prepayment" />

                        <p class="m-0 flex flex-row items-center justify-between" v-if="entity.prepayment">
                            <span class="flex items-center justify-between gap-x-2">
                                <Icon name="HandCoins" />
                                Prepayment
                            </span>
                            <Tag :value="entity.prepayment" :severity="getStatusLabel(entity.prepayment)" />
                        </p>
                    </TabPanel>
                </TabPanels>
            </Tabs>
        </div>
        <template #footer>
            <Button label="Cancel" variant="outlined" size="small" severity="secondary" @click="open = false" autofocus />
        </template>
    </Dialog>
</template>
