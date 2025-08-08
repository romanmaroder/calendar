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

// Compute whether we should show the avatar image
const showAvatar = computed(() => props.entity.avatar && props.entity.avatar !== '');
const visible = ref(false);
const {getFullname} = useFullname();
const { getInitials } = useInitials();
const { getPhone } = usePhoneLink();

const resize =()=>{

    window.addEventListener('resize', () => {
        if (window.innerWidth >= 640) {
            visible.value = false;
        }
    })

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
    <Drawer
        v-model:visible="visible"
        :header="getFullname(entity.name, entity.surname)"
        :blockScroll="true"
        position="bottom"
        closeIcon="pi pi-chevron-down text-sky-500"
        :pt="{
            root:{
                class:'sm:hidden! h-[100vh]!'
            },
            mask:{
                class:'sm:hidden!',
            },
            header: {
                class: '!py-[0.5rem]',
            },
            title: {
                class: '!text-lg',
            },
        }"
    >
            <div class="flex flex-col w-full items-center justify-center gap-y-5">
                <Avatar
                    class="h-[100%] w-[89vw] overflow-hidden rounded-sm"
                >
                    <AvatarImage v-if="showAvatar" :src="entity.avatar" :alt="entity.name" />
                    <AvatarFallback class="rounded-lg text-black dark:text-white min-h-[20vh]">
                        {{ getInitials(getFullname(entity.name, entity.surname)) }}
                    </AvatarFallback>
                </Avatar>
                <div
                    class="flex-col items-center justify-center w-full rounded-lg border border-1 bg-white p-2 transition-all dark:bg-[#18181B]"
                >
                    <div class="flex flex-col items-center justify-center">
                        <Button
                            class="text-2xl! font-bold! text-gray-600! dark:text-gray-400! p-0!"
                            as="a"
                            variant="link"
                            :label="entity.phone"
                            :href="'tel:' + getPhone(entity.phone)"
                            rel="noopener"
                        />
                        <Button
                            class="text-xs! font-light! p-0!"
                            as="a"
                            variant="link"
                            :label="entity.email"
                            :href="'mailto:' + entity.email"
                            rel="noopener"
                        />
                    </div>
                    <Divider/>
                                        <div class="flex items-center justify-between gap-x-10">
                        <div>
                            <Button
                                class="!hover:text-sky-800 px-0"
                                as="a"
                                variant="link"
                                :label="entity.phone"
                                :href="'tel:' + getPhone(entity.phone)"
                                rel="noopener"
                            >
                                <Icon name="PhoneOutgoing" class="h-7 w-7" />
                            </Button>
                        </div>
                                            <Divider layout="vertical"/>
                        <div>
                            <Button class="px-0" as="a" variant="link" :label="entity.phone" :href="'sms:' +
                            getPhone(entity.phone)" rel="noopener">
                                <Icon name="MessageSquareText" class="h-7 w-7" />
                            </Button>
                        </div>
                    </div>
                </div>
            <Tabs value="0">
                <TabList>
                    <Tab value="0">History</Tab>
                    <Tab value="1" v-if="entity.comment">Comment</Tab>
                    <Tab value="2" v-if="entity.discount || entity.blacklist || entity.prepayment">Info</Tab>
                </TabList>
                <TabPanels class="px-0! min-w-[85vw]!">
                    <TabPanel value="0">
                        <p class="m-0 flex flex-row items-center justify-between">
                            <span class="flex items-center justify-between gap-x-2">
                                <Icon name="CalendarPlus2" />
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
                        <p class="m-0 flex flex-row items-center justify-between"
                           v-if="entity.comment">
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
    </Drawer>
    <Button @click="visible = true" size="small" variant="link">
        <Icon :name="iconName" />
        {{ label }}
    </Button>
</template>
