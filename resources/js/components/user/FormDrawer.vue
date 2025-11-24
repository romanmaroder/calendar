<script setup lang="ts">
import FormUser from '@/components/user/FormUser.vue';
import { getFullname } from '@/composables/useFullname';
import { PropType, ref } from 'vue';

interface Data {
    name?: string;
    surname?: string;
    avatar?: string;
}
const emit = defineEmits(['newUser','updateUser']);

defineProps({
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
    entity:{
        type:Object,
        default(){
            return {};
        }
    },
    variant: {
        type: String as PropType<'outlined'|'text' |'link'>,
        validator(value:string) {
            return [ "outlined", "text" , "link" ].includes(value)
        },
        default:null
    },
});

const visible = ref(false);
const form= ref<Data>({});

const previewOfUserData = (data: Data) => {
    return form.value={
        name: data.name,
        surname: data.surname,
        avatar: data.avatar,
    };
};
const newUser = ()=>{emit('newUser'); visible.value = false;};
const updateUser = ()=>{emit('updateUser'); visible.value = false;};


</script>

<template>
    <Drawer
        v-model:visible="visible"
        :blockScroll="true"
        :position="entity.id ? 'right' : undefined "
        :closeIcon="entity.id ? 'pi pi-chevron-right' : 'pi pi-chevron-left' "
        :pt="{
            header: {
                class: '!py-[0.5rem]'
            },
            title: {
                class: '!text-lg'
            }
        }"
    >
        <template #header>
            <div class="flex items-center gap-2">
                <Avatar
                    v-if="form.avatar || entity.avatar"
                    :image="form.avatar ?? entity.avatar"
                    shape="circle"
                    :pt="{
                        image: {
                            class: 'object-cover',
                        },
                    }"
                />
                <Avatar v-else icon="pi pi-user" shape="circle" />
                <span v-if="form.name || form.surname" class="inline-block w-[150px] truncate font-bold">
                    {{ getFullname({ name: form.name, surname: form.surname }) }} </span
                >
                <span v-else-if="entity.name || entity.surname" class="inline-block w-[150px] truncate font-bold">
                    {{ getFullname({ name: entity.name, surname: entity.surname }) }} </span
                >
                <span v-else class="line-clamp-1 inline-block w-[150px] font-bold">{{ title }}</span>
            </div>
        </template>
        <FormUser @previewOfUserData="previewOfUserData"
                  @createItem="newUser"
                  @updateItem="updateUser"
                  :user="entity"
        />
    </Drawer>

    <Button :icon="iconName" :label="label" @click="visible = true" size="small" :variant="variant" />
</template>
