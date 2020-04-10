import Vue from 'vue';
import auth from './modules/auth';
import Vuex from 'vuex';

Vue.use(Vuex);

export default new Vuex.Store({
    modules: {
        auth
    }
});