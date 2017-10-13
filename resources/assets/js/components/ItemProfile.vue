<template>
  <div>
    <div v-if="showForm">
      <form
        method="POST"
        @submit.prevent="onSubmit"
      >
        <h1 class="title">Update {{ form.name }}</h1>

        <b-field label="Item Type">
          <b-select
            :disabled="isSubmitting"
            expanded
            placeholder="Select a type"
            required
            v-model="form.arrangeable_type_id"
          >
            <option
              v-for="type in types"
              :value="type.id"
              :key="type.id">
              {{ type.title }}
            </option>
          </b-select>
        </b-field>

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

        <b-field
          label="Description"
          :type="form.errors.has('email') ? 'is-danger' : ''"
          :message="form.errors.has('email') ? form.errors.get('description') : ''"
        >
          <b-input
            type="text"
            v-model="form.description"
            :disabled="isSubmitting"
            name="description"
          ></b-input>
        </b-field>

        <b-field
          label="Inventory"
          :type="form.errors.has('inventory') ? 'is-danger' : ''"
          :message="form.errors.has('inventory') ? form.errors.get('inventory') : ''"
        >
          <b-input
            type="number"
            v-model="form.inventory"
            :disabled="isSubmitting"
            name="inventory"
          ></b-input>
        </b-field>

        <b-field
          label="Cost"
          :type="form.errors.has('cost') ? 'is-danger' : ''"
          :message="form.errors.has('cost') ? form.errors.get('cost') : ''"
        >
          <b-input
            type="number"
            v-model="form.cost"
            :disabled="isSubmitting"
            name="cost"
          ></b-input>
        </b-field>

        <div class="field">
            <b-checkbox
              v-model="form.use_default_markup"
            >Use the default markup for {{ item.type.name }}s</b-checkbox>
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
            >Update Item</button>
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
    </div>

    <div v-else>
      <div>
        <button
          class="button is-link is-pulled-right"
          @click="showForm = !showForm"
        >Edit</button>
        <h1 class="title">{{ form.name }} <b-tag>{{ item.type.name }}</b-tag></h1>
        <h2 class="subtitle">{{ form.description }}</h2>
      </div>

      <div class="section">
        <div class="content">
          <strong>Inventory:</strong> {{ form.inventory }}<br />
          <strong>Cost:</strong> {{ form.cost }}<br />
          <strong>Retail Price:</strong> {{ item.price }}
          <p v-if="item.use_default_markup">
            Uses the default markup for {{ item.type.name }} items
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Form from '../helpers/Form';

export default {
  name: 'item-profile',

  props: {
    item: Object,
    markups: Array,
    types: Array,
  },

  data() {
    return {
      form: new Form({
        arrangeable_type_id: this.item.arrangeable_type_id,
        cost: this.item.cost,
        description: this.item.description,
        inventory: this.item.inventory,
        markup_id: this.item.markup_id,
        markup_value: this.item.markup_value,
        name: this.item.name,
        use_default_markup: this.item.use_default_markup,
      }, false),
      isSubmitting: false,
      markupValueLabel: '',
      showForm: false,
      showMarkupValue: false,
    };
  },

  created() {
    let markup;

    if (!this.item.use_default_markup &&
      Object.prototype.hasOwnProperty.call(this.item, 'markup')) {
      this.markupValueLabel = this.item.markup.field_label;
      this.showMarkupValue = this.item.markup.allow_entry;
    } else {
      // get markup by id
      markup = this.getMarkupById(this.item.markup_id);
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

      this.form.patch(`/api/items/${this.item.id}`)
        .then((data) => {
          // Update the retail price
          this.item.price = data.price;

          window.flash(`${this.form.name} updated successfully!`, 'success');

          this.showForm = false;
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
