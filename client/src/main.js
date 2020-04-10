import Vue from 'vue'
import App from './App.vue'
import router from './router/router';
import store from './store';
import Router from 'vue-router';
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue'
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'

Vue.config.productionTip = false;

Vue.use(BootstrapVue);

Vue.use(IconsPlugin);

Vue.use(Router);



new Vue({
  render: h => h(App),
  router,
  store
}).$mount('#app');
