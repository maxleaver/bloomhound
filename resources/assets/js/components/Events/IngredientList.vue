<template>
  <div>
    <b-table
      default-sort="name"
      :data="items"
      :default-sort-direction="defaultSortDirection"
      :loading="isLoading"
      :mobile-cards="hasMobileCards"
    >
      <template scope="props">
        <b-table-column field="name" label="Name" sortable>
          {{ props.row.arrangeable.ingredient_name }}
        </b-table-column>

        <b-table-column field="quantity" label="Quantity" sortable>
          {{ props.row.quantity }}
        </b-table-column>

        <b-table-column centered>
          <span @click="deleteRow(props.row.id)">
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
      :arrangementId="arrangementId"
      :arrangeables="arrangeables"
      @created="addRows"
    ></ingredient-form>
  </div>
</template>

<script>
import IngredientForm from 'components/Events/IngredientForm';
import collection from 'mixins/collection';

export default {
  name: 'ingredient-list',
  components: { IngredientForm },
  mixins: [collection],
  props: {
    arrangementId: Number,
    arrangeables: Array,
  },

  data() {
    return {
      defaultSortDirection: 'asc',
      hasMobileCards: true,
    };
  },

  created() {
    this.fetch(`/api/arrangements/${this.arrangementId}/ingredients`);
  },

  methods: {
    addRows(data) {
      data.forEach((item) => {
        this.add(item);
      });
    },

    deleteRow(id) {
      window.axios.delete(`/api/arrangements/${this.arrangementId}/ingredients/${id}`)
        .then(() => {
          this.removeById(id);
        });
    },
  },
};
</script>
