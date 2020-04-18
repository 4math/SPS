import { START_LOADING, FINISH_LOADING } from "./../actions/loading";


const state = {
    refCount: 0,
    isLoading: false
};

const getters = {
    refCount: state => state.refCount,
    isLoading: state => state.isLoading
};

const mutations = {
    [START_LOADING]: state => {
        state.isLoading = true;
        state.refCount++;
    },

    [FINISH_LOADING]: state => {
        state.refCount--;
        state.isLoading = (state.refCount > 0);
    }
};

export default {
    state,
    getters,
    mutations
};