<template>
  <transition name="fade" mode="out-in">
    <add-setup
      v-if="showForm"
      :eventId="event.id"
      :isLoading="isLoading"
      :toggleForm="toggleForm"
      @created="add"
    ></add-setup>
    <setup-list
      v-else
      :setups="items"
      :subtotal="subtotal"
      :toggleForm="toggleForm"
    ></setup-list>
  </transition>
</template>

<script>
import AddSetup from 'components/Events/AddSetup';
import SetupList from 'components/Events/SetupList';
import collection from 'mixins/collection';

export default {
  name: 'setups',
  components: { AddSetup, SetupList },
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
    this.fetch(`/api/events/${this.event.id}/setups`);
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
