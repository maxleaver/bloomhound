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
          :isTaxable="settings.use_tax"
          :taxAmount="settings.tax_amount"
        ></arrangement-list>
      </card-collapse>

      <card-collapse title="Deliveries">
        <h1 class="title">Delivery schedule/fees will go here...</h1>
      </card-collapse>

      <card-collapse title="Setups">
        <h1 class="title">Setup schedule/fees will go here...</h1>
      </card-collapse>

      <card-collapse title="Payment History">
        <h1 class="title">Payment history will go here...</h1>
      </card-collapse>
    </div>

    <section class="section">
      <div class="container">
        <h1 class="title">Event Totals</h1>
      </div>
    </section>
  </div>
</template>

<script>
import ArrangementList from 'components/Events/ArrangementList';
import CardCollapse from 'components/CardCollapse';
import EventHeader from 'components/Events/EventHeader';
import EventVendorList from 'components/Events/EventVendorList';

export default {
  name: 'event-profile',
  components: {
    ArrangementList,
    CardCollapse,
    EventHeader,
    EventVendorList,
  },

  props: {
    event: Object,
    settings: Object,
  },

  data() {
    return {
      isArrangementsOpen: false,
      isVendorListOpen: false,
    };
  },

  methods: {

  },
};
</script>
