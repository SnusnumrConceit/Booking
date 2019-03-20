export default {
    namespaced: true,

    state: {
        token: localStorage.getItem('token') || '',
        user: {},
        csrf_token: ''
    },

    mutations: {
        set(state, { prop, value}) {
            state[prop] = value;
        }
    },

    actions: {
        setUser({ commit }, value) {
            commit('set', {
                value: value,
                prop: 'user',
            })
        },

        setCSRF({ commit }, value) {
            commit('set', {
                value: value,
                prop: 'csrf_token'
            })
        }
    },

    getters: {
        getToken(state) {
            return state.token;
        },

        getUser(state) {
            return state.user;
        },

        getCSRF(state) {
            return state.csrf_token;
        },

        isAuthenthicated(state) {
            return !!state.token;
        }
    }
}
