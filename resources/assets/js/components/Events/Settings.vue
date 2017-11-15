<template>
  <div>
    <b-field grouped>
      <b-field>
        <b-select
          placeholder="Switch to version"
          @input="onSwitchVersion"
        >
          <option
            v-for="row in store.state.event.proposals"
            :value="row.id"
            :key="row.id"
          >
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

    <b-field grouped group-multiline>
      <div class="control">
        <b-switch
          @input="store.commit('toggleCosts', showCosts)"
          v-model="showCosts"
        >{{ costToggleText }}</b-switch>
      </div>

      <div class="control">
        <b-switch
          @input="store.commit('togglePrices', showPrices)"
          v-model="showPrices"
        >{{ priceToggleText }}</b-switch>
      </div>
    </b-field>

  </div>
</template>

<script>
export default {
  name: 'event-settings',

  props: {
    store: Object,
  },

  data() {
    return {
      makeActiveCheckbox: false,
      showCosts: this.store.state.showCosts,
      showPrices: this.store.state.showPrices,
    };
  },

  computed: {
    activeVersionText: function () {
      return this.makeActiveCheckbox ? 'This is the primary version' : 'Make this the primary version';
    },

    costToggleText: function () {
      return this.showCosts ? 'Show Costs' : 'Hide Costs';
    },

    priceToggleText: function () {
      return this.showPrices ? 'Show Prices' : 'Hide Prices';
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
