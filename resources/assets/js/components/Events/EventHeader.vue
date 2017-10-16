<template>
  <div class="hero is-light">
    <div class="hero-body">
      <div v-if="showForm" class="container">
        <form
          method="POST"
          @submit.prevent="onSubmit"
        >
          <div class="columns">
            <div class="column">
              <b-field
                label="Name"
                :type="form.errors.has('name') ? 'is-danger' : ''"
                :message="form.errors.has('name') ? form.errors.get('name') : ''"
              >
                <b-input
                  type="text"
                  v-model="form.name"
                  :disabled="isSubmitting"
                  name="name"
                  required
                ></b-input>
              </b-field>
            </div>

            <div class="column">
              <b-field label="Select a Date">
                <b-datepicker
                  v-model="form.date"
                  icon="today"
                ></b-datepicker>
              </b-field>
            </div>
          </div>

          <div class="field is-grouped">
            <div class="control">
              <button
                class="button is-primary"
                type="submit"
                v-bind:class="{'is-loading' : isSubmitting}"
                :disabled="isSubmitting"
              >Update Event</button>
            </div>

            <div class="control">
              <button
                class="button"
                @click.prevent="showForm = !showForm"
                :disabled="isSubmitting"
              >Nevermind</button>
            </div>
          </div>
        </form>
      </div>

      <div v-else class="container">
        <button
          class="button is-pulled-right"
          @click="showForm = !showForm"
        >Edit</button>

        <h1 class="title">{{ name }}</h1>
        <h2 class="subtitle">
          {{ date.format('MMMM Do YYYY') }} ({{ date.fromNow() }})
        </h2>
      </div>
    </div>
  </div>
</template>

<script>
import moment from 'moment';
import Form from 'helpers/Form';

export default {
  name: 'event-header',

  props: {
    event: Object,
  },

  data() {
    return {
      date: moment(this.event.date),
      form: new Form({
        date: new Date(this.event.date),
        name: this.event.name,
      }, false),
      isSubmitting: false,
      name: this.event.name,
      showForm: false,
    };
  },

  methods: {
    onSubmit() {
      this.isSubmitting = true;

      this.form.patch(`/api/events/${this.event.id}`)
        .then((data) => {
          this.isSubmitting = false;

          this.updateData();

          window.flash(`${this.form.name} updated successfully!`, 'success');

          this.$emit('updated', data);

          this.showForm = false;
        })
        .catch(() => {
          this.isSubmitting = false;

          window.flash(`There was a problem updating ${this.form.name}!`, 'danger');
        });
    },

    updateData() {
      this.date = moment(this.form.date);
      this.name = this.form.name;
    },
  },
};
</script>
