<template>
  <div>
    <b-table
      :data="this.sourceList"
      :mobile-cards="hasMobileCards"
    >
      <template scope="props">
          <b-table-column label="Vendor">
              {{ props.row.vendor.name }}
              <span v-if="props.row.isBestPrice" class="tag is-success">Best Price</span>
          </b-table-column>

          <b-table-column label="Stems per Bunch" numeric>
              {{ props.row.stems_per_bunch }}
          </b-table-column>

          <b-table-column label="Cost" numeric>
              {{ toCurrency(props.row.cost) }}
          </b-table-column>

          <b-table-column label="Cost/Stem" numeric>
              {{ toCurrency(props.row.cost / props.row.stems_per_bunch) }}
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

    <a v-if="!showForm" @click.prevent="toggleForm">Add purchase sources and costs</a>

    <flower-variety-source-form
      v-if="showForm"
      :id="id"
      :vendors="vendors"
      :toggleForm="toggleForm"
      @created="addRows"
    ></flower-variety-source-form>
  </div>
</template>

<script>
import FlowerVarietySourceForm from './FlowerVarietySourceForm.vue';

export default {
  name: 'variety-source-list',
  components: { FlowerVarietySourceForm },

  props: {
    id: Number,
    sources: Array,
    vendors: Array,
  },

  data() {
    return {
      hasMobileCards: true,
      isSubmitting: false,
      showForm: false,
      sourceList: [],
    };
  },

  created() {
    if (typeof this.sources !== 'undefined') {
      this.sourceList = this.sources;
    }
  },

  methods: {
    addRows(data) {
      data.forEach((row) => {
        if (Object.prototype.hasOwnProperty.call(row, 'isBestPrice') && row.isBestPrice) {
          // Remove the old best price
          this.unsetBestPrice();

          // Trigger parent event to update price
          this.$emit('bestPriceUpdated', [this.id, row.cost_per_stem]);
        }

        this.sourceList.push(row);
      });
    },

    unsetBestPrice() {
      const oldBestPrice = this.sourceList.find(source => source.isBestPrice);

      if (typeof oldBestPrice !== 'undefined') {
        oldBestPrice.isBestPrice = false;
      }
    },

    toCurrency(num) {
      return num.toFixed(2);
    },

    toggleForm() {
      this.showForm = !this.showForm;
    },
  },
};
</script>

<style>
  .tag {
    margin-left: 10px;
  }
</style>
