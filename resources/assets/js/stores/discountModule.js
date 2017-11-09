import Form from 'helpers/Form';

export default {
  namespaced: true,

  state: {
    form: new Form({
      name: '',
      amount: 0,
      type: 'fixed',
    }),
    isSubmitting: false,
    records: [],
  },

  mutations: {
    set(state, records) {
      state.records = records;
    },

    submitRequest(state) {
      state.isSubmitting = true;
    },

    submitSuccess(state, data) {
      state.records.unshift(data);
      state.isSubmitting = false;
      state.form.reset();
    },

    submitFailure(state, error) {
      state.isSubmitting = false;
      state.form.errors.record(error);
    },

    deleteRequest(state) {
      state.isSubmitting = true;
    },

    deleteSuccess(state, id) {
      const index = state.records.findIndex(i => i.id === id);
      state.records.splice(index, 1);
      state.isSubmitting = false;
    },

    deleteFailure(state) {
      state.isSubmitting = false;
    },
  },

  actions: {
    add({ commit, rootState }, data) {
      const url = `/api/proposals/${rootState.proposal.id}/discounts`;

      commit('submitRequest');

      window.axios.post(url, data)
        .then((response) => {
          commit('submitSuccess', response.data);
          window.flash('Discount added successfully', 'success');
        })
        .catch((error) => {
          console.log(error);
          commit('submitFailure', error.response.data.errors);
          window.flash('There was a problem adding your discount. Please try again.', 'danger');
        });
    },

    delete({ commit, rootState }, id) {
      const url = `/api/proposals/${rootState.proposal.id}/discounts/${id}`;

      commit('deleteRequest');

      window.axios.delete(url)
        .then(() => {
          commit('deleteSuccess', id);
          window.flash('Discount deleted successfully', 'success');
        })
        .catch((error) => {
          console.log(error);
          commit('deleteFailure', error.response.data.errors);
          window.flash('There was a problem deleting your discount. Please try again.', 'danger');
        });
    },
  },

  getters: {
    subtotal: (state) => {
      let sum = 0;

      if (typeof state.records !== 'undefined' && state.records.length > 0) {
        sum = state.records.reduce((subtotal, item) => subtotal + item.amount, 0);
      }

      return Number(sum);
    },
  },
};
