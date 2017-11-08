<template>
  <div>
    <section class="section">
      <event-header :store="store"></event-header>
    </section>

    <div class="container">
      <card-collapse title="Vendors">
        <vendors :store="store"></vendors>
      </card-collapse>

      <card-collapse :title="arrangementTitle">
        <arrangement-list :store="store"></arrangement-list>
      </card-collapse>

      <card-collapse :title="deliveryTitle">
        <deliveries
          :store="store"
          :timezone="timezone"
        ></deliveries>
      </card-collapse>

      <card-collapse :title="setupTitle">
        <setups
          :store="store"
          :timezone="timezone"
        ></setups>
      </card-collapse>

      <card-collapse title="Payment History">
        <h1 class="title">Payment history will go here...</h1>
      </card-collapse>
    </div>

    <section class="section" v-if="store.state.showPrices">
      <div class="container">
        <div class="has-text-right content">
          Arrangements: ${{ toTwoDigits(arrangementSubtotal) }}<br />
          Delivery: ${{ toTwoDigits(deliverySubtotal) }}<br />
          Setup: ${{ toTwoDigits(setupSubtotal) }}<br />
          <strong>Subtotal:</strong> ${{ toTwoDigits(subtotal) }}<br />
          Tax: ${{ toTwoDigits(tax) }}<br />
          <strong>Total: ${{ toTwoDigits(total) }}</strong>
        </div>
      </div>
    </section>
  </div>
</template>

<script>
import moment from 'moment-timezone';
import ArrangementList from 'components/Events/ArrangementList';
import CardCollapse from 'components/CardCollapse';
import Deliveries from 'components/Events/Deliveries';
import EventHeader from 'components/Events/EventHeader';
import Vendors from 'components/Events/Vendors';
import Setups from 'components/Events/Setups';
import eventStore from '../../stores/eventStore';

export default {
  name: 'event-profile',
  components: {
    ArrangementList,
    CardCollapse,
    Deliveries,
    EventHeader,
    Setups,
    Vendors,
  },

  props: {
    event: Object,
    proposal: Object,
    settings: Object,
    vendors: Array,
  },

  data() {
    return {
      arrangements: this.proposal.arrangements,
      deliveries: this.proposal.deliveries,
      setups: this.proposal.setups,
      showCosts: eventStore.state.showCosts,
      store: eventStore,
      timezone: moment.tz.guess(),
    };
  },

  created() {
    this.store.dispatch('init', {
      event: this.event,
      proposal: this.proposal,
      vendors: this.vendors,
    });
  },

  computed: {
    arrangementSubtotal: function () {
      return this.store.getters['arrangement/subtotal'];
    },

    arrangementTitle: function () {
      return `${this.store.state.arrangement.records.length} Arrangements`;
    },

    costToggleText: function () {
      return this.showCosts ? 'Show Costs' : 'Hide Costs';
    },

    deliveryTitle: function () {
      return `${this.store.state.delivery.records.length} Deliveries`;
    },

    deliverySubtotal: function () {
      return this.store.getters['delivery/subtotal'];
    },

    setupSubtotal: function () {
      return this.store.getters['setup/subtotal'];
    },

    setupTitle: function () {
      return `${this.store.state.setup.records.length} Setups`;
    },

    subtotal: function () {
      let subtotal = this.arrangementSubtotal;
      subtotal += this.deliverySubtotal;
      subtotal += this.setupSubtotal;

      return subtotal;
    },

    tax: function () {
      if (this.settings.use_tax) {
        return this.subtotal * (this.settings.tax_amount / 100);
      }

      return 0;
    },

    total: function () {
      return this.subtotal + this.tax;
    },
  },

  methods: {
    toTwoDigits(number) {
      return Number(number).toFixed(2);
    },
  },
};
</script>
