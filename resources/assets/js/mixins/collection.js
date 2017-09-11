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
      this.items = data.data;

      window.scrollTo(0, 0);
    },

    add(item) {
      this.items.unshift(item);

      this.$emit('added');
    },

    remove(index) {
      this.items.splice(index, 1);

      this.$emit('removed');
    },
  },
};
