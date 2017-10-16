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
            @click="isAddModalActive = true">
            <span class="icon is-small">
              <i class="fa fa-plus"></i>
            </span>
            <span>Add an Arrangement</span>
          </button>
        </p>
      </div>
    </nav>

    <b-modal :active.sync="isAddModalActive" :canCancel="canCancel" has-modal-card>
      <add-event-arrangement @created="add" :eventId="eventId"></add-event-arrangement>
    </b-modal>

    <b-modal
      :active.sync="isDeleteModalActive"
      :canCancel="canCancel"
      has-modal-card
    >
      <delete-arrangement-modal v-bind="deleteModalProps" @deleted="removeById"></delete-arrangement-modal>
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

        <b-table-column centered>
          <span @click="showDeleteModal(props.row)">
            <b-icon icon="delete"></b-icon>
          </span>
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
import AddEventArrangement from 'components/Events/AddEventArrangement';
import DeleteArrangementModal from 'components/Events/DeleteArrangementModal';
import IngredientList from 'components/Events/IngredientList';
import collection from 'mixins/collection';

export default {
  name: 'event-arrangements',
  components: { AddEventArrangement, DeleteArrangementModal, IngredientList },
  mixins: [collection],
  props: {
    eventId: Number,
  },

  data() {
    return {
      arrangeables: [],
      canCancel: ['escape'],
      defaultSortDirection: 'asc',
      deleteModalProps: {
        id: '',
        name: '',
      },
      hasMobileCards: true,
      isDeleteModalActive: false,
      isAddModalActive: false,
    };
  },

  created() {
    this.fetch(`/api/events/${this.eventId}/arrangements`);
    this.fetchArrangeables();
  },

  methods: {
    fetchArrangeables() {
      window.axios.get('/api/arrangeables')
        .then((data) => {
          this.arrangeables = data.data;
        });
    },

    showDeleteModal(row) {
      this.deleteModalProps.id = row.id;
      this.deleteModalProps.name = row.name;

      this.isDeleteModalActive = true;
    },
  },
};
</script>
