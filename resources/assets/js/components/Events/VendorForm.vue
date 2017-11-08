<template>
  <form
    method="POST"
    @submit.prevent="onSubmit"
    @keydown="errors.clear($event.target.name)"
  >
    <h1 class="title">Add a Vendor</h1>

    <b-field
      label="Type the name of a new or existing vendor"
      :message="getErrors()"
      :type="errors.has('vendor_id') || errors.has('vendor_name') ? 'is-danger' : ''"
    >
      <b-autocomplete
        field="name"
        name="vendor"
        v-model="form.vendor_name"
        :data="filteredDataObj"
        :disabled="isSubmitting"
        :keep-first="keepFirst"
        @select="onSelect"
      ></b-autocomplete>
    </b-field>

    <b-field grouped>
      <p class="control">
        <button
          class="button is-primary"
          type="submit"
          v-bind:class="{'is-loading' : isSubmitting}"
          :disabled="isSubmitting || errors.any()"
        >Add Vendor</button>
      </p>

      <p class="control">
        <button
          class="button"
          type="button"
          @click="store.commit('vendor/toggleForm')"
          :disabled="isSubmitting"
        >Nevermind</button>
      </p>
    </b-field>
  </form>
</template>

<script>
import Form from 'helpers/Form';

export default {
  name: 'proposal-vendor-form',
  props: {
    store: Object,
  },

  data() {
    return {
      form: new Form({
        vendor_id: null,
        vendor_name: '',
      }),
      keepFirst: true,
    };
  },

  computed: {
    errors() {
      return this.store.state.vendor.errors;
    },

    filteredDataObj() {
      return this.store.state.vendors.filter(option =>
        option.name
          .toString()
          .toLowerCase()
          .indexOf(this.form.vendor_name.toLowerCase()) >= 0);
    },

    isSubmitting() {
      return this.store.state.vendor.isSubmitting;
    },
  },

  methods: {
    getErrors() {
      if (this.errors.has('vendor_id') || this.errors.has('vendor_id')) {
        return this.errors.get('vendor_id') ? this.errors.get('vendor_id') : this.errors.get('vendor_name');
      }

      return '';
    },

    onSelect(option) {
      // clear any validation errors
      this.errors.clear('vendor_id');
      this.errors.clear('vendor_name');

      if (option && Object.prototype.hasOwnProperty.call(option, 'id')) {
        this.form.vendor_id = option.id;
        return;
      }

      this.form.vendor_id = null;
    },

    onSubmit() {
      this.store.dispatch('vendor/add', this.form.data());
    },
  },
};
</script>
