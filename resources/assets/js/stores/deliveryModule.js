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
      window.flash('Delivery added successfully!', 'success');

      state.records.unshift(record);
      state.errors.clear();

      state.isSubmitting = false;
      state.showForm = false;
    },

    submitFailure(state, errors) {
      window.flash('There was a problem saving your delivery!', 'danger');

      state.isSubmitting = false;
      state.errors.record(errors);
    },
  },
  actions: {
    fetch({ commit, rootState }) {
      commit('fetchRequest');

      window.axios.get(`/api/events/${rootState.event.id}/deliveries`)
        .then((data) => {
          commit('fetchSuccess', data.data);
        });
    },

    submit({ commit, rootState }, data) {
      commit('submitRequest');

      window.axios.post(`/api/events/${rootState.event.id}/deliveries`, data)
        .then((response) => {
          commit('submitSuccess', response.data);
        })
        .catch((error) => {
          commit('submitFailure', error.response.data.errors);
        });
    },
  },
  getters: {
    subtotal: state => calculateSum(state.records, 'fee'),
  },
};
