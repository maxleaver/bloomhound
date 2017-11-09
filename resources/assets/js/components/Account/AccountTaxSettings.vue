<template>
  <form
    method="PATCH"
    action="/api/account/settings"
    @submit.prevent="onSubmit"
    @keydown="form.errors.clear($event.target.name)"
  >
    <h1 class="title">Tax</h1>

    <div class="field">
      <b-switch
        v-model="form.use_tax"
        :disabled="isSubmitting"
      >{{ switchText }}</b-switch>
    </div>

    <b-field
      label="Tax Percent"
      v-if="form.use_tax"
      :type="form.errors.has('tax_amount') ? 'is-danger' : ''"
      :message="form.errors.has('tax_amount') ? form.errors.get('tax_amount') : ''"
    >
      <b-input
        type="number"
        step="any"
        v-model="form.tax_amount"
        :disabled="isSubmitting"
      ></b-input>
    </b-field>

    <button
      type="submit"
      class="button is-primary"
      v-bind:class="{'is-loading' : isSubmitting}"
      :disabled="isSubmitting"
    >Save Tax Settings</button>
  </form>
</template>

<script>
import Form from 'helpers/Form';

export default {
  name: 'account-tax-settings',

  props: {
    settings: Object,
  },

  data() {
    return {
      form: new Form({
        use_tax: !!this.settings.use_tax,
        tax_amount: this.settings.tax_amount,
      }, false),
      isSubmitting: false,
    };
  },

  computed: {
    switchText() {
      return this.form.use_tax ? 'Apply tax to proposals' : 'Don\'t apply tax to proposals';
    },
  },

  methods: {
    onSubmit() {
      this.isSubmitting = true;

      this.form.patch('/api/account/settings')
        .then(() => {
          window.flash('Your tax settings were updated successfully!', 'success');
        })
        .catch(() => {
          window.flash('There was a problem updating your tax settings!', 'danger');
        })
        .then(() => {
          this.isSubmitting = false;
        });
    },
  },
};
</script>
