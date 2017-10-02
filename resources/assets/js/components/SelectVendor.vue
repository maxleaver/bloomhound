<template>
  <form
    method="POST"
    @submit.prevent="onSubmit"
    @keydown="form.errors.clear($event.target.name)"
  >
    <div class="modal-card">
      <header class="modal-card-head">
        <p class="modal-card-title">Add a Vendor</p>
      </header>

      <section class="modal-card-body">
        <b-field
          label="Select or add a vendor"
          :type="form.errors.has('vendor_id') || form.errors.has('vendor_name') ? 'is-danger' : ''"
        >
          <b-autocomplete
            field="name"
            name="vendor"
            v-model="form.vendor_name"
            :data="filteredDataObj"
            :disabled="isSubmitting"
            :keep-first="keepFirst"
            @select="onSelect"
          ></b-autocomplete>
        </b-field>
      </section>

      <footer class="modal-card-foot">
        <button class="button" type="button" @click="$parent.close()" :disabled="isSubmitting">Close</button>
        <button
          class="button is-primary"
          type="submit"
          v-bind:class="{'is-loading' : isSubmitting}"
          :disabled="isSubmitting || form.errors.any()"
        >Add Vendor</button>
      </footer>
    </div>
  </form>
</template>

<script>
import Form from '../helpers/Form';

export default {
  name: 'select-vendor',

  props: {
    eventId: Number,
    vendors: Array,
  },

  data() {
    return {
      isSubmitting: false,
      form: new Form({
        vendor_id: null,
        vendor_name: '',
      }),
      keepFirst: true,
    };
  },

  computed: {
    filteredDataObj() {
      return this.vendors.filter(option =>
        option.name
          .toString()
          .toLowerCase()
          .indexOf(this.form.vendor_name.toLowerCase()) >= 0);
    },
  },

  methods: {
    onSelect(option) {
      // clear any validation errors
      this.form.errors.clear('vendor_id');
      this.form.errors.clear('vendor_name');

      if (option && Object.prototype.hasOwnProperty.call(option, 'id')) {
        this.form.vendor_id = option.id;
        return;
      }

      this.form.vendor_id = null;
    },

    onSubmit() {
      this.isSubmitting = true;

      this.form.post(`/api/events/${this.eventId}/vendors`)
        .then((data) => {
          this.isSubmitting = false;

          window.flash('Vendor successfully added!', 'success');

          this.$emit('created', data);

          this.$parent.close();
        })
        .catch(() => {
          this.isSubmitting = false;

          console.log('ERROR!');

          window.flash('There was a problem saving your vendor!', 'danger');
        });
    },
  },
};
</script>
