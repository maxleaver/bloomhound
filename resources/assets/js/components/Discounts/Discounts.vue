<template>
  <div>
    <h1 class="title is-5">Discounts</h1>

    <b-table
      default-sort="name"
      :data="discounts"
      :default-sort-direction="defaultSortDirection"
      :mobile-cards="hasMobileCards"
    >
      <template slot-scope="props">
        <b-table-column field="name" label="Name" sortable>
          {{ props.row.name }}
        </b-table-column>

        <b-table-column field="amount" label="Amount" sortable>
          {{ formatAmount(props.row.amount, props.row.type) }}
        </b-table-column>

        <b-table-column field="type" label="Type" sortable>
          {{ props.row.type }}
        </b-table-column>

        <b-table-column label="" width="40" centered>
          <span @click="onDelete(id, props.row.id)">
            <b-icon icon="delete"></b-icon>
          </span>
        </b-table-column>
      </template>
    </b-table>

    <form
      method="POST"
      @submit.prevent="onSubmit(id)"
    >
      <b-field grouped>
        <b-field
          expanded
          label="Name"
          :type="form.errors.has('name') ? 'is-danger' : ''"
          :message="form.errors.has('name') ? form.errors.get('name') : ''"
        >
          <b-input
            type="text"
            v-model="form.name"
            :disabled="isSubmitting"
            required
          ></b-input>
        </b-field>

        <b-field
          expanded
          label="Amount"
          :type="form.errors.has('amount') ? 'is-danger' : ''"
          :message="form.errors.has('amount') ? form.errors.get('amount') : ''"
        >
          <b-input
            type="number"
            v-model="form.amount"
            :disabled="isSubmitting"
            required
          ></b-input>
        </b-field>

        <b-field label="Type">
          <b-field>
            <b-radio-button v-model="form.type" native-value="fixed">
              <span>$ off</span>
            </b-radio-button>

            <b-radio-button v-model="form.type" native-value="percent">
              <span>% off</span>
            </b-radio-button>
          </b-field>
        </b-field>
      </b-field>

      <button
        class="button is-primary"
        type="submit"
        v-bind:class="{'is-loading' : isSubmitting}"
        :disabled="isSubmitting"
      >Add Discount</button>
    </form>
  </div>
</template>

<script>
export default {
  name: 'discounts',
  props: {
    discounts: Array,
    form: Object,
    id: Number,
    isSubmitting: Boolean,
    onDelete: Function,
    onSubmit: Function,
  },

  data() {
    return {
      defaultSortDirection: 'asc',
      hasMobileCards: true,
    };
  },

  methods: {
    formatAmount(amount, type) {
      if (type === 'fixed') {
        return Number(amount).toFixed(2);
      }

      if (type === 'percent') {
        return `${amount}%`;
      }

      return amount;
    },
  },
};
</script>
