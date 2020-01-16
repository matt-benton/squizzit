import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

export const store = new Vuex.Store({
    state: {
        token: null,
        email: null,
        numInvites: null,
    },
    mutations: {
        storeUser (state, userData) {
            state.token = userData.token;
            state.email = userData.email;
        },
        clearUser (state) {
            state.token = null;
            state.email = null;
        },
        storeNumInvites (state, data) {
            state.numInvites = data.numInvites;
        },
        decrementNumInvites (state) {
            state.numInvites--;
        },
    },
    actions: {
        storeUser ({commit}, userData) {
            commit('storeUser', {
                token: userData.token,
                email: userData.email
            });
        },
        clearUser ({commit}) {
            commit('clearUser');
        },
        checkForToken ({commit}) {
            const token = localStorage.getItem('token');
            const email = localStorage.getItem('email');
            if (!token) {
                return;
            }

            commit('storeUser', {
                token,
                email
            })
        },
        storeNumInvites ({commit}, data) {
            commit('storeNumInvites', {
                numInvites: data.quizInvites.length,
            })
        },
        decrementNumInvites ({commit}) {
            commit('decrementNumInvites');
        },
    },
    getters: {

    }
});
