<template>
  <form
    method="POST"
    action="/api/events"
    @submit.prevent="onSubmit"
    @keydown="form.errors.clear($event.target.name)"
  >
    <div class="modal-card">
      <header class="modal-card-head">
        <p class="modal-card-title">Add an Event</p>
      </header>

      <section class="modal-card-body">
      <b-field
        label="Event Name"
        :type="form.errors.has('name') ? 'is-danger' : ''"
        :message="form.errors.has('name') ? form.errors.get('name') : ''"
      >
          <b-input
            type="text"
            v-model="form.name"
            size="is-medium"
            :disabled="isSubmitting"
            required
          ></b-input>
        </b-field>

        <template>
          <b-datepicker
            v-model="form.date"
            inline
          ></b-datepicker>
        </template>
      </section>

      <footer class="modal-card-foot">
        <button class="button" type="button" @click="$parent.close()" :disabled="isSubmitting">Close</button>
        <button
          class="button is-primary"
          type="submit"
          v-bind:class="{'is-loading' : isSubmitting}"
          :disabled="isSubmitting || form.errors.any()"
        >Add Event</button>
      </footer>
    </div>
  </form>
</template>

<script>
import Form from '../helpers/Form';

export default {
  name: 'add-event',

  data() {
    return {
      isSubmitting: false,
      isFullWidth: true,
      form: new Form({
        name: '',
        date: new Date(),
      }),
    };
  },

  methods: {
    onSubmit() {
      this.isSubmitting = true;

      this.form.post('/api/events')
        .then(({ data }) => {
          this.isSubmitting = false;

          window.location.href = `/events/${data.id}`;
        })
        .catch(() => {
          this.isSubmitting = false;

          window.flash('There was a problem saving your event!', 'danger');
        });
    },
  },
};
</script>

<style scoped>
  .modal-card {
    width: auto;
  }
</style>
