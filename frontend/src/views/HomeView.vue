<template>
  <!--   <LoginForm />
  <RegisterForm /> -->
  <component
    @updateComponent="updateSelectedComponent"
    :is="selectedComponent"
  />
</template>

<script lang="ts">
import LoginForm from "@/components/Authentication/LoginForm.vue";
import RegisterForm from "@/components/Authentication/RegisterForm.vue";

export default {
  name: "HomeView",

  components: {
    LoginForm,
    RegisterForm,
  },

  data() {
    return {
      selectedComponent: "LoginForm",
    };
  },

  methods: {
    updateSelectedComponent(value: string) {
      this.selectedComponent = value;
    },
  },
};
</script>
