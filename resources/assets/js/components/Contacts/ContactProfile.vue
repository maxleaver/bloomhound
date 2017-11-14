<template>
  <div class="columns">
    <div class="column is-half">
      <div class="box">
        <div class="level">
          <div class="level-left">
            <h1 class="title">Profile</h1>
          </div>
          <div class="level-right">
            <button
              class="button is-text"
              @click="showForm = !showForm"
            >{{ editButtonText }}</button>
          </div>
        </div>

        <div v-if="showForm">
          <form
            method="POST"
            @submit.prevent="onSubmit"
          >
            <b-field grouped>
              <b-field
                label="First Name"
                :type="form.errors.has('first_name') ? 'is-danger' : ''"
                :message="form.errors.has('first_name') ? form.errors.get('first_name') : ''"
              >
                <b-input
                  type="text"
                  v-model="form.first_name"
                  :disabled="isSubmitting"
                  name="first_name"
                  required
                ></b-input>
              </b-field>

              <b-field
                label="Last Name"
                :type="form.errors.has('last_name') ? 'is-danger' : ''"
                :message="form.errors.has('last_name') ? form.errors.get('last_name') : ''"
              >
                <b-input
                  type="text"
                  v-model="form.last_name"
                  :disabled="isSubmitting"
                  name="last_name"
                  required
                ></b-input>
              </b-field>
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
              label="Relationship"
              :type="form.errors.has('relationship') ? 'is-danger' : ''"
              :message="form.errors.has('relationship') ? form.errors.get('relationship') : ''"
            >
              <b-input
                type="text"
                v-model="form.relationship"
                :disabled="isSubmitting"
                name="relationship"
                placeholder="Mother of the bride"
              ></b-input>
            </b-field>

            <div class="field is-grouped">
              <div class="control">
                <button
                  class="button is-primary"
                  type="submit"
                  v-bind:class="{'is-loading' : isSubmitting}"
                  :disabled="isSubmitting"
                >Update Contact Profile</button>
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
          <strong>{{ first_name }} {{ last_name }}</strong><br />
          <span v-if="address" class="address">{{ address }}<br /><br /></span>

          <span v-if="email">Email: <a :href="`mailto:${email}`">{{ email }}</a><br /></span>
          <span v-if="phone">Tel: {{ phone }}<br /></span>
        </div>
      </div>
    </div>
    <div class="column is-half">
      <div class="box">
        <h1 class="title">Customer</h1>

        <div class="content">
          <span v-if="relationship">I am the {{ relationship.toLowerCase() }} for<br /></span>
          <strong>{{ contact.customer.name }}</strong><br />
          <span v-if="contact.customer.address" class="address">{{ contact.customer.address }}<br /><br /></span>

          <span v-if="contact.customer.email">Email: <a :href="`mailto:${contact.customer.email}`">{{ contact.customer.email }}</a><br /></span>
          <span v-if="contact.customer.phone">Tel: {{ contact.customer.phone }}<br /></span>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Form from 'helpers/Form';

export default {
  name: 'contact-profile',

  props: {
    contact: Object,
  },

  data() {
    return {
      address: this.contact.address,
      email: this.contact.email,
      first_name: this.contact.first_name,
      form: new Form({
        address: this.contact.address,
        email: this.contact.email,
        first_name: this.contact.first_name,
        last_name: this.contact.last_name,
        phone: this.contact.phone,
        relationship: this.contact.relationship,
      }, false),
      isSubmitting: false,
      last_name: this.contact.last_name,
      phone: this.contact.phone,
      relationship: this.contact.relationship,
      showForm: false,
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

      this.form.patch(`/api/contacts/${this.contact.id}`)
        .then((data) => {
          this.isSubmitting = false;

          this.updateData();

          window.flash(`${this.form.first_name} ${this.form.last_name} updated successfully!`, 'success');

          this.$emit('updated', data);

          this.showForm = false;
        })
        .catch(() => {
          this.isSubmitting = false;

          window.flash(`There was a problem updating ${this.form.first_name} ${this.form.last_name}!`, 'danger');
        });
    },

    updateData() {
      this.address = this.form.address;
      this.email = this.form.email;
      this.first_name = this.form.first_name;
      this.last_name = this.form.last_name;
      this.phone = this.form.phone;
      this.relationship = this.form.relationship;
    },
  },
};
</script>
