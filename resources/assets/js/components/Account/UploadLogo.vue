<template>
  <b-field>
    <b-upload
      v-model="form.logo"
      drag-drop
      v-on:input="upload"
      type="is-black"
    >
      <div v-if="path" class="account_logo image is-128x128">
        <img v-bind:src="path" v-bind:alt="name">
        <span class="logo-change-prompt">
        <b-icon
          icon="camera_alt"
          size="is-medium">
        </b-icon>
        Change Logo
        </span>
      </div>

      <section v-else class="is-128x128">
        <div class="content has-text-centered is-clipped is-vcentered">
          <p class="is-size-7 is-marginless">Drop your logo here</p>
          <p class="is-marginless">
            <b-icon
              icon="file_upload"
              size="is-large">
            </b-icon>
          </p>
          <p class="is-size-7 is-marginless">or click to upload</p>
        </div>
      </section>
    </b-upload>
  </b-field>
</template>

<script>
import Form from 'helpers/Form';

export default {
  name: 'upload-logo',
  props: {
    path: [String, Boolean],
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
        .then((response) => {
          this.$emit('uploaded', response.data);
        })
        .catch(() => {
          window.flash('There was a problem uploading your logo. Please try again.', 'danger');
        });
    },
  },
};
</script>

<style>
  .logo-change-prompt {
    display: none;
    background: rgba(0,0,0,.5);
    color: #FFF;
    position: absolute;
    height: 100%;
    width: 100%;
    top: 0;
    left: 0;
  }

  .account_logo:hover .logo-change-prompt {
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    text-align: center;
    height: 128px;
  }

  .is-vcentered {
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    text-align: center;
    height: 128px;
  }
</style>
