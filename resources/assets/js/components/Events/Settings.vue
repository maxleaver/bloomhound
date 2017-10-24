<template>
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
</template>

<script>
export default {
  name: 'event-settings',

  props: {
    store: Object,
  },

  data() {
    return {
      showCosts: this.store.state.showCosts,
      showPrices: this.store.state.showPrices,
    };
  },

  computed: {
    costToggleText: function () {
      return this.showCosts ? 'Show Costs' : 'Hide Costs';
    },

    priceToggleText: function () {
      return this.showPrices ? 'Show Prices' : 'Hide Prices';
    },
  },
};
</script>
