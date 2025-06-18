<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

defineProps({
    links: {
        type: Object,
        default() {
            return {};
        },
    },
});
</script>

<template>
    <div v-if="links.links.length > 3" class="border-t border-gray-200 bg-white px-4 py-3 sm:px-6">
        <div class="flex flex-col items-center justify-between sm:flex-row">
            <div class="mb-4 sm:mb-0">
                <p class="text-sm text-gray-700">
                    Showing <span class="font-medium">{{ links.from }}</span> to <span class="font-medium">{{ links.to }}</span> of
                    <span class="font-medium">{{ links.total }}</span> results
                </p>
            </div>
            <div>
                <nav class="relative z-0 inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
                    <Link
                        v-for="(link, k) in links.links"
                        :key="k"
                        :disabled="link.url === null"
                        :class="{
                            'text-bold bg-gray-100 text-indigo-600': link.active,
                            'pointer-events-none cursor-not-allowed bg-gray-300': link.url === null,
                        }"
                        :href="link.url"
                        v-html="link.label"
                        class="relative inline-flex items-center border border-gray-300 px-4 py-2 text-sm font-medium hover:bg-indigo-100"
                    />
                </nav>
            </div>
        </div>
    </div>
</template>
