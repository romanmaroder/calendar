<script setup lang="ts"></script>

<template>
    <div class="max-w-9xl px-3 py-6 md:px-6 md:py-8">
        <div class="grid grid-cols-1 gap-3 md:gap-6 lg:grid-cols-12">
            <!-- Left + Center merged -->
            <div class="space-y-1 md:space-y-6 lg:col-span-9">
                <div class="grid grid-cols-1 gap-3 md:gap-6 md:grid-cols-3">
                    <div class="md:col-span-1">
                        <slot name="left-center-column" />
                    </div>

                    <div class="md:col-span-2">
                        <slot name="right-center-column" />
                    </div>
                </div>

                <slot name="center-column" />
            </div>

            <!-- Right column -->
            <aside class="space-y-3 self-start lg:col-span-3">
                <slot name="right-column" />
            </aside>
        </div>
    </div>
</template>

<style scoped>

</style>