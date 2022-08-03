<template>
  <CDropdown
    inNav
    class="c-header-nav-items"
    placement="bottom-end"
    add-menu-classes="pt-0"
  >
    <template #toggler>
      <CHeaderNavLink>
        <div class="c-avatar">
          <img src="assets/images/avatar.png" class="c-avatar-img" />
        </div>
      </CHeaderNavLink>
    </template>

    <CDropdownDivider />

    <CDropdownItem @click="logout"> <CIcon name="cil-lock-locked"  />Logout </CDropdownItem>
  </CDropdown>
</template>

<script>
export default {
  name: "TheHeaderDropdownAccnt",
  data() {
    return {
      itemsCount: 42,
    };
  },
  methods:{
    logout(){
      let vm=this;
      vm.$localStorage.remove('access_token');
      vm.$localStorage.remove('user');
      vm.$router.push({name:'login'})
    }
  }
};
</script>

<style scoped>
.c-icon {
  margin-right: 0.3rem;
}
</style>
