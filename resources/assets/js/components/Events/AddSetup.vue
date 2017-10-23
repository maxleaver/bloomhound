<template>
  <form
    method="POST"
    @submit.prevent="onSubmit"
    @keydown="errors.clear($event.target.name)"
  >
    <h1 class="title">Add a Setup</h1>

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
        <b-field label="Setup Date">
          <b-datepicker
            v-model="form.setup_on"
            inline
          ></b-datepicker>
        </b-field>

        <b-field label="Setup Time">
          <time-picker
            :format="dateFormat"
            hide-clear-button
            v-model="setupTime"
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
        >Add Setup</button>
      </p>

      <p class="control">
        <button
          class="button"
          type="button"
          @click="store.commit('setup/toggleForm')"
          :disabled="isSubmitting"
        >Nevermind</button>
      </p>
    </b-field>
  </form>
</template>

<script>
import Form from 'helpers/Form';
import TimePicker from 'components/TimePicker';
import moment from 'moment';

export default {
  name: 'add-setup',
  components: { TimePicker },
  props: {
    store: Object,
  },

  data() {
    return {
      dateFormat: 'hh:mm A',
      setupTime: {
        hh: '04',
        mm: '00',
        ss: '00',
        A: 'PM',
      },
      form: new Form({
        address: '',
        setup_on: new Date(),
        description: '',
        fee: '',
      }),
    };
  },

  computed: {
    errors() {
      return this.store.state.setup.errors;
    },

    isSubmitting() {
      return this.store.state.setup.isSubmitting;
    },
  },

  methods: {
    setTime() {
      // Append time to the date
      const date = moment(this.form.setup_on);
      let hour = Number.parseInt(this.setupTime.hh, 10);

      if (this.setupTime.A === 'PM') {
        hour += 12;
      }

      date.hour(hour);
      date.minute(this.setupTime.mm);
      date.second(0);

      this.form.setup_on = date.toDate();
    },

    onSubmit() {
      this.setTime();

      this.store.dispatch('setup/submit', this.form.data());
    },
  },
};
</script>
