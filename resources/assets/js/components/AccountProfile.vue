<template>
  <div class="columns">
    <div class="column is-half">
      <h4 class="title is-4">Your Account Profile</h4>

      <form
        method="PATCH"
        action="/api/account"
        @submit.prevent="onSubmit"
        @keydown="form.errors.clear($event.target.name)"
      >
        <b-field
          label="Company Name"
          :type="form.errors.has('name') ? 'is-danger' : ''"
          :message="form.errors.has('name') ? form.errors.get('name') : ''"
        >
          <b-input
            type="string"
            v-model="form.name"
            :disabled="isSubmitting"
            name="name"
            required
          >
          </b-input>
        </b-field>

        <b-field
          label="Address"
          :type="form.errors.has('address') ? 'is-danger' : ''"
          :message="form.errors.has('address') ? form.errors.get('address') : ''"
        >
          <b-input
            type="textarea"
            v-model="form.address"
            :disabled="isSubmitting"
            name="address"
          ></b-input>
        </b-field>

        <b-field
          label="Website"
          :type="form.errors.has('website') ? 'is-danger' : ''"
          :message="form.errors.has('website') ? form.errors.get('website') : ''"
        >
          <b-input
            type="string"
            v-model="form.website"
            :disabled="isSubmitting"
            name="website"
          >
          </b-input>
        </b-field>

        <b-field
          label="Email Address"
          :type="form.errors.has('email') ? 'is-danger' : ''"
          :message="form.errors.has('email') ? form.errors.get('email') : ''"
        >
          <b-input
            type="email"
            v-model="form.email"
            :disabled="isSubmitting"
            name="email"
          >
          </b-input>
        </b-field>

        <b-field
          label="Phone"
          :type="form.errors.has('phone') ? 'is-danger' : ''"
          :message="form.errors.has('phone') ? form.errors.get('phone') : ''"
        >
          <b-input
            type="string"
            v-model="form.phone"
            :disabled="isSubmitting"
            name="phone"
          >
          </b-input>
        </b-field>

        <button
          class="button is-primary"
          type="submit"
          v-bind:class="{'is-loading' : isSubmitting}"
          :disabled="isSubmitting || form.errors.any()"
        >Update Account</button>
      </form>
    </div>

    <div class="column is-half">
      <account-preview
        :logo="account.logo_path"
        :name="form.name"
        :address="form.address"
        :website="form.website"
        :email="form.email"
        :phone="form.phone"
      ></account-preview>
    </div>
  </div>
</template>

<script>
import AccountPreview from './AccountPreview.vue';
import Form from '../helpers/Form';

export default {
  name: 'account-profile',
  components: { AccountPreview },

  props: {
    account: Object,
  },

  data() {
    return {
      isSubmitting: false,
      form: new Form({
        name: '',
        address: '',
        website: '',
        email: '',
        phone: '',
      }, false),
    };
  },

  created() {
    Object.keys(this.form.data()).forEach((key) => {
      this.form[key] = this.account[key];
    });
  },

  methods: {
    onSubmit() {
      this.isSubmitting = true;

      this.form.patch('/api/account')
        .then(() => {
          window.flash('Account updated!', 'success');
        })
        .catch(() => {
          window.flash('There was a problem updating your account.', 'danger');
        })
        .then(() => {
          this.isSubmitting = false;
        });
    },
  },
};
</script>
