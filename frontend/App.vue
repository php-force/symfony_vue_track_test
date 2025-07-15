<template>
  <div>
    <h1>Track Manager</h1>
    <track-table />
  </div>
</template>

<script setup>
import TrackTable from './src/components/TrackTable.vue';
</script>

<style scoped>
h1 {
  text-align: center;
  margin-bottom: 20px;
}
</style>