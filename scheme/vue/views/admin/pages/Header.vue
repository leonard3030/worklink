<template>
  <div class="clientHeader">
    <h1>Header</h1>
  </div>
</template>

<script>
export default {
  name: "clientHeader",
  data() {
    return {
      showDrop: false,
      closeOnClickOutside: true,
      transition: true,
      showNav: false,
      client: null,
    };
  },
  methods: {
    collapse() {
      let om = this;
      om.showNav = !om.showNav;
    },
  },
  mounted() {
    window.scrollTo(0, 0);
	if (localStorage.client) {
		this.client = JSON.parse(localStorage.client);
	}
  },
};
</script>

<style lang="scss" scoped>
* {
  transition: all 0.4s ease-in;
}
$mainBlue: #005ea7;
$mainDarkBlue: #072e4d;
$mainVeryDarkBlue: #03243f;
$mainYellow: #ffd11b;
.client-navbar {
  display: flex;
  align-items: center;
  justify-content: space-between;
  background: $mainBlue;
  margin: 0 !important;
  padding: 0 !important;
  position: fixed;
  width: 100%;
  top: 0;
  z-index: 3;
  .logo-widget {
    // width: 15%;
    label {
      padding: 20px 10px;
      color: $mainYellow;
      font-weight: 700;
      font-size: 20px;
      display: block;
      &:hover {
        cursor: pointer;
      }
      @media screen and (max-width: 600px) {
        font-size: 16px;
      }
    }
    @media screen and (max-width: 600px) {
      // width: 10%;
    }
  }
  .links-widget {
    // width: 70%;
    display: flex;
    justify-content: center;
    .nav-links {
      display: flex;
      align-items: center;
      margin-bottom: 0px;
      .nav-link {
        color: white;
        padding: 20px 15px;
        background: none;
        border: none;
        &:hover,
        &:active,
        &:focus {
          background: $mainDarkBlue;
          color: $mainYellow;
        }
      }
      .nav-drops {
        position: absolute;
        display: flex;
        flex-direction: column;
        background: $mainVeryDarkBlue;
        border-radius: 0px 0px 2px 2px;
        .dropdown-item {
          padding: 10px;
          color: white;
          &:hover,
          &:active,
          &:focus {
            background: $mainVeryDarkBlue;
            color: $mainYellow;
          }
        }
      }
      .router-link-exact-active {
        background: $mainDarkBlue;
        color: $mainYellow;
      }
      @media screen and (max-width: 950px) {
        display: none;
        // width: 40%;
      }
    }
  }
  .showNav {
    display: flex;
    @media screen and (max-width: 950px) {
      display: flex;
    }
  }
  .right-links {
    // width: 15%;
    display: flex;
    align-items: center;
    padding-right: 10px;
    .client {
      color: $mainYellow;
	  font-size: 1rem;
	  font-weight: 500;
	  background: #000;
	  padding: 5px 1rem;
	  align-content: center;
	  margin-right: 1rem;
	  margin-top: 1rem;
	  display: flex;
	  align-items: center;
	  i{
		  color: red;
		  font-size: .75rem;
		  margin-left: 1rem;
		  cursor: pointer;
	  }
    }
    .nav-link {
      background: $mainBlue;
      padding: 10px 20px;
      color: white;
      font-weight: 400;
      font-size: 16px;
      border-radius: 5px;
      border: 1px white solid;
      &:hover,
      &:active,
      &:focus {
        background: white;
        color: $mainDarkBlue;
        cursor: pointer;
      }
      @media screen and (max-width: 950px) {
        font-size: 14px;
        padding: 5px 20px;
      }
    }
    @media screen and (max-width: 950px) {
      padding: 10px 2px;
      // width: 40%;
    }
  }
  .nav-collapse-img {
    img {
      margin-right: 10px;
      height: 40px;
    }
    @media screen and (min-width: 950px) {
      display: none;
    }
  }
  @media screen and (max-width: 950px) {
    padding: 10px;
  }
}
.collapse-nav-links {
  background: $mainBlue;
  position: fixed;
  display: block;
  width: 100%;
  margin-top: 0px;
  z-index: 1;
  transition: all 1s ease-in;
  .nav-link {
    display: block;
    color: white;
    padding: 15px;
    background: none;
    border: none;
    text-align: center;
    &:hover,
    &:active,
    &:focus {
      background: $mainDarkBlue;
      color: $mainYellow;
    }
  }
  .nav-drops {
    position: relative;
    text-align: center;
    display: flex;
    flex-direction: column;
    background: $mainVeryDarkBlue;
    border-radius: 0px 0px 2px 2px;
    .dropdown-item {
      padding: 10px;
      color: white;
      &:hover,
      &:active,
      &:focus {
        background: $mainVeryDarkBlue;
        color: $mainYellow;
      }
    }
  }
  @media screen and (min-width: 951px) {
    display: none;
  }
  @media screen and (max-width: 950px) {
    display: flex;
    flex-direction: column;
  }
}
</style>
