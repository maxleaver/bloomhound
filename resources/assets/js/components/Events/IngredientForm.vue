<template>
  <form
    method="POST"
    @submit.prevent="onSubmit"
  >
    <hr>

    <div v-for="(form, index) in formContainer.forms">
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
import FormContainer from 'helpers/FormContainer';

export default {
  name: 'ingredient-form',
  components: { AddIngredient },

  props: {
    arrangeables: Array,
    arrangementId: Number,
  },

  data() {
    return {
      formContainer: new FormContainer({
        id: null,
        type: '',
        quantity: null,
      }, 3),
      isSubmitting: false,
    };
  },

  methods: {
    addRow(index) {
      this.formContainer.addRow(index);
    },

    onSubmit() {
      if (this.formContainer.isEmpty()) {
        return;
      }

      this.isSubmitting = true;

      this.formContainer.post(`/api/arrangements/${this.arrangementId}/ingredients`)
        .then((data) => {
          // Reset form to the defaults
          this.formContainer.reset();
          this.$emit('reset');

          window.flash('Ingredients added!', 'success');

          this.$emit('created', data);
        })
        .catch((error) => {
          // Only show the flash message if it's not a validation error
          if (typeof error.errors === 'undefined') {
            window.flash('There was a problem saving your ingredients. Please try again.', 'danger');
          }
        })
        .then(() => {
          this.isSubmitting = false;
        });
    },
  },
};
</script>
