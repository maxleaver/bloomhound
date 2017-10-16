<template>
  <form
    method="POST"
    @submit.prevent="onSubmit"
  >
    <hr>

    <h1 class="title is-size-5">Add Purchase Sources</h1>

    <div v-for="(form, index) in formContainer.forms">
      <add-flower-variety-source
        :form="form"
        :key="index"
        :id="id"
        :index="index"
        :isSubmitting="isSubmitting"
        :onFocus="addRow"
        :showLabels="index === 0"
        :vendors="vendors"
      ></add-flower-variety-source>
    </div>

    <div class="field is-grouped">
      <div class="control">
        <button
          class="button is-primary"
          type="submit"
          v-bind:class="{'is-loading' : isSubmitting}"
          :disabled="isSubmitting"
        >Add Sources</button>
      </div>

      <div class="control">
        <button class="button" @click.prevent="toggleForm">
          Nevermind
        </button>
      </div>
    </div>
  </form>
</template>

<script>
import AddFlowerVarietySource from 'components/Flowers/AddFlowerVarietySource';
import FormContainer from 'helpers/FormContainer';

export default {
  name: 'variety-source-form',
  components: { AddFlowerVarietySource },

  props: {
    id: Number,
    toggleForm: Function,
    vendors: Array,
  },

  data() {
    return {
      formContainer: new FormContainer({
        cost: null,
        stems_per_bunch: null,
        vendor_name: '',
        vendor_id: null,
      }, 3),
      isSubmitting: false,
    };
  },

  methods: {
    addRow(index) {
      this.formContainer.addRow(index);
    },

    onSubmit() {
      if (this.formContainer.isEmpty()) {
        return;
      }

      this.isSubmitting = true;

      this.formContainer.post(`/api/varieties/${this.id}/sources`)
        .then((data) => {
          this.isSubmitting = false;

          // Reset form to the defaults
          this.formContainer.reset();

          window.flash('Purchase sources added!', 'success');

          this.$emit('created', data);
        })
        .catch((error) => {
          this.isSubmitting = false;

          // Only show the flash message if it's not a validation error
          if (typeof error.errors === 'undefined') {
            window.flash('There was a problem saving your purchase sources. Please try again.', 'danger');
          }
        });
    },
  },
};
</script>
