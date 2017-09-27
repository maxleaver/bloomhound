<template>
  <form
    method="POST"
    @submit.prevent="onSubmit"
    @keydown="form.errors.clear($event.target.name)"
  >
    <b-field grouped>
      <b-field
        label="Name"
        :type="form.errors.has('name') ? 'is-danger' : ''"
        :message="form.errors.has('name') ? form.errors.get('name') : ''"
      >
        <b-input
          type="text"
          v-model="form.name"
          placeholder="ex. Bridal Bouquet"
          :disabled="isSubmitting"
          required
        ></b-input>
      </b-field>

      <b-field
        label="Quantity"
        :type="form.errors.has('quantity') ? 'is-danger' : ''"
        :message="form.errors.has('quantity') ? form.errors.get('quantity') : ''"
      >
        <b-input
          type="number"
          v-model="form.quantity"
          :disabled="isSubmitting"
          required
        ></b-input>
      </b-field>
    </b-field>

    <div class="field is-grouped">
      <div class="control">
        <button
          class="button is-primary"
          type="submit"
          v-bind:class="{'is-loading' : isSubmitting}"
          :disabled="isSubmitting"
        >Add Arrangement</button>
      </div>

      <div class="control">
        <button class="button" @click.prevent="toggleForm">
          Nevermind
        </button>
      </div>
    </div>
  </form>
</template>

<script>
import Form from '../helpers/Form';

export default {
  name: 'add-event-arrangement',
  props: {
    eventId: Number,
    toggleForm: Function,
  },

  data() {
    return {
      isSubmitting: false,
      form: new Form({
        name: '',
        quantity: 1,
      }),
    };
  },

  methods: {
    onSubmit() {
      this.isSubmitting = true;

      this.form.post(`/api/events/${this.eventId}/arrangements`)
        .then((data) => {
          this.isSubmitting = false;

          window.flash('Arrangement added successfully!', 'success');

          this.$emit('created', data);
        })
        .catch(() => {
          this.isSubmitting = false;

          window.flash('There was a problem saving your arrangement!', 'danger');
        });
    },
  },
};
</script>
