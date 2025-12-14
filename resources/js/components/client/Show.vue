<script setup lang="ts">
import FinanceCard from '@/components/client/profile/FinanceCard.vue';
import InfoCard from '@/components/user/profile/InfoCard.vue';
import ProfileCard from '@/components/user/profile/ProfileCard.vue';
import { useFullname } from '@/composables/useFullname';
import { useMediaQuery } from '@vueuse/core';
import ProfileLayout from '@/layouts/profile/ProfileLayout.vue';
import { Client } from '@/types';
import { computed, PropType, ref, watch } from 'vue';

const props = defineProps({
    entity: {
        type: Object as PropType<Client>,
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
    variant: {
        type: String,
        validator(value: string) {
            return ['outlined', 'text', 'link'].includes(value);
        },
        default: 'link',
    },
});

const { getFullname } = useFullname();

const visible = ref(false);

const isLargeScreen = useMediaQuery('(min-width: 640px)');

watch(isLargeScreen, () => {
    visible.value = false;
});

const aov = computed(() => {
    if (
        props.entity?.total !== undefined &&
        props.entity?.records !== undefined
    ) {
        return Math.trunc(props.entity.total / props.entity.records);
        //FIXME подумать над логикой расчета среднего чека. Может перенести в другое место
    }
    return null;
});
</script>

<template>
    <Drawer
        v-model:visible="visible"
        :header="getFullname({ name: props.entity.name, surname: props.entity.surname })"
        :blockScroll="true"
        position="bottom"
        closeIcon="pi pi-chevron-down"
        :pt="{
            root: {
                class: 'sm:hidden! h-[100vh]! dark:bg-black!',
            },
            content: {
                class: 'p-0!',
            },
            mask: {
                class: 'sm:hidden!',
            },
            header: {
                class: '!py-[0.5rem] ![text-shadow:_0.5px_0.5px_2px_rgba(0,0,0,0.5)]',
            },
            title: {
                class: '!text-lg',
            },
        }"
    >
        <ProfileLayout>
            <template #center-column>
                <ProfileCard :user="entity" />
                <InfoCard :user="entity" class="mt-2" />

                <FinanceCard
                    title="Доходность"
                    :data="{ total: entity.total, records: entity.records, aov: aov }"
                    class="mt-2"
                />
            </template>
        </ProfileLayout>
    </Drawer>
    <Button @click="visible = true" :icon="iconName" :label="label" size="small" variant="link" />
</template>
