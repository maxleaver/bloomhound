<template>
  <form
    method="PATCH"
    action="/api/arrangeables/settings"
    @submit.prevent="onSubmit"
  >
    <h1 class="title">Default Markups</h1>

    <arrangeable-type-setting
      v-for="(setting, index) in settings"
      :currentMarkup="setting.markup"
      :form="formContainer.forms[index]"
      :id="setting.type.id"
      :isSubmitting="isSubmitting"
      :key="setting.type.id"
      :markups="markups"
      :markupValue="setting.markup_value"
      :title="setting.type.title"
    ></arrangeable-type-setting>

    <div class="content">
      <strong>NOTE:</strong> Changes to these settings <strong>will not effect</strong> flower varieties or items where you have applied a custom markup.
    </div>

    <button
      type="submit"
      class="button is-primary"
      v-bind:class="{'is-loading' : isSubmitting}"
      :disabled="isSubmitting"
    >Save Default Markups</button>
  </form>
</template>

<script>
import ArrangeableTypeSetting from 'components/Account/ArrangeableTypeSetting';
import FormContainer from 'helpers/FormContainer';

export default {
  name: 'account-markups',
  components: { ArrangeableTypeSetting },

  props: {
    markups: Array,
    settings: Array,
  },

  data() {
    return {
      formContainer: new FormContainer({
        arrangeable_type_id: 0,
        markup_id: 0,
        markup_value: null,
      }, this.settings.length, false),
      isSubmitting: false,
    };
  },

  methods: {
    onSubmit() {
      this.isSubmitting = true;

      this.formContainer.patch('/api/arrangeables/settings')
        .then(() => {
          window.flash('Your markup settings were updated successfully!', 'success');
        })
        .catch((error) => {
          // Only show the flash message if it's not a validation error
          if (typeof error.errors === 'undefined') {
            window.flash('There was a problem updating your markup settings!', 'danger');
          }
        })
        .then(() => {
          this.isSubmitting = false;
        });
    },
  },
};
</script>
