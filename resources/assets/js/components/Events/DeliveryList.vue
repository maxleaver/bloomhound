<template>
  <div>
    <nav class="level">
      <div class="level-left">
        <div class="level-item">
          <p class="subtitle is-5">
            <strong>{{ items.length }}</strong> deliveries
          </p>
        </div>
      </div>

      <div class="level-right">
        <p class="level-item">
          <button class="button is-success is-pulled-right"
            @click="isModalActive = true">
            <span class="icon is-small">
              <i class="fa fa-plus"></i>
            </span>
            <span>Add a Delivery</span>
          </button>
        </p>
      </div>
    </nav>

    <b-modal :active.sync="isModalActive" :canCancel="canCancel" has-modal-card>
      <add-delivery @created="add" :eventId="event.id"></add-delivery>
    </b-modal>

    <b-table
      default-sort="deliver_on"
      :data="items"
      :default-sort-direction="defaultSortDirection"
      :loading="isLoading"
      :mobile-cards="hasMobileCards"
    >
      <template scope="props">
        <b-table-column field="deliver_on" label="Date" sortable>
          {{ getMoment(props.row.deliver_on).format('MMM DD') }}
        </b-table-column>

        <b-table-column field="deliver_on" label="Time" sortable>
          {{ getMoment(props.row.deliver_on).format('H:mm a') }}
        </b-table-column>

        <b-table-column field="address" label="Address" sortable>
          {{ props.row.address }}
        </b-table-column>

        <b-table-column field="description" label="Description" sortable>
          {{ props.row.description }}
        </b-table-column>

        <b-table-column field="fee" label="Fee" sortable numeric>
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

      <template slot="footer">
        <div class="has-text-right content">
          <strong>Subtotal: ${{ toTwoDigits(subtotal) }}</strong>
        </div>
      </template>
    </b-table>
  </div>
</template>

<script>
import AddDelivery from 'components/Events/AddDelivery';
import collection from 'mixins/collection';
import moment from 'moment';

export default {
  name: 'delivery-list',
  components: { AddDelivery },
  mixins: [collection],
  props: {
    event: Object,
  },

  data() {
    return {
      canCancel: ['escape'],
      defaultSortDirection: 'asc',
      hasMobileCards: true,
      isModalActive: false,
    };
  },

  computed: {
    subtotal: function () {
      let sum;

      if (this.items.length > 0) {
        sum = this.items.reduce((total, item) => total + (item.fee), 0);
      } else {
        sum = 0.00;
      }

      this.$emit('totalUpdate', sum);

      return sum;
    },
  },

  created() {
    this.fetch(`/api/events/${this.event.id}/deliveries`);
  },

  methods: {
    getMoment(date) {
      return moment(date);
    },

    toTwoDigits(number) {
      return Number(number).toFixed(2);
    },
  },
};
</script>
