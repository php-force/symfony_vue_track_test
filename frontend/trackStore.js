import { defineStore } from 'pinia';
import axios from 'axios';

export const useTrackStore = defineStore('track', {
    state: () => ({
        tracks: [],
    }),
    actions: {
        async fetchTracks() {
            const response = await axios.get('/api/tracks');
            this.tracks = response.data;
            return response.data;
        },
        async createTrack(data) {
            const response = await axios.post('/api/tracks', data);
            this.tracks.push(response.data);
            return response.data;
        },
        async updateTrack(id, data) {
            const response = await axios.put(`/api/tracks/${id}`, data);
            const index = this.tracks.findIndex((track) => track.id === id);
            if (index !== -1) {
                this.tracks[index] = response.data;
            }
            return response.data;
        },
    },
});