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

      <div class="level-right">
        <p class="level-item">
          <button class="button is-success is-pulled-right"
            @click="isModalActive = true">
            <span class="icon is-small">
              <i class="fa fa-plus"></i>
            </span>
            <span>Add a Flower</span>
          </button>
        </p>
      </div>
    </nav>

    <b-modal :active.sync="isModalActive" :canCancel="canCancel" has-modal-card>
      <add-flower @created="add"></add-flower>
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
import AddFlower from 'components/Flowers/AddFlower';
import collection from 'mixins/collection';

export default {
  name: 'flower-list',
  components: { AddFlower },
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
    this.fetch('/api/flowers');
  },

  methods: {
    onClick(data) {
      // Redirect to detail page
      window.location.href = `/flowers/${data.id}`;
    },

    convertVarietiesToList(varieties) {
      const list = varieties.map(el => el.name);
      return list.join(', ');
    },
  },
};
</script>
