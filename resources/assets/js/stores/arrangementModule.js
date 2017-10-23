import Errors from 'helpers/Errors';
import FormContainer from 'helpers/FormContainer';

export default {
  namespaced: true,
  state: {
    arrangeables: [],
    deleteRecord: {
      id: '',
      name: '',
    },
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
    records: [],
    showForm: false,
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

    updateRequest(state) {
      state.isSubmitting = true;
    },

    updateSuccess(state, data) {
      const record = state.records.find(item => item.id === data.id);
      record.name = data.name;
      record.description = data.description;
      record.quantity = data.quantity;

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
      const arrangement = state.records.find(item => item.id === data.arrangement_id);

      data.ingredients.forEach((ingredient) => {
        arrangement.cost += ingredient.cost;
        arrangement.default_price += ingredient.price;

        arrangement.ingredients.unshift(ingredient);
      });

      state.isAddingIngredient = false;

      window.flash('Ingredients added successfully', 'success');
    },

    addIngredientFailure(state, error) {
      state.isAddingIngredient = false;

      window.flash('There was a problem adding your ingredients. Please try again.', 'danger');

      state.ingredientFormContainer.onFail(error);
    },

    deleteIngredientSuccess(state, data) {
      const arrangement = state.records.find(item => item.id === data.arrangement_id);

      arrangement.ingredients.forEach((ingredient, index) => {
        if (ingredient.id === data.ingredient_id) {
          arrangement.cost -= ingredient.cost;
          arrangement.default_price -= ingredient.price;

          arrangement.ingredients.splice(index, 1);
        }
      });

      window.flash('Ingredient deleted', 'success');
    },

    deleteIngredientFailure(state, error) {
      window.flash('There was a problem deleting that ingredient. Please try again.', 'danger');
      console.log(error);
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

      window.axios.post(`/api/events/${rootState.event.id}/arrangements`, data)
        .then((response) => {
          commit('submitSuccess', response.data);
        })
        .catch((error) => {
          commit('submitFailure', error.response.data.errors);
        });
    },

    update({ commit }, data) {
      commit('updateRequest');

      window.axios.patch(`/api/arrangements/${data.id}`, data.data)
        .then((response) => {
          commit('updateSuccess', response.data);
        })
        .catch((error) => {
          commit('updateFailure', error.response.data.errors);
        });
    },

    addIngredient({ commit }, data) {
      const url = `/api/arrangements/${data.arrangement_id}/ingredients`;

      commit('addIngredientRequest');

      window.axios.post(url, data.data)
        .then((response) => {
          commit('addIngredientSuccess', {
            arrangement_id: data.arrangement_id,
            ingredients: response.data,
          });
        })
        .catch((error) => {
          commit('addIngredientFailure', error.response.data.errors);
        });
    },

    deleteIngredient({ commit }, data) {
      const url = `/api/arrangements/${data.arrangement_id}/ingredients/${data.ingredient_id}`;

      window.axios.delete(url)
        .then(() => {
          commit('deleteIngredientSuccess', {
            arrangement_id: data.arrangement_id,
            ingredient_id: data.ingredient_id,
          });
        })
        .catch((error) => {
          commit('deleteIngredientFailure', error.response.data.errors);
        });
    },
  },
  getters: {
    subtotal: (state) => {
      let sum = 0;

      if (typeof state.records !== 'undefined' && state.records.length > 0) {
        sum = state.records.reduce((accumulator, item) => {
          const price = item.default_price * item.quantity;
          return accumulator + price;
        }, 0);
      }

      return Number(sum);
    },
  },
};
