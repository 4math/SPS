import {
  USER_REQUEST,
  USER_SUCCESS,
  USER_ERROR,
  USER_SOCKETS,
  USER_SOCKETS_SUCCESS,
} from "./../actions/user";
import { AUTH_LOGOUT } from "../actions/auth";
import axios from "axios";

const state = {
  status: "",
  profile: {},
  sockets: {},
};

const getters = {
  getProfile: (state) => state.profile,
  getSockets: (state) => state.sockets,
  isProfileLoaded: (state) => !!state.profile.name,
};

const actions = {
  [USER_REQUEST]: ({ commit, dispatch }) => {
    return new Promise((resolve, reject) => {
      commit(USER_REQUEST);
      axios
        .get("/auth/user")
        .then((response) => {
          commit(USER_SUCCESS, response);
          dispatch(USER_SOCKETS).then(() => {
            resolve();
          });
        })
        .catch(() => {
          commit(USER_ERROR);
          // if response is unauthorized, logout, to
          dispatch(AUTH_LOGOUT);
          reject();
        });
    });
  },

  [USER_SOCKETS]: ({ commit, dispatch }) => {
    return new Promise((resolve, reject) => {
      commit(USER_REQUEST);
      axios
        .get("/sockets/list")
        .then((response) => {
          commit(USER_SOCKETS_SUCCESS, response);
          resolve();
        })
        .catch(() => {
            commit(USER_ERROR);
            dispatch(AUTH_LOGOUT);
            reject();
        });
    });
  },
};

const mutations = {
  [USER_REQUEST]: (state) => {
    state.status = "loading";
  },
  [USER_SUCCESS]: (state, response) => {
    state.status = "success";
    state.profile = response.data;
  },
  [USER_SOCKETS_SUCCESS]: (state, response) => {
    state.sockets = response.data;
  },
  [USER_ERROR]: (state) => {
    state.status = "error";
  },
  [AUTH_LOGOUT]: (state) => {
    state.profile = {};
  },
};

export default {
  state,
  getters,
  actions,
  mutations,
};
