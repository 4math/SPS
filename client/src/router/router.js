import Router from 'vue-router';
import HelloWorld from '@/components/HelloWorld';
import Login from '@/components/auth/Login';
import Dashboard from '@/components/Dashboard';


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
            }
        },
        {
            path: '/dash',
            name: 'DashBoard',
            component: Dashboard,
            meta: {
                auth: true
            }
        },
    ]
});

export default router;