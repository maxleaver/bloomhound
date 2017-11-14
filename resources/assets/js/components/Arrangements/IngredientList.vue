<template>
  <div>
    <h1 class="title is-5">Ingredients</h1>

    <b-table
      default-sort="name"
      focusable="true"
      hoverable="true"
      :data="ingredients"
      :default-sort-direction="defaultSortDirection"
      :mobile-cards="hasMobileCards"
    >
      <template slot-scope="{ row }">
        <b-table-column field="name" label="Name" sortable>
          {{ row.arrangeable.ingredient_name }}
        </b-table-column>

        <b-table-column field="quantity" label="Quantity" width="100">
          <b-field v-bind:type="row.hasError ? 'is-danger' : ''">
            <b-input
              type="number"
              step="any"
              v-model="row.quantity"
              @input="updateQuantity(row)"
              required
            ></b-input>
          </b-field>
        </b-table-column>

        <b-table-column
          field="cost"
          label="Cost/Unit"
          sortable
          width="100"
          :visible="store.state.showCosts"
        >
          {{ Number(row.arrangeable.cost).toFixed(2) }}
        </b-table-column>

        <b-table-column
          field="cost"
          label="Cost"
          sortable
          width="100"
          :visible="store.state.showCosts"
        >
          {{ Number(row.arrangeable.cost * row.quantity).toFixed(2) }}
        </b-table-column>

        <b-table-column
          field="price"
          label="Price/Unit"
          sortable
          width="100"
          :visible="store.state.showPrices"
        >
          {{ row.arrangeable.price }}
        </b-table-column>

        <b-table-column
          field="subtotal"
          label="Subtotal"
          sortable
          width="100"
          :visible="store.state.showPrices"
        >
          {{ Number(row.arrangeable.price * row.quantity).toFixed(2) }}
        </b-table-column>

        <b-table-column label="" width="40" centered>
          <span @click="deleteIngredient(row.id)">
            <b-icon icon="delete"></b-icon>
          </span>
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
      class="button is-text"
      type="button"
      @click="store.commit('arrangement/toggleIngredientForm')"
      v-if="!showIngredientForm"
    >Add Ingredients</button>

    <ingredient-form
      :arrangeables="arrangeables"
      :id="id"
      :store="store"
      v-if="showIngredientForm"
    ></ingredient-form>
  </div>
</template>

<script>
import IngredientForm from 'components/Arrangements/IngredientForm';

export default {
  name: 'ingredient-list',
  components: { IngredientForm },
  props: {
    id: Number,
    arrangeables: Array,
    ingredients: Array,
    store: Object,
  },

  data() {
    return {
      defaultSortDirection: 'asc',
      doneTypingInterval: 500,
      hasMobileCards: true,
      typingTimer: '',
    };
  },

  computed: {
    showIngredientForm() {
      return this.store.state.arrangement.showIngredientForm;
    },
  },

  methods: {
    deleteIngredient(ingredientId) {
      this.store.dispatch('arrangement/deleteIngredient', {
        arrangement_id: this.id,
        ingredient_id: ingredientId,
      });
    },

    validate(data) {
      const quantity = parseFloat(data);

      console.log(quantity);

      if (!quantity) {
        // Quantity is empty
        return false;
      }

      if (Number.isNaN(quantity) || quantity <= 0) {
        // Quantity is not a valid value
        return false;
      }

      return true;
    },

    updateQuantity(row) {
      clearTimeout(this.typingTimer);

      if (!this.validate(row.quantity)) {
        // Set the validation style
        row.hasError = true;
        return;
      }

      row.hasError = false;
      this.typingTimer = setTimeout(this.doneTyping, this.doneTypingInterval, row);
    },

    doneTyping(row) {
      this.store.dispatch('arrangement/updateIngredient', {
        arrangement_id: row.arrangement_id,
        ingredient_id: row.id,
        quantity: parseFloat(row.quantity),
      });
    },
  },
};
</script>
