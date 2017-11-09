<template>
  <form
    method="POST"
    @submit.prevent="onSubmit"
  >
    <b-field grouped>
      <b-field
        expanded
        label="Name"
        :message="form.errors.has('name') ? form.errors.get('name') : ''"
        :type="form.errors.has('name') ? 'is-danger' : ''"
      >
        <b-input
          required
          type="text"
          v-model="form.name"
          :disabled="isSubmitting"
        ></b-input>
      </b-field>

      <b-field
        expanded
        label="Description"
        :message="form.errors.has('description') ? form.errors.get('description') : ''"
        :type="form.errors.has('description') ? 'is-danger' : ''"
      >
        <b-input
          type="text"
          v-model="form.description"
          :disabled="isSubmitting"
        ></b-input>
      </b-field>

      <b-field
        expanded
        label="Quantity"
        :message="form.errors.has('quantity') ? form.errors.get('quantity') : ''"
        :type="form.errors.has('quantity') ? 'is-danger' : ''"
      >
        <b-input
          required
          type="number"
          v-model="form.quantity"
          :disabled="isSubmitting"
        ></b-input>
      </b-field>
    </b-field>

    <b-field grouped>
      <div class="field">
        <b-switch v-model="form.override_price" :disabled="isSubmitting">
          {{ overrideText }}
        </b-switch>
      </div>

      <b-field
        expanded
        v-if="form.override_price"
        :message="form.errors.has('price') ? form.errors.get('price') : ''"
        :type="form.errors.has('price') ? 'is-danger' : ''"
      >
        <b-input
          placeholder="Price per Arrangement"
          required
          type="number"
          v-model="form.price"
          :disabled="isSubmitting"
        ></b-input>
      </b-field>
    </b-field>

    <button
      class="button is-primary"
      type="submit"
      v-bind:class="{'is-loading' : isSubmitting}"
      :disabled="isSubmitting"
    >Update Arrangement</button>
  </form>
</template>

<script>
import Form from 'helpers/Form';

export default {
  name: 'update-arrangement',

  props: {
    arrangement: Object,
    isSubmitting: Boolean,
    store: Object,
  },

  data() {
    return {
      form: new Form({
        name: this.arrangement.name,
        description: this.arrangement.description,
        quantity: this.arrangement.quantity,
        override_price: this.arrangement.override_price,
        price: this.arrangement.price,
      }, false),
    };
  },

  computed: {
    overrideText: function () {
      return this.form.override_price ? 'Set the price manually' : 'Calculate a retail price';
    },
  },

  methods: {
    onSubmit() {
      this.store.dispatch('arrangement/update', {
        id: this.arrangement.id,
        data: this.form.data(),
      });
    },
  },
};
</script>
