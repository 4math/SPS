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
        // console.log("Started loading ", state.isLoading, state.refCount);
        state.isLoading = true;
        state.refCount++;
    },

    [FINISH_LOADING]: state => {
        // console.log("Ended loading ", state.isLoading, state.refCount);
        state.isLoading = false;
        state.refCount--;
    }
};

export default {
    state,
    getters,
    mutations
};