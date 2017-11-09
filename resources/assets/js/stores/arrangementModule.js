import Errors from 'helpers/Errors';
import Form from 'helpers/Form';
import FormContainer from 'helpers/FormContainer';

export default {
  namespaced: true,

  state: {
    arrangeables: [],
    deleteRecord: {
      id: '',
      name: '',
    },
    discountForm: new Form({
      name: '',
      amount: 0,
      type: 'fixed',
    }),
    errors: new Errors(),
    ingredientFormContainer: new FormContainer({
      id: null,
      type: '',
      quantity: null,
    }, 3),
    isAddingIngredient: false,
    isDeleteConfirmationVisible: false,
    isLoading: false,
    isSubmitting: false,
    isSubmittingDiscount: false,
    records: [],
    showForm: false,
    showIngredientForm: false,
  },

  mutations: {
    set(state, records) {
      state.records = records;
    },

    showDeleteConfirmation(state, arrangement) {
      state.deleteRecord.id = arrangement.id;
      state.deleteRecord.name = arrangement.name;
      state.isDeleteConfirmationVisible = true;
    },

    hideDeleteConfirmation(state) {
      state.deleteRecord.id = '';
      state.deleteRecord.name = '';
      state.isDeleteConfirmationVisible = false;
    },

    toggleForm(state) {
      state.showForm = !state.showForm;
      state.errors.clear();
    },

    toggleIngredientForm(state) {
      state.showIngredientForm = !state.showIngredientForm;
    },

    fetchArrangeablesRequest(state) {
      state.isLoading = true;
    },

    fetchArrangeablesSuccess(state, arrangeables) {
      state.isLoading = false;
      state.arrangeables = arrangeables;
    },

    submitRequest(state) {
      state.isSubmitting = true;
    },

    submitSuccess(state, record) {
      window.flash(`${record.name} arrangement added successfully!`, 'success');

      state.records.unshift(record);
      state.errors.clear();

      state.isSubmitting = false;
      state.showForm = false;
    },

    submitFailure(state, errors) {
      window.flash('There was a problem saving your arrangement!', 'danger');

      state.isSubmitting = false;
      state.errors.record(errors);
    },

    deleteSuccess(state) {
      const index = state.records.findIndex(i => i.id === state.deleteRecord.id);
      state.records.splice(index, 1);

      window.flash('Arrangement deleted successfully!', 'success');

      state.isDeleteConfirmationVisible = false;
    },

    updateSuccess(state, data) {
      const record = state.records.find(item => item.id === data.id);
      record.name = data.name;
      record.description = data.description;
      record.quantity = data.quantity;
      record.price = data.price;
      record.total_price = data.total_price;

      window.flash(`${data.name} was updated successfully!`, 'success');

      state.isSubmitting = false;
    },

    updateFailure(state, errors) {
      window.flash('There was a problem updating your arrangement. Please try again.', 'danger');

      state.isSubmitting = false;
      state.errors.record(errors);
    },

    addIngredientRow(state, index) {
      state.ingredientFormContainer.addRow(index);
    },

    addIngredientRequest(state) {
      state.isAddingIngredient = true;
    },

    addIngredientSuccess(state, data) {
      const arrangement = state.records.find(item => item.id === data.id);
      arrangement.ingredients = data.ingredients;
      arrangement.cost = data.cost;
      arrangement.price = data.price;
      arrangement.total_price = data.total_price;

      state.isAddingIngredient = false;

      window.flash('Ingredients added successfully', 'success');
    },

    addIngredientFailure(state, error) {
      state.isAddingIngredient = false;

      window.flash('There was a problem adding your ingredients. Please try again.', 'danger');

      state.ingredientFormContainer.onFail(error);
    },

    deleteIngredientSuccess(state, data) {
      const arrangement = state.records.find(item => item.id === data.id);
      arrangement.ingredients = data.ingredients;
      arrangement.cost = data.cost;
      arrangement.price = data.price;
      arrangement.total_price = data.total_price;

      window.flash('Ingredient deleted', 'success');
    },

    deleteIngredientFailure(state, error) {
      window.flash('There was a problem deleting that ingredient. Please try again.', 'danger');
      console.log(error);
    },

    updateIngredientSuccess(state, data) {
      const arrangement = state.records.find(item => item.id === data.id);
      arrangement.ingredients = data.ingredients;
      arrangement.cost = data.cost;
      arrangement.price = data.price;
      arrangement.total_price = data.total_price;
    },

    updateIngredientFailure(state, error) {
      window.flash('There was a problem updating your ingredient quantity. Please try again.', 'danger');
      console.log(error);
    },

    addDiscountRequest(state) {
      state.isSubmittingDiscount = true;
    },

    addDiscountSuccess(state, data) {
      const arrangement = state.records.find(item => item.id === data.arrangement_id);

      arrangement.total_price = data.discount.discountable.total_price;

      if (Object.prototype.hasOwnProperty.call(arrangement, 'discounts')) {
        arrangement.discounts.unshift(data.discount);
      } else {
        arrangement.discounts = [data.discount];
      }

      state.isSubmittingDiscount = false;

      state.discountForm.reset();

      window.flash('Discount added successfully', 'success');
    },

    addDiscountFailure(state, error) {
      state.isSubmittingDiscount = false;

      window.flash('There was a problem adding your discount. Please try again.', 'danger');

      state.discountForm.errors.record(error);
    },

    deleteDiscountRequest(state) {
      state.isSubmittingDiscount = true;
    },

    deleteDiscountSuccess(state, data) {
      const arrangement = state.records.find(item => item.id === data.id);

      arrangement.total_price = data.total_price;
      arrangement.discounts = data.discounts;

      state.isSubmittingDiscount = false;

      window.flash('Discount deleted successfully', 'success');
    },

    deleteDiscountFailure(state) {
      state.isSubmittingDiscount = false;
      window.flash('There was a problem deleting your discount. Please try again.', 'danger');
    },
  },

  actions: {
    fetchArrangeables({ commit }) {
      commit('fetchArrangeablesRequest');

      window.axios.get('/api/arrangeables')
        .then((data) => {
          commit('fetchArrangeablesSuccess', data.data);
        });
    },

    delete({ commit, state }) {
      window.axios.delete(`/api/arrangements/${state.deleteRecord.id}`)
        .then(() => {
          commit('deleteSuccess');
        });
    },

    submit({ commit, rootState }, data) {
      commit('submitRequest');

      window.axios.post(`/api/proposals/${rootState.proposal.id}/arrangements`, data)
        .then((response) => {
          commit('submitSuccess', response.data);
        })
        .catch((error) => {
          console.log(error);
          commit('submitFailure', error.response.data.errors);
        });
    },

    update({ commit }, data) {
      commit('submitRequest');

      window.axios.patch(`/api/arrangements/${data.id}`, data.data)
        .then((response) => {
          commit('updateSuccess', response.data);
        })
        .catch((error) => {
          console.log(error);
          commit('updateFailure', error.response.data.errors);
        });
    },

    addIngredient({ commit }, data) {
      const url = `/api/arrangements/${data.arrangement_id}/ingredients`;

      commit('addIngredientRequest');

      window.axios.post(url, data.data)
        .then((response) => {
          commit('addIngredientSuccess', response.data);
        })
        .catch((error) => {
          console.log(error);
          commit('addIngredientFailure', error.response.data.errors);
        });
    },

    deleteIngredient({ commit }, data) {
      const url = `/api/arrangements/${data.arrangement_id}/ingredients/${data.ingredient_id}`;

      window.axios.delete(url)
        .then((response) => {
          commit('deleteIngredientSuccess', response.data);
        })
        .catch((error) => {
          console.log(error);
          commit('deleteIngredientFailure', error.response.data.errors);
        });
    },

    updateIngredient({ commit }, data) {
      const url = `/api/arrangements/${data.arrangement_id}/ingredients/${data.ingredient_id}`;

      window.axios.patch(url, { quantity: data.quantity })
        .then((response) => {
          commit('updateIngredientSuccess', response.data);
        })
        .catch((error) => {
          console.log(error);
          commit('updateIngredientFailure', error.response.data.errors);
        });
    },

    addDiscount({ commit }, data) {
      const url = `/api/arrangements/${data.id}/discounts`;

      commit('addDiscountRequest');

      window.axios.post(url, data.discount)
        .then((response) => {
          commit('addDiscountSuccess', {
            arrangement_id: data.id,
            discount: response.data,
          });
        })
        .catch((error) => {
          console.log(error);
          commit('addDiscountFailure', error.response.data.errors);
        });
    },

    deleteDiscount({ commit }, data) {
      const url = `/api/arrangements/${data.arrangement_id}/discounts/${data.discount_id}`;

      commit('deleteDiscountRequest');

      window.axios.delete(url)
        .then((response) => {
          commit('deleteDiscountSuccess', response.data);
        })
        .catch((error) => {
          console.log(error);
          commit('deleteDiscountFailure', error.response.data.errors);
        });
    },
  },

  getters: {
    subtotal: (state) => {
      let sum = 0;

      if (typeof state.records !== 'undefined' && state.records.length > 0) {
        sum = state.records.reduce((subtotal, item) => subtotal + item.total_price, 0);
      }

      return Number(sum);
    },
  },
};
