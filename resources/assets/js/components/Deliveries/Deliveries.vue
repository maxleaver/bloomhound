<template>
  <transition name="fade" mode="out-in">
    <delivery-form
      v-if="store.state.delivery.showForm"
      :form="form"
      :store="store"
      :timezone="timezone"
    ></delivery-form>
    <delivery-list
      v-else
      :store="store"
      :timezone="timezone"
    ></delivery-list>
  </transition>
</template>

<script>
import DeliveryForm from 'components/Deliveries/DeliveryForm';
import DeliveryList from 'components/Deliveries/DeliveryList';
import Form from 'helpers/Form';

export default {
  name: 'deliveries',
  components: { DeliveryForm, DeliveryList },
  props: {
    store: Object,
    timezone: String,
  },

  data() {
    return {
      form: new Form({
        address: '',
        deliver_on: new Date(),
        description: '',
        fee: '',
      }),
    };
  },
};
</script>
