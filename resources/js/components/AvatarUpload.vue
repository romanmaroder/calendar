<script setup lang="ts">
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
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

const uploadImage = (e: Event) => {
    if (avatar.value) {
        showUploadProgress.value = true;
        processingUpload.value = true;
        uploadPercent.value = 0;

        emit('updateAvatar', { url, message });
        const file = e.target.files[0];
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
                message.value = error.message;
            });
    }
};

const deleteAvatar = () => {
    emit('updateAvatar', {url,message});
    axios
        .put(route(props.updateUrl, props.entity.id), {
            avatar: props.entity.avatar,
            id: props.entity.id,
        })
        .then((res) => {
            url.value = res.data.url;
            message.value = res.data.message;
            props.entity.avatar = res.data.url;
            names.value = res.data.name;
            resetUpload()
        });

};

const resetUpload = () => {
    console.log(props.entity);
    axios.post('/api/delete', { name: names.value }).then((res) => {
        url.value = res.data.url;
        message.value = res.data.message;
    });
};


</script>

<template>
    <Label for="avatar" class="flex cursor-pointer flex-col items-center gap-2">
        <span
            v-if="entity.avatar"
            @click.prevent="deleteAvatar"
            class="flex flex-col items-center font-medium text-gray-600 transition-colors hover:text-gray-500"
        >
            <img v-if="entity.avatar" :src="entity.avatar" :alt="names" class="max-h-[150px]" />
            <img v-else :src="url" :alt="names" class="max-h-[150px]" />
            <span class="mt-2">{{ textDelete }}</span>
        </span>

        <span
            v-else-if="url"
            @click.prevent="resetUpload"
            class="flex flex-col items-center font-medium text-gray-600 transition-colors hover:text-gray-500"
        >
            <img :src="url" :alt="names" />
            <span class="mt-2">{{ textDelete }}</span>
        </span>
        <span v-else class="flex flex-col items-center font-medium text-gray-600 transition-colors hover:text-gray-500">
            <img src="../../../resources/img/no_avatar_big.png" alt="avatar" />
            <span v-if="showUploadProgress">Uploading: {{ uploadPercent }} %</span>
            <span v-else class="mt-2">{{ textAdd }}</span>
        </span>
    </Label>
    <Input id="avatar" type="file" ref="avatar" name="avatar" @change="uploadImage" class="hidden" />
</template>

<style scoped></style>
