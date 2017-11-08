<template>
  <transition name="fade" mode="out-in">
    <vendor-form
      v-if="store.state.vendor.showForm"
      :store="store"
    ></vendor-form>
    <vendor-list v-else :store="store"></vendor-list>
  </transition>
</template>

<script>
import VendorForm from 'components/Events/VendorForm';
import VendorList from 'components/Events/VendorList';

export default {
  name: 'vendors',
  components: { VendorForm, VendorList },
  props: {
    store: Object,
  },
};
</script>
