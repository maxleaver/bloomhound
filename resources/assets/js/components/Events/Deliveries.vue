<template>
  <transition name="fade" mode="out-in">
    <add-delivery
      v-if="store.state.delivery.showForm"
      :store="store"
    ></add-delivery>
    <delivery-list
      v-else
      :store="store"
    ></delivery-list>
  </transition>
</template>

<script>
import AddDelivery from 'components/Events/AddDelivery';
import DeliveryList from 'components/Events/DeliveryList';

export default {
  name: 'deliveries',
  components: { AddDelivery, DeliveryList },
  props: {
    store: Object,
  },

  created() {
    this.store.dispatch('delivery/fetch');
  },
};
</script>
