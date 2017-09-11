<template>
  <form
    method="POST"
    action="/api/customers"
    @submit.prevent="onSubmit"
  >
    <div class="modal-card">
      <header class="modal-card-head">
        <p class="modal-card-title">Add a Contact</p>
      </header>

      <section class="modal-card-body">
        <b-field grouped>
          <b-field label="First Name">
            <b-input
              type="text"
              v-model="first_name"
              required>
            </b-input>
          </b-field>

          <b-field label="Last Name">
            <b-input
              type="text"
              v-model="last_name"
              required>
            </b-input>
          </b-field>
        </b-field>

        <b-field label="Email">
          <b-input
            type="email"
            v-model="email">
          </b-input>
        </b-field>

        <b-field label="Phone">
          <b-input
            type="phone"
            v-model="phone">
          </b-input>
        </b-field>

        <b-field label="Relationship">
          <b-input
            type="text"
            v-model="relationship"
            placeholder="Mother of the bride">
          </b-input>
        </b-field>

        <b-field label="Address">
          <b-input
            type="text"
            v-model="address">
          </b-input>
        </b-field>
      </section>

      <footer class="modal-card-foot">
        <button class="button" type="button" @click="$parent.close()">Close</button>
        <button class="button is-primary">Add Contact</button>
      </footer>
    </div>
  </form>
</template>

<script>
export default {
  name: 'add-customer',
  props: {
    customer_id: Number,
  },

  data() {
    return {
      first_name: '',
      last_name: '',
      email: '',
      phone: '',
      relationship: '',
      address: '',
    };
  },

  methods: {
    onSubmit() {
      window.axios.post('/api/contacts', {
        customer_id: this.customer_id,
        first_name: this.first_name,
        last_name: this.last_name,
        email: this.email,
        phone: this.phone,
        relationship: this.relationship,
        address: this.address,
      })
        .catch((error) => {
          window.flash(error.response.data, 'danger');
        })
        .then(({ data }) => {
          this.reset();

          window.flash('Contact successfully added!', 'success');

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