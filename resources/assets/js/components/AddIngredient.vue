<template>
  <b-field grouped>
    <b-field
      expanded
      :label="showLabels ? 'Ingredient' : ''"
      :type="form.errors.has('id') || form.errors.has('type') ? 'is-danger' : ''"
    >
      <b-autocomplete
        field="name"
        name="ingredient"
        v-model="ingredient"
        :data="filteredDataObj"
        :disabled="isSubmitting"
        :keep-first="keepFirst"
        @select="onSelect"
      ></b-autocomplete>
    </b-field>

    <b-field
      :label="showLabels ? 'Quantity' : ''"
      :type="form.errors.has('quantity') ? 'is-danger' : ''"
    >
      <b-input
        name="quantity"
        type="number"
        v-model="form.quantity"
        :disabled="isSubmitting"
        @focus="handleFocus"
      ></b-input>
    </b-field>
  </b-field>
</template>

<script>
export default {
  name: 'add-ingredient',

  props: {
    arrangeables: Array,
    form: Object,
    index: Number,
    isSubmitting: Boolean,
    onFocus: Function,
    showLabels: Boolean,
  },

  created: function () {
    this.$parent.$on('reset', this.reset);
  },

  data() {
    return {
      ingredient: '',
      keepFirst: true,
    };
  },

  computed: {
    filteredDataObj() {
      return this.arrangeables.filter(option =>
        option.name
          .toString()
          .toLowerCase()
          .indexOf(this.ingredient.toLowerCase()) >= 0);
    },
  },

  methods: {
    handleFocus(event) {
      // clear any validation errors
      this.form.errors.clear(event.target.name);

      // trigger the parent on focus function
      this.onFocus(this.index);
    },

    onSelect(option) {
      // clear any validation errors
      this.form.errors.clear('id');
      this.form.errors.clear('type');

      if (option && Object.prototype.hasOwnProperty.call(option, 'id')) {
        this.form.id = option.id;
      }

      if (option && Object.prototype.hasOwnProperty.call(option, 'arrangeable_type')) {
        this.form.type = option.arrangeable_type;
      }
    },

    reset() {
      this.ingredient = '';
    },
  },
};
</script>
