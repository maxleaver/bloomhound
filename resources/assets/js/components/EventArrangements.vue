<template>
  <div>
    <h1 class="title">Arrangements</h1>

    <b-table
      :data="items"
      :default-sort-direction="defaultSortDirection"
      :mobile-cards="hasMobileCards"
      default-sort="name"
      :loading="isLoading"
    >
      <template scope="props">
        <b-table-column field="name" label="Name" sortable>
          {{ props.row.name }}
        </b-table-column>

        <b-table-column field="quantity" label="Quantity" sortable>
          {{ props.row.quantity }}
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

    <button
      v-if="!showForm"
      @click="toggleForm"
      type="button"
      class="button"
    >Add an Arrangement</button>

    <div v-if="showForm">
      <add-event-arrangement @created="add" :eventId="eventId" :toggleForm="toggleForm"></add-event-arrangement>
    </div>
  </div>
</template>

<script>
import AddEventArrangement from './AddEventArrangement.vue';
import collection from '../mixins/collection';

export default {
  name: 'event-arrangements',
  components: { AddEventArrangement },
  mixins: [collection],
  props: {
    eventId: Number,
  },

  data() {
    return {
      defaultSortDirection: 'asc',
      hasMobileCards: true,
      isLoading: true,
      showForm: false,
    };
  },

  created() {
    this.fetch();
  },

  methods: {
    fetch() {
      window.axios.get(`/api/events/${this.eventId}/arrangements`)
        .then((data) => {
          this.isLoading = false;
          this.refresh(data);
        });
    },

    toggleForm() {
      this.showForm = !this.showForm;
    },
  },
};
</script>
