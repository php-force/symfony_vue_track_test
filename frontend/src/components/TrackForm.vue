<template>
  <div class="modal" v-if="show">
    <div class="modal-content">
      <h2>{{ isEditing ? 'Edit Track' : 'Add Track' }}</h2>
      <form @submit.prevent="submitForm">
        <div class="form-group">
          <div class="input-group">
            <label>Title</label>
            <input v-model="form.title" required />
          </div>
          <span class="error" v-if="errors.title">{{ errors.title }}</span>
        </div>
        <div class="form-group">
          <div class="input-group">
            <label>Artist</label>
            <input v-model="form.artist" required />
          </div>
          <span class="error" v-if="errors.artist">{{ errors.artist }}</span>
        </div>
        <div class="form-group">
          <div class="input-group">
            <label>Duration (mm:ss)</label>
            <input v-model="form.durationInput" placeholder="mm:ss" @input="parseDuration" required />
          </div>
          <span class="error" v-if="errors.duration">{{ errors.duration }}</span>
        </div>
        <div class="form-group">
          <div class="input-group">
            <label>ISRC</label>
            <input v-model="form.isrc" @input="validateIsrc" />
          </div>
          <span class="error" v-if="errors.isrc">{{ errors.isrc }}</span>
        </div>
        <div class="form-actions">
          <button type="submit">Save</button>
          <button type="button" @click="close">Cancel</button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue';
import { useTrackStore } from '../../trackStore';

const props = defineProps({
  show: Boolean,
  isEditing: Boolean,
  track: Object,
});

const emit = defineEmits(['close']);

const trackStore = useTrackStore();
const form = ref({
  title: '',
  artist: '',
  duration: 0,
  durationInput: '',
  isrc: '',
});
const errors = ref({});

watch(
    () => props.track,
    (newTrack) => {
      if (newTrack) {
        form.value = {
          title: newTrack.title || '',
          artist: newTrack.artist || '',
          duration: newTrack.duration || 0,
          durationInput: newTrack.duration ? formatDuration(newTrack.duration) : '',
          isrc: newTrack.isrc || '',
        };
      } else {
        resetForm();
      }
    },
    { immediate: true }
);

function parseDuration() {
  const match = form.value.durationInput.match(/^(\d+):(\d{2})$/);
  if (match) {
    const minutes = parseInt(match[1]);
    const seconds = parseInt(match[2]);
    if (seconds < 60) {
      form.value.duration = minutes * 60 + seconds;
      errors.value.duration = '';
    } else {
      errors.value.duration = 'Seconds must be less than 60';
    }
  } else {
    errors.value.duration = 'Format must be mm:ss';
  }
}

function validateIsrc() {
  if (form.value.isrc && !/^[A-Z]{2}-[A-Z0-9]{3}-\d{2}-\d{5}$/.test(form.value.isrc)) {
    errors.value.isrc = 'Invalid ISRC format';
  } else {
    errors.value.isrc = '';
  }
}

function formatDuration(seconds) {
  const minutes = Math.floor(seconds / 60);
  const secs = seconds % 60;
  return `${minutes}:${secs.toString().padStart(2, '0')}`;
}

function resetForm() {
  form.value = { title: '', artist: '', duration: 0, durationInput: '', isrc: '' };
  errors.value = {};
}

async function submitForm() {
  errors.value = {};
  if (!form.value.title) errors.value.title = 'Title is required';
  if (!form.value.artist) errors.value.artist = 'Artist is required';
  if (!form.value.durationInput) errors.value.duration = 'Duration is required';
  if (Object.keys(errors.value).length > 0) return;

  try {
    const data = {
      title: form.value.title,
      artist: form.value.artist,
      duration: form.value.duration, // Send as integer (seconds)
      isrc: form.value.isrc || null,
    };
    if (props.track && props.track.id) {
      await trackStore.updateTrack(props.track.id, data);
    } else {
      await trackStore.createTrack(data);
    }
    resetForm();
    emit('close');
  } catch (error) {
    if (error.response && error.response.data) {
      Object.keys(error.response.data).forEach((key) => {
        errors.value[key] = error.response.data[key].join(', ');
      });
    }
  }
}

function close() {
  resetForm();
  emit('close');
}
</script>