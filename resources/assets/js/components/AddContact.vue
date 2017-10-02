<template>
  <form
    method="POST"
    action="/api/contacts"
    @submit.prevent="onSubmit"
    @keydown="form.errors.clear($event.target.name)"
  >
    <div class="modal-card">
      <header class="modal-card-head">
        <p class="modal-card-title">Add a Contact</p>
      </header>

      <section class="modal-card-body">
        <b-field label="Customer" v-if="showCustomerList">
          <b-select
            placeholder="Select a customer"
            icon="person"
            v-model="form.customer_id"
            expanded
            required
            :disabled="isSubmitting"
            @input="changeCustomer"
          >
            <option
              v-for="customer in customers"
              :value="customer.id"
              :key="customer.id">
              {{ customer.name }}
            </option>
          </b-select>
        </b-field>

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
          ></b-input>
        </b-field>

        <b-field
          label="Phone"
          :type="form.errors.has('phone') ? 'is-danger' : ''"
          :message="form.errors.has('phone') ? form.errors.get('phone') : ''"
        >
          <b-input
            type="phone"
            v-model="form.phone"
            :disabled="isSubmitting"
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
            placeholder="Mother of the bride"
          ></b-input>
        </b-field>

        <b-field
          label="Address"
          :type="form.errors.has('address') ? 'is-danger' : ''"
          :message="form.errors.has('address') ? form.errors.get('address') : ''"
        >
          <b-input
            type="text"
            v-model="form.address"
            :disabled="isSubmitting"
          ></b-input>
        </b-field>
      </section>

      <footer class="modal-card-foot">
        <button class="button" type="button" @click="$parent.close()" :disabled="isSubmitting">Close</button>
        <button
          class="button is-primary"
          type="submit"
          v-bind:class="{'is-loading' : isSubmitting}"
          :disabled="isSubmitting || form.errors.any()"
        >Add Contact</button>
      </footer>
    </div>
  </form>
</template>

<script>
import Form from '../helpers/Form';

export default {
  name: 'add-contact',
  props: {
    customer_id: Number,
    customers: Array,
  },

  data() {
    return {
      isSubmitting: false,
      form: new Form({
        customer_id: '',
        first_name: '',
        last_name: '',
        email: '',
        phone: '',
        relationship: '',
        address: '',
      }),
      showCustomerList: false,
    };
  },

  created() {
    if (this.customer_id) {
      this.form.customer_id = this.customer_id;
      return;
    }

    this.showCustomerList = true;
  },

  methods: {
    onSubmit() {
      this.isSubmitting = true;

      this.form.post('/api/contacts')
        .then((data) => {
          this.isSubmitting = false;

          window.flash('Contact successfully added!', 'success');

          this.$emit('created', data);

          this.$parent.close();
        })
        .catch(() => {
          this.isSubmitting = false;

          window.flash('There was a problem saving your contact!', 'danger');
        });
    },

    changeCustomer(id) {
      this.form.customer_id = id;
    },
  },
};
</script>
