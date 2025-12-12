<script setup lang="ts">
import { computed, PropType } from 'vue';
import { Client } from '@/types';
import { getFullname } from '@/composables/useFullname';
import { getPhone } from '@/composables/usePhoneLink';
import { getInitials } from '@/composables/useInitials';

const props = defineProps({
    client: { type: Object as PropType<Client | null> },
});

const showAvatar = computed(() => props.client?.avatar && props.client?.avatar !== '');
</script>

<template>
    <Card class="w-full rounded-xl shadow-sm not-dark:!bg-gray-100">
        <template #content>
            <div class="flex flex-col items-center space-y-2 text-center">
                <slot>
                    <Avatar
                        v-if="!showAvatar"
                        size="large"
                        class="shadow-[0_3px_1px_-2px_rgba(0,_0,_0,_0.2),_0_2px_2px_0_rgba(0,_0,_0,_0.14),_0_1px_5px_0_rgba(0,_0,_0,_0.12)]"
                        :label="getInitials(getFullname({ name: client?.name, surname: client?.surname }))"
                    />
                    <Image
                        v-else
                        :src="client?.avatar"
                        :alt="client?.avatar"
                        preview
                        :pt="{
                            image: {
                                class: 'max-w-[48px] max-h-[48px] rounded-md shadow-[0_3px_1px_-2px_rgba(0,_0,_0,_0.2),_0_2px_2px_0_rgba(0,_0,_0,_0.14),_0_1px_5px_0_rgba(0,_0,_0,_0.12)]',
                            },
                        }"
                    />
                    <h3 class="text-center text-lg leading-tight font-bold">
                        {{ getFullname({ name: client?.name, middlename: client?.middleName, surname: client?.surname }) }}
                    </h3>
                    <Button
                        class="m-0! p-0! text-sm! font-medium! text-slate-500! md:text-base! dark:text-slate-300!"
                        as="a"
                        variant="link"
                        :label="client?.phone"
                        :href="`tel:${getPhone(client?.phone)}`"
                        rel="noopener"
                    />

                    <Button
                        class="m-0! hidden! p-0! text-xs! font-medium! text-slate-500! md:block! dark:text-slate-300!"
                        as="a"
                        variant="link"
                        :label="client?.email"
                        :href="`mailto:${client?.email}`"
                        rel="noopener"
                    />
                    <div class="flex items-center justify-end md:hidden">
                        <Button as="a" variant="link" :href="`tel:${getPhone(client?.phone)}`" rel="noopener" icon="pi pi-phone" />
                        <Divider layout="vertical" />
                        <Button
                            class="font-medium text-slate-500 dark:text-slate-300"
                            as="a"
                            variant="link"
                            :href="`sms:${getPhone(client?.phone)}`"
                            rel="noopener"
                            icon="pi pi-envelope"
                        />
                    </div>
                </slot>
            </div>
        </template>
    </Card>
</template>

<style scoped></style>