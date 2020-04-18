
import axios from 'axios';
import {
    AUTH_REQUEST,
    AUTH_ERROR,
    AUTH_SUCCESS,
    AUTH_LOGOUT
} from "../actions/auth";



const state = {
    token: localStorage.getItem('user-token') || '',
    status: ''
};

const getters = {
    isAuthenticated: state => !!state.token,
    authStatus: state => state.status
};

const actions = {

    [AUTH_REQUEST]: ({ commit }, user) => {

        return new Promise((resolve, reject) => {
            commit(AUTH_REQUEST);
            axios.post('/auth/login', user)
                .then(response => {
                    const token = response.data.token;
                    // store the token in localstorage
                    localStorage.setItem('user-token', token);
                    commit(AUTH_SUCCESS, response);
                    resolve(response);
                })
                .catch(err => {
                    commit(AUTH_ERROR, err);
                    // if the request fails, remove any possible user token if possible
                    localStorage.removeItem('user-token');
                    reject(err);
                })
        });

    },

    [AUTH_LOGOUT]: ({ commit }) => {
        return new Promise((resolve) => {
            axios.get("/auth/logout").then(() => {
                commit(AUTH_LOGOUT);
                delete axios.defaults.headers.common['Authorization'];
                localStorage.removeItem('user-token');
                resolve();
            });
        });
    }

};

const mutations = {
    [AUTH_REQUEST]: (state) => {
        state.status = 'loading'
    },
    [AUTH_SUCCESS]: (state, token) => {
        state.status = 'success'
        state.token = token
    },
    [AUTH_ERROR]: (state) => {
        state.status = 'error'
    },
    [AUTH_LOGOUT]: state => {
        state.token = "";
    }
};


export default {
    getters,
    actions,
    mutations,
    state
};