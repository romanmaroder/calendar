<script setup lang="ts">
import { getFullname } from '@/composables/useFullname';
import { getInitials } from '@/composables/useInitials';
import { getPhone } from '@/composables/usePhoneLink';
import { User } from '@/types';
import { computed, PropType } from 'vue';

const props = defineProps({
    user: { type: Object as PropType<User | null>},
});

const showAvatar = computed(() => props.user?.avatar && props.user?.avatar !== '');
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
                        :label="user?.name ? getInitials(getFullname({ name: user?.name, surname: user?.surname })) : 'JD'"
                    />
                    <Image
                        v-else
                        :src="user?.avatar"
                        :alt="user?.avatar"
                        preview
                        :pt="{
                            image: {
                                class:
                                'max-w-[64px] max-h-[64px] rounded-md shadow-[0_3px_1px_-2px_rgba(0,_0,_0,_0.2),_0_2px_2px_0_rgba(0,_0,_0,_0.14),_0_1px_5px_0_rgba(0,_0,_0,_0.12)]',
                            },
                        }"
                    />
                    <h3 class="text-center text-lg leading-tight font-bold">
                        {{ getFullname({ name: user?.name, middlename: user?.middleName, surname: user?.surname }) }}
                    </h3>
                    <Button
                        class="m-0! p-0! text-sm! font-medium! text-slate-500! md:text-base! dark:text-slate-300!"
                        as="a"
                        variant="link"
                        :label="user?.phone"
                        :href="`tel:${getPhone(user?.phone)}`"
                        rel="noopener"
                    />

                    <Button
                        class="m-0! hidden! p-0! text-xs! font-medium! text-slate-500! md:block! dark:text-slate-300!"
                        as="a"
                        variant="link"
                        :label="user?.email"
                        :href="`mailto:${user?.email}`"
                        rel="noopener"
                    />
                    <div class="flex items-center justify-end md:hidden">
                        <Button as="a" variant="link" :href="`tel:${getPhone(user?.phone)}`" rel="noopener" icon="pi pi-phone" />
                        <Divider layout="vertical" />
                        <Button
                            class="font-medium text-slate-500 dark:text-slate-300"
                            as="a"
                            variant="link"
                            :href="`sms:${getPhone(user?.phone)}`"
                            rel="noopener"
                            icon="pi pi-envelope"
                        />
                    </div>
                </div>
            </slot>
        </template>
    </Card>
</template>

<style scoped></style>
