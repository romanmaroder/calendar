<script setup lang="ts">
import { computed, PropType, ref, watch } from 'vue';
import { Company } from '@/types';
import { useMediaQuery } from '@vueuse/core';
import FormCompany from '@/components/company/FormCompany.vue';

interface Data {
    name?: string;
    surname?: string;
    avatar?: string;
}
const emit = defineEmits(['newCompany', 'updateCompany']);

const props = defineProps({
    iconName: {
        type: String,
        default: '',
    },
    label: {
        type: String,
        default: 'Add',
    },
    title: {
        type: String,
        default: 'New',
    },
    company: {
        type: Object as PropType<Company | null>,
        default() {
            return {};
        },
    },
    variant: {
        type: String as PropType<'outlined' | 'text' | 'link'>,
        validator(value: string) {
            return ['outlined', 'text', 'link'].includes(value);
        },
        default: null,
    },
    raised: {
        type: Boolean,
        default: false,
    },
});

const visible = ref(false);
const isLargeScreen = useMediaQuery('(min-width: 640px)');

watch(isLargeScreen, () => {
    visible.value = false;
});
const form = ref<Data>({});

const previewOfCompanyData = (data: Data) => {
    console.log(data);
    return (form.value = {
        name: data.name,
        avatar: data.avatar,
    });
};

const avatar = computed(() => {
    return form.value.avatar ?? props.company?.avatar ?? '';
});

const newCompany = () => {
    emit('newCompany');
    visible.value = false;
};
const updateCompany = () => {
    emit('updateCompany');
    visible.value = false;
};
</script>

<template>
    <Drawer
        v-model:visible="visible"
        :blockScroll="true"
        :position="company?.id ? 'right' : undefined"
        :closeIcon="company?.id ? 'pi pi-chevron-right' : 'pi pi-chevron-left'"
        :pt="{
            header: {
                class: '!py-[0.5rem]',
            },
            content: {
                class: 'p-0!',
            },
            title: {
                class: '!text-lg',
            },
        }"
    >
        <template #header>
            <div class="flex items-center gap-2">
                <Avatar
                    :image="avatar"
                    shape="circle"
                    :icon="avatar ? '' : 'pi pi-builder'"
                    :pt="{
                        image: {
                            class: 'object-cover',
                        },
                    }"
                />
                <span v-if="form.name" class="inline-block w-[150px] truncate font-bold">
                    {{ form.name }}
                </span>
                <span v-else-if="company?.name" class="inline-block w-[150px] truncate font-bold">
                    {{ company?.name }}
                </span>
                <span v-else class="line-clamp-1 inline-block w-[150px] font-bold">{{ title }}</span>
            </div>
        </template>
        <form-company :company="company" @newCompany="newCompany" @updateCompany="updateCompany"
                      @drawerData="previewOfCompanyData" />
    </Drawer>

    <Button :icon="iconName" :label="label" @click="visible = true" size="small" :variant="variant" :raised="raised" />
</template>
