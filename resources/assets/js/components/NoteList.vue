<template>
  <div>
    <note
      v-for="(item, index) in items"
      :key="item.id"
      :id="item.id"
      :index="index"
      :submitted_by="item.user.name"
      :created_at="item.created_at"
      :text="item.text"
      @deleted="remove"
      @updated="update"
    ></note>

    <div class="box">
      <add-note @created="add" :url="path"></add-note>
    </div>
  </div>
</template>

<script>
import AddNote from './AddNote.vue';
import Note from './Note.vue';
import collection from '../mixins/collection';

export default {
  name: 'note-list',
  components: { AddNote, Note },
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

    update(index, newText) {
      this.items[index].text = newText;
    },
  },
};
</script>
