<template>
  <form
    method="POST"
    @submit.prevent="onSubmit"
    @keydown="errors.clear($event.target.name)"
  >
    <h1 class="title" v-if="!isUpdateForm">Add a Setup</h1>

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
        >{{ buttonText }}</button>
      </p>

      <p class="control" v-if="!isUpdateForm">
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
import TimePicker from 'components/TimePicker';
import moment from 'moment';

export default {
  name: 'setup-form',
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
      setupTime: {
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
      return this.isUpdateForm ? 'Update Setup' : 'Add Setup';
    },

    errors() {
      return this.store.state.setup.errors;
    },

    isSubmitting() {
      return this.store.state.setup.isSubmitting;
    },
  },

  methods: {
    appendTimeToDate() {
      const date = moment(this.form.setup_on);
      const hour = this.convertTo24Hour(this.setupTime.hh, this.setupTime.A);

      date.hour(hour);
      date.minute(this.setupTime.mm);
      date.second(0);

      this.form.setup_on = date.utc().toDate();
    },

    convertTo24Hour(hour, ampm) {
      const time = Number.parseInt(hour, 10);
      return ampm === 'PM' ? time + 12 : time;
    },

    onSubmit() {
      this.appendTimeToDate();

      if (this.isUpdateForm) {
        this.store.dispatch('setup/update', {
          id: this.id,
          data: this.form.data(),
        });
      } else {
        this.store.dispatch('setup/submit', this.form.data());
      }
    },

    setTime() {
      let hours = this.form.setup_on.getHours();
      const minutes = this.form.setup_on.getMinutes();

      hours = hours % 12 || 12;
      this.setupTime.hh = hours < 10 ? `0${hours}` : hours;
      this.setupTime.mm = minutes < 10 ? `0${minutes}` : minutes;
      this.setupTime.A = hours >= 12 ? 'PM' : 'AM';
    },
  },
};
</script>
