<script setup lang="ts">
import type { HTMLAttributes } from 'vue'
import { cn } from '@/lib/utils'
import DialogUserClose from '@/components/ui/dialog/DialogUserClose.vue';
import { X } from 'lucide-vue-next'
const props = defineProps<{
  class?: HTMLAttributes['class']
}>()
</script>

<template>
  <div
    data-slot="dialog-header"
    :class="cn('flex flex-col gap-2 text-center sm:text-left', props.class)"
  >
    <slot />
  </div>

    <DialogUserClose
        class="ring-offset-background focus:ring-ring bg-accent text-muted-foreground absolute
        top-4 right-4 rounded-xs opacity-70 transition-opacity
         hover:opacity-100 focus:ring-2 focus:ring-offset-2
         focus:outline-hidden disabled:pointer-events-none [&_svg]:pointer-events-none [&_svg]:shrink-0 [&_svg:not([class*='size-'])]:size-4"
    >
        <X />
        <span class="sr-only">Close</span>
    </DialogUserClose>

</template>
