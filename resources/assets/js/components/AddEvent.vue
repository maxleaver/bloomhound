<template>
  <form
    method="POST"
    action="/api/events"
    @submit.prevent="onSubmit"
    @keydown="form.errors.clear($event.target.name)"
  >
    <div class="modal-card">
      <header class="modal-card-head">
        <p class="modal-card-title">Add an Event</p>
      </header>

      <section class="modal-card-body">
        <div class="columns">
          <div class="column">
            <b-field
              label="Event Name"
              :type="form.errors.has('name') ? 'is-danger' : ''"
              :message="form.errors.has('name') ? form.errors.get('name') : ''"
            >
              <b-input
                type="text"
                v-model="form.name"
                placeholder="My Customers Event"
                :disabled="isSubmitting"
                required
              ></b-input>
            </b-field>

            <div v-if="showCustomerSelect">
              <b-field
                label="Customer Name"
                :type="form.errors.has('customer') ? 'is-danger' : ''"
                :message="form.errors.has('customer') ? form.errors.get('customer') : ''"
              >
                <b-autocomplete
                  v-model="form.customer"
                  placeholder="e.g. Anne"
                  :keep-first="keepFirst"
                  :data="filteredDataObj"
                  field="name"
                  @select="onSelectChange"
                  :disabled="isSubmitting"
                  required
                ></b-autocomplete>
              </b-field>
            </div>
          </div>

          <div class="column">
            <b-field label="Select a Date">
              <b-datepicker
                v-model="form.date"
                inline
              ></b-datepicker>
            </b-field>
          </div>
        </div>
      </section>

      <footer class="modal-card-foot">
        <button class="button" type="button" @click="$parent.close()" :disabled="isSubmitting">Close</button>
        <button
          class="button is-primary"
          type="submit"
          v-bind:class="{'is-loading' : isSubmitting}"
          :disabled="isSubmitting || form.errors.any()"
        >Add Event</button>
      </footer>
    </div>
  </form>
</template>

<script>
import Form from '../helpers/Form';

export default {
  name: 'add-event',

  props: {
    customer_id: Number,
    customers: Array,
  },

  data() {
    return {
      isSubmitting: false,
      isFullWidth: true,
      form: new Form({
        name: '',
        customer_id: null,
        customer: '',
        date: new Date(),
      }),
      keepFirst: true,
      showCustomerSelect: true,
    };
  },

  created() {
    if (this.customer_id) {
      this.form.customer_id = this.customer_id;
      this.showCustomerSelect = false;
    }
  },

  computed: {
    filteredDataObj() {
      return this.customers.filter(option =>
        option.name
          .toString()
          .toLowerCase()
          .indexOf(this.form.customer.toLowerCase()) >= 0);
    },
  },

  methods: {
    onSubmit() {
      let url = '/api/events';

      this.isSubmitting = true;

      if (this.form.customer_id) {
        url = `/api/customers/${this.form.customer_id}/events`;
      }

      this.form.post(url)
        .then((data) => {
          window.location.href = `/events/${data.id}`;
        })
        .catch(() => {
          this.isSubmitting = false;

          window.flash('There was a problem saving your event!', 'danger');
        });
    },

    onSelectChange(option) {
      if (option && Object.prototype.hasOwnProperty.call(option, 'id')) {
        this.form.customer_id = option.id;
        return;
      }

      this.form.customer_id = null;
    },
  },
};
</script>

<style scoped>
  .modal-card {
    width: auto;
  }
</style>
