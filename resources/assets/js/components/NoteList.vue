<template>
  <div>
    <article class="message is-warning" v-for="item in items">
      <div class="message-body">
        <div class="content">
          <p>
            <strong>{{ item.user.name }}</strong> posted on {{ item.created_at }}
          </p>
        </div>
        <div>
          {{ item.text }}
        </div>
      </div>
    </article>

    <div class="box">
      <add-note @created="add" :url="path"></add-note>
    </div>
  </div>
</template>

<script>
import AddNote from './AddNote.vue';
import collection from '../mixins/collection';

export default {
  name: 'note-list',
  components: { AddNote },
  mixins: [collection],

  data() {
    return {
      path: '',
    };
  },

  created() {
    this.fetch();
  },

  methods: {
    fetch() {
      this.setPath();

      window.axios.get(this.path)
        .then(this.refresh);
    },

    setPath() {
      this.path = `/api${window.location.pathname}/notes`;
    },
  },
};
</script>
