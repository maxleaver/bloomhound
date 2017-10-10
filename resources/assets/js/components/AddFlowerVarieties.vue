<template>
  <div class="box">
    <form
      method="POST"
      @submit.prevent="onSubmit"
      @keydown="form.errors.clear($event.target.name)"
    >
      <b-field
        label="Add a Variety"
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

      <button
        class="button is-primary"
        type="submit"
        v-bind:class="{'is-loading' : isSubmitting}"
        :disabled="isSubmitting || form.errors.any()"
      >Add Variety</button>
    </form>
  </div>
</template>

<script>
import Form from '../helpers/Form';

export default {
  name: 'add-varieties',

  props: {
    id: Number,
  },

  data() {
    return {
      form: new Form({
        name: '',
      }),
      isSubmitting: false,
    };
  },

  methods: {
    onSubmit() {
      this.isSubmitting = true;

      this.form.post(`/api/flowers/${this.id}/varieties`)
        .then((data) => {
          window.flash('Flower variety added!', 'success');

          this.$emit('created', data);
        })
        .catch(() => {
          window.flash('There was a problem saving your flower variety!', 'danger');
        })
        .then(() => {
          this.isSubmitting = false;
        });
    },
  },
};
</script>
