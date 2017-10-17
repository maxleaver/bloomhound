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
      <add-arrangement @created="add" :eventId="event.id"></add-arrangement>
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

        <b-table-column field="cost" label="Cost per Unit" sortable>
          {{ toTwoDigits(props.row.cost) }}
        </b-table-column>

        <b-table-column label="Total Cost" sortable>
          {{ toTwoDigits(props.row.cost * props.row.quantity) }}
        </b-table-column>

        <b-table-column field="default_price" label="Price" sortable>
          {{ toTwoDigits(props.row.default_price) }}
        </b-table-column>

        <b-table-column label="Subtotal" sortable>
          {{ toTwoDigits(props.row.default_price * props.row.quantity) }}
        </b-table-column>

        <b-table-column centered>
          <span @click="showDeleteModal(props.row)">
            <b-icon icon="delete"></b-icon>
          </span>
        </b-table-column>
      </template>

      <template slot="detail" scope="props">
        <div>
          <div class="content">
            <update-arrangement
              :arrangement="props.row"
              @updated="onUpdate"
            ></update-arrangement>
          </div>

          <ingredient-list
            :arrangementId="props.row.id"
            :arrangeables="arrangeables"
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

      <template slot="footer">
        <div class="has-text-right content">
          <strong>Subtotal:</strong> ${{ toTwoDigits(subtotal) }}<br />
          <strong>Tax:</strong> ${{ toTwoDigits(tax) }}<br />
          <strong>Total: ${{ toTwoDigits(total) }}</strong>
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
import collection from 'mixins/collection';

export default {
  name: 'arrangement-list',
  components: {
    AddArrangement,
    DeleteArrangementModal,
    IngredientList,
    UpdateArrangement,
  },
  mixins: [collection],
  props: {
    event: Object,
    isTaxable: Boolean,
    settings: Object,
    taxAmount: Number,
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

  computed: {
    subtotal: function () {
      if (this.items.length > 0) {
        return this.items.reduce((total, item) => total + item.default_price, 0);
      }

      return 0.00;
    },

    tax: function () {
      if (this.isTaxable) {
        return this.subtotal * (this.taxAmount / 100);
      }

      return 0.00;
    },

    total: function () {
      return this.subtotal + this.tax;
    },
  },

  created() {
    this.fetch(`/api/events/${this.event.id}/arrangements`);
    this.fetchArrangeables();
  },

  methods: {
    toTwoDigits(number) {
      return Number(number).toFixed(2);
    },

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

    decreaseTotals(data) {
      this.updateTotals('subtract', data);
    },

    increaseTotals(data) {
      this.updateTotals('add', data);
    },

    onUpdate(data) {
      const arrangement = this.findById(data.id);

      arrangement.name = data.name;
      arrangement.quantity = data.quantity;
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
