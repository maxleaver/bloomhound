<template>
  <div class="container">
    <form
      method="POST"
      @submit.prevent="store.dispatch('update', form.data())"
      v-if="store.state.showEditForm"
    >
      <div class="columns">
        <div class="column">
          <b-field
            label="Name"
            :type="form.errors.has('name') ? 'is-danger' : ''"
            :message="form.errors.has('name') ? form.errors.get('name') : ''"
          >
            <b-input
              type="text"
              v-model="form.name"
              :disabled="store.state.isSubmitting"
              name="name"
              required
            ></b-input>
          </b-field>
        </div>

        <div class="column">
          <b-field label="Select a Date">
            <b-datepicker
              v-model="form.date"
              icon="calendar-today"
            ></b-datepicker>
          </b-field>
        </div>
      </div>

      <div class="field is-grouped">
        <div class="control">
          <button
            class="button is-primary"
            type="submit"
            v-bind:class="{'is-loading' : store.state.isSubmitting}"
            :disabled="store.state.isSubmitting"
          >Update Event</button>
        </div>

        <div class="control">
          <button
            class="button"
            @click.prevent="store.commit('toggleEditForm')"
            :disabled="store.state.isSubmitting"
          >Nevermind</button>
        </div>
      </div>
    </form>

    <div v-else class="container">
      <button
        class="button is-pulled-right"
        @click="store.commit('toggleEditForm')"
      >Edit</button>

      <h1 class="title">{{ store.state.event.name }}</h1>
      <h2 class="subtitle">
        {{ store.state.event.date.format('MMMM Do YYYY') }} ({{ store.state.event.date.fromNow() }})
      </h2>

      <div>
        <h4 class="title is-size-4">Proposal for {{ event.customer.name }}</h4>
        <h2 class="subtitle is-size-6">Version {{ store.state.proposal.version }}</h2>
      </div>
    </div>
  </div>
</template>

<script>
import Form from 'helpers/Form';

export default {
  name: 'event-header',

  props: {
    store: Object,
  },

  data() {
    return {
      form: new Form({
        date: new Date(this.store.state.event.date),
        name: this.store.state.event.name,
      }, false),
    };
  },

  computed: {
    event: function () {
      return this.store.state.event;
    },
  },
};
</script>
