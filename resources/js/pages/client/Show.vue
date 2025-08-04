<script setup lang="ts">
  import Layout from '@/layouts/AppLayout.vue'
  import { Head } from '@inertiajs/vue3'

  defineProps({
      client: {
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
<h1>{{client.name}}</h1>
  </Layout>
</template>
