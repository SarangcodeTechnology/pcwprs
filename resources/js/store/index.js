import Vue from 'vue'

import Vuex from 'vuex'
import createPersistedState from "vuex-persistedstate";
import auth from "./modules/auth";
import webservice from "./modules/webservice";

Vue.use(Vuex)

const opts = {
    modules: {
        auth,
        webservice,
    },

    state: {
        notifications: [],
        miniVariant: false,
    },

    mutations: {
        SET_MINIVARIANT(state, payload) {
            state.miniVariant = payload;
        },
        ADD_NOTIFICATION(state, notification) {
            state.notifications.push({
                type: notification.type,
                message: notification.message,
                id: (Math.random().toString(36) + Date.now().toString(36)).substr(2),
            })
        },
        REMOVE_NOTIFICATION(state, notificationToRemove) {
            state.notifications = state.notifications.filter(notification => {
                return notification.id != notificationToRemove.id;
            })
        }
    },

    actions: {
        setMiniVariant(state, payload) {
            state.commit('SET_MINIVARIANT', payload)
        },
        addNotification(state, notification) {
            state.commit('ADD_NOTIFICATION', notification);
        },
        removeNotification(state, notification) {
            state.commit('REMOVE_NOTIFICATION', notification);
        }
    },

    getters: {
        GET_MINIVARIANT(state) {
            return state.miniVariant;
        }
    },
    plugins: [createPersistedState({
            storage: window.sessionStorage,
        }
    )]
}

export default new Vuex.Store(opts)
