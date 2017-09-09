<template>
  <div>
    <div class="hero is-primary">
      <div class="hero-body">
        <div class="container">
          <button class="button is-pulled-right"
              @click="isModalActive = true">
              Add a Customer
          </button>

          <h1 class="title">Your Customers</h1>
          <h2 class="subtitle">Some subtitle</h2>
        </div>
      </div>
    </div>

    <b-modal :active.sync="isModalActive" :canCancel="canCancel" has-modal-card>
        <new-customer @created="add"></new-customer>
    </b-modal>

    <b-table
      :data="items"
      :default-sort-direction="defaultSortDirection"
      :mobile-cards="hasMobileCards"
      default-sort="name"
      @click="onClick"
    >
      <template scope="props">
        <b-table-column field="id" label="ID" width="40" sortable numeric>
          {{ props.row.id }}
        </b-table-column>

        <b-table-column field="name" label="Name" sortable>
          {{ props.row.name }}
        </b-table-column>

        <b-table-column field="created_at" label="Created On" sortable centered>
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
import NewCustomer from '../components/NewCustomer.vue';
import collection from '../mixins/collection';

export default {
  name: 'customer-list',
  components: { NewCustomer },
  mixins: [collection],

  data() {
    return {
      isModalActive: false,
      canCancel: ['escape'],
      defaultSortDirection: 'asc',
      hasMobileCards: true,
    };
  },

  methods: {
    onClick(data) {
      // Redirect to detail page
      window.location.href = `/customers/${data.id}`;
    },
  },
};
</script>
