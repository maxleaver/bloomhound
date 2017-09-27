<template>
  <div>
    <nav class="level">
      <div class="level-left">
        <div class="level-item">
          <p class="subtitle is-5">
            <strong>{{ items.length }}</strong> flowers
          </p>
        </div>
      </div>
    </nav>

    <b-table
      :data="items"
      :default-sort-direction="defaultSortDirection"
      :mobile-cards="hasMobileCards"
      default-sort="date"
      :loading="isLoading"
      detailed
    >
      <template scope="props">
        <b-table-column field="name" label="Name" sortable>
          {{ props.row.name }}
        </b-table-column>

        <b-table-column field="created_at" label="Created" sortable numeric>
          {{ new Date(props.row.created_at).toLocaleDateString() }}
        </b-table-column>
      </template>

      <template slot="detail" scope="props">
        <flower-variety-source-list
          :id="props.row.id"
          :vendors="vendors"
          :sources="props.row.sources"
        ></flower-variety-source-list>
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

    <section class="section">
      <add-flower-varieties @created="add" :id="id"></add-flower-varieties>
    </section>
  </div>
</template>

<script>
import AddFlowerVarieties from './AddFlowerVarieties.vue';
import FlowerVarietySourceList from './FlowerVarietySourceList.vue';
import collection from '../mixins/collection';

export default {
  name: 'variety-list',
  components: { AddFlowerVarieties, FlowerVarietySourceList },
  mixins: [collection],

  props: {
    id: Number,
  },

  data() {
    return {
      defaultSortDirection: 'asc',
      hasMobileCards: true,
      isLoading: true,
      vendors: [],
    };
  },

  created() {
    this.fetch();
    this.fetchVendors();
  },

  methods: {
    fetch() {
      window.axios.get(`/api/flowers/${this.id}/varieties`)
        .then((data) => {
          this.isLoading = false;
          this.refresh(data);
        });
    },

    fetchVendors() {
      window.axios.get('/api/vendors')
        .then((data) => {
          this.vendors = data.data;
        });
    },
  },
};
</script>
