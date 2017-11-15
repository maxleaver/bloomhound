<template>
  <div>
    <section class="section">
      <event-header :store="store"></event-header>
    </section>

    <div class="container">
      <b-tabs position="is-centered" type="is-boxed" v-model="activeTab">
        <b-tab-item icon="worker" label="Vendors">
          <section class="section">
            <vendors :store="store"></vendors>
          </section>
        </b-tab-item>

        <b-tab-item icon="flower" label="Arrangements">
          <section class="section">
            <arrangement-list :store="store"></arrangement-list>
          </section>
        </b-tab-item>

        <b-tab-item icon="truck-delivery" label="Deliveries">
          <section class="section">
            <deliveries
              :store="store"
              :timezone="timezone"
            ></deliveries>
          </section>
        </b-tab-item>

        <b-tab-item icon="wrench" label="Setups">
          <section class="section">
            <setups
              :store="store"
              :timezone="timezone"
            ></setups>
          </section>
        </b-tab-item>

        <b-tab-item icon="sale" label="Discounts">
          <section class="section">
            <discounts
              :discounts="store.state.discount.records"
              :form="store.state.discount.form"
              :id="store.state.proposal.id"
              :isSubmitting="store.state.discount.isSubmitting"
              :onDelete="onDeleteDiscount"
              :onSubmit="onSubmitDiscount"
            ></discounts>
          </section>
        </b-tab-item>

        <b-tab-item icon="settings" label="Settings">
          <section class="section">
            <settings :store="store"></settings>
          </section>
        </b-tab-item>
      </b-tabs>
    </div>

    <section class="section" v-if="store.state.showPrices">
      <div class="container">
        <hr>

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
  </div>
</template>

<script>
import moment from 'moment-timezone';
import ArrangementList from 'components/Arrangements/ArrangementList';
import Deliveries from 'components/Deliveries/Deliveries';
import Discounts from 'components/Discounts/Discounts';
import EventHeader from 'components/Events/EventHeader';
import Vendors from 'components/Events/Vendors';
import Settings from 'components/Events/Settings';
import Setups from 'components/Setups/Setups';
import eventStore from '../../stores/eventStore';

export default {
  name: 'event-profile',
  components: {
    ArrangementList,
    Deliveries,
    Discounts,
    EventHeader,
    Settings,
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
      activeTab: 0,
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

    costToggleText: function () {
      return this.showCosts ? 'Show Costs' : 'Hide Costs';
    },

    deliverySubtotal: function () {
      return this.store.getters['delivery/subtotal'];
    },

    discountSubtotal: function () {
      return this.store.getters['discount/subtotal'];
    },

    setupSubtotal: function () {
      return this.store.getters['setup/subtotal'];
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
