import { AUTH_LOGOUT } from "@/store/actions/auth";
import store from "@/store";

export default function setup(http) {
  http.interceptors.request.use(
    config => {
      const token = localStorage.getItem("user-token");
      if (token) {
        config.headers.common["Authorization"] = "Bearer " + token;
      }
      return config;
    },
    error => {
      return Promise.reject(error);
    }
  );

  http.interceptors.response.use(
    response => {
      const newToken = response.headers.authorization;
      if (newToken) {
        // using substr to delete 'Bearer ' 
        localStorage.setItem("user-token", newToken.substr(7));
      }

      if (response.status === 401) {
        // if you ever get an unauthorized, logout the user
        store.dispatch(AUTH_LOGOUT);
      }
      return response;
    },
    error => {
      if (error.response && error.response.data) {
        return Promise.reject(error.response.data);
      }
      return Promise.reject(error.message);
    }
  );
}
