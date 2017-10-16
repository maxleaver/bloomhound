export default {
  data() {
    return {
      dataSet: false,
      isLoading: true,
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

      this.$emit('added', item);
    },

    fetch(url) {
      window.axios.get(url)
        .then((data) => {
          this.isLoading = false;
          this.refresh(data);
        });
    },

    findById(id) {
      return this.items.find(item => item.id === id);
    },

    remove(index) {
      this.items.splice(index, 1);

      this.$emit('removed', index);
    },

    removeById(id) {
      const index = this.items.findIndex(i => i.id === id);
      this.remove(index);
    },
  },
};
