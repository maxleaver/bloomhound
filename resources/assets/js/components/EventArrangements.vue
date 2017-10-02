<template>
  <div>
    <nav class="level">
      <div class="level-left">
        <div class="level-item">
          <p class="subtitle is-5">
            <strong>{{ items.length }}</strong> arrangements
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
            <span>Add an Arrangement</span>
          </button>
        </p>
      </div>
    </nav>

    <b-modal :active.sync="isModalActive" :canCancel="canCancel" has-modal-card>
      <add-event-arrangement @created="add" :eventId="eventId"></add-event-arrangement>
    </b-modal>

    <b-table
      default-sort="name"
      detailed
      :data="items"
      :default-sort-direction="defaultSortDirection"
      :loading="isLoading"
      :mobile-cards="hasMobileCards"
    >
      <template scope="props">
        <b-table-column field="name" label="Name" sortable>
          {{ props.row.name }}
        </b-table-column>

        <b-table-column field="quantity" label="Quantity" sortable>
          {{ props.row.quantity }}
        </b-table-column>
      </template>

      <template slot="detail" scope="props">
        <div>
          <ingredient-list
            :arrangementId="props.row.id"
            :arrangeables="arrangeables"
          ></ingredient-list>
        </div>
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
import AddEventArrangement from './AddEventArrangement.vue';
import IngredientList from './IngredientList.vue';
import collection from '../mixins/collection';

export default {
  name: 'event-arrangements',
  components: { AddEventArrangement, IngredientList },
  mixins: [collection],
  props: {
    eventId: Number,
  },

  data() {
    return {
      arrangeables: [],
      canCancel: ['escape'],
      defaultSortDirection: 'asc',
      hasMobileCards: true,
      isLoading: true,
      isModalActive: false,
    };
  },

  created() {
    this.fetch();
    this.fetchArrangeables();
  },

  methods: {
    fetch() {
      window.axios.get(`/api/events/${this.eventId}/arrangements`)
        .then((data) => {
          this.isLoading = false;
          this.refresh(data);
        });
    },

    fetchArrangeables() {
      window.axios.get('/api/arrangeables')
        .then((data) => {
          this.arrangeables = data.data;
        });
    },
  },
};
</script>
