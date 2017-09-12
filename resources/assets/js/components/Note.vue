<template>
  <article class="message is-warning">
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
          <a v-on:click.prevent="onDelete" class="level-item has-text-info is-size-7">
            <span>Delete</span>
          </a>
        </div>
      </nav>

      <div>
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
    return {};
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
  },
};
</script>
