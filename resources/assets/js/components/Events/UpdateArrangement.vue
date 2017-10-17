<template>
  <form
    method="POST"
    @submit.prevent="onSubmit"
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

    <button
      class="button is-primary"
      type="submit"
      v-bind:class="{'is-loading' : isSubmitting}"
      :disabled="isSubmitting"
    >Update Arrangement</button>
  </form>
</template>

<script>
import Form from 'helpers/Form';

export default {
  name: 'update-arrangement',

  props: {
    arrangement: Object,
  },

  data() {
    return {
      form: new Form({
        name: this.arrangement.name,
        quantity: this.arrangement.quantity,
      }, false),
      isSubmitting: false,
    };
  },

  methods: {
    onSubmit() {
      this.isSubmitting = true;

      this.form.patch(`/api/arrangements/${this.arrangement.id}`)
        .then((data) => {
          window.flash(`${this.form.name} was updated successfully!`, 'success');

          this.$emit('updated', data);
        })
        .catch(() => {
          window.flash(`There was a problem updating ${this.form.name}. Please try again.`, 'danger');
        })
        .then(() => {
          this.isSubmitting = false;
        });
    },
  },
};
</script>
