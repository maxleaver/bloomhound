<template>
  <div class="section">
    <div class="container">
      <div class="box">
        <h1 class="title is-size-4">Notes</h1>

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

        <add-note @created="add" :url="path"></add-note>
      </div>
    </div>
  </div>
</template>

<script>
import AddNote from 'components/Notes/AddNote';
import Note from 'components/Notes/Note';
import collection from 'mixins/collection';

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
    this.setPath();

    this.fetch(this.path);
  },

  methods: {
    setPath() {
      this.path = `/api${window.location.pathname}/notes`;
    },

    update(index, newText) {
      this.items[index].text = newText;
    },
  },
};
</script>
