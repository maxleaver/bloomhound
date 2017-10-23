<template>
  <transition name="fade" mode="out-in">
    <add-setup
      v-if="store.state.setup.showForm"
      :store="store"
    ></add-setup>
    <setup-list
      v-else
      :store="store"
    ></setup-list>
  </transition>
</template>

<script>
import AddSetup from 'components/Events/AddSetup';
import SetupList from 'components/Events/SetupList';

export default {
  name: 'setups',
  components: { AddSetup, SetupList },
  props: {
    store: Object,
  },

  created() {
    this.store.dispatch('setup/fetch');
  },
};
</script>
