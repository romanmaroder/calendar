<!--loading an avatar with a context menu on the desktop right-click on mobile hold and release the component uploads
 a file to the public/avatars folder with a unique name
 on the backend create a controller with methods for adding and deleting a file create routes
-->

<template>
    <div class="max-w-md justify-self-center">
        <div class="flex flex-col items-center gap-4">
            <div ref="triggerEl" class="group cursor-pointer" @click="openFileDialog">
                <Avatar
                    v-if="!previewImage"
                    :image="croppedImage ? croppedImage : undefined"
                    :icon="!croppedImage ? 'pi pi-camera' : undefined"
                    size="xlarge"
                    :class="{ 'h-[100px]! w-[100px]! !shadow-none': croppedImage }"
                    class="border-4 border-transparent shadow-[0_3px_1px_-2px_rgba(0,_0,_0,_0.2),_0_2px_2px_0_rgba(0,_0,_0,_0.14),_0_1px_5px_0_rgba(0,_0,_0,_0.12)] transition-all duration-300 group-hover:border-blue-500"
                    :pt="{
                        image: 'rounded-md shadow-[0_3px_1px_-2px_rgba(0,_0,_0,_0.2),_0_2px_2px_0_rgba(0,_0,_0,_0.14),_0_1px_5px_0_rgba(0,_0,_0,_0.12)]',
                    }"
                />
            </div>
        </div>

        <input ref="fileInput" name="fileInput" type="file" accept="image/*" @change="onFileChange" class="hidden" />

        <div v-show="previewImage" class="flex w-[200px] flex-col gap-4 sm:max-w-[150px]">
            <div @contextmenu.prevent="showMenu" class="mobile-area">
                <AvatarCropper
                    ref="cropper"
                    :output-size="512"
                    :show-grid="true"
                    :show-circle="false"
                    @cropped="crop"
                    class="shadow-[0_3px_1px_-2px_rgba(0,_0,_0,_0.2),_0_2px_2px_0_rgba(0,_0,_0,_0.14),_0_1px_5px_0_rgba(0,_0,_0,_0.12)]"
                />
                <ContextMenu ref="ctxMenu" :model="menuItems" />
            </div>
            <div class="zoom-control mb-2">
                <Slider v-model="zoomValue" :min="0" :max="10" :step="1" class="zoom-slider" />
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import AvatarCropper from '@sakhnovkrg/vue-avatar-cropper';
import '@sakhnovkrg/vue-avatar-cropper/dist/index.css';
import axios from 'axios';
import Avatar from 'primevue/avatar';
import { ref, watch } from 'vue';

const emit = defineEmits<{
    (e: 'cropped', value: string): void;
    (e: 'delete', value: string): void;
    (e: 'error', message: string): void;
}>();

const croppedImage = ref('');
const previewImage = ref('');
const path = ref('');

const cropper = ref<InstanceType<typeof AvatarCropper>>();
const fileInput = ref();
const zoomValue = ref(0);

const ctxMenu = ref();

const showMenu = (event: Event) => {
    ctxMenu.value.show(event);
};

watch(zoomValue, (newValue, oldValue) => {
    if (!cropper.value) return;

    if (newValue > oldValue) {
        cropper.value.zoomIn();
    } else {
        cropper.value.zoomOut();
    }
});

const crop = (cropped: string) => {
    croppedImage.value = cropped;
    previewImage.value = '';
};

const openFileDialog = () => {
    if (path.value) {
        deleteCropper();
        fileInput.value.click();
        return;
    }
    if (fileInput.value) {
        //console.log(croppedImage.value);
        fileInput.value.click();
    }
};

const onFileChange = (event: Event) => {
    const target = event.target as HTMLInputElement;
    const file = target.files?.[0];
    if (file && cropper.value) {
        cropper.value.loadImage(file);
        previewImage.value = URL.createObjectURL(file);
        target.value = '';
    }
};

const uploadCropper = () => {
    const formData = new FormData();
    formData.append('fileInput', croppedImage.value);
    axios
        .post('/api/upload', formData)
        .then((res) => {
            path.value = res.data.file_path;
            emit('cropped', path.value);
        })
        .catch(function (error) {
            console.log(error.response.data.message);
        });
};

const deleteCropper = () => {
    const encodedPath = encodeURIComponent(path.value);
    axios
        .delete(`/api/destroy?path=${encodedPath}`)
        .then((res) => {
            console.log(res.data);
            emit('delete', res.data.message); // Отправляем новое значение,
            removeAvatar();
        })
        .catch(function (error) {
            console.log(error.response.data.message);
        });
};

const removeAvatar = () => {
    croppedImage.value = '';
    previewImage.value = '';
    fileInput.value = null;
};

const menuItems = ref([
    {
        label: 'Crop and Save',
        icon: 'pi pi-save',
        command: () => {
            cropper.value?.cropImage();
            uploadCropper();
            zoomValue.value = 0;
        },
    },
    {
        label: 'Reset Zoom',
        icon: 'pi pi-search-minus',
        command: () => {
            cropper.value?.resetZoom();
            zoomValue.value = 0;
        },
    },
    { label: 'Reset', icon: 'pi pi-trash', command: () => removeAvatar() },
]);

//The function converts base64 to a File object If the server does not have base64_decode conversion
// const dataURLtoFile= (dataUrl: string, filename = 'image.png')=> {
//     // 1. Разделяем строку на части
//     const arr = dataUrl.split(',');
//
//     // 2. Извлекаем MIME-тип (например, 'image/png')
//
//     const mime = arr[0].match(/:(.*?);/)[1];
//
//     // 3. Декодируем base64-часть
//     const bstr = atob(arr[1]);
//     // 4. Создаём Uint8Array для бинарных данных
//     let n = bstr.length;
//     const u8arr = new Uint8Array(n);
//
//     while (n--) {
//         u8arr[n] = bstr.charCodeAt(n);
//     }
//
//     // 5. Возвращаем File-объект
//     return new File([u8arr], filename, { type: mime });
// }
</script>
