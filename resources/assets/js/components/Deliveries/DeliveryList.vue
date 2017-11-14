<template>
  <div>
    <nav class="level">
      <div class="level-left">
        <div class="level-item">
          <p class="subtitle is-5">
            <strong>{{ deliveries.length }}</strong> deliveries
          </p>
        </div>
      </div>

      <div class="level-right">
        <p class="level-item">
          <button
            class="button is-success is-pulled-right"
            @click="store.commit('delivery/toggleForm')"
          >
            <b-icon icon="plus"></b-icon>
            <span>Add a Delivery</span>
          </button>
        </p>
      </div>
    </nav>

    <b-table
      default-sort="deliver_on"
      detailed
      :data="deliveries"
      :default-sort-direction="defaultSortDirection"
      :loading="isLoading"
      :mobile-cards="hasMobileCards"
    >
      <template slot-scope="props">
        <b-table-column field="deliver_on" label="Date" sortable>
          {{ getLocalTime(props.row.deliver_on).format('MMM DD') }}
        </b-table-column>

        <b-table-column field="deliver_on" label="Time" sortable>
          {{ getLocalTime(props.row.deliver_on).format('h:mm a') }}
        </b-table-column>

        <b-table-column field="address" label="Address" sortable>
          <span class="address">{{ props.row.address }}</span>
        </b-table-column>

        <b-table-column field="description" label="Description" sortable>
          {{ props.row.description }}
        </b-table-column>

        <b-table-column
          field="fee"
          label="Fee"
          sortable
          numeric
          :visible="store.state.showPrices"
        >
          {{ toTwoDigits(props.row.fee) }}
        </b-table-column>
      </template>

      <template slot="empty">
        <section class="section">
          <div class="content has-text-grey has-text-centered">
            <p>
              <b-icon
                icon="sentiment_very_dissatisfied"
                size="is-large">
              </b-icon>
            </p>
            <p>Nothing here.</p>
          </div>
        </section>
      </template>

      <template slot="detail" slot-scope="props">
        <delivery-form
          :form="getUpdateForm(props.row)"
          :id="props.row.id"
          :isUpdateForm="true"
          :store="store"
          :timezone="timezone"
        ></delivery-form>
      </template>

      <template slot="footer" v-if="store.state.showPrices">
        <div class="has-text-right content">
          <strong>Subtotal: ${{ subtotal }}</strong>
        </div>
      </template>
    </b-table>
  </div>
</template>

<script>
import DeliveryForm from 'components/Deliveries/DeliveryForm';
import Form from 'helpers/Form';
import moment from 'moment-timezone';

export default {
  name: 'delivery-list',
  components: { DeliveryForm },
  props: {
    store: Object,
    timezone: String,
  },

  data() {
    return {
      defaultSortDirection: 'asc',
      hasMobileCards: true,
    };
  },

  computed: {
    deliveries() {
      return this.store.state.delivery.records;
    },

    isLoading() {
      return this.store.state.delivery.isLoading;
    },

    subtotal() {
      return Number(this.store.getters['delivery/subtotal']).toFixed(2);
    },
  },

  methods: {
    getLocalTime(date) {
      return moment.utc(date).tz(this.timezone);
    },

    getUpdateForm(delivery) {
      return new Form({
        address: delivery.address,
        deliver_on: moment.utc(delivery.deliver_on).tz(this.timezone).toDate(),
        description: delivery.description,
        fee: delivery.fee,
      });
    },

    toTwoDigits(number) {
      return Number(number).toFixed(2);
    },
  },
};
</script>
