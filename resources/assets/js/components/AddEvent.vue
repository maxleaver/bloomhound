<template>
  <form
    method="POST"
    action="/api/events"
    @submit.prevent="onSubmit"
  >
    <div class="modal-card">
      <header class="modal-card-head">
        <p class="modal-card-title">Add an Event</p>
      </header>

      <section class="modal-card-body">
      <b-field label="Event Name">
          <b-input
            type="text"
            v-model="name"
            size="is-medium"
            :disabled="isSubmitting"
            required
          >
          </b-input>
        </b-field>

        <template>
          <b-datepicker v-model="date" inline></b-datepicker>
        </template>
      </section>

      <footer class="modal-card-foot">
        <button class="button" type="button" @click="$parent.close()" :disabled="isSubmitting">Close</button>
        <button class="button is-primary" v-bind:class="{'is-loading' : isSubmitting}">Add Event</button>
      </footer>
    </div>
  </form>
</template>

<script>
export default {
  name: 'add-event',

  data() {
    return {
      isSubmitting: false,
      name: '',
      date: new Date(),
      isFullWidth: true,
    };
  },

  methods: {
    onSubmit() {
      this.isSubmitting = true;

      window.axios.post('/api/events', {
        name: this.name,
        date: this.date,
      })
        .catch((error) => {
          window.flash(error.response.data, 'danger');
        })
        .then(({ data }) => {
          this.reset();

          window.flash('Event successfully added!', 'success');

          this.$emit('created', data.data);

          this.$parent.close();
        });
    },

    reset() {
      Object.assign(this.$data, this.$options.data());
    },
  },
};
</script>

<style scoped>
  .modal-card {
    width: auto;
  }
</style>