<script setup lang="ts">
import axios from 'axios';
import { ref } from 'vue';
import { route } from 'ziggy-js';

const props = defineProps({
    textAdd: {
        type: String,
        default: 'Upload photos',
    },
    textDelete: {
        type: String,
        default: 'Delete a photo',
    },
    entity: {
        type: Object,
        default() {
            return {};
        },
    },
    updateUrl: {
        type: String,
        default: 'updateAvatar',
    },
});

const emit = defineEmits(['updateAvatar']);

const url = ref('');
const names = ref('');

const avatar = ref('');

const uploadPercent = ref(0);
const showUploadProgress = ref(false);
const processingUpload = ref(false);
const message = ref('');

const uploadImage = (event) => {
    if (avatar.value) {
        showUploadProgress.value = true;
        processingUpload.value = true;
        uploadPercent.value = 0;

        emit('updateAvatar', { url, message });
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
                url.value = res.data.url;
                names.value = res.data.name;
                message.value = res.data.message;

                showUploadProgress.value = false;
                processingUpload.value = false;
            })
            .catch(function (error) {
                console.log(error);
                message.value = error.response.data.message;
            });
    }
};

const deleteAvatar = () => {
    emit('updateAvatar', { url, message });
    axios
        .put(route(props.updateUrl, props.entity.id), {
            avatar: props.entity.avatar,
            id: props.entity.id,
        })
        .then((res) => {
            console.log(res);
            url.value = res.data.url;
            message.value = res.data.message;
            props.entity.avatar = res.data.url;
            names.value = res.data.name;
            resetUpload();
        });
};

const resetUpload = () => {
    axios.post('/api/delete', { name: names.value }).then((res) => {
        url.value = res.data.url;
        message.value = res.data.message;
    });
    uploadPercent.value = 0;
};

</script>

<template>
    <div class="card flex flex-col items-start gap-6">
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
                        '!flex-col !w-full !p-1.5 !text-sm !font-light !gap-0 ',
                    },
                },
            }"
            v-if="!url && !props.entity.avatar"
        />
    </div>

    <p v-if="url" class="flex flex-row items-center gap-1">
        <Avatar icon="pi pi-refresh"
                class="mr-2 !w-full !bg-[#10B981] !text-base !text-white dark:!text-[#191F20] !font-light" size="large"
                @click="resetUpload" :pt="{
                    icon:{
                        class:'!text-lg'
                    }
                }"/>
        <Avatar :image="url" class="mr-2" size="large">
            <div class="card flex justify-center">
                <Image
                    :src="url"
                    :alt="names"
                    preview
                    :pt="{
                        root: {
                            class: '!max-h-[3rem] !max-w-[250px]',
                        },
                        image: {
                            class: 'rounded-md',
                        },
                    }"
                />
            </div>
        </Avatar>
    </p>
    <p v-else-if="entity.avatar" class="flex flex-row items-center gap-1">
        <Avatar icon="pi pi-trash"
                class="mr-2 !w-full !bg-[#10B981] !text-base !text-white dark:!text-[#191F20] !font-light"
                size="large" @click="deleteAvatar" :pt="{
                    icon:{
                        class:'!text-lg'
                    }
                }"/>
        <Avatar :image="props.entity.avatar" class="mr-2" size="large">
            <div class="card flex justify-center">
                <Image
                    :src="props.entity.avatar"
                    :alt="names"
                    preview
                    :pt="{
                        root: {
                            class: '!max-h-[3rem] !max-w-[250px]',
                        },
                        image: {
                            class: 'rounded-md',
                        },
                    }"
                />
            </div>
        </Avatar>
    </p>
</template>
