<template>
  <form
    method="POST"
    @submit.prevent="onSubmit"
    @keydown="form.errors.clear($event.target.name)"
  >
    <b-field
      label="Add a Note"
      :type="form.errors.has('text') ? 'is-danger' : ''"
      :message="form.errors.has('text') ? form.errors.get('text') : ''"
    >
      <b-input
        type="textarea"
        v-model="form.text"
        :disabled="isSubmitting"
        required
      ></b-input>
    </b-field>

    <button
      class="button is-primary"
      type="submit"
      v-bind:class="{'is-loading' : isSubmitting}"
      :disabled="isSubmitting || form.errors.any()"
    >Add Note</button>
  </form>
</template>

<script>
import Form from 'helpers/Form';

export default {
  name: 'add-note',
  props: {
    url: String,
  },

  data() {
    return {
      isSubmitting: false,
      form: new Form({
        text: '',
      }),
    };
  },

  methods: {
    onSubmit() {
      this.isSubmitting = true;

      this.form.post(this.url)
        .then((data) => {
          window.flash('Note added!', 'success');

          this.$emit('created', data);
        })
        .catch(() => {
          window.flash('There was a problem saving your note!', 'danger');
        })
        .then(() => {
          this.isSubmitting = false;
        });
    },
  },
};
</script>
