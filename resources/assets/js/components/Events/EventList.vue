<template>
  <div>
    <nav class="level">
      <div class="level-left">
        <div class="level-item">
          <p class="subtitle is-5">
            <strong>{{ items.length }}</strong> events
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
            <span>Add an Event</span>
          </button>
        </p>
      </div>
    </nav>

    <b-modal :active.sync="isModalActive" :canCancel="canCancel" has-modal-card>
      <add-event @created="add" :customers="customers" :customer_id="customer_id"></add-event>
    </b-modal>

    <b-table
      :data="items"
      :default-sort-direction="defaultSortDirection"
      :mobile-cards="hasMobileCards"
      default-sort="date"
      @click="onClick"
      :loading="isLoading"
    >
      <template scope="props">
        <b-table-column field="status" label="Status" sortable>
          <b-tag rounded type="is-primary" size="is-medium">{{ props.row.status.title }}</b-tag>
        </b-table-column>

        <b-table-column field="date" label="Date" sortable>
          <strong>{{ new Date(props.row.date).toLocaleDateString() }}</strong>
        </b-table-column>

        <b-table-column field="name" label="Name" sortable>
          {{ props.row.name }}
        </b-table-column>

        <b-table-column field="created_at" label="Created" sortable centered>
          {{ new Date(props.row.created_at).toLocaleDateString() }}
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
    </b-table>
  </div>
</template>

<script>
import AddEvent from 'components/Events/AddEvent';
import collection from 'mixins/collection';

export default {
  name: 'event-list',
  components: { AddEvent },
  mixins: [collection],

  props: {
    customer_id: Number,
  },

  data() {
    return {
      canCancel: ['escape'],
      customers: [],
      defaultSortDirection: 'asc',
      hasMobileCards: true,
      isModalActive: false,
    };
  },

  created() {
    let url = 'api/events';

    if (this.customer_id) {
      url = `/api/customers/${this.customer_id}/events`;
    }

    this.fetch(url);

    if (!this.customer_id) {
      this.fetchCustomers();
    }
  },

  methods: {
    fetchCustomers() {
      window.axios.get('api/customers')
        .then(({ data }) => {
          this.customers = data;
        });
    },

    onClick(data) {
      // Redirect to detail page
      window.location.href = `/events/${data.id}`;
    },
  },
};
</script>
