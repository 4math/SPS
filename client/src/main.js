import Vue from 'vue'
import App from './App.vue'
import router from './router/router';
import store from './store';
import loadingInterceptor from './services/interceptors/loading';
import authInterceptor from './services/interceptors/auth';
import Router from 'vue-router';
import axios from "axios";
import VueAxios from 'vue-axios';
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue'
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'


Vue.config.productionTip = false;

axios.defaults.baseURL = 'http://localhost:8000/api';

Vue.use(BootstrapVue);

Vue.use(IconsPlugin);

Vue.use(Router);

Vue.use(VueAxios, axios);

authInterceptor(axios);
loadingInterceptor(axios);

new Vue({
  render: h => h(App),
  router,
  store,
}).$mount('#app');
