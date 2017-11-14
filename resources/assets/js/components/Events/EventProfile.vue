<template>
  <div>
    <section class="section">
      <event-header :store="store"></event-header>
    </section>

    <div class="container">
      <card-collapse :title="vendorTitle">
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

      <card-collapse :title="discountTitle">
        <discounts
          :discounts="store.state.discount.records"
          :form="store.state.discount.form"
          :id="store.state.proposal.id"
          :isSubmitting="store.state.discount.isSubmitting"
          :onDelete="onDeleteDiscount"
          :onSubmit="onSubmitDiscount"
        ></discounts>
      </card-collapse>
    </div>

    <section class="section" v-if="store.state.showPrices">
      <div class="container">
        <div class="has-text-right content">
          Arrangements: ${{ toTwoDigits(arrangementSubtotal) }}<br />
          Delivery: ${{ toTwoDigits(deliverySubtotal) }}<br />
          Setup: ${{ toTwoDigits(setupSubtotal) }}<br />
          Discounts: ${{ toTwoDigits(discountSubtotal) }}<br />
          <strong>Subtotal:</strong> ${{ toTwoDigits(store.state.proposal.subtotal) }}<br />
          Tax: ${{ toTwoDigits(store.state.proposal.tax) }}<br />
          <strong>Total: ${{ toTwoDigits(store.state.proposal.total) }}</strong>
        </div>
      </div>
    </section>

    <section class="section">
      <div class="container">
        <h1 class="title">Invoices will go here...</h1>
      </div>
    </section>
  </div>
</template>

<script>
import moment from 'moment-timezone';
import ArrangementList from 'components/Arrangements/ArrangementList';
import CardCollapse from 'components/CardCollapse';
import Deliveries from 'components/Deliveries/Deliveries';
import Discounts from 'components/Discounts/Discounts';
import EventHeader from 'components/Events/EventHeader';
import Vendors from 'components/Events/Vendors';
import Setups from 'components/Setups/Setups';
import eventStore from '../../stores/eventStore';

export default {
  name: 'event-profile',
  components: {
    ArrangementList,
    CardCollapse,
    Deliveries,
    Discounts,
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

    discountSubtotal: function () {
      return this.store.getters['discount/subtotal'];
    },

    discountTitle: function () {
      return `${this.store.state.discount.records.length} Discounts`;
    },

    setupSubtotal: function () {
      return this.store.getters['setup/subtotal'];
    },

    setupTitle: function () {
      return `${this.store.state.setup.records.length} Setups`;
    },

    vendorTitle: function () {
      return `${this.store.state.vendor.records.length} Vendors`;
    },
  },

  methods: {
    onDeleteDiscount(id, discountId) {
      this.store.dispatch('discount/delete', discountId);
    },

    onSubmitDiscount() {
      this.store.dispatch('discount/add', this.store.state.discount.form.data());
    },

    toTwoDigits(number) {
      return Number(number).toFixed(2);
    },
  },
};
</script>
