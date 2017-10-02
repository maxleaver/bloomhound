<template>
  <div class="box">
    <h4 class="title is-4">Account Preview</h4>
    <h4 class="subtitle is-6">How your account will appear on proposals and invoices</h4>

    <article class="media">
      <figure class="media-left">
        <upload-logo :path="logoPath" :name="name" @uploaded="updateLogo"></upload-logo>
      </figure>

      <div class="media-content">
        <div class="content">
          <strong>{{ name }}</strong><br />
          <span v-if="address" class="address">{{ address }}<br /></span>
          <span v-if="phone">Tel: {{ phone }}<br /></span>
          <span v-if="email"><a v-bind:mailto="email">{{ email }}</a><br /></span>
          <span v-if="website"><a v-bind:href="fullUrl">{{ website }}</a></span>
        </div>
      </div>
    </article>
  </div>
</template>

<script>
import UploadLogo from './UploadLogo.vue';

export default {
  name: 'account-preview',
  components: { UploadLogo },

  props: {
    logo: [String, Boolean],
    name: String,
    address: String,
    website: String,
    email: String,
    phone: String,
  },

  data() {
    return {
      logoPath: '',
    };
  },

  created() {
    this.logoPath = this.logo;
  },

  computed: {
    fullUrl: function () {
      return `http://${this.website}`;
    },
  },

  methods: {
    updateLogo(newPath) {
      this.logoPath = newPath;
    },
  },
};
</script>
