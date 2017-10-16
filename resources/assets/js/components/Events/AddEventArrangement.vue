<template>
  <form
    method="POST"
    @submit.prevent="onSubmit"
    @keydown="form.errors.clear($event.target.name)"
  >
    <div class="modal-card">
      <header class="modal-card-head">
        <p class="modal-card-title">Add an Arrangement</p>
      </header>

      <section class="modal-card-body">
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
      </section>

      <footer class="modal-card-foot">
        <button
          class="button is-primary"
          type="submit"
          v-bind:class="{'is-loading' : isSubmitting}"
          :disabled="isSubmitting || form.errors.any()"
        >Add Arrangement</button>
        <button
          class="button"
          type="button"
          @click="$parent.close()"
          :disabled="isSubmitting"
        >Nevermind</button>
      </footer>
    </div>
  </form>
</template>

<script>
import Form from 'helpers/Form';

export default {
  name: 'add-event-arrangement',
  props: {
    eventId: Number,
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
          window.flash('Arrangement added successfully!', 'success');

          this.$emit('created', data);

          this.$parent.close();
        })
        .catch(() => {
          window.flash('There was a problem saving your arrangement!', 'danger');
        })
        .then(() => {
          this.isSubmitting = false;
        });
    },
  },
};
</script>
