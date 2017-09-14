<template>
  <article
    class="message"
    v-bind:class="{ 'is-warning' : !isEditing, 'is-primary' : isEditing }"
  >
    <div class="message-body">
      <nav class="level">
        <div class="level-left">
          <div class="level-item">
            <p class="subtitle is-6">
              <strong>{{ submitted_by }}</strong> posted on {{ created_at }}
            </p>
          </div>
        </div>

        <div class="level-right note-controls">
          <a v-on:click.prevent="toggleEdit" class="level-item has-text-info is-size-7">
            <span>Edit</span>
          </a>

          <a v-on:click.prevent="onDelete" class="level-item has-text-info is-size-7">
            <span>Delete</span>
          </a>
        </div>
      </nav>

      <div v-if="isEditing">
        <form
          method="POST"
          @submit.prevent="onSubmit"
          @keydown="form.errors.clear($event.target.name)"
        >
          <b-field>
            <b-input
              type="textarea"
              v-model="form.text"
              :disabled="isSubmitting"
            >{{ form.text }}</b-input>
          </b-field>

          <button
            v-on:click="onEditSubmit"
            :disabled="isSubmitting"
            class="button is-primary"
          >Save</button>
        </form>
      </div>

      <div v-else>
        {{ text }}
      </div>
    </div>
  </article>
</template>

<script>
import Form from '../helpers/Form';

export default {
  name: 'note',
  props: {
    id: Number,
    index: Number,
    submitted_by: String,
    created_at: String,
    text: String,
  },

  data() {
    return {
      isEditing: false,
      isSubmitting: false,
      form: new Form({
        text: '',
      }, false),
    };
  },

  created() {
    this.form.text = this.text;
  },

  methods: {
    onDelete() {
      this.form.delete(`/api/notes/${this.id}`)
        .then(() => {
          window.flash('Note deleted!', 'success');

          this.$emit('deleted', this.index);
        })
        .catch(() => {
          window.flash('There was a problem deleting your note!', 'danger');
        });
    },

    toggleEdit() {
      this.isEditing = !this.isEditing;
    },

    onEditSubmit() {
      this.isSubmitting = true;

      this.form.patch(`/api/notes/${this.id}`)
        .then(() => {
          this.isEditing = false;
          this.isSubmitting = false;

          this.$emit('updated', this.index, this.form.text);
        })
        .catch(() => {
          this.isSubmitting = false;

          window.flash('There was a problem saving your note!', 'danger');
        });
    },
  },
};
</script>
