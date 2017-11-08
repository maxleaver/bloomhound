import Vuex from 'vuex';
import moment from 'moment';
import arrangementModule from './arrangementModule';
import deliveryModule from './deliveryModule';
import setupModule from './setupModule';
import vendorModule from './vendorModule';

window.Vue.use(Vuex);

export default new Vuex.Store({
  modules: {
    arrangement: arrangementModule,
    delivery: deliveryModule,
    setup: setupModule,
    vendor: vendorModule,
  },

  state: {
    event: {},
    isSubmitting: false,
    proposal: {},
    showCosts: false,
    showEditForm: false,
    showPrices: true,
    showSettingsPanel: false,
    vendors: [],
  },

  mutations: {
    addVersionFailure(state, errors) {
      console.log(errors);
      window.flash('We could not generate a new version of your proposal.', 'danger');
    },

    makeProposalActive(state, proposal) {
      state.event.active_proposal_id = proposal.id;
      state.event.active_proposal = proposal;
    },

    setEvent(state, event) {
      state.event = event;
      state.event.date = moment(state.event.date);
    },

    setProposal(state, proposal) {
      state.proposal = proposal;
    },

    setVendors(state, vendors) {
      state.vendors = vendors;
    },

    switchProposal(state, id) {
      const foundProposal = state.event.proposals.find(item => item.id === id);
      state.proposal = foundProposal;
    },

    toggleCosts(state, showCosts) {
      state.showCosts = showCosts;
    },

    toggleEditForm(state) {
      state.showEditForm = !state.showEditForm;
    },

    togglePrices(state, showPrices) {
      state.showPrices = showPrices;
    },

    toggleSettingPanel(state) {
      state.showSettingsPanel = !state.showSettingsPanel;
    },

    updateRequest(state) {
      state.isSubmitting = true;
    },

    updateSuccess(state, data) {
      state.isSubmitting = false;
      state.event.date = moment(data.date);
      state.event.name = data.name;
      state.showEditForm = false;
    },

    updateFailure(state) {
      state.isSubmitting = false;
    },
  },

  actions: {
    addVersion({ commit, state }) {
      window.axios.post(`/api/events/${state.event.id}/proposals`)
        .then((response) => {
          commit('setProposal', response.data);
          window.flash('A new version of your proposal has been created.', 'success');
        })
        .catch((error) => {
          commit('addVersionFailure', error.response.data.errors);
        });
    },

    init({ commit, dispatch }, data) {
      commit('setEvent', data.event);
      commit('setVendors', data.vendors);
      dispatch('setProposal', data.proposal);
    },

    setProposal({ commit }, proposal) {
      commit('setProposal', proposal);
      commit('arrangement/set', proposal.arrangements);
      commit('delivery/fetchSuccess', proposal.deliveries);
      commit('setup/fetchSuccess', proposal.setups);
      commit('vendor/set', proposal.vendors);
    },

    fetchProposal({ dispatch }, id) {
      window.axios.get(`/api/proposals/${id}`)
        .then((response) => {
          dispatch('setProposal', response.data.data);
        })
        .catch((error) => {
          console.log(error.response.data.errors);
        });
    },

    makeActive({ commit, state }) {
      const url = `/api/events/${state.event.id}/proposals/${state.proposal.id}`;

      window.axios.patch(url)
        .then((response) => {
          commit('makeProposalActive', response.data);
          window.flash(`Version ${response.data.version} is now the active version`, 'success');
        })
        .catch((error) => {
          console.log(error.response.data.errors);
          window.flash('We could not change the active version for this event', 'danger');
        });
    },

    update({ commit, state }, data) {
      commit('updateRequest');

      window.axios.patch(`/api/events/${state.event.id}`, data)
        .then((response) => {
          commit('updateSuccess', response.data);
          window.flash(`${data.name} updated successfully!`, 'success');
        })
        .catch((error) => {
          commit('updateFailure', error.response.data.errors);
          window.flash(`There was a problem updating ${data.name}!`, 'danger');
        });
    },
  },

  getters: {

  },
});
