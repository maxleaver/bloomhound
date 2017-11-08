<template>
  <transition name="fade" mode="out-in">
    <setup-form
      v-if="store.state.setup.showForm"
      :form="form"
      :store="store"
      :timezone="timezone"
    ></setup-form>
    <setup-list
      v-else
      :store="store"
      :timezone="timezone"
    ></setup-list>
  </transition>
</template>

<script>
import SetupForm from 'components/Events/SetupForm';
import SetupList from 'components/Events/SetupList';
import Form from 'helpers/Form';

export default {
  name: 'setups',
  components: { SetupForm, SetupList },
  props: {
    store: Object,
    timezone: String,
  },

  data() {
    return {
      form: new Form({
        address: '',
        setup_on: new Date(),
        description: '',
        fee: '',
      }),
    };
  },
};
</script>
