import Vue from 'vue'
import App from './App.vue'
import router from './router/router';
import Router from 'vue-router';
import Vuex from 'vuex';
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue'
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'

Vue.config.productionTip = false;

Vue.use(BootstrapVue);

Vue.use(IconsPlugin);

Vue.use(Router);

Vue.use(Vuex);


new Vue({
  render: h => h(App),
  router
}).$mount('#app');
