<template>
  <div>
    <nav class="level">
      <div class="level-left">
        <div class="level-item">
          <p class="subtitle is-5">
            <strong>{{ items.length }}</strong> items
          </p>
        </div>
      </div>

      <div class="level-right">
        <p class="level-item">
          <button
            class="button is-success is-pulled-right"
            @click="isModalActive = true"
          >
            <b-icon icon="plus"></b-icon>
            <span>Add an Item</span>
          </button>
        </p>
      </div>
    </nav>

    <b-modal :active.sync="isModalActive" :canCancel="canCancel" has-modal-card>
      <add-item @created="add" :types="types"></add-item>
    </b-modal>

    <b-table
      :data="items"
      :default-sort-direction="defaultSortDirection"
      :mobile-cards="hasMobileCards"
      default-sort="name"
      @click="onClick"
      :loading="isLoading"
    >
      <template slot-scope="props">
        <b-table-column field="name" label="Name" sortable>
          <strong>{{ props.row.name }}</strong>
        </b-table-column>

        <b-table-column field="description" label="Description" sortable>
          {{ props.row.description }}
        </b-table-column>

        <b-table-column field="type" label="Type" sortable>
          <b-tag>{{ props.row.type.title }}</b-tag>
        </b-table-column>

        <b-table-column field="inventory" label="Inventory" sortable numeric>
          {{ props.row.inventory }}
        </b-table-column>

        <b-table-column field="cost" label="Cost" sortable numeric>
          {{ Number(props.row.cost).toFixed(2) }}
        </b-table-column>

        <b-table-column field="price" label="Retail Price" sortable numeric>
          {{ Number(props.row.price).toFixed(2) }}
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
import AddItem from 'components/Items/AddItem';
import collection from 'mixins/collection';

export default {
  name: 'event-list',
  components: { AddItem },
  mixins: [collection],

  data() {
    return {
      canCancel: ['escape'],
      customers: [],
      defaultSortDirection: 'asc',
      hasMobileCards: true,
      isModalActive: false,
      types: [],
    };
  },

  created() {
    this.fetch('api/items');
    this.fetchTypes();
  },

  methods: {
    onClick(data) {
      window.location.href = `/items/${data.id}`;
    },

    fetchTypes() {
      window.axios.get('api/arrangeables/types?type=item')
        .then(({ data }) => {
          this.types = data;
        });
    },
  },
};
</script>
