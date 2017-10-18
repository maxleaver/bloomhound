<template>
  <div>
    <event-header :event="event"></event-header>

    <div class="container">
      <slot></slot>
    </div>

    <section class="section">
      <div class="container">
        <h1 class="title">Event Proposal for {{ event.customer.name }}</h1>
      </div>
    </section>

    <div class="container">
      <card-collapse title="Vendors">
        <event-vendor-list :event="event"></event-vendor-list>
      </card-collapse>

      <card-collapse title="Arrangements">
        <arrangement-list
          :event="event"
          @totalUpdate="updateArrangementTotal"
        ></arrangement-list>
      </card-collapse>

      <card-collapse title="Deliveries">
        <deliveries
          :event="event"
          @totalUpdate="updateDeliveryTotal"
        ></deliveries>
      </card-collapse>

      <card-collapse title="Setups">
        <setups
          :event="event"
          @totalUpdate="updateSetupTotal"
        ></setups>
      </card-collapse>

      <card-collapse title="Payment History">
        <h1 class="title">Payment history will go here...</h1>
      </card-collapse>
    </div>

    <section class="section">
      <div class="container">
        <div class="has-text-right content">
          Arrangements: ${{ toTwoDigits(arrangementSubtotal) }}<br />
          Setup Fees: ${{ toTwoDigits(setupSubtotal) }}<br />
          Delivery Fees: ${{ toTwoDigits(deliverySubtotal) }}<br />
          <strong>Subtotal:</strong> ${{ toTwoDigits(subtotal) }}<br />
          Tax: ${{ toTwoDigits(tax) }}<br />
          <strong>Total: ${{ toTwoDigits(total) }}</strong>
        </div>
      </div>
    </section>
  </div>
</template>

<script>
import ArrangementList from 'components/Events/ArrangementList';
import CardCollapse from 'components/CardCollapse';
import Deliveries from 'components/Events/Deliveries';
import EventHeader from 'components/Events/EventHeader';
import EventVendorList from 'components/Events/EventVendorList';
import Setups from 'components/Events/Setups';

export default {
  name: 'event-profile',
  components: {
    ArrangementList,
    CardCollapse,
    Deliveries,
    EventHeader,
    EventVendorList,
    Setups,
  },

  props: {
    event: Object,
    settings: Object,
  },

  data() {
    return {
      arrangementSubtotal: 0,
      deliverySubtotal: 0,
      setupSubtotal: 0,
    };
  },

  computed: {
    subtotal: function () {
      return this.arrangementSubtotal + this.deliverySubtotal + this.setupSubtotal;
    },

    tax: function () {
      if (this.settings.use_tax) {
        return this.subtotal * (this.settings.tax_amount / 100);
      }

      return 0.00;
    },

    total: function () {
      return this.subtotal + this.tax;
    },
  },

  methods: {
    toTwoDigits(number) {
      return Number(number).toFixed(2);
    },

    updateArrangementTotal(sum) {
      this.arrangementSubtotal = sum;
    },

    updateDeliveryTotal(sum) {
      this.deliverySubtotal = sum;
    },

    updateSetupTotal(sum) {
      this.setupSubtotal = sum;
    },
  },
};
</script>
