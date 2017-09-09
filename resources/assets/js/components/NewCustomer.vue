<template>
  <form
    method="POST"
    action="/api/customers"
    @submit.prevent="onSubmit"
  >
    <div class="modal-card">
      <header class="modal-card-head">
        <p class="modal-card-title">Add a Customer</p>
      </header>

      <section class="modal-card-body">
        <b-field label="Customer Name">
          <b-input
            type="text"
            v-model="name"
            placeholder="John and Jane Doe"
            required>
          </b-input>
        </b-field>
      </section>

      <footer class="modal-card-foot">
        <button class="button" type="button" @click="$parent.close()">Close</button>
        <button class="button is-primary">Add Customer</button>
      </footer>
    </div>
  </form>
</template>

<script>
export default {
  name: 'new-customer',

  data() {
    return {
      name: '',
    };
  },

  methods: {
    onSubmit() {
      window.axios.post('/api/customers', { name: this.name })
        .catch((error) => {
          window.flash(error.response.data, 'danger');
        })
        .then(({ data }) => {
          this.name = '';

          window.flash('Customer successfully added!', 'success');

          this.$emit('created', data.data);

          this.$parent.close();
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