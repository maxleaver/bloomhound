<template>
  <div>
    <nav class="level">
      <div class="level-left">
        <div class="level-item">
          <p class="subtitle is-5">
            <strong>{{ vendors.length }}</strong> vendors
          </p>
        </div>
      </div>

      <div class="level-right">
        <p class="level-item">
          <button
            class="button is-success is-pulled-right"
            @click="store.commit('vendor/toggleForm')"
          >
            <b-icon icon="plus"></b-icon>
            <span>Add a Vendor</span>
          </button>
        </p>
      </div>
    </nav>

    <b-table
      default-sort="name"
      default-sort-direction="asc"
      :data="vendors"
      :hoverable="true"
      :loading="false"
      :mobile-cards="true"
    >
      <template slot-scope="props">
        <b-table-column field="name" label="Name" sortable>
          <strong>{{ props.row.name }}</strong>
        </b-table-column>

        <b-table-column field="created_at" label="Created On" sortable centered>
          {{ new Date(props.row.created_at).toLocaleDateString() }}
        </b-table-column>

        <b-table-column centered>
          <span @click="store.dispatch('vendor/delete', props.row.id)">
            <b-icon icon="delete"></b-icon>
          </span>
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
export default {
  name: 'proposal-vendor-list',

  props: {
    store: Object,
  },

  computed: {
    vendors() {
      return this.store.state.vendor.records;
    },
  },
};
</script>
