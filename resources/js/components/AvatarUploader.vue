<!--Загрузка аватарки с контекстным меню
На десктопе клик правой кнопкой мыши
На мобильном удержать и отпустить
ДОДУМАТЬ ЛОГИКУ ЗАГРУЗКИ -->

<template>
    <div class="max-w-md">
        <div class="flex flex-col items-center gap-4">
            <div ref="triggerEl" class="group cursor-pointer" @click="openFileDialog">
                <Avatar
                    v-if="!previewImage"
                    :image="croppedImage ? croppedImage : undefined"
                    :icon="!croppedImage ? 'pi pi-camera' : undefined"
                    size="xlarge"
                    :class="{ 'h-[100px]! w-[100px]!': croppedImage }"
                    class="border-4 border-transparent transition-all duration-300 group-hover:border-blue-500"
                    :pt="{
                        image: 'rounded-md shadow-[0_3px_1px_-2px_rgba(0,_0,_0,_0.2),_0_2px_2px_0_rgba(0,_0,_0,_0.14),_0_1px_5px_0_rgba(0,_0,_0,_0.12)]',
                    }"
                />
            </div>
        </div>

        <input ref="fileInput" type="file" accept="image/*" @change="onFileChange" class="hidden" />

        <div class="flex w-[200px] flex-col gap-4 sm:max-w-[150px]" :class="{ hidden: !previewImage }">
            <div @contextmenu.prevent="showMenu" class="mobile-area max-w-[]">
                <AvatarCropper ref="cropper" :output-size="512" :show-grid="true" :show-circle="false" @cropped="crop" />
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
import Avatar from 'primevue/avatar';
import { ref, watch } from 'vue';


const croppedImage = ref('');
const previewImage = ref('');


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
    if (fileInput.value) {
        fileInput.value.click();
    }
};

const onFileChange = (event: Event) => {
    const target = event.target as HTMLInputElement;
    const file = target.files?.[0];
    if (file && cropper.value) {
        cropper.value.loadImage(file);
        previewImage.value = URL.createObjectURL(file);
        croppedImage.value = URL.createObjectURL(file);
        target.value = '';
    }
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
</script>
