<template>
  <b-collapse class="card" :open.sync="isOpen">
    <div slot="trigger" class="card-header">
        <p class="card-header-title">{{ title}}</p>
        <a class="card-header-icon">
            <b-icon :icon="isOpen ?
                'arrow_drop_down' : 'arrow_drop_up'">
            </b-icon>
        </a>
    </div>
    <div class="card-content">
        <div class="content">
            <slot></slot>
        </div>
    </div>
  </b-collapse>
</template>

<script>
export default {
  name: 'card-collapse',

  props: {
    openOnLoad: Boolean,
    title: String,
  },

  data() {
    return {
      isOpen: this.openOnLoad ? this.openOnLoad : false,
    };
  },
};
</script>
