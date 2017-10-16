<template>
  <form
    method="POST"
    action="/api/items"
    @submit.prevent="onSubmit"
    @keydown="form.errors.clear($event.target.name)"
  >
    <div class="modal-card">
      <header class="modal-card-head">
        <p class="modal-card-title">Add an Item</p>
      </header>

      <section class="modal-card-body">
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
          label="Item Name"
          :type="form.errors.has('name') ? 'is-danger' : ''"
          :message="form.errors.has('name') ? form.errors.get('name') : ''"
        >
          <b-input
            type="text"
            v-model="form.name"
            placeholder="ex. Glass Vase"
            :disabled="isSubmitting"
            required
          ></b-input>
        </b-field>

        <b-field
          label="Description"
          :type="form.errors.has('description') ? 'is-danger' : ''"
          :message="form.errors.has('description') ? form.errors.get('description') : ''"
        >
          <b-input
            type="text"
            v-model="form.description"
            :disabled="isSubmitting"
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
          ></b-input>
        </b-field>

        <b-field
          label="Cost"
          :type="form.errors.has('cost') ? 'is-danger' : ''"
          :message="form.errors.has('cost') ? form.errors.get('cost') : ''"
        >
          <b-input
            placeholder="9.95"
            step="0.01"
            type="number"
            v-model="form.cost"
            :disabled="isSubmitting"
          ></b-input>
        </b-field>
      </section>

      <footer class="modal-card-foot">
        <button class="button" type="button" @click="$parent.close()" :disabled="isSubmitting">Close</button>
        <button
          class="button is-primary"
          type="submit"
          v-bind:class="{'is-loading' : isSubmitting}"
          :disabled="isSubmitting || form.errors.any()"
        >Add Item</button>
      </footer>
    </div>
  </form>
</template>

<script>
import Form from 'helpers/Form';

export default {
  name: 'add-item',

  props: {
    types: Array,
  },

  data() {
    return {
      isSubmitting: false,
      form: new Form({
        arrangeable_type_id: null,
        cost: null,
        description: null,
        inventory: null,
        name: null,
      }),
    };
  },

  methods: {
    onSubmit() {
      this.isSubmitting = true;

      this.form.post('/api/items')
        .then((data) => {
          window.flash('Item successfully added!', 'success');

          this.$emit('created', data);

          this.$parent.close();
        })
        .catch(() => {
          window.flash('There was a problem saving your item!', 'danger');
        })
        .then(() => {
          this.isSubmitting = false;
        });
    },
  },
};
</script>
