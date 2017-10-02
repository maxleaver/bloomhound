export default {
  data() {
    return {
      dataSet: false,
      items: [],
    };
  },

  methods: {
    refresh({ data }) {
      this.dataSet = data;
      this.items = data;
    },

    add(item) {
      this.items.unshift(item);

      this.$emit('added');
    },

    remove(index) {
      this.items.splice(index, 1);

      this.$emit('removed');
    },

    removeById(id) {
      const index = this.items.findIndex(i => i.id === id);
      this.remove(index);
    },
  },
};
