<script setup lang="ts">
import { getFullname } from '@/composables/useFullname';
import { computed, PropType, ref, watch } from 'vue';
import { Client } from '@/types';
import { useMediaQuery } from '@/composables/useMediaQuery';
import FormClient from '@/components/client/FormClient.vue';

interface Data {
    name?: string;
    surname?: string;
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
    entity: {
        type: Object as PropType<Client | null>,
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
const isLargeScreen = useMediaQuery(640);

watch(isLargeScreen, () => {
    visible.value = false;
});
const form = ref<Data>({});

const previewOfUserData = (data: Data) => {
    console.log(data);
    return (form.value = {
        name: data.name,
        surname: data.surname,
        avatar: data.avatar,
    });
};

const avatar = computed(() => {
    return form.value.avatar ?? props.entity?.avatar ?? '';
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
        :position="entity?.id ? 'right' : undefined"
        :closeIcon="entity?.id ? 'pi pi-chevron-right' : 'pi pi-chevron-left'"
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
                    :icon="avatar ? '' : 'pi pi-user'"
                    :pt="{
                        image: {
                            class: 'object-cover',
                        },
                    }"
                />
                <span v-if="form.name || form.surname" class="inline-block w-[150px] truncate font-bold">
                    {{ getFullname({ name: form.name, surname: form.surname }) }}
                </span>
                <span v-else-if="entity?.name || entity?.surname" class="inline-block w-[150px] truncate font-bold">
                    {{ getFullname({ name: entity?.name, surname: entity?.surname }) }}
                </span>
                <span v-else class="line-clamp-1 inline-block w-[150px] font-bold">{{ title }}</span>
            </div>
        </template>
        <form-client :client="entity" @createUser="newUser" @updateUser="updateUser" @drawerData="previewOfUserData" />
    </Drawer>

    <Button :icon="iconName" :label="label" @click="visible = true" size="small" :variant="variant" :raised="raised" />
</template>
