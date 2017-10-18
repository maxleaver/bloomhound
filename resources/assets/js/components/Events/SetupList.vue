<template>
  <div>
    <nav class="level">
      <div class="level-left">
        <div class="level-item">
          <p class="subtitle is-5">
            <strong>{{ setups.length }}</strong> setups
          </p>
        </div>
      </div>

      <div class="level-right">
        <p class="level-item">
          <button class="button is-success is-pulled-right"
            @click="toggleForm">
            <span class="icon is-small">
              <i class="fa fa-plus"></i>
            </span>
            <span>Add a Setup</span>
          </button>
        </p>
      </div>
    </nav>

    <b-table
      default-sort="setup_on"
      :data="setups"
      :default-sort-direction="defaultSortDirection"
      :loading="isLoading"
      :mobile-cards="hasMobileCards"
    >
      <template scope="props">
        <b-table-column field="setup_on" label="Date" sortable>
          {{ getMoment(props.row.setup_on).format('MMM DD') }}
        </b-table-column>

        <b-table-column field="setup_on" label="Time" sortable>
          {{ getMoment(props.row.setup_on).format('h:mm a') }}
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
import moment from 'moment';

export default {
  name: 'setup-list',
  props: {
    isLoading: Boolean,
    setups: Array,
    subtotal: Number,
    toggleForm: Function,
  },

  data() {
    return {
      defaultSortDirection: 'asc',
      hasMobileCards: true,
    };
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
