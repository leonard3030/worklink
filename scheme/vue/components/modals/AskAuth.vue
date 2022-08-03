 <template>
  <transition name="fade">
    <div class="auth-overlay">
      <div class="auth-widget">
        <div class="content">
          <h2>
            <span class="action">Sing In</span>
            {{message}}
          </h2>
        </div>
        <div class="option">
          <button class="btn-success" @click="confirm()">Okay</button>
        </div>
      </div>
    </div>
  </transition>
</template>
<script>
export default {
  name: "Ask-Auth",
  components: {},
  props: {
    message: {
      type: String,
      default: "",
    },
  },
  data() {
    return {};
  },
  mounted() {},
  methods: {
    confirm() {
      let vm = this;
      vm.$router.push({ name: "login" });
    },
  },
};
</script>

<style lang="scss" scoped>
.auth-overlay {
  position: fixed;
  z-index: 999999999999999;
  top: 0px;
  left: 0px;
  bottom: 0px;
  right: 0px;
  background: rgba(#fff, 0.9);
  display: flex;
  justify-content: center;
  align-items: center;
  .auth-widget {
    width: 300px;
    border: 1px solid #ddd;
    padding: 20px;
    .content {
      .action {
        color: #8dc73f;
      }
    }
  }
}
</style>