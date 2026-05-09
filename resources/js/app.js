import { createApp } from 'vue';
import App from './App.vue';
import '../css/app.css';
//import "tabulator-tables/dist/css/tabulator.min.css";
import router from "./router"

//createApp(App).mount('#app');
createApp(App).use(router).mount("#app")


//import { createApp } from 'vue'
//import Grid from './components/Grid.vue'
//
//createApp(Grid).mount('#app')