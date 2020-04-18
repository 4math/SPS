
import { AUTH_LOGOUT } from "@/store/actions/auth";
import store from '@/store';

export default function setup(http) {

    http.interceptors.request.use(config => {

        const token = localStorage.getItem('user-token');
        if (token) {
            config.headers.common['Authorization'] = "Bearer " + token;
        }
        return config;
    }, error => {
        return Promise.reject(error);
    });

    http.interceptors.response.use(
        response => {
            return response;
        },
        err => {
            console.log("not ok");
            if (err.status === 401) {
                // if you ever get an unauthorized, logout the user
                store.dispatch(AUTH_LOGOUT);
            }
            return Promise.reject(err);
        }
    );

}