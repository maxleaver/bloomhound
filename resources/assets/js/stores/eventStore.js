import Vuex from 'vuex';
import arrangementModule from './arrangementModule';
import deliveryModule from './deliveryModule';
import setupModule from './setupModule';

window.Vue.use(Vuex);

export default new Vuex.Store({
  modules: {
    arrangement: arrangementModule,
    delivery: deliveryModule,
    setup: setupModule,
  },

  state: {
    event: {},
    showCosts: false,
    showSettingsPanel: false,
  },

  mutations: {
    setEvent(state, event) {
      state.event = event;
    },

    toggleCosts(state, showCosts) {
      state.showCosts = showCosts;
    },

    toggleSettingPanel(state) {
      state.showSettingsPanel = !state.showSettingsPanel;
    },
  },

  actions: {

  },

  getters: {

  },
});
