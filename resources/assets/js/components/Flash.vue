<template>
  <div></div>
</template>

<script>
export default {
  name: 'flash',

  data() {
    return {
      body: this.message,
      level: 'success',
    };
  },

  created() {
    window.events.$on('flash', data => this.flash(data));
  },

  methods: {
    flash(data) {
      this.$toast.open({
        duration: 5000,
        message: data.message,
        position: 'is-bottom',
        type: `is-${data.level}`,
      });
    },
  },
};
</script>
