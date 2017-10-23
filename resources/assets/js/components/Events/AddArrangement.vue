<template>
  <form
    method="POST"
    @submit.prevent="store.dispatch('arrangement/submit', form.data())"
    @keydown="errors.clear($event.target.name)"
  >
    <div class="modal-card">
      <header class="modal-card-head">
        <p class="modal-card-title">Add an Arrangement</p>
      </header>

      <section class="modal-card-body">
        <b-field
          label="Name"
          :type="errors.has('name') ? 'is-danger' : ''"
          :message="errors.has('name') ? errors.get('name') : ''"
        >
          <b-input
            type="text"
            v-model="form.name"
            placeholder="ex. Bridal Bouquet"
            :disabled="isSubmitting"
            required
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
          label="Quantity"
          :type="errors.has('quantity') ? 'is-danger' : ''"
          :message="errors.has('quantity') ? errors.get('quantity') : ''"
        >
          <b-input
            type="number"
            v-model="form.quantity"
            :disabled="isSubmitting"
            required
          ></b-input>
        </b-field>
      </section>

      <footer class="modal-card-foot">
        <button
          class="button is-primary"
          type="submit"
          v-bind:class="{'is-loading' : isSubmitting}"
          :disabled="isSubmitting || errors.any()"
        >Add Arrangement</button>
        <button
          class="button"
          type="button"
          @click="store.commit('arrangement/toggleForm')"
          :disabled="isSubmitting"
        >Nevermind</button>
      </footer>
    </div>
  </form>
</template>

<script>
import Form from 'helpers/Form';

export default {
  name: 'add-arrangement',
  props: {
    store: Object,
  },

  data() {
    return {
      form: new Form({
        description: '',
        name: '',
        quantity: 1,
      }),
    };
  },

  computed: {
    errors() {
      return this.store.state.arrangement.errors;
    },

    isSubmitting() {
      return this.store.state.arrangement.isSubmitting;
    },
  },
};
</script>
