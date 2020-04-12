import {
    USER_REQUEST,
    USER_SUCCESS,
    USER_ERROR
} from './../actions/user';
import { AUTH_LOGOUT } from "../actions/auth";
import axios from 'axios';

const state = {
    status: "",
    profile: {}
};

const getters = {
    getProfile: state => state.profile,
    isProfileLoaded: state => !!state.profile.name
};

const actions = {

    [USER_REQUEST]: ({ commit, dispatch }) => {
        return new Promise((resolve, reject) => {
            commit(USER_REQUEST);
            axios.get("/users/list")
                .then(response => {
                    commit(USER_SUCCESS, response);
                    resolve();
                })
                .catch(() => {
                    commit(USER_ERROR);
                    // if response is unauthorized, logout, to
                    dispatch(AUTH_LOGOUT);
                    reject();
                });
        });
    },



};

const mutations = {
    [USER_REQUEST]: state => {
        state.status = "loading";
    },
    [USER_SUCCESS]: (state, response) => {
        state.status = "success";
        // Vue.set(state, "profile", resp);
        console.log(`response is ${response}`);
    },
    [USER_ERROR]: state => {
        state.status = "error";
    },
    [AUTH_LOGOUT]: state => {
        state.profile = {};
    }
};

export default {
    state,
    getters,
    actions,
    mutations
};