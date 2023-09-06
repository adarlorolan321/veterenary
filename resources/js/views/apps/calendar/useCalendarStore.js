import axios from '@axios'

export const useCalendarStore = defineStore('calendar', {
  // arrow function recommended for full type inference
  state: () => ({
    availableCalendars: [
      {
        color: 'error',
        label: 'Grooming',
      },
      {
        color: 'primary',
        label: 'Checkup',
      },
      {
        color: 'warning',
        label: 'Others',
      },
      {
        color: 'success',
        label: 'Personal',
      },
    
    ],
    selectedCalendars: ['Personal', 'Checkup', 'Grooming', 'Others'],
  }),
  actions: {
    async fetchEvents() {
      return axios.get('/appointments', { params: { calendars: this.selectedCalendars.join(',') } })
    },
    async addEvent(event) {
     
      return axios.post('/appointments', { event })
    },
    async updateEvent(event) {
      return axios.put(`/appointments/${event.id}`, { event })
    },
    async removeEvent(eventId) {
      return axios.delete(`/appointments/${eventId}`)
    },
  },
})
