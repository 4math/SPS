
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

    retrieveToken(context, credentials) {

    }

};