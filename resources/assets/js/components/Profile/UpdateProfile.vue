<template>
  <form
    method="PATCH"
    action="/api/profile"
    @submit.prevent="onSubmit"
    @keydown="form.errors.clear($event.target.name)"
  >
    <b-field
      label="Full Name"
      :type="form.errors.has('name') ? 'is-danger' : ''"
      :message="form.errors.has('name') ? form.errors.get('name') : ''"
    >
      <b-input
        type="text"
        v-model="form.name"
        :disabled="isSubmitting"
        size="is-medium"
        name="name"
        required
      ></b-input>
    </b-field>

    <b-field
      label="Email Address"
      :type="form.errors.has('email') ? 'is-danger' : ''"
      :message="form.errors.has('email') ? form.errors.get('email') : ''"
    >
      <b-input
        type="text"
        v-model="form.email"
        :disabled="isSubmitting"
        size="is-medium"
        name="email"
        required
      ></b-input>
    </b-field>

    <button
      class="button is-primary"
      type="submit"
      v-bind:class="{'is-loading' : isSubmitting}"
      :disabled="isSubmitting || form.errors.any()"
    >Update Profile</button>
  </form>
</template>

<script>
import Form from 'helpers/Form';

export default {
  name: 'update-profile',
  props: {
    name: String,
    email: String,
  },

  data() {
    return {
      isSubmitting: false,
      form: new Form({
        name: '',
        email: '',
      }, false),
    };
  },

  created() {
    this.form.name = this.name;
    this.form.email = this.email;
  },

  methods: {
    onSubmit() {
      this.isSubmitting = true;

      this.form.patch('/api/profile')
        .then(() => {
          window.flash('Your profile has been updated!', 'success');
        })
        .catch(() => {
          window.flash('There was a problem updating your profile', 'danger');
        })
        .then(() => {
          this.isSubmitting = false;
        });
    },
  },
};
</script>
