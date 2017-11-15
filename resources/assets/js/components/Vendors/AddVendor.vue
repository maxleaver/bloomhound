<template>
  <form
    method="POST"
    action="/api/vendors"
    @submit.prevent="onSubmit"
    @keydown="form.errors.clear($event.target.name)"
  >
    <div class="modal-card">
      <header class="modal-card-head">
        <p class="modal-card-title">Add a Vendor</p>
      </header>

      <section class="modal-card-body">
        <b-field
          label="Name"
          :message="form.errors.has('name') ? form.errors.get('name') : ''"
          :type="form.errors.has('name') ? 'is-danger' : ''"
        >
          <b-input
            name="name"
            required
            size="is-large"
            type="text"
            v-model="form.name"
            :disabled="isSubmitting"
          ></b-input>
        </b-field>

        <b-field
          label="Email"
          :message="form.errors.has('email') ? form.errors.get('email') : ''"
          :type="form.errors.has('email') ? 'is-danger' : ''"
        >
          <b-input
            name="email"
            type="email"
            v-model="form.email"
            :disabled="isSubmitting"
          ></b-input>
        </b-field>

        <b-field
          label="Address"
          :message="form.errors.has('address') ? form.errors.get('address') : ''"
          :type="form.errors.has('address') ? 'is-danger' : ''"
        >
          <b-input
            maxlength="255"
            name="address"
            type="textarea"
            v-model="form.address"
            :disabled="isSubmitting"
          ></b-input>
        </b-field>

        <b-field
          label="Phone"
          :message="form.errors.has('phone') ? form.errors.get('phone') : ''"
          :type="form.errors.has('phone') ? 'is-danger' : ''"
        >
          <b-input
            name="phone"
            type="text"
            v-model="form.phone"
            :disabled="isSubmitting"
          ></b-input>
        </b-field>

        <b-field
          label="Website"
          :message="form.errors.has('website') ? form.errors.get('website') : ''"
          :type="form.errors.has('website') ? 'is-danger' : ''"
        >
          <b-input
            name="website"
            placeholder="www.example.com"
            type="text"
            v-model="form.website"
            :disabled="isSubmitting"
          ></b-input>
        </b-field>
      </section>

      <footer class="modal-card-foot">
        <button
          class="button"
          type="button"
          :disabled="isSubmitting"
          @click="$parent.close()"
        >Close</button>

        <button
          class="button is-primary"
          type="submit"
          v-bind:class="{'is-loading' : isSubmitting}"
          :disabled="isSubmitting || form.errors.any()"
        >Add Vendor</button>
      </footer>
    </div>
  </form>
</template>

<script>
import Form from 'helpers/Form';

export default {
  name: 'add-vendor',

  data() {
    return {
      isSubmitting: false,
      form: new Form({
        name: '',
        address: '',
        email: '',
        phone: '',
        website: '',
      }),
    };
  },

  methods: {
    onSubmit() {
      this.isSubmitting = true;

      this.form.post('/api/vendors')
        .then((data) => {
          window.flash('Vendor successfully added!', 'success');

          this.$emit('created', data);

          this.$parent.close();
        })
        .catch(() => {
          window.flash('There was a problem saving your vendor!', 'danger');
        })
        .then(() => {
          this.isSubmitting = false;
        });
    },
  },
};
</script>
