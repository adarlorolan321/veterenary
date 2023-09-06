<script setup>
import { useCrud } from "@core/composable/useCrud";

const routeName = "appointments";

const formObject = {
  pet_id: "",
  vet_id: 1,
  appointment_date: "",
  appointment_time: "",
  reason_for_visit: "",
  status: "Pending",
};

let {
  isLoadingComponents,
  paginatedData,
  form,
  createPromise,
  updatePromise,
  deletePromise,
  handleCreate,
  isDialogVisible,
  serverQuery,
  handleServerQuery,
  handleEdit,
  formState,
} = useCrud(formObject, routeName);
const now = new Date();
const currentDate = now.getDate();
const currentMonth = now.toLocaleString("default", { month: "2-digit" });
const currentYear = now.getFullYear();
</script>

<script>
import Layout from "@/layouts/default.vue";
import { Head } from "@inertiajs/vue3";
import { useRoute } from "vue-router";

import { ref, onMounted } from "vue";

import FullCalendar from "@fullcalendar/vue3";
import dayGridPlugin from "@fullcalendar/daygrid";
import interactionPlugin from "@fullcalendar/interaction";
import timeGridPlugin from "@fullcalendar/timegrid";
import listPlugin from '@fullcalendar/list';

export default {
  name: "Staff",
  components: {
    FullCalendar, // make the <FullCalendar> tag available
  },
  layout: (h, page) => h(Layout, [page]),
  props: {
    data: Object,
    user: Object,
  },
  data() {
    return {
      calendarOptions: {
        plugins: [dayGridPlugin, interactionPlugin, timeGridPlugin, listPlugin],
        initialView: "dayGridMonth",
        headerToolbar: {
          start: "drawerToggler,prev,next title",
          end: "dayGridMonth,timeGridWeek,timeGridDay,listMonth",
        },
        events: [],

        // ❗ We need this to be true because when its false and event is allDay event and end date is same as start data then Full calendar will set end to null
        forceEventDuration: true,

        /*
        Enable dragging and resizing event
        Docs: https://fullcalendar.io/docs/editable
      */
        editable: true,

        /*
        Enable resizing event from start
        Docs: https://fullcalendar.io/docs/eventResizableFromStart
      */
        eventResizableFromStart: true,

        /*
        Automatically scroll the scroll-containers during event drag-and-drop and date selecting
        Docs: https://fullcalendar.io/docs/dragScroll
      */
        dragScroll: true,

        /*
        Max number of events within a given day
        Docs: https://fullcalendar.io/docs/dayMaxEvents
      */
        dayMaxEvents: 2,

        /*
        Determines if day names and week names are clickable
        Docs: https://fullcalendar.io/docs/navLinks
      */
        navLinks: true,
        
        eventClick({ event: clickedEvent }) {
          // * Only grab required field otherwise it goes in infinity loop
          // ! Always grab all fields rendered by form (even if it get `undefined`) otherwise due to Vue3/Composition API you might get: "object is not extensible"
          event.value = extractEventDataFromEventApi(clickedEvent);
          isEventHandlerSidebarActive.value = true;
        },

        // customButtons
        dateClick(info) {
          event.value = { ...event.value, start: String(new Date(info.date)) };
          isEventHandlerSidebarActive.value = true;
        },

        /*
          Handle event drop (Also include dragged event)
          Docs: https://fullcalendar.io/docs/eventDrop
          We can use `eventDragStop` but it doesn't return updated event so we have to use `eventDrop` which returns updated event
        */
        eventDrop({ event: droppedEvent }) {
          console.log(droppedEvent);
          // updateEvent(extractEventDataFromEventApi(droppedEvent));
        },

        /*
          Handle event resize
          Docs: https://fullcalendar.io/docs/eventResize
        */
        eventResize({ event: resizedEvent }) {
          if (resizedEvent.start && resizedEvent.end)
            updateEvent(extractEventDataFromEventApi(resizedEvent));
        },
        customButtons: {
          drawerToggler: {
            text: "calendarDrawerToggler",
            click() {
              isLeftSidebarOpen.value = true;
            },
          },
        },
      },
    };
  },
  watch: {
    data: {
      handler(newData) {
        // When the data prop changes, update the events with the transformed data
        this.calendarOptions.events = this.transformedEvents;
      },
      immediate: true, // Call the handler immediately when the component is created
    },
  },
  computed: {
    transformedEvents() {
      // Transform the prop data into the events format expected by FullCalendar
      return this.data.data.map((item) => ({
        title: "Taken",
        start: `${item.appointment_date}T${item.appointment_time}`,
      }));
    },
  },

  methods: {
    handleDateClick: function (arg) {
      alert("date click! " + arg.dateStr);
    },
  },
};
</script>

