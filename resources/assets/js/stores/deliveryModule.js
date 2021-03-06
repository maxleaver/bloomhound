import Errors from 'helpers/Errors';
import { calculateSum } from 'helpers/helpers';

export default {
  namespaced: true,

  state: {
    errors: new Errors(),
    isLoading: false,
    isSubmitting: false,
    records: [],
    showForm: false,
  },

  mutations: {
    toggleForm(state) {
      state.showForm = !state.showForm;
      state.errors.clear();
    },

    fetchRequest(state) {
      state.isRequesting = true;
    },

    fetchSuccess(state, records) {
      state.isRequesting = false;
      state.records = records;
    },

    submitRequest(state) {
      state.isSubmitting = true;
    },

    submitSuccess(state, record) {
      state.records.unshift(record);
      state.errors.clear();

      state.isSubmitting = false;
      state.showForm = false;

      window.flash('Delivery added successfully!', 'success');
    },

    submitFailure(state, errors) {
      state.isSubmitting = false;
      state.errors.record(errors);

      window.flash('There was a problem saving your delivery!', 'danger');
    },

    updateRequest(state) {
      state.isSubmitting = true;
    },

    updateSuccess(state, data) {
      const record = state.records.find(item => item.id === data.id);
      record.address = data.address;
      record.description = data.description;
      record.fee = data.fee;
      record.deliver_on = data.deliver_on;

      state.errors.clear();
      state.isSubmitting = false;

      window.flash('Delivery updated successfully!', 'success');
    },

    updateFailure(state, errors) {
      state.isSubmitting = false;
      state.errors.record(errors);

      window.flash('There was a problem updating your delivery!', 'danger');
    },
  },

  actions: {
    fetch({ commit, rootState }) {
      commit('fetchRequest');

      window.axios.get(`/api/proposals/${rootState.proposal.id}/deliveries`)
        .then((data) => {
          commit('fetchSuccess', data.data);
        });
    },

    submit({ commit, rootState }, data) {
      commit('submitRequest');

      window.axios.post(`/api/proposals/${rootState.proposal.id}/deliveries`, data)
        .then((response) => {
          commit('submitSuccess', response.data);
        })
        .catch((error) => {
          commit('submitFailure', error.response.data.errors);
        });
    },

    update({ commit }, { id, data }) {
      commit('updateRequest');

      window.axios.patch(`/api/deliveries/${id}`, data)
        .then((response) => {
          commit('updateSuccess', response.data);
        })
        .catch((error) => {
          commit('updateFailure', error.response.data.errors);
        });
    },
  },

  getters: {
    subtotal: state => calculateSum(state.records, 'fee'),
  },
};
