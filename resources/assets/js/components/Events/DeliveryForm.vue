<template>
  <form
    method="POST"
    @submit.prevent="onSubmit"
    @keydown="errors.clear($event.target.name)"
  >
    <h1 class="title" v-if="!isUpdateForm">Add a Delivery</h1>

    <div class="columns">
      <div class="column">
        <b-field
          label="Address"
          :type="errors.has('address') ? 'is-danger' : ''"
          :message="errors.has('address') ? errors.get('address') : ''"
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
          :type="errors.has('description') ? 'is-danger' : ''"
          :message="errors.has('description') ? errors.get('description') : ''"
        >
          <b-input
            type="text"
            v-model="form.description"
            :disabled="isSubmitting"
          ></b-input>
        </b-field>

        <b-field
          label="Fee"
          :type="errors.has('fee') ? 'is-danger' : ''"
          :message="errors.has('fee') ? errors.get('fee') : ''"
        >
          <b-input
            type="number"
            v-model="form.fee"
            :disabled="isSubmitting"
          ></b-input>
        </b-field>
      </div>

      <div class="column">
        <b-field label="Delivery Date">
          <b-datepicker
            v-model="form.deliver_on"
            inline
          ></b-datepicker>
        </b-field>

        <b-field label="Delivery Time">
          <time-picker
            :format="dateFormat"
            hide-clear-button
            v-model="deliveryTime"
          ></time-picker>
        </b-field>
      </div>
    </div>

    <b-field grouped>
      <p class="control">
        <button
          class="button is-primary"
          type="submit"
          v-bind:class="{'is-loading' : isSubmitting}"
          :disabled="isSubmitting || errors.any()"
        >{{ buttonText }}</button>
      </p>

      <p class="control">
        <button
          class="button"
          type="button"
          @click="store.commit('delivery/toggleForm')"
          :disabled="isSubmitting"
        >Nevermind</button>
      </p>
    </b-field>
  </form>
</template>

<script>
import TimePicker from 'components/TimePicker';
import moment from 'moment';

export default {
  name: 'delivery-form',
  components: { TimePicker },
  props: {
    form: Object,
    id: Number,
    isUpdateForm: Boolean,
    store: Object,
    timezone: String,
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
    };
  },

  created() {
    if (this.isUpdateForm) {
      this.setTime();
    }
  },

  computed: {
    buttonText() {
      return this.isUpdateForm ? 'Update Delivery' : 'Add Delivery';
    },

    errors() {
      return this.store.state.delivery.errors;
    },

    isSubmitting() {
      return this.store.state.delivery.isSubmitting;
    },
  },

  methods: {
    appendTimeToDate() {
      const date = moment(this.form.deliver_on);
      const hour = this.convertTo24Hour(this.deliveryTime.hh, this.deliveryTime.A);

      date.hour(hour);
      date.minute(this.deliveryTime.mm);
      date.second(0);

      this.form.deliver_on = date.utc().toDate();
    },

    convertTo24Hour(hour, ampm) {
      const time = Number.parseInt(hour, 10);
      return ampm === 'PM' ? time + 12 : time;
    },

    onSubmit() {
      this.appendTimeToDate();

      if (this.isUpdateForm) {
        this.store.dispatch('delivery/update', {
          id: this.id,
          data: this.form.data(),
        });
      } else {
        this.store.dispatch('delivery/submit', this.form.data());
      }
    },

    setTime() {
      let hours = this.form.deliver_on.getHours();
      const minutes = this.form.deliver_on.getMinutes();

      hours = hours % 12 || 12;
      this.deliveryTime.hh = hours < 10 ? `0${hours}` : hours;
      this.deliveryTime.mm = minutes < 10 ? `0${minutes}` : minutes;
      this.deliveryTime.A = hours >= 12 ? 'PM' : 'AM';
    },
  },
};
</script>
