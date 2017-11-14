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
      <add-vendor @created="add"></add-vendor>
    </b-modal>

    <b-table
      default-sort="name"
      :data="items"
      :default-sort-direction="defaultSortDirection"
      :hoverable="true"
      :loading="isLoading"
      :mobile-cards="hasMobileCards"
      @click="onClick"
    >
      <template slot-scope="props">
        <b-table-column field="name" label="Name" sortable>
          <strong>{{ props.row.name }}</strong>
        </b-table-column>

        <b-table-column field="address" label="Address" sortable>
          <span class="address">{{ props.row.address }}</span class="address">
        </b-table-column>

        <b-table-column field="email" label="Email" sortable>
          {{ props.row.email }}
        </b-table-column>

        <b-table-column field="phone" label="Phone" sortable>
          {{ props.row.phone }}
        </b-table-column>

        <b-table-column field="website" label="Website" sortable>
          <a
            :alt="props.row.name"
            :href="props.row.website"
          >{{ props.row.website }}</a>
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
                icon="emoticon-sad"
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
import AddVendor from 'components/Vendors/AddVendor';
import collection from 'mixins/collection';

export default {
  name: 'vendor-list',
  components: { AddVendor },
  mixins: [collection],

  data() {
    return {
      canCancel: ['escape'],
      defaultSortDirection: 'asc',
      hasMobileCards: true,
      isModalActive: false,
    };
  },

  created() {
    this.fetch('/api/vendors');
  },

  methods: {
    onClick(data) {
      // Redirect to detail page
      window.location.href = `/vendors/${data.id}`;
    },
  },
};
</script>
