<template>
  <div class="modal-card">
    <section class="modal-card-body">
      <h3 class="title">Are you sure?</h3>
      <p>Do you really want to delete {{ name }}? There is no undo!</p>
    </section>

    <footer class="modal-card-foot">
      <button
        class="button is-danger"
        @click="store.dispatch('arrangement/delete')"
      >Yep, I'm sure!</button>

      <button
        class="button"
        type="button"
        @click="store.commit('arrangement/hideDeleteConfirmation')"
      >Nevermind</button>
    </footer>
  </div>
</template>

<script>
export default {
  name: 'delete-arrangement-modal',

  props: {
    store: Object,
  },

  computed: {
    id() {
      return this.store.state.arrangement.deleteRecord.id;
    },

    name() {
      return this.store.state.arrangement.deleteRecord.name;
    },
  },
};
</script>
