<template>
  <div class="modal-card">
    <section class="modal-card-body">
      <h3 class="title">Are you sure?</h3>
      <p>Do you really want to delete {{ name }}? There is no undo!</p>
    </section>

    <footer class="modal-card-foot">
        <button class="button is-danger" @click="onSubmit">Yep, I'm sure!</button>
        <button class="button" type="button" @click="onCancel">Nevermind</button>
    </footer>
  </div>
</template>

<script>
export default {
  name: 'delete-arrangement-modal',

  props: {
    id: Number,
    name: String,
  },

  methods: {
    onCancel() {
      this.$parent.close();
    },

    onSubmit() {
      window.axios.delete(`/api/arrangements/${this.id}`)
        .then(() => {
          window.flash('Arrangement deleted successfully!', 'success');

          this.$emit('deleted', this.id);

          this.$parent.close();
        });
    },
  },
};
</script>
