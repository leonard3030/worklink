<template>
  <div class="cagura-wrapper">
    <clientHeader />
    <div class="all-content">
      <h1 class="main-text">
        Ooops!!! can't find the page you was are looking for
      </h1>
    </div>
    <clientFooter />
  </div>
</template>

<script>
export default {
  name: "NotFound",
  data() {
    return {};
  },
  methods: {},
};
</script>

<style lang="scss" scoped>
.main-text {
  height: 48vh;
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 0 2rem;
  @media screen and (max-width: 700px) {
    font-size: 1.25rem;
  }
}
</style>
