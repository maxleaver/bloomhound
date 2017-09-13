<template>
  <form
    method="POST"
    @submit.prevent="onSubmit"
  >
    <b-field label="Add a Note">
      <b-input
        type="textarea"
        v-model="text"
        :disabled="isSubmitting"
        required
      ></b-input>
    </b-field>

    <button class="button is-primary" v-bind:class="{'is-loading' : isSubmitting}">Add Note</button>
  </form>
</template>

<script>
export default {
  name: 'add-note',
  props: {
    url: String,
  },

  data() {
    return {
      isSubmitting: false,
      text: '',
    };
  },

  methods: {
    onSubmit() {
      this.isSubmitting = true;

      window.axios.post(this.url, {
        text: this.text,
      })
        .catch((error) => {
          window.flash(error.response.data, 'danger');
        })
        .then(({ data }) => {
          this.reset();

          window.flash('Note added!', 'success');

          this.$emit('created', data.data);
        });
    },

    reset() {
      Object.assign(this.$data, this.$options.data());
    },
  },
};
</script>
