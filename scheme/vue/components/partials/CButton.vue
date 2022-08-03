<template>
  <button class="c-btn-cagura" :disabled="is_loading" @click="onClick">
    <loading
      :height="15"
      color="#fff"
      :opacity="1"
      :width="15"
      :active.sync="is_loading"
      :is-full-page="false"
      :can-cancel="false"
      background-color="#5947e8"
      class="loader"
    ></loading>
    <span>
      <slot></slot>
    </span>
  </button>
</template>
<script>
export default {
  name: "cagura-button",
  props: {
    loading: {
      type: Boolean,
      default: false,
    },
  },
  components: {},
  data() {
    return {
      is_loading: false,
    };
  },
  watch: {
    loading: function (val) {
      this.is_loading = val;
    },
  },
  mounted() {
    let vm = this;
    vm.is_loading = vm.loading;
  },
  methods: {
    onClick(e) {
      let vm = this;
      vm.$emit("click", e);
    },
  },
};
</script>

<style lang="scss" scoped>
.c-btn-cagura {
  position: relative;
}
.loader {
  border-radius: 5px;
}
</style>
