<template>
  <div>
    <form
      method="POST"
      v-if="showForm"
      @submit.prevent="onSubmit"
    >
      <b-field
        label="Name"
        :type="form.errors.has('name') ? 'is-danger' : ''"
        :message="form.errors.has('name') ? form.errors.get('name') : ''"
      >
        <b-input
          type="text"
          v-model="form.name"
          :disabled="isSubmitting"
          name="name"
          required
        ></b-input>
      </b-field>

      <div class="field">
          <b-checkbox
            v-model="form.use_default_markup"
          >Use the default markup for flower varieties</b-checkbox>
      </div>

      <div v-if="!form.use_default_markup" class="field">
        <b-field grouped>
          <b-field label="Markup Type">
            <b-select
              placeholder="Select a markup"
              :loading="isSubmitting"
              :value="form.markup_id"
              @input="onMarkupChange"
            >
              <option
                v-for="markup in markups"
                :value="markup.id"
                :key="markup.id">
                {{ markup.title }}
              </option>
            </b-select>
          </b-field>

          <b-field
            v-if="showMarkupValue"
            :label="markupValueLabel"
            :message="form.errors.has('markup_value') ? form.errors.get('markup_value') : ''"
            :type="form.errors.has('markup_value') ? 'is-danger' : ''"
          >
            <b-input
              type="number"
              v-model="form.markup_value"
              :disabled="isSubmitting"
            ></b-input>
          </b-field>
        </b-field>
      </div>

      <div class="field is-grouped">
        <div class="control">
          <button
            class="button is-primary"
            type="submit"
            v-bind:class="{'is-loading' : isSubmitting}"
            :disabled="isSubmitting"
          >Update Flower Variety</button>
        </div>

        <div class="control">
          <button
            class="button"
            @click.prevent="resetToDefault"
            :disabled="isSubmitting"
          >Nevermind</button>
        </div>
      </div>
    </form>

    <div v-else>
      <button
        class="button is-link is-pulled-right"
        @click="showForm = !showForm"
      >Edit</button>

      <div v-if="form.use_default_markup">
        <strong>{{ form.name }} {{ variety.flower.name }}</strong> uses the <strong>default markup</strong> to generate it's retail price.
      </div>

      <div v-else>
        <strong>{{ form.name }} {{ variety.flower.name }}</strong> uses <strong>{{ getMarkupById(form.markup_id).title }}</strong> to generate it's retail price.
      </div>
    </div>
  </div>
</template>

<script>
import Form from 'helpers/Form';

export default {
  name: 'update-flower-variety',
  props: {
    markups: Array,
    variety: Object,
  },

  data() {
    return {
      form: new Form({
        markup_id: this.variety.markup_id,
        markup_value: this.variety.markup_value,
        name: this.variety.name,
        use_default_markup: this.variety.use_default_markup,
      }, false),
      isSubmitting: false,
      markupValueLabel: '',
      showForm: false,
      showMarkupValue: false,
    };
  },

  created() {
    let markup;

    if (!this.variety.use_default_markup &&
      Object.prototype.hasOwnProperty.call(this.variety, 'markup')) {
      this.markupValueLabel = this.variety.markup.field_label;
      this.showMarkupValue = this.variety.markup.allow_entry;
    } else {
      // get markup by id
      markup = this.getMarkupById(this.variety.markup_id);
      this.markupValueLabel = markup.field_label;
      this.showMarkupValue = markup.allow_entry;
    }
  },

  methods: {
    getMarkupById(id) {
      return this.markups.find(markup => markup.id === id);
    },

    onMarkupChange(option) {
      const record = this.getMarkupById(option);

      this.form.markup_id = option;

      if (record.allow_entry) {
        this.showMarkupValue = true;
        this.markupValueLabel = record.field_label;
      } else {
        this.showMarkupValue = false;
        this.markupValueLabel = null;
        this.form.markup_value = null;
      }
    },

    onSubmit() {
      this.isSubmitting = true;

      this.form.patch(`/api/varieties/${this.variety.id}`)
        .then((data) => {
          window.flash(`${this.form.name} updated successfully!`, 'success');

          this.showForm = false;

          this.$emit('updated', data);
        })
        .catch(() => {
          window.flash(`There was a problem updating ${this.form.name}!`, 'danger');
        })
        .then(() => {
          this.isSubmitting = false;
        });
    },

    resetToDefault() {
      this.form.reset();
      this.showForm = false;
    },
  },
};
</script>
