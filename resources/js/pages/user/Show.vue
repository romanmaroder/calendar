<script setup lang="ts">
  import Layout from '@/layouts/AppLayout.vue'
  import { Head } from '@inertiajs/vue3'

  defineProps({
      user: {
          type: Object,
          required: true,
      },
      count: {
          type: Number,
      },
  });
</script>

<template>
  <Layout>
    <Head title="Show" />
<h1>{{user.name}}</h1>
  </Layout>
</template>
