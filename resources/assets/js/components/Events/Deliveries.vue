<template>
  <transition name="fade" mode="out-in">
    <add-delivery
      v-if="showForm"
      :eventId="event.id"
      :isLoading="isLoading"
      :toggleForm="toggleForm"
      @created="add"
    ></add-delivery>
    <delivery-list
      v-else
      :deliveries="items"
      :subtotal="subtotal"
      :toggleForm="toggleForm"
    ></delivery-list>
  </transition>
</template>

<script>
import AddDelivery from 'components/Events/AddDelivery';
import DeliveryList from 'components/Events/DeliveryList';
import collection from 'mixins/collection';

export default {
  name: 'deliveries',
  components: { AddDelivery, DeliveryList },
  mixins: [collection],
  props: {
    event: Object,
  },

  data() {
    return {
      showForm: false,
    };
  },

  computed: {
    subtotal: function () {
      let sum;

      if (this.items.length > 0) {
        sum = this.items.reduce((total, item) => total + (item.fee), 0);
      } else {
        sum = 0.00;
      }

      this.$emit('totalUpdate', sum);

      return sum;
    },
  },

  created() {
    this.fetch(`/api/events/${this.event.id}/deliveries`);
  },

  methods: {
    toggleForm() {
      this.showForm = !this.showForm;
    },
  },
};
</script>

<style>
  .fade-enter-active, .fade-leave-active {
    transition: opacity .3s ease;
  }

  .fade-enter, .fade-leave-to {
    opacity: 0;
  }
</style>
