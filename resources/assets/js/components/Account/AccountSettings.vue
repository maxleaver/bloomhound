<template>
  <div>
    <section class="section">
      <account-markups :markups="markups" :settings="typeSettings"></account-markups>
    </section>

    <section class="section">
      <account-tax-settings :settings="settings"></account-tax-settings>
    </section>
  </div>
</template>

<script>
import AccountMarkups from 'components/Account/AccountMarkups';
import AccountTaxSettings from 'components/Account/AccountTaxSettings';

export default {
  name: 'account-settings',
  components: { AccountMarkups, AccountTaxSettings },

  props: {
    markups: Array,
    settings: Object,
    typeSettings: Array,
  },
};
</script>
