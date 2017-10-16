<template>
  <b-field grouped>
    <b-field :label="title">
      <b-select
        placeholder="Select a markup"
        :loading="isSubmitting"
        :value="form.markup_id"
        @input="onChange"
      >
        <option
          v-for="markup in markups"
          :value="markup.id"
          :key="markup.id">
          {{ markup.title }}
        </option>
      </b-select>
    </b-field>

    <b-field
      v-if="showField"
      :label="valueLabel"
      :message="form.errors.has('markup_value') ? form.errors.get('markup_value') : ''"
      :type="form.errors.has('markup_value') ? 'is-danger' : ''"
    >
      <b-input
        type="number"
        v-model="form.markup_value"
        :disabled="isSubmitting"
      ></b-input>
    </b-field>
  </b-field>
</template>

<script>
export default {
  name: 'arrangeable-type-setting',

  props: {
    currentMarkup: Object,
    form: Object,
    id: Number,
    isSubmitting: Boolean,
    markups: Array,
    markupValue: Number,
    title: String,
  },

  data() {
    return {
      showField: false,
      valueLabel: this.currentMarkup.field_label,
    };
  },

  created() {
    if (this.currentMarkup.allow_entry) {
      this.showField = true;
    }

    this.form.arrangeable_type_id = this.id;
    this.form.markup_id = this.currentMarkup.id;
    this.form.markup_value = this.markupValue;
  },

  methods: {
    onChange(option) {
      const record = this.markups.find(markup => markup.id === option);

      this.form.markup_id = option;

      if (record.allow_entry) {
        this.showField = true;
        this.valueLabel = record.field_label;
      } else {
        this.showField = false;
        this.valueLabel = null;
        this.form.markup_value = null;
      }
    },
  },
};
</script>
