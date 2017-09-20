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
      @click="onClick"
      :loading="isLoading"
    >
      <template scope="props">
        <b-table-column field="name" label="Name" sortable>
          {{ props.row.name }}
        </b-table-column>

        <b-table-column field="varieties" label="Varieties">
          {{ convertVarietiesToList(props.row.varieties) }}
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
import collection from '../mixins/collection';

export default {
  name: 'flower-list',
  mixins: [collection],

  data() {
    return {
      isModalActive: false,
      canCancel: ['escape'],
      defaultSortDirection: 'asc',
      hasMobileCards: true,
      isLoading: true,
    };
  },

  created() {
    this.fetch();
  },

  methods: {
    fetch() {
      window.axios.get('/api/flowers?lib=custom')
        .then((data) => {
          this.isLoading = false;
          this.refresh(data);
        });
    },

    onClick() {
      console.log('TODO: Redirect to flower detail page');
    },

    convertVarietiesToList(varieties) {
      const list = varieties.map(el => el.name);
      return list.join();
    },
  },
};
</script>
