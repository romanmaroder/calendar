<script setup lang="ts">
import { getInitials } from '@/composables/useInitials';
import { Branch } from '@/types';
import { computed, PropType } from 'vue';
import { getPhone } from '@/composables/utils/phone/usePhoneLink';

const props = defineProps({
    branch: { type: Object as PropType<Branch | null> },
});

const showAvatar = computed(() => props.branch?.avatar && props.branch?.avatar !== '');
</script>

<template>
    <Card class="w-full rounded-xl shadow-sm not-dark:!bg-gray-100">
        <template #content>
            <slot>
                <div
                    class="flex flex-row items-center justify-around space-y-4 space-x-2 text-center md:flex-col md:justify-center">
                    <Avatar
                        v-if="!showAvatar"
                        size="xlarge"
                        class="shadow-[0_3px_1px_-2px_rgba(0,_0,_0,_0.2),_0_2px_2px_0_rgba(0,_0,_0,_0.14),_0_1px_5px_0_rgba(0,_0,_0,_0.12)]"
                        :label="branch?.name ? getInitials(branch?.name) : 'JD'"
                    />
                    <Image
                        v-else
                        :src="branch?.avatar"
                        :alt="branch?.avatar"
                        preview
                        :pt="{
                            image: {
                                class: 'max-w-[64px] max-h-[64px] rounded-md shadow-[0_3px_1px_-2px_rgba(0,_0,_0,_0.2),_0_2px_2px_0_rgba(0,_0,_0,_0.14),_0_1px_5px_0_rgba(0,_0,_0,_0.12)]',
                            },
                        }"
                    />
                    <div class="flex flex-col">
                        <p class="text-center text-lg leading-tight font-bold break-all">
                            {{ branch?.name }}
                        </p>
                        <Button
                            class="m-0! p-0! font-medium!"
                            as="a"
                            variant="link"
                            :label="branch?.phone"
                            :href="`tel:${getPhone(branch?.phone)}`"
                            rel="noopener"
                            size="large"
                        />
                        <time class="font-light" :datetime="branch?.created_at"
                            ><small>{{ branch?.created_at }}</small></time
                        >
                    </div>
                </div>
            </slot>
        </template>
    </Card>
</template>

<style scoped></style>
