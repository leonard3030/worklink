<template>
  <div class="Sidebar">
    <div class="nav">
      <div class="brand">
        <img src="/assets/images/worklogo.png" alt="" srcset="" />
      </div>
      <ul class="nav-items">
        <li>
          <router-link to="/admin" class="nav-item"
            ><i class="fa fa-tachometer-alt"></i>&nbsp;&nbsp;Links</router-link
          >
        </li>
      </ul>
    </div>
  </div>
</template>

<script>
export default {
  name: "Sidebar",
  method: {},
  mounted() {
  },
};
</script>

<style lang="scss" scoped>
$orange: #151948;
$green: #151c61;
.Sidebar {
  .nav {
    width: 200px;
    background: $green;
    height: 100vh;
    overflow: hidden;
    box-shadow: 0px 5px 20px 10px #f0f0f0;
    @media only screen and(max-width: 600px) {
      width: 100px;
    }
    .brand {
      position: relative;
      padding: 1rem 1.5rem;
      background: #fff;
      display: flex;
      justify-content: center;
      img {
        width: 80%;
      }
    }
    .nav-items {
      display: flex;
      flex-direction: column;
      li {
        display: block;
        border-bottom: 1px #eeeeee3a solid;
        .nav-item {
          background: none;
          border: none;
          padding: 0.75rem 1rem;
          color: white;
          display: block;
          font-size: 0.85rem;
          width: 100%;
          text-align: left;
          cursor: pointer;
          &.router-link-exact-active,
          &:hover {
            background: $orange;
          }
        }
      }
    }
  }
}
</style>
