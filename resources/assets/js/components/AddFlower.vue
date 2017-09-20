<template>
  <form
    method="POST"
    action="/api/flowers"
    @submit.prevent="onSubmit"
    @keydown="form.errors.clear($event.target.name)"
  >
    <div class="modal-card">
      <header class="modal-card-head">
        <p class="modal-card-title">Add a Flower</p>
      </header>

      <section class="modal-card-body">
        <b-field
          label="Flower Name"
          :type="form.errors.has('name') ? 'is-danger' : ''"
          :message="form.errors.has('name') ? form.errors.get('name') : ''"
        >
          <b-input
            type="text"
            v-model="form.name"
            placeholder="Rose"
            :disabled="isSubmitting"
            required
          ></b-input>
        </b-field>
      </section>

      <footer class="modal-card-foot">
        <button class="button" type="button" @click="$parent.close()" :disabled="isSubmitting">Close</button>
        <button
          class="button is-primary"
          type="submit"
          v-bind:class="{'is-loading' : isSubmitting}"
          :disabled="isSubmitting || form.errors.any()"
        >Add Flower</button>
      </footer>
    </div>
  </form>
</template>

<script>
import Form from '../helpers/Form';

export default {
  name: 'add-flower',

  data() {
    return {
      isSubmitting: false,
      form: new Form({
        name: '',
      }),
    };
  },

  methods: {
    onSubmit() {
      this.isSubmitting = true;

      this.form.post('/api/flowers')
        .then((data) => {
          window.location.href = `/flowers/${data.id}`;
        })
        .catch(() => {
          this.isSubmitting = false;
          window.flash('There was a problem saving your flower!', 'danger');
        });
    },
  },
};
</script>

<style scoped>
  .modal-card {
    width: auto;
  }
</style>
