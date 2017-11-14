<template>
  <div>
    <nav class="level">
      <div class="level-left">
        <div class="level-item">
          <p class="subtitle is-5">
            <strong>{{ items.length }}</strong> vendors
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
            <span>Add a Vendor</span>
          </button>
        </p>
      </div>
    </nav>

    <b-modal :active.sync="isModalActive" :canCancel="canCancel" has-modal-card>
      <select-vendor @created="add" :eventId="event.id" :vendors="vendors"></select-vendor>
    </b-modal>

    <b-table
      default-sort="name"
      focusable="true"
      hoverable="true"
      :data="items"
      :default-sort-direction="defaultSortDirection"
      :loading="isLoading"
      :mobile-cards="hasMobileCards"
    >
      <template slot-scope="props">
        <b-table-column field="name" label="Name" sortable>
          <strong>{{ props.row.name }}</strong>
        </b-table-column>

        <b-table-column field="created_at" label="Created On" sortable centered>
          {{ new Date(props.row.created_at).toLocaleDateString() }}
        </b-table-column>

        <b-table-column centered>
          <span @click="deleteRow(props.row.id)">
            <b-icon icon="delete"></b-icon>
          </span>
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
import SelectVendor from 'components/Vendors/SelectVendor';
import collection from 'mixins/collection';

export default {
  name: 'event-vendor-list',
  components: { SelectVendor },
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
      vendors: [],
    };
  },

  created() {
    // this.fetch(`/api/events/${this.event.id}/vendors`);
    this.fetchVendors();
  },

  methods: {
    deleteRow(id) {
      window.axios.delete(`/api/events/${this.event.id}/vendors/${id}`)
        .then(() => {
          this.removeById(id);
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
