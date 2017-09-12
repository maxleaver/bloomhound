<template>
  <form
    method="POST"
    action="/api/vendors"
    @submit.prevent="onSubmit"
  >
    <div class="modal-card">
      <header class="modal-card-head">
        <p class="modal-card-title">Add a Vendor</p>
      </header>

      <section class="modal-card-body">
        <b-field label="Name">
          <b-input
            type="text"
            v-model="name"
            required>
          </b-input>
        </b-field>
      </section>

      <footer class="modal-card-foot">
        <button class="button" type="button" @click="$parent.close()">Close</button>
        <button class="button is-primary">Add Vendor</button>
      </footer>
    </div>
  </form>
</template>

<script>
export default {
  name: 'add-vendor',

  data() {
    return {
      name: '',
    };
  },

  methods: {
    onSubmit() {
      window.axios.post('/api/vendors', {
        name: this.name,
      })
        .catch((error) => {
          window.flash(error.response.data, 'danger');
        })
        .then(({ data }) => {
          this.reset();

          window.flash('Vendor successfully added!', 'success');

          this.$emit('created', data.data);

          this.$parent.close();
        });
    },

    reset() {
      Object.assign(this.$data, this.$options.data());
    },
  },
};
</script>

<style scoped>
  .modal-card {
    width: auto;
  }
</style>