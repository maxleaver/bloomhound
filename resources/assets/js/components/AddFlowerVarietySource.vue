<template>
  <b-field grouped>
    <b-field
      expanded
      :label="showLabels ? 'Vendor' : ''"
      :type="form.errors.has('vendor_name') ? 'is-danger' : ''"
    >
      <b-autocomplete
        field="name"
        name="vendor"
        placeholder="e.g. Florist Wholesaler, Inc."
        v-model="form.vendor_name"
        :data="filteredDataObj"
        :disabled="isSubmitting"
        :keep-first="keepFirst"
        @select="vendorSelect"
      ></b-autocomplete>
    </b-field>

    <b-field
      :label="showLabels ? 'Stems per Bunch' : ''"
      :type="form.errors.has('stems_per_bunch') ? 'is-danger' : ''"
    >
      <b-input
        name="stems_per_bunch"
        placeholder="10"
        type="number"
        v-model="form.stems_per_bunch"
        :disabled="isSubmitting"
        @focus="handleFocus"
      ></b-input>
    </b-field>

    <b-field
      :label="showLabels ? 'Cost' : ''"
      :type="form.errors.has('cost') ? 'is-danger' : ''"
    >
      <b-input
        name="cost"
        placeholder="9.95"
        step="0.01"
        type="number"
        v-model="form.cost"
        :disabled="isSubmitting"
        @focus="handleFocus"
      ></b-input>
    </b-field>
  </b-field>
</template>

<script>
export default {
  name: 'add-variety-source',

  props: {
    form: Object,
    onFocus: Function,
    id: Number,
    index: Number,
    isSubmitting: Boolean,
    showLabels: Boolean,
    vendors: Array,
  },

  data() {
    return {
      keepFirst: true,
    };
  },

  computed: {
    filteredDataObj() {
      return this.vendors.filter(option =>
        option.name
          .toString()
          .toLowerCase()
          .indexOf(this.form.vendor_name.toLowerCase()) >= 0);
    },
  },

  methods: {
    handleFocus(event) {
      // clear any validation errors
      this.form.errors.clear(event.target.name);

      // trigger the parent on focus function
      this.onFocus(this.index);
    },

    vendorSelect(option) {
      // clear any validation errors
      this.form.errors.clear('vendor_id');
      this.form.errors.clear('vendor_name');

      if (option && Object.prototype.hasOwnProperty.call(option, 'id')) {
        this.form.vendor_id = option.id;
        return;
      }

      this.form.vendor_id = null;
    },
  },
};
</script>
