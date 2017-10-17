<template>
  <form
    method="POST"
    @submit.prevent="onSubmit"
    @keydown="form.errors.clear($event.target.name)"
  >
    <div class="modal-card">
      <header class="modal-card-head">
        <p class="modal-card-title">Add a Delivery</p>
      </header>

      <section class="modal-card-body">
        <div class="columns">
          <div class="column">
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
              label="Description"
              :type="form.errors.has('description') ? 'is-danger' : ''"
              :message="form.errors.has('description') ? form.errors.get('description') : ''"
            >
              <b-input
                type="text"
                v-model="form.description"
                :disabled="isSubmitting"
              ></b-input>
            </b-field>

            <b-field
              label="Fee"
              :type="form.errors.has('fee') ? 'is-danger' : ''"
              :message="form.errors.has('fee') ? form.errors.get('fee') : ''"
            >
              <b-input
                type="number"
                v-model="form.fee"
                :disabled="isSubmitting"
              ></b-input>
            </b-field>
          </div>

          <div class="column">
            <b-field label="Delivery Time">
              <time-picker
                :format="dateFormat"
                hide-clear-button
                v-model="deliveryTime"
              ></time-picker>
            </b-field>

            <b-field label="Delivery Date">
              <b-datepicker
                v-model="form.deliver_on"
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
        >Add Delivery</button>
      </footer>
    </div>
  </form>
</template>

<script>
import Form from 'helpers/Form';
import TimePicker from 'components/TimePicker';
import moment from 'moment';

export default {
  name: 'add-delivery',
  components: { TimePicker },
  props: {
    eventId: Number,
  },

  data() {
    return {
      dateFormat: 'hh:mm A',
      deliveryTime: {
        hh: '04',
        mm: '00',
        ss: '00',
        A: 'PM',
      },
      isSubmitting: false,
      form: new Form({
        address: '',
        deliver_on: new Date(),
        description: '',
        fee: '',
      }),
    };
  },

  methods: {
    setDeliveryTime() {
      // Append delivery time to the date
      const date = moment(this.form.deliver_on);
      const hour = this.deliveryTime.A === 'PM' ? Number.parseInt(this.deliveryTime.hh, 10) + 12 : this.deliveryTime.hh;

      date.hour(hour);
      date.minute(this.deliveryTime.mm);
      date.second(0);

      this.form.deliver_on = date.toDate();
    },

    onSubmit() {
      this.setDeliveryTime();

      this.isSubmitting = true;

      this.form.post(`/api/events/${this.eventId}/deliveries`)
        .then((data) => {
          window.flash('Delivery added successfully!', 'success');

          this.$emit('created', data);

          this.$parent.close();
        })
        .catch(() => {
          window.flash('There was a problem saving your delivery!', 'danger');
        })
        .then(() => {
          this.isSubmitting = false;
        });
    },
  },
};
</script>
