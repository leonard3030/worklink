<template>
  <div class="map-loaction-wrapper">
    <div class="location-widget" @click="openMap()">
      <slot></slot>
    </div>
  </div>
</template>
<script>
export default {
  name: "map-trigger",
  components: {},
  data() {
    return {};
  },
  mounted() {},
  methods: {
    openMap() {
      let vm = this;
      vm.$InitMap({
        callback: (data) => {
          vm.$emit("onSubmit", data);
        },
      });
    },
  },
};
</script>
<style lang="scss" scoped>
.map-loaction-wrapper {
  width: 100%;
  .location-widget {
    width: 100%;
  }
}
</style>
