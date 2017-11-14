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
          <b-timepicker
            placeholder="Click to select..."
            icon="clock"
            hour-format="12"
            v-model="setupTime">
          </b-timepicker>
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
export default {
  name: 'setup-form',
  props: {
    form: Object,
    id: Number,
    isUpdateForm: Boolean,
    store: Object,
    timezone: String,
  },

  data() {
    return {
      setupTime: new Date(),
    };
  },

  created() {
    if (this.isUpdateForm) {
      this.setupTime = this.form.setup_on;
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
      this.form.setup_on.setHours(this.setupTime.getHours());
      this.form.setup_on.setMinutes(this.setupTime.getMinutes());
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
  },
};
</script>
