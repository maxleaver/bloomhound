<template>
  <form
    method="PATCH"
    action="/api/password"
    @submit.prevent="onSubmit"
    @keydown="form.errors.clear($event.target.name)"
  >
    <b-field
      label="Current Password"
      :type="form.errors.has('current_password') ? 'is-danger' : ''"
      :message="form.errors.has('current_password') ? form.errors.get('current_password') : ''"
    >
      <b-input
        type="password"
        v-model="form.current_password"
        :disabled="isSubmitting"
        size="is-medium"
        name="current_password"
        required
      >
      </b-input>
    </b-field>

    <b-field
      label="New Password"
      :type="form.errors.has('password') ? 'is-danger' : ''"
      :message="form.errors.has('password') ? form.errors.get('password') : ''"
    >
      <b-input
        type="password"
        v-model="form.password"
        :disabled="isSubmitting"
        size="is-medium"
        name="password"
        required
      >
      </b-input>
    </b-field>

    <b-field
      label="Confirm your new password"
    >
      <b-input
        type="password"
        v-model="form.password_confirmation"
        :disabled="isSubmitting"
        size="is-medium"
        name="password_confirmation"
        required
      >
      </b-input>
    </b-field>

    <button
      class="button is-primary"
      type="submit"
      v-bind:class="{'is-loading' : isSubmitting}"
      :disabled="isSubmitting || form.errors.any()"
    >Update Password</button>
  </form>
</template>

<script>
import Form from '../helpers/Form';

export default {
  name: 'update-password',

  data() {
    return {
      isSubmitting: false,
      form: new Form({
        current_password: '',
        password: '',
        password_confirmation: '',
      }),
    };
  },

  methods: {
    onSubmit() {
      this.isSubmitting = true;

      this.form.patch('/api/password')
        .then(() => {
          this.isSubmitting = false;
          window.flash('Password updated!', 'success');
        })
        .catch(() => {
          this.isSubmitting = false;
        });
    },
  },
};
</script>
