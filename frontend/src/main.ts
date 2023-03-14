import { createApp } from "vue";
import { createPinia } from "pinia";
import { translator } from "./services/translator";
import { useWatchers } from "./composables/useWatchers";

import App from "./App.vue";
import router from "./router";

import "./base.css";

const app = createApp(App);
const pinia = createPinia();

app.use(pinia);
app.use(router);
app.use(translator);

useWatchers(pinia);

app.mount("#app");
