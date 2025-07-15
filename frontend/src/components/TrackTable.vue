<template>
  <div class="app-container">
    <button class="add-track-btn" @click="openForm">Add Track</button>
    <table class="track-table">
      <thead>
      <tr>
        <th>Title</th>
        <th>Artist</th>
        <th>Duration</th>
        <th>ISRC</th>
        <th>Actions</th>
      </tr>
      </thead>
      <tbody>
      <tr v-for="track in computedTracks" :key="track.id">
        <td>{{ track.title }}</td>
        <td>{{ track.artist }}</td>
        <td>{{ formatDuration(track.duration) }}</td>
        <td>{{ track.isrc }}</td>
        <td><button @click="editTrack(track)">Edit</button></td>
      </tr>
      </tbody>
    </table>
    <TrackForm
        :show="showModal"
        :isEditing="isEditing"
        :track="currentTrack"
        @close="closeForm"
    />
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import { useTrackStore } from '../../trackStore';
import TrackForm from './TrackForm.vue';

// Reactive state for modal visibility and editing
const store = useTrackStore();
const showModal = ref(false);
const isEditing = ref(false);
const currentTrack = ref(null);

// Use computed property to ensure reactivity of store.tracks
const computedTracks = computed(() => store.tracks);

// Open form for adding a new track
async function openForm() {
  isEditing.value = false;
  currentTrack.value = null;
  showModal.value = true;
}

// Open form for editing a track, copying track data to avoid mutating store
async function editTrack(track) {
  isEditing.value = true;
  currentTrack.value = { ...track };
  showModal.value = true;
}

// Close form and refresh tracks to ensure table updates
async function closeForm() {
  showModal.value = false;
  await store.fetchTracks(); // Refresh tracks after form submission
}

// Format duration from seconds to mm:ss for user-friendly display
function formatDuration(seconds) {
  const minutes = Math.floor(seconds / 60);
  const secs = seconds % 60;
  return `${minutes}:${secs.toString().padStart(2, '0')}`;
}

// Fetch tracks on component mount to populate table
onMounted(async () => {
    await store.fetchTracks();
});
</script>