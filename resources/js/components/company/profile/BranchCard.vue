<script setup lang="ts">
import { PropType } from 'vue';
import { Branch } from '@/types';
import Accordion from 'primevue/accordion';
import AccordionPanel from 'primevue/accordionpanel';
import AccordionHeader from 'primevue/accordionheader';
import AccordionContent from 'primevue/accordioncontent';
import { getPhone } from '@/composables/utils/phone/usePhoneLink';

defineProps({
    branches: { type: Object as PropType<Branch | null> },
    title: { type: String, default: '' },
});
</script>

<template>
    <Card  class="rounded-xl shadow-sm not-dark:!bg-gray-100">
        <template #content>
            <div class="mb-3 flex items-start justify-between">
                <h3 class="text-sm font-semibold text-shadow-slate-500 md:text-lg dark:text-slate-300">{{ title }}</h3>
            </div>
            <Accordion :value="['0']" multiple>
                <AccordionPanel v-for="branch in branches" :key="branch?.id" :value="branch?.id">
                    <AccordionHeader>{{ branch?.name }}</AccordionHeader>
                    <AccordionContent>
                        <p class="m-0">
                            <Button
                            class="!px-0"
                            as="a"
                            variant="link"
                            :label="branch?.phone"
                            :href="'tel:' + getPhone(branch?.phone)"
                            rel="noopener"
                        /></p>
                        <p class="m-0">{{ branch.contact }}</p>
                        <p class="mt-2"><small class="flex justify-end"><data value="{{branch.created_at}}"
                                                                             class="m-0">{{
                                branch.created_at
                            }}</data></small></p>
                    </AccordionContent>
                </AccordionPanel>
            </Accordion>
        </template>
    </Card>
</template>

<style scoped></style>