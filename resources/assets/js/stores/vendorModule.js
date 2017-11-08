import Errors from 'helpers/Errors';

export default {
  namespaced: true,

  state: {
    errors: new Errors(),
    isSubmitting: false,
    records: [],
    showForm: false,
  },

  mutations: {
    set(state, records) {
      state.records = records;
    },

    toggleForm(state) {
      state.showForm = !state.showForm;
      state.errors.clear();
    },

    submitRequest(state) {
      state.isSubmitting = true;
    },

    submitSuccess(state, record) {
      state.records.unshift(record);
      state.errors.clear();

      state.isSubmitting = false;
      state.showForm = false;

      window.flash('Vendor added successfully!', 'success');
    },

    submitFailure(state, errors) {
      state.isSubmitting = false;
      state.errors.record(errors);

      window.flash('There was a problem saving your vendor!', 'danger');
    },

    deleteSuccess(state, id) {
      const index = state.records.findIndex(i => i.id === id);
      state.records.splice(index, 1);
      state.isSubmitting = false;

      window.flash('Vendor deleted successfully!', 'success');
    },

    deleteFailure(state) {
      state.isSubmitting = false;
      window.flash('There was a problem deleting your vendor.', 'danger');
    },
  },

  actions: {
    add({ commit, rootState }, data) {
      commit('submitRequest');

      window.axios.post(`/api/proposals/${rootState.proposal.id}/vendors`, data)
        .then((response) => {
          commit('submitSuccess', response.data);
        })
        .catch((error) => {
          commit('submitFailure', error.response.data.errors);
        });
    },

    delete({ commit, rootState }, id) {
      commit('submitRequest');

      window.axios.delete(`/api/proposals/${rootState.proposal.id}/vendors/${id}`)
        .then(() => {
          commit('deleteSuccess', id);
        })
        .catch((error) => {
          commit('deleteFailure', error.response.data.errors);
        });
    },
  },

  getters: {

  },
};
