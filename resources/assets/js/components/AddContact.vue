<template>
  <form
    method="POST"
    action="/api/contacts"
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
              :disabled="isSubmitting"
              required
            >
            </b-input>
          </b-field>

          <b-field label="Last Name">
            <b-input
              type="text"
              v-model="last_name"
              :disabled="isSubmitting"
              required
            >
            </b-input>
          </b-field>
        </b-field>

        <b-field label="Email">
          <b-input
            type="email"
            v-model="email"
            :disabled="isSubmitting"
          >
          </b-input>
        </b-field>

        <b-field label="Phone">
          <b-input
            type="phone"
            v-model="phone"
            :disabled="isSubmitting"
          >
          </b-input>
        </b-field>

        <b-field label="Relationship">
          <b-input
            type="text"
            v-model="relationship"
            :disabled="isSubmitting"
            placeholder="Mother of the bride"
          >
          </b-input>
        </b-field>

        <b-field label="Address">
          <b-input
            type="text"
            v-model="address"
            :disabled="isSubmitting"
          >
          </b-input>
        </b-field>
      </section>

      <footer class="modal-card-foot">
        <button class="button" type="button" @click="$parent.close()" :disabled="isSubmitting">Close</button>
        <button class="button is-primary" v-bind:class="{'is-loading' : isSubmitting}">Add Contact</button>
      </footer>
    </div>
  </form>
</template>

<script>
export default {
  name: 'add-contact',
  props: {
    customer_id: Number,
  },

  data() {
    return {
      isSubmitting: false,
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
      this.isSubmitting = true;

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