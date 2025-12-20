<script setup lang="ts">
import { computed, PropType, ref, watch } from 'vue';
import { Branch } from '@/types';
import { useMediaQuery } from '@vueuse/core';
import FormBranch from '@/components/branch/FormBranch.vue';

interface Data {
    name?: string;
    avatar?: string;
}
const emit = defineEmits(['newUser', 'updateUser']);

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
    branch: {
        type: Object as PropType<Branch | null>,
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

const previewOfUserData = (data: Data) => {
    console.log(data);
    return (form.value = {
        name: data.name,
        avatar: data.avatar,
    });
};

const avatar = computed(() => {
    return form.value.avatar ?? props.branch?.avatar ?? '';
});

const newUser = () => {
    emit('newUser');
    visible.value = false;
};
const updateUser = () => {
    emit('updateUser');
    visible.value = false;
};
</script>

<template>
    <Drawer
        v-model:visible="visible"
        :blockScroll="true"
        :position="branch?.id ? 'right' : undefined"
        :closeIcon="branch?.id ? 'pi pi-chevron-right' : 'pi pi-chevron-left'"
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
                    :icon="avatar ? '' : 'pi pi-map-marker'"
                    :pt="{
                        image: {
                            class: 'object-cover',
                        },
                    }"
                />
                <span v-if="form.name" class="inline-block w-[150px] truncate font-bold">
                    {{ form.name }}
                </span>
                <span v-else-if="branch?.name" class="inline-block w-[150px] truncate font-bold">
                    {{ branch?.name }}
                </span>
                <span v-else class="line-clamp-1 inline-block w-[150px] font-bold">{{ title }}</span>
            </div>
        </template>
        <form-branch :branch="branch" @createUser="newUser" @updateUser="updateUser" @drawerData="previewOfUserData" />
    </Drawer>

    <Button :icon="iconName" :label="label" @click="visible = true" size="small" :variant="variant" :raised="raised" />
</template>
