import Vue from 'vue';
import Vuex from 'vuex';
Vue.use(Vuex);

import Auth from './modules/auth';

export default new Vuex.Store({
    modules: {
        Auth
    }
});
