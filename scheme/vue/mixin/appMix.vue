<script>
export default {
  data() {
    return {};
  },
  mounted() {},
  methods: {
    $startLoader() {
      let vm = this;
      vm.$store.state.isLoading = true;
    },
    $stopLoader() {
      let vm = this;
      vm.$store.state.isLoading = false;
    },

    $writerlogout() {
      let vm = this;
      vm.$localStorage.remove("writter_access_token");
      vm.$localStorage.remove("writer");
      vm.$router.push({ name: "Login" });
    },
    $clientlogout() {
      let vm = this;
      vm.$localStorage.remove("client_access_token");
      vm.$localStorage.remove("client");
      vm.$notify({
        group: "status",
        type: "success",
        title: "Important",
        text: "You are logged out now",
      });
      document.location.reload();
    },
    $isFieldsValidated(form, rules) {
      let vm = this;
      let is_error = false;
      Object.keys(rules).forEach((key, index) => {
        if (rules[key].run(form[key])) {
          let error_message = rules[key].run(form[key]);
          if (error_message) {
            if (!is_error) {
              vm.$notify({
                group: "status",
                type: "warn",
                title: "OOPS!!!",
                text: error_message,
              });
            }
            is_error = true;
          }
        }
      });
      return !is_error;
    },
  },
};
</script>
