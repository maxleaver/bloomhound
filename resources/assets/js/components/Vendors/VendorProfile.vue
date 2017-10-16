<template>
  <div class="box">
    <div class="level">
      <div class="level-left">
        <h1 class="title">Profile</h1>
      </div>
      <div class="level-right">
        <button class="button is-link" @click="showForm = !showForm">{{ editButtonText }}</button>
      </div>
    </div>

    <div v-if="showForm">
      <form
        method="POST"
        @submit.prevent="onSubmit"
      >
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

        <b-field
          label="Email"
          :type="form.errors.has('email') ? 'is-danger' : ''"
          :message="form.errors.has('email') ? form.errors.get('email') : ''"
        >
          <b-input
            type="email"
            v-model="form.email"
            :disabled="isSubmitting"
            name="email"
          ></b-input>
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
          label="Phone"
          :type="form.errors.has('phone') ? 'is-danger' : ''"
          :message="form.errors.has('phone') ? form.errors.get('phone') : ''"
        >
          <b-input
            type="number"
            v-model="form.phone"
            :disabled="isSubmitting"
            name="phone"
          ></b-input>
        </b-field>

        <b-field
          label="Website"
          :type="form.errors.has('website') ? 'is-danger' : ''"
          :message="form.errors.has('website') ? form.errors.get('website') : ''"
        >
          <b-input
            type="text"
            v-model="form.website"
            :disabled="isSubmitting"
            name="website"
          ></b-input>
        </b-field>

        <div class="field is-grouped">
          <div class="control">
            <button
              class="button is-primary"
              type="submit"
              v-bind:class="{'is-loading' : isSubmitting}"
              :disabled="isSubmitting"
            >Update Vendor Profile</button>
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

    <div v-else class="content">
      <strong>{{ name }}</strong><br />
      <span v-if="address" class="address">{{ address }}<br /><br /></span>

      <span v-if="email">Email: <a :href="`mailto:${email}`">{{ email }}</a><br /></span>
      <span v-if="phone">Tel: {{ phone }}<br /></span>
      <a v-if="website" v-bind:href="website">{{ website }}</a>
    </div>
  </div>
</template>

<script>
import Form from 'helpers/Form';

export default {
  name: 'vendor-profile',

  props: {
    vendor: Object,
  },

  data() {
    return {
      address: this.vendor.address,
      email: this.vendor.email,
      form: new Form({
        address: this.vendor.address,
        email: this.vendor.email,
        name: this.vendor.name,
        phone: this.vendor.phone,
        website: this.vendor.website,
      }, false),
      isSubmitting: false,
      name: this.vendor.name,
      phone: this.vendor.phone,
      showForm: false,
      website: this.vendor.website,
    };
  },

  computed: {
    editButtonText() {
      return this.showForm ? 'Cancel' : 'Edit';
    },
  },

  methods: {
    onSubmit() {
      this.isSubmitting = true;

      this.form.patch(`/api/vendors/${this.vendor.id}`)
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
      this.address = this.form.address;
      this.email = this.form.email;
      this.name = this.form.name;
      this.phone = this.form.phone;
      this.website = this.form.website;
    },
  },
};
</script>
