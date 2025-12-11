<!--loading an avatar with a context menu on the desktop right-click on mobile hold and release the component uploads
 a file to the public/avatars folder with a unique name
 on the backend create a controller with methods for adding and deleting a file create routes
-->
<script setup lang="ts">
import AvatarCropper from '@sakhnovkrg/vue-avatar-cropper';
import '@sakhnovkrg/vue-avatar-cropper/dist/index.css';
import axios from 'axios';
import Avatar from 'primevue/avatar';
import { computed, ref, watch } from 'vue';

const props = defineProps({
    avatar: {
        type: String,
        default: null,
    },
});

const emit = defineEmits<{
    (e: 'cropped', value: string): void;
    (e: 'delete'): void;
}>();

const croppedImage = ref('');
const previewImage = ref('');
const url = ref('');
const path = ref(props.avatar || '');

const cropper = ref<InstanceType<typeof AvatarCropper>>();
const fileInput = ref();
const zoomValue = ref(0);

const ctxCropper = ref();
const ctxAvatar = ref();

const loadImages = computed(() => {
    return props.avatar ? props.avatar : undefined;
});

watch(zoomValue, (newValue, oldValue) => {
    if (!cropper.value) return;

    if (newValue > oldValue) {
        cropper.value.zoomIn();
    } else {
        cropper.value.zoomOut();
    }
});

const encodedPath = computed(() => {
    return encodeURIComponent(path.value.replace(/^\/storage/, ''));
});

const crop = (cropped: string) => {
    croppedImage.value = cropped;
    previewImage.value = '';
};

const openFileDialog = () => {
    if (path.value || url.value) {
        deleteCropper();
        emit('delete');
    }
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
        target.value = '';
    }
};

const uploadCropper = () => {
    const formData = new FormData();
    formData.append('fileInput', croppedImage.value);
    axios
        .post('/api/upload', formData)
        .then((res) => {
            path.value = res.data.path;
            url.value = res.data.url;
            emit('cropped', url.value);
        })
        .catch(function (error) {
            console.log(error.response.data.message);
        });
};

const deleteCropper = () => {
    console.log(encodedPath.value);
    axios
        .delete(`/api/destroy?path=${encodedPath.value}`)
        .then(() => {
            removeAvatar();
        })
        .catch(function (error) {
            console.log(error.response.data.message);
        });
};

const saveCrop = () => {
    cropper.value?.cropImage();
    uploadCropper();
    zoomValue.value = 0;
};

const resetZoom = () => {
    cropper.value?.resetZoom();
    zoomValue.value = 0;
};
const removeAvatar = () => {
    croppedImage.value = '';
    previewImage.value = '';
    url.value = '';
    path.value = '';
    fileInput.value = null;
};

const showMenuCropper = (event: Event) => {
    ctxCropper.value.show(event);
};
const menuCropper = ref([
    {
        label: 'Crop and Save',
        icon: 'pi pi-save',
        command: () => saveCrop(),
    },
    {
        label: 'Reset Zoom',
        icon: 'pi pi-search-minus',
        command: () => resetZoom(),
    },
    { label: 'Reset', icon: 'pi pi-trash', command: () => removeAvatar() },
]);

const showMenuAvatar = (event: Event) => {
    if (croppedImage.value || loadImages.value) {
        ctxAvatar.value.show(event);
    }
};

const menuAvatar = ref([
    {
        label: 'Remove avatar',
        icon: 'pi pi-trash',
        command: () => {
            deleteCropper();
            emit('delete');
        },
    },
]);

//The function converts base64 to a File object If the server does not have base64_decode conversion
/*const dataURLtoFile= (dataUrl: string, filename = 'image.png')=> {
    // 1. Разделяем строку на части
    const arr = dataUrl.split(',');

    // 2. Извлекаем MIME-тип (например, 'image/png')

    const mime = arr[0].match(/:(.*?);/)[1];

    // 3. Декодируем base64-часть
    const bstr = atob(arr[1]);
    // 4. Создаём Uint8Array для бинарных данных
    let n = bstr.length;
    const u8arr = new Uint8Array(n);

    while (n--) {
        u8arr[n] = bstr.charCodeAt(n);
    }

    // 5. Возвращаем File-объект
    return new File([u8arr], filename, { type: mime });
}*/
</script>
<template>
    <div class="max-w-md justify-self-center">
        <div class="flex flex-col items-center gap-4">
            <div class="group cursor-pointer" @click="openFileDialog">
                <div @contextmenu.prevent="showMenuAvatar" class="mobile-area">
                    <Avatar
                        v-if="!previewImage"
                        :image="croppedImage ? croppedImage : loadImages"
                        :icon="!croppedImage && !loadImages ? 'pi pi-camera' : undefined"
                        size="xlarge"
                        :class="{ 'h-full! w-full! !shadow-none': croppedImage || loadImages }"
                        class="border-4 border-transparent shadow-[0_3px_1px_-2px_rgba(0,_0,_0,_0.2),_0_2px_2px_0_rgba(0,_0,_0,_0.14),_0_1px_5px_0_rgba(0,_0,_0,_0.12)] transition-all duration-300 group-hover:border-blue-500"
                        :pt="{
                            image: 'max-w-[64px] max-h-[64px] rounded-md shadow-[0_3px_1px_-2px_rgba(0,_0,_0,_0.2),_0_2px_2px_0_rgba(0,_0,_0,_0.14),_0_1px_5px_0_rgba(0,_0,_0,_0.12)]',
                        }"
                    />
                    <ContextMenu ref="ctxAvatar" :model="menuAvatar" />
                </div>
            </div>
        </div>

        <input ref="fileInput" name="fileInput" type="file" accept="image/*" @change="onFileChange" class="hidden" />

        <div v-show="previewImage" class="flex w-[200px] flex-col gap-4 sm:max-w-[150px]">
            <div @contextmenu.prevent="showMenuCropper" class="mobile-area">
                <AvatarCropper
                    ref="cropper"
                    :output-size="512"
                    :show-grid="true"
                    :show-circle="false"
                    @cropped="crop"
                    class="shadow-[0_3px_1px_-2px_rgba(0,_0,_0,_0.2),_0_2px_2px_0_rgba(0,_0,_0,_0.14),_0_1px_5px_0_rgba(0,_0,_0,_0.12)]"
                />
                <ContextMenu ref="ctxCropper" :model="menuCropper" />
                <Message class="mt-2" severity="secondary" variant="simple" :pt="{
                    text:'text-center w-full'
                }">Hold for
                    2sec</Message>
            </div>
            <div class="zoom-control mb-2">
                <Slider v-model="zoomValue" :min="0" :max="10" :step="1" class="zoom-slider" />
            </div>
        </div>
    </div>
</template>