<template>
  <VNavigationDrawer
    width="420"
    location="end"
    class="scrollable-content"
    v-model="isDialogVisible"
  >
    <div class="container">
      <div class="form-group mb-3 mx-3">
        <label for="">Pet <span class="required">*</span></label>
        <VSelect
          v-model="form.pet_id"
          :items="[1, 2]"
          placeholder="Select Pet"
          :class="{ 'is-invalid': form.errors.pet_id }"
          @update:modelValue="form.clearErrors('pet_id')"
        />
        <div class="invalid-feedback">
          {{ form.errors.pet_id }}
        </div>
      </div>
      <div class="form-group mb-3 mx-3">
        <AppDateTimePicker
          v-model="form.appointment_date"
          :config="{
            dateFormat: 'Y-m-d',
            disable: [
              {
                to: `${currentYear}-${currentMonth}-${currentDate}`,
                from: `1990-${currentMonth}-25`,
              },
            ],
          }"
          label="Date"
        />
      </div>
      <div class="form-group mb-3 mx-3">
        <AppDateTimePicker
          v-model="form.appointment_time"
          label="Time picker"
          :config="{ enableTime: true, noCalendar: true, dateFormat: 'H:i' }"
        />
      </div>
      <div class="form-group mb-3 mx-3">
        <VTextField
          v-model="form.reason_for_visit"
          label="Reason for visit"
          :error-messages="form.errors.reason_for_visit"
        />
      </div>
    </div>

    <VCardText class="d-flex justify-end flex-wrap gap-3">
      <VBtn color="secondary" variant="tonal" @click="isDialogVisible = false">
        Cancel
      </VBtn>
      <VBtn
        v-if="formState == 'create'"
        class="btn btn-primary"
        @click="createPromise"
      >
        <span
          v-if="form.processing"
          class="spinner-border me-1"
          role="status"
          aria-hidden="true"
        />
        Save
      </VBtn>
      <VBtn
        v-if="formState == 'update'"
        class="btn btn-primary"
        @click="updatePromise"
      >
        <span
          v-if="form.processing"
          class="spinner-border me-1"
          role="status"
          aria-hidden="true"
        />
        Save changes
      </VBtn>
    </VCardText>
  </VNavigationDrawer>
  <div class="d-flex justify-end">
    <VBtn color="primary" @click="handleCreate"> Add </VBtn>
  </div>
  <div>
    <VCard>
      <!-- `z-index: 0` Allows overlapping vertical nav on calendar -->
      <VLayout style="z-index: 0">
        <!-- 👉 Navigation drawer -->

        <VMain>
          <div class="calendar-container mx-5 mt-5">
            <FullCalendar class="calendar" :options="calendarOptions" />
          </div>
        </VMain>
      </VLayout>
    </VCard>
    <!--
      <CalendarEventHandler
      v-model:isDrawerOpen="isEventHandlerSidebarActive"
      :event="event"
      @add-event="addEvent"
      @update-event="updateEvent"
      @remove-event="removeEvent"
      /> 
    -->
  </div>
</template>

<style lang="scss">
@use "@core-scss/template/libs/full-calendar";

.calendars-checkbox {
  .v-label {
    color: rgba(var(--v-theme-on-surface), var(--v-high-emphasis-opacity));
    opacity: var(--v-high-emphasis-opacity);
  }
}

.calendar-add-event-drawer {
  &.v-navigation-drawer {
    border-end-start-radius: 0.375rem;
    border-start-start-radius: 0.375rem;
  }
}

.calendar-date-picker {
  display: none;

  + .flatpickr-input {
    + .flatpickr-calendar.inline {
      border: none;
      box-shadow: none;

      .flatpickr-months {
        border-block-end: none;
      }
    }
  }
}
</style>

<style lang="scss" scoped>
.v-layout {
  overflow: visible !important;

  .v-card {
    overflow: visible;
  }
}
</style>
