import VueRouter from 'vue-router'// Pages
import Register from './pages/register'
import Login from './pages/login'
import MemberDashboard from './pages/member/dashboard'
import AdminDashboard from './pages/admin/dashboard'// Routes
import ClientDashboard from './pages/client/dashboard'// Routes
import ForgotPassword from './pages/forgot-password'// Routes
const routes = [
    {
        path: '/',
        name: 'home',
        component: Login,
        meta: {

        }
    },
    {
        path: '/forgot-password',
        name: 'forgot.password',
        component: ForgotPassword,
        meta: {
        }
    },
    // ADMIN ROUTES
    {
        path: '/admin',
        name: 'admin.dashboard',
        component: AdminDashboard,
        meta: {
            auth: {roles: 1, redirect: {name: 'home'}, forbiddenRedirect: '/403'}
        }
    },
    // MEMBER ROUTES
    {
        path: '/projects',
        name: 'member.dashboard',
        component: MemberDashboard,
        meta: {
            auth: {roles: 2, redirect: {name: 'home'}, forbiddenRedirect: '/403'}        }
    },
    // MEMBER ROUTES
    {
        path: '/dashboard',
        name: 'client.dashboard',
        component: ClientDashboard,
        meta: {
            auth: {roles: 3, redirect: {name: 'home'}, forbiddenRedirect: '/403'}        }
    },


]
const router = new VueRouter({
    history: true,
    mode: 'history',
    routes,
})
export default router