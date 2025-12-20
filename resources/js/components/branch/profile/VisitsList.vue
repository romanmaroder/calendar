<script setup lang="ts">

defineProps({
    tabs: { type: Array, required: true },
    visitsFuture: { type: Array, required: true },
    visitsCurrent: { type: Array, required: true },
    visitsPast: { type: Array, required: true },
});
</script>

<template>
    <Tabs value="0" scrollable class="shadow-sm rounded-t-xl rounded-b-xl">
        <TabList
            :pt="{
                tabList: ' not-dark:!bg-gray-100 rounded-t-xl',
                content: ' not-dark:!bg-gray-100 rounded-t-xl',
            }"
        >
            <Tab v-for="tab in tabs" :key="tab.key" :value="tab.key"> {{`${tab.label} ${tab.count}`}}</Tab>
        </TabList>
        <TabPanels
            :pt="{
                root: { class: ' not-dark:!bg-gray-100 rounded-b-xl' },
            }"
        >
            <TabPanel v-for="tab in tabs" :key="tab.key" :value="tab.key" >
                <div>
                    <div v-if="tab.key === 'future'" class="space-y-4  divide-y-2 divide-gray-200 sm:divide-y-0">
                        <div v-for="(visit, idx) in visitsFuture" :key="idx"
                             class="grid grid-cols-1 items-center gap-4 sm:grid-cols-12">
                            <div class="text-sm sm:col-span-3">
                                <time class="text-xs text-slate-400 dark:text-slate-300" :datetime="visit.time">{{ visit.time }}</time>
                                <time class="block font-semibold" :datetime="visit.date">{{ visit.date }}</time>
                            </div>
                            <div class="text-sm sm:col-span-5">
                                <div class="text-xs text-slate-400 dark:text-slate-300">Услуга:</div>
                                <div class="font-medium">{{ visit.service }}</div>
                            </div>
                            <div class="text-sm sm:col-span-2">
                                <div class="text-xs text-slate-400 dark:text-slate-300">Врач:</div>
                                <div class="font-medium">{{ visit.master }}</div>
                            </div>
                            <div class="text-sm sm:col-span-2">
                                <div class="text-xs text-slate-400 dark:text-slate-300">Статус:</div>
                                <span class="font-medium">{{ visit.status }}</span>
                            </div>
                        </div>
                    </div>

                    <div v-if="tab.key === 'past'" class="space-y-4  divide-y-2 divide-gray-200 sm:divide-y-0">
                        <div v-for="(visit, idx) in visitsPast" :key="idx"
                             class="grid grid-cols-1 items-center gap-4 sm:grid-cols-12">
                            <div class="text-sm sm:col-span-3">
                                <time class="text-xs text-slate-400 dark:text-slate-300"
                                      :datetime="visit.time">{{visit.time}}</time>
                                <time class="block font-semibold" :datetime="visit.date">{{ visit.date }}</time>
                            </div>
                            <div class="text-sm sm:col-span-5">
                                <div class="text-xs text-slate-400 dark:text-slate-300">Услуга:</div>
                                <div class="font-medium">{{ visit.service }}</div>
                            </div>
                            <div class="text-sm sm:col-span-2">
                                <div class="text-xs text-slate-400 dark:text-slate-300">Мастер:</div>
                                <div class="font-medium">{{ visit.master }}</div>
                            </div>
                            <div class="text-sm sm:col-span-2">
                                <div class="text-xs text-slate-400 dark:text-slate-300">Статус:</div>
                                <span class="font-medium">{{ visit.status }}</span>
                            </div>
                        </div>
                    </div>

                    <div v-if="tab.key === 'planned'" class="space-y-4  divide-y-2 divide-gray-200 sm:divide-y-0">
                        <div v-for="(visit, idx) in visitsCurrent" :key="idx"
                             class="grid grid-cols-1 items-center gap-4 sm:grid-cols-12">
                            <div class="text-sm sm:col-span-3">
                                <time class="text-xs text-slate-400 dark:text-slate-300" :datetime="visit.time">{{ visit.time }}</time>
                                <time class="block font-semibold" :datetime="visit.date">{{ visit.date }}</time>
                            </div>
                            <div class="text-sm sm:col-span-5">
                                <div class="text-xs text-slate-400 dark:text-slate-300">Услуга:</div>
                                <div class="font-medium">{{ visit.service }}</div>
                            </div>
                            <div class="text-sm sm:col-span-2">
                                <div class="text-xs text-slate-400 dark:text-slate-300">Врач:</div>
                                <div class="font-medium">{{ visit.master }}</div>
                            </div>
                            <div class="text-sm sm:col-span-2">
                                <div class="text-xs text-slate-400 dark:text-slate-300">Статус:</div>
                                <span class="font-medium">{{ visit.status }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </TabPanel>
        </TabPanels>
    </Tabs>
</template>

<style scoped></style>
