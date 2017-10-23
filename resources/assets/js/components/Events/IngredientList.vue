<template>
  <div>
    <h1 class="title is-5">Ingredients</h1>

    <b-table
      default-sort="name"
      :data="ingredients"
      :default-sort-direction="defaultSortDirection"
      :mobile-cards="hasMobileCards"
    >
      <template scope="props">
        <b-table-column field="name" label="Name" sortable>
          {{ props.row.arrangeable.ingredient_name }}
        </b-table-column>

        <b-table-column field="quantity" label="Quantity" sortable>
          {{ props.row.quantity }}
        </b-table-column>

        <b-table-column field="cost" label="Cost/Unit" sortable>
          {{ Number(props.row.arrangeable.cost).toFixed(2) }}
        </b-table-column>

        <b-table-column field="cost" label="Cost" sortable>
          {{ Number(props.row.arrangeable.cost * props.row.quantity).toFixed(2) }}
        </b-table-column>

        <b-table-column field="price" label="Price/Unit" sortable>
          {{ props.row.arrangeable.price }}
        </b-table-column>

        <b-table-column label="Subtotal" sortable>
          {{ Number(props.row.arrangeable.price * props.row.quantity).toFixed(2) }}
        </b-table-column>

        <b-table-column centered>
          <span @click="deleteIngredient(props.row.id)">
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

    <ingredient-form
      :arrangeables="arrangeables"
      :id="id"
      :store="store"
    ></ingredient-form>
  </div>
</template>

<script>
import IngredientForm from 'components/Events/IngredientForm';

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
      hasMobileCards: true,
    };
  },

  methods: {
    deleteIngredient(ingredientId) {
      this.store.dispatch('arrangement/deleteIngredient', {
        arrangement_id: this.id,
        ingredient_id: ingredientId,
      });
    },
  },
};
</script>
