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
        <a
          class="button is-pulled-right"
          @click="store.commit('toggleSettingPanel')"
        >
          <b-icon icon="settings"></b-icon>
          <span>{{ settingsButton }}</span>
        </a>

        <h4 class="title is-size-4">Proposal for {{ event.customer.name }}</h4>
        <h2 class="subtitle is-size-6">Version {{ store.state.proposal.version }}</h2>

        <b-collapse :open="store.state.showSettingsPanel">
          <b-field grouped>
            <b-field>
              <b-select
                placeholder="Switch to version"
                @input="onSwitchVersion"
              >
                <option
                    v-for="row in event.proposals"
                    :value="row.id"
                    :key="row.id">
                    {{ row.version }}
                </option>
              </b-select>
            </b-field>

            <b-field>
              <button
                class="button"
                @click="store.dispatch('addVersion')"
              >
                <b-icon icon="plus"></b-icon>
                <span>New Version</span>
              </button>
            </b-field>
          </b-field>

          <div
            class="field"
            v-if="store.state.proposal.id !== store.state.event.active_proposal_id"
          >
            <b-checkbox
              v-model="makeActiveCheckbox"
              @input="store.dispatch('makeActive')"
            >{{ activeVersionText }}</b-checkbox>
          </div>

          <settings :store="store"></settings>
        </b-collapse>
      </div>
    </div>
  </div>
</template>

<script>
import Form from 'helpers/Form';
import Settings from 'components/Events/Settings';

export default {
  name: 'event-header',
  components: { Settings },

  props: {
    store: Object,
  },

  data() {
    return {
      makeActiveCheckbox: false,
      form: new Form({
        date: new Date(this.store.state.event.date),
        name: this.store.state.event.name,
      }, false),
    };
  },

  computed: {
    activeVersionText: function () {
      return this.makeActiveCheckbox ? 'This is the primary version' : 'Make this the primary version';
    },

    event: function () {
      return this.store.state.event;
    },

    settingsButton: function () {
      return this.store.state.showSettingsPanel ? 'Hide Settings' : 'Show Settings';
    },
  },

  methods: {
    onSwitchVersion(id) {
      this.store.dispatch('fetchProposal', id);
      this.makeActiveCheckbox = false;
    },
  },
};
</script>
