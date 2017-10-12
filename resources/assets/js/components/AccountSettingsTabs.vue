<template>
  <section>
    <b-tabs type="is-boxed" position="is-centered" v-model="activeTab" @change="onTabChange">
      <b-tab-item label="Profile">
        <div class="section">
          <account-profile :account="account"></account-profile>
        </div>
      </b-tab-item>
      <b-tab-item label="Settings">
        <div class="section">
          <account-settings
            :markups="markups"
            :settings="settings"
          ></account-settings>
        </div>
      </b-tab-item>
    </b-tabs>
  </section>
</template>

<script>
import AccountProfile from './AccountProfile.vue';
import AccountSettings from './AccountSettings.vue';

export default {
  name: 'account-settings-tabs',
  components: { AccountProfile, AccountSettings },

  props: {
    account: Object,
    markups: Array,
    settings: Array,
  },

  created() {
    switch (window.location.hash) {
      case '#settings':
        this.activeTab = 1;
        break;
      default:
        this.activeTab = 0;
        break;
    }
  },

  data() {
    return {
      activeTab: 0,
    };
  },

  methods: {
    onTabChange(tab) {
      switch (tab) {
        case 0:
          window.location.hash = '';
          break;
        case 1:
          window.location.hash = 'settings';
          break;
        default:
          break;
      }
    },
  },
};
</script>
