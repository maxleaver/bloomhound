<template>
  <form
    method="POST"
    @submit.prevent="onSubmit"
  >
    <hr>

    <div v-for="(form, index) in rows">
      <add-ingredient
        :arrangeables="arrangeables"
        :form="form"
        :key="index"
        :index="index"
        :isSubmitting="isSubmitting"
        :onFocus="addRow"
        :showLabels="index === 0"
      ></add-ingredient>
    </div>

    <button
      class="button is-primary"
      type="submit"
      v-bind:class="{'is-loading' : isSubmitting}"
      :disabled="isSubmitting"
    >Add Ingredients</button>
  </form>
</template>

<script>
import AddIngredient from 'components/Events/AddIngredient';

export default {
  name: 'ingredient-form',
  components: { AddIngredient },

  props: {
    arrangeables: Array,
    id: Number,
    store: Object,
  },

  computed: {
    rows() {
      return this.store.state.arrangement.ingredientFormContainer.forms;
    },

    isSubmitting() {
      return this.store.state.arrangement.isAddingIngredient;
    },
  },

  methods: {
    addRow(index) {
      this.store.commit('arrangement/addIngredientRow', index);
    },

    onSubmit() {
      const formContainer = this.store.state.arrangement.ingredientFormContainer;

      if (formContainer.isEmpty()) {
        return;
      }

      this.store.dispatch('arrangement/addIngredient', {
        arrangement_id: this.id,
        data: formContainer.data(),
      });

      formContainer.reset();
      this.$emit('reset');
    },
  },
};
</script>
