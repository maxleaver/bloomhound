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
            @click="store.commit('arrangement/toggleForm')">
            <span class="icon is-small">
              <i class="fa fa-plus"></i>
            </span>
            <span>Add an Arrangement</span>
          </button>
        </p>
      </div>
    </nav>

    <b-modal :active.sync="showForm" :canCancel="canCancel" has-modal-card>
      <add-arrangement :store="store"></add-arrangement>
    </b-modal>

    <b-modal
      :active.sync="store.state.arrangement.isDeleteConfirmationVisible"
      :canCancel="canCancel"
      has-modal-card
    >
      <delete-arrangement-modal :store="store"></delete-arrangement-modal>
    </b-modal>

    <b-table
      default-sort="name"
      detailed
      :data="items"
      :default-sort-direction="defaultSortDirection"
      :mobile-cards="hasMobileCards"
    >
      <template scope="props">
        <b-table-column field="name" label="Name" sortable>
          <strong>{{ props.row.name }}</strong>
          <span v-if="props.row.description"><br />{{ props.row.description }}</span>
        </b-table-column>

        <b-table-column field="quantity" label="Quantity" sortable>
          {{ props.row.quantity }}
        </b-table-column>

        <b-table-column
          field="cost"
          label="Cost per Unit"
          sortable
          :visible="store.state.showCosts"
        >
          {{ Number(props.row.cost).toFixed(2) }}
        </b-table-column>

        <b-table-column
          label="Total Cost"
          sortable
          :visible="store.state.showCosts"
        >
          {{ Number(props.row.cost * props.row.quantity).toFixed(2) }}
        </b-table-column>

        <b-table-column
          field="default_price"
          label="Price"
          sortable
          :visible="store.state.showPrices"
        >
          {{ Number(props.row.default_price).toFixed(2) }}
        </b-table-column>

        <b-table-column label="Subtotal" sortable :visible="store.state.showPrices">
          {{ Number(props.row.default_price * props.row.quantity).toFixed(2) }}
        </b-table-column>

        <b-table-column centered>
          <span @click="store.commit('arrangement/showDeleteConfirmation', props.row)">
            <b-icon icon="delete"></b-icon>
          </span>
        </b-table-column>
      </template>

      <template slot="detail" scope="props">
        <div>
          <div class="content">
            <update-arrangement
              :arrangement="props.row"
              :isSubmitting="store.state.arrangement.isSubmitting"
              :store="store"
            ></update-arrangement>
          </div>

          <ingredient-list
            :arrangeables="store.state.arrangement.arrangeables"
            :id="props.row.id"
            :ingredients="props.row.ingredients"
            :store="store"
            @added="increaseTotals"
            @deleted="decreaseTotals"
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

      <template slot="footer" v-if="store.state.showPrices">
        <div class="has-text-right content">
          <strong>Subtotal: ${{ subtotal }}</strong>
        </div>
      </template>
    </b-table>
  </div>
</template>

<script>
import AddArrangement from 'components/Events/AddArrangement';
import DeleteArrangementModal from 'components/Events/DeleteArrangementModal';
import IngredientList from 'components/Events/IngredientList';
import UpdateArrangement from 'components/Events/UpdateArrangement';

export default {
  name: 'arrangement-list',
  components: {
    AddArrangement,
    DeleteArrangementModal,
    IngredientList,
    UpdateArrangement,
  },
  props: {
    store: Object,
  },

  data() {
    return {
      canCancel: ['escape'],
      defaultSortDirection: 'asc',
      hasMobileCards: true,
    };
  },

  created() {
    this.store.dispatch('arrangement/fetchArrangeables');
  },

  computed: {
    items() {
      return this.store.state.arrangement.records;
    },

    showForm() {
      return this.store.state.arrangement.showForm;
    },

    subtotal() {
      return Number(this.store.getters['arrangement/subtotal']).toFixed(2);
    },
  },

  methods: {
    decreaseTotals(data) {
      this.updateTotals('subtract', data);
    },

    increaseTotals(data) {
      this.updateTotals('add', data);
    },

    updateTotals(action, data) {
      const arrangement = this.findById(data.arrangement_id);

      if (action === 'add') {
        arrangement.cost += data.cost;
        arrangement.default_price += data.price;
      }

      if (action === 'subtract') {
        arrangement.cost -= data.cost;
        arrangement.default_price -= data.price;
      }
    },
  },
};
</script>
