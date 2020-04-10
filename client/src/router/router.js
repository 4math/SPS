import Router from 'vue-router';
import HelloWorld from '@/components/HelloWorld';
import Login from '@/components/auth/Login';
import Dashboard from '@/components/Dashboard';
import store from '@/store';


const ifNotAuth = (to, from, next) => {
    if (!store.getters.isAuthenticated) {
        next();
        return;
    }
    next('/');
}

const ifAuth = (to, from, next) => {
    if (store.getters.isAuthenticated) {
        next();
        return;
    }
    next('/login');
}


const router = new Router({
    mode: 'history',
    routes: [
        {
            path: '/',
            name: 'HelloWorld',
            component: HelloWorld,
            meta: {
                //public route 
                auth: undefined
            }
        },
        {
            path: '/login',
            name: 'Login',
            component: Login,
            meta: {
                auth: false
            },
            beforeEnter: ifNotAuth
        },
        {
            path: '/dash',
            name: 'DashBoard',
            component: Dashboard,
            meta: {
                auth: true
            },
            beforeEnter: ifAuth
        },
    ]
});

export default router;