<script setup lang="ts">
import { cn } from '@/lib/utils';
import axios from 'axios';
import { ref } from 'vue';
import { route } from 'ziggy-js';

const props = defineProps({
    class: {
        type: String,
    },
    orientation: {
        type: String,
        validator(value: string) {
            return ['landscape', 'portrait'].includes(value);
        },
        default() {
            return 'landscape';
        },
    },
    preview: {
        type: Boolean,
    },
    imgPosition: {
        type: String,
        validator(value: string) {
            return ['top', 'bottom', 'left', 'right'].includes(value);
        },
        default: 'bottom left',
    },
    size: {
        type: String,
        validator(value: string) {
            return ['full', 'small', 'medium', 'large'].includes(value);
        },
        default: 'full',
    },
    entity: {
        type: Object,
        default() {
            return {};
        },
    },
    url: {
        type: String,
        default: 'avatar',
    },
});

const emit = defineEmits(['updateAvatar']);

const src = ref('');
const names = ref('');

const uploadPercent = ref(0);
const showUploadProgress = ref(false);
const message = ref('');

const uploadImage = (event: any) => {
    showUploadProgress.value = true;
    uploadPercent.value = 0;

    emit('updateAvatar', { src, message });

    const file = event.files[0];
    const formData = new FormData();
    formData.append('avatar', file);

    axios
        .post('/api/upload', formData, {
            onUploadProgress: (progressEvent) => {
                uploadPercent.value = progressEvent.lengthComputable ? Math.round((progressEvent.loaded * 100) / progressEvent.total) : 0;
            },
        })
        .then((res) => {
            src.value = res.data.url;
            names.value = res.data.name;
            message.value = res.data.message;
            showUploadProgress.value = false;
        })
        .catch(function (error) {
            message.value = error.response.data.message;
        });
};

const deleteAvatar = () => {
    emit('updateAvatar', { src, message });
    axios
        .put(route(props.url, props.entity.id), {
            id: props.entity.id,
        })
        .then((res) => {
            src.value = res.data.url;
            message.value = res.data.message;
            names.value = res.data.name;
            resetUpload();
        });
};

const resetUpload = () => {
    axios.post('/api/delete', { name: names.value }).then((res) => {
        src.value = res.data.url;
        message.value = res.data.message;
    });
    uploadPercent.value = 0;
};
</script>

<template>
    <div
        v-if="!src && !props.entity.avatar"
        :class="[
            cn('flex items-center', props.class),
            [
                {
                    'max-w-full': props.size == 'full',
                    'max-w-3xs': props.size == 'small',
                    'max-w-xs': props.size == 'medium',
                    'max-w-md': props.size == 'large',
                },
            ],
        ]"
    >
        <FileUpload
            id="avatar"
            ref="avatar"
            name="avatar"
            mode="basic"
            auto
            choose-label="Avatar"
            chooseIcon="pi pi-camera"
            customUpload
            @select="uploadImage"
            :pt="{
                root: {
                    class: '!w-full',
                },
                pcChooseButton: {
                    root: {
                        class:
                        '!w-full !text-sm !shadow-[0_3px_1px_-2px_rgba(0,0,0,0.2),0_2px_2px_0_rgba(0,0,0,0.14),0_1px_5px_0_rgba(0,0,0,0.12)]',
                    },
                },
            }"
        />
    </div>

    <ProgressBar
        v-if="showUploadProgress"
        :class="[
            'mt-2',
            {
                'max-w-full': props.size == 'full',
                'max-w-3xs': props.size == 'small',
                'max-w-xs': props.size == 'medium',
                'max-w-md': props.size == 'large',
            },
        ]"
        :value="uploadPercent"
    />

    <div
        v-if="src ? src : props.entity.avatar"
        :class="[
            cn('flex', props.class),
            [
                {
                    'flex-row items-center': props.orientation == 'landscape',
                    'flex-col': props.orientation == 'portrait',
                    'max-w-full': props.size == 'full',
                    'max-w-3xs': props.size == 'small',
                    'max-w-xs': props.size == 'medium',
                    'max-w-md': props.size == 'large',
                    'gap-1':props.imgPosition == 'left' || 'right'
                },
            ],
        ]"
    >
        <Avatar
            :icon="src ? 'pi pi-refresh' : 'pi pi-trash'"
            :class="['!w-full !h-[2.35rem] cursor-pointer !bg-[#10B981] !text-sm !text-white !shadow-[0_3px_1px_-2px_rgba(0,0,0,0.2),0_2px_2px_0_rgba(0,0,0,0.14),0_1px_5px_0_rgba(0,0,0,0.12)] hover:!bg-[#059669]  dark:!text-[#191F20] ']"
            size="normal"
            @click="src ? resetUpload() : deleteAvatar()"
            :pt="{
                icon: {
                    class: '',
                },
            }"
        />
        <div
            v-if="props.preview && props.orientation == 'portrait'"
            :class="[
                'overflow-hidden rounded-sm',
                {
                    'max-w-full': props.size == 'full',
                    'max-w-3xs': props.size == 'small',
                    'max-w-xs': props.size == 'medium',
                    'max-w-md': props.size == 'large',
                    'order-first': props.imgPosition == 'top',
                    'order-last': props.imgPosition == 'bottom',
                },
            ]"
        >
            <Image
                :src="src ? src : props.entity.avatar"
                :alt="names"
                preview
                :pt="{
                    image: {
                        class: 'rounded-md',
                    },
                }"
            />
        </div>

        <Avatar
            v-if="props.preview && props.orientation == 'landscape'"
            :class="{ 'order-first': props.imgPosition == 'left', 'order-last': props.imgPosition == 'right' }"
        >
            <div class="grid">
                <Image
                    :src="src ? src : props.entity.avatar"
                    :alt="names"
                    preview
                    :pt="{
                        image: {
                            class: 'rounded-md !h-[3rem]',
                        },
                    }"
                />
            </div>
        </Avatar>
    </div>
</template>
