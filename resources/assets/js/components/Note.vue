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
        <b-field>
          <b-input
            type="textarea"
            v-model="updateText"
            :disabled="isSubmitting"
          >{{ updateText }}</b-input>
        </b-field>

        <button
          v-on:click="onEditSubmit"
          :disabled="isSubmitting"
          class="button is-primary"
        >Save</button>
      </div>

      <div v-else>
        {{ text }}
      </div>
    </div>
  </article>
</template>

<script>
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
      updateText: '',
    };
  },

  created() {
    this.updateText = this.text;
  },

  methods: {
    onDelete() {
      window.axios.delete(`/api/notes/${this.id}`)
        .catch((error) => {
          window.flash(error.response.data, 'danger');
        })
        .then(() => {
          window.flash('Note deleted!', 'success');

          this.$emit('deleted', this.index);
        });
    },

    toggleEdit() {
      this.isEditing = !this.isEditing;
    },

    onEditSubmit() {
      this.isSubmitting = true;

      window.axios.put(`/api/notes/${this.id}`, { text: this.updateText })
        .catch((error) => {
          window.flash(error.response.data, 'danger');

          this.isSubmitting = false;
        })
        .then(() => {
          this.isEditing = false;
          this.isSubmitting = false;

          this.$emit('updated', this.index, this.updateText);
        });
    },
  },
};
</script>
