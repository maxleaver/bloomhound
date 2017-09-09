export default {
  data() {
    return {
      dataSet: false,
      items: [],
    };
  },

  created() {
    this.fetch();
  },

  methods: {
    // fetch(page) {
    fetch() {
      window.axios.get('api/customers')
      // window.axios.get(this.url(page))
        .then(this.refresh);
    },

    // url(value) {
    //   let page = value;

    //   if (!page) {
    //     const query = window.location.search.match(/page=(\d+)/);
    //     page = query ? query[1] : 1;
    //   }

    //   return `${this.rootUrl}?page=${page}`;
    // },

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
