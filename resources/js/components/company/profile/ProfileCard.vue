<script setup lang="ts">
import { getInitials } from '@/composables/useInitials';
import { Company } from '@/types';
import { computed, PropType } from 'vue';
import { getPhone } from '@/composables/utils/phone/usePhoneLink';

const props = defineProps({
    company: { type: Object as PropType<Company | null> },
});

const showAvatar = computed(() => props.company?.avatar && props.company?.avatar !== '');
</script>

<template>
    <Card class="w-full rounded-xl shadow-sm not-dark:!bg-gray-100">
        <template #content>
            <slot>
                <div class="flex flex-col items-center space-y-4 text-center">
                    <Avatar
                        v-if="!showAvatar"
                        size="xlarge"
                        class="shadow-[0_3px_1px_-2px_rgba(0,_0,_0,_0.2),_0_2px_2px_0_rgba(0,_0,_0,_0.14),_0_1px_5px_0_rgba(0,_0,_0,_0.12)]"
                        :label="company?.name ? getInitials(company?.name) : 'JD'"
                    />
                    <Image
                        v-else
                        :src="company?.avatar"
                        :alt="company?.avatar"
                        preview
                        :pt="{
                            image: {
                                class: 'max-w-[64px] max-h-[64px] rounded-md shadow-[0_3px_1px_-2px_rgba(0,_0,_0,_0.2),_0_2px_2px_0_rgba(0,_0,_0,_0.14),_0_1px_5px_0_rgba(0,_0,_0,_0.12)]',
                            },
                        }"
                    />
                    <h3
                        class="break-words md:w-48 xl:break-normal xl:w-auto text-center text-lg leading-tight font-bold">
                        {{ company?.name }}
                    </h3>
                    <Button
                        class="m-0! p-0! text-sm! font-medium! text-slate-500! md:text-base! dark:text-slate-300!"
                        as="a"
                        variant="link"
                        :label="company?.phone"
                        :href="`tel:${getPhone(company?.phone)}`"
                        rel="noopener"
                    />
                    <time class="font-light" :datetime="company?.created_at"
                        ><small>{{ company?.created_at }}</small></time
                    >
                </div>
            </slot>
        </template>
    </Card>
</template>

<style scoped></style>
