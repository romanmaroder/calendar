<script setup lang="ts">
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import axios from 'axios';
import { ref } from 'vue';

const props = defineProps({
    textAdd: {
        type: String,
    },
    textDelete: {
        type: String,
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


let file =null;
let formData = null;

const storeImage = (e: Event) => {
    if (avatar.value) {

        showUploadProgress.value = true;
        processingUpload.value = true;
        uploadPercent.value = 0;

        emit('updateAvatar', {url, message});
        file = e.target.files[0];
        formData = new FormData();
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
            }).catch(function (error) {
                message.value = error.message;
        });
    }
};

const uploadReset = () => {
    axios.post('/api/delete', { name: names.value }).then((res) => {
        url.value = '';
        message.value = res.data.message;
    });
};
</script>

<template>
    <Label for="avatar" class="flex cursor-pointer flex-col items-center gap-2">
        <img v-if="url" :src="url" :alt="names" />
        <img v-else src="../../../resources/img/no_avatar_big.png" alt="avatar" />
        <span
            v-if="url"
            @click.prevent="uploadReset"
            class="relative z-20 flex flex-row items-center space-x-1 font-medium text-gray-900 transition-colors hover:text-gray-500"
        >
            <span v-if="showUploadProgress">Uploading: {{ uploadPercent }} %</span>
            <span v-else>{{ textDelete }}</span>
        </span>
        <span v-else class="flex flex-row items-center space-x-1 font-medium text-gray-900 transition-colors hover:text-gray-500">
            <span v-if="showUploadProgress">Uploading: {{ uploadPercent }} %</span>
            <span v-else>{{ textAdd }}</span>
        </span>
    </Label>

    <Input id="avatar" type="file" ref="avatar" name="avatar" @change="storeImage" class="hidden" />
</template>

<style scoped></style>
