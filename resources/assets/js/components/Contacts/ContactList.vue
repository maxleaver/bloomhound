<template>
  <div>
    <nav class="level">
      <div class="level-left">
        <div class="level-item">
          <p class="subtitle is-5">
            <strong>{{ items.length }}</strong> contacts
          </p>
        </div>
      </div>

      <div class="level-right">
        <p class="level-item">
          <button
            class="button is-success is-pulled-right"
            @click="isModalActive = true">
            <b-icon icon="plus"></b-icon>
            <span>Add a Contact</span>
          </button>
        </p>
      </div>
    </nav>

    <b-modal :active.sync="isModalActive" :canCancel="canCancel" has-modal-card>
      <add-contact @created="add" :customer_id="customer_id" :customers="customers"></add-contact>
    </b-modal>

    <b-table
      default-sort="name"
      :data="items"
      :default-sort-direction="defaultSortDirection"
      :focusable="true"
      :hoverable="true"
      :loading="isLoading"
      :mobile-cards="hasMobileCards"
      @click="onClick"
    >
      <template slot-scope="props">
        <b-table-column field="name" label="Name" sortable>
          <strong>{{ props.row.name }}</strong>
        </b-table-column>

        <b-table-column field="email" label="Email" sortable>
          {{ props.row.email }}
        </b-table-column>

        <b-table-column field="phone" label="Phone" sortable>
          {{ props.row.phone }}
        </b-table-column>

        <b-table-column field="relationship" label="Relationship" sortable>
          {{ props.row.relationship }}
        </b-table-column>

        <b-table-column field="address" label="Address" sortable>
          <span class="address">{{ props.row.address }}</span>
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
import AddContact from 'components/Contacts/AddContact';
import collection from 'mixins/collection';

export default {
  name: 'contact-list',
  components: { AddContact },
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
    let url = '/api/contacts';

    if (this.customer_id) {
      url = `/api/customers/${this.customer_id}/contacts`;
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
      window.location.href = `/contacts/${data.id}`;
    },
  },
};
</script>
