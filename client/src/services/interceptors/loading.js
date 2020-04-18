import store from '@/store';
import { START_LOADING, FINISH_LOADING } from "@/store/actions/loading";


export default function setup(http) {
    
    http.interceptors.request.use(config => {
        store.commit(START_LOADING);
        return config;

    }, error => {
        store.commit(FINISH_LOADING);
        return new Promise.reject(error);
    });


    http.interceptors.response.use(config => {
        store.commit(FINISH_LOADING);
        return config;

    }, error => {
        store.commit(FINISH_LOADING);
        return new Promise.reject(error);
    });
}

