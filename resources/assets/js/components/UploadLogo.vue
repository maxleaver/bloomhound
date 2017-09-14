<template>
  <b-field>
    <b-upload
      v-model="form.logo"
      drag-drop
      v-on:input="upload"
    >
      <p class="image is-128x128">
        <img v-if="logo" v-bind:src="logo" v-bind:alt="name">

        <section v-else class="section">
          <div class="content has-text-centered">
            <p>
              <b-icon
                icon="file_upload"
                size="is-large">
              </b-icon>
            </p>
            <p class="is-size-7">Drop your logo here or click to upload</p>
          </div>
        </section>
      </p>
    </b-upload>
  </b-field>
</template>

<script>
import Form from '../helpers/Form';

export default {
  name: 'upload-logo',
  props: {
    logo: String,
    name: String,
  },

  data() {
    return {
      form: new Form({
        logo: [],
      }),
    };
  },

  methods: {
    upload() {
      const formData = new FormData();
      formData.append('logo', this.form.logo[0]);

      window.axios.post('/api/account/logo', formData, {
        headers: {
          'Content-Type': 'multipart/form-data',
        },
      })
        .then((data) => {
          console.log(data);
        })
        .catch((error) => {
          console.log(error);
        });
    },
  },
};
</script>
