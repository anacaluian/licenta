import VueRouter from 'vue-router'// Pages
import Register from './pages/register'
import Login from './pages/login'
import Project from './pages/member/project'
import Discussions from './components/discussions'
import Notes from './components/notes'// Routes
import MemberDashboard from './pages/member/dashboard'
import AdminDashboard from './pages/admin/dashboard'// Routes
import AdminMembers from './pages/admin/members'// Routes
import AdminClients from './pages/admin/clients'// Routes
import ClientDashboard from './pages/client/dashboard'// Routes
import ForgotPassword from './pages/forgot-password'// Routes
const routes = [
    {
        path: '/',
        name: 'home',
        component: Login,
        meta: {
            auth: false
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
    {
        path: '/admin/members',
        name: 'admin.members',
        component: AdminMembers,
        meta: {
            auth: {roles: 1, redirect: {name: 'home'}, forbiddenRedirect: '/403'}
        }
    },
    {
        path: '/admin/clients',
        name: 'admin.clients',
        component: AdminClients,
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
    {
        path: '/projects/:id',
        name: 'project',
        component: Project,
        children: [
            {
                path: 'discussions',
                name: 'project.discussions',
                component: Discussions
            },
            {
                path: 'notes',
                name: 'project.notes',
                component: Notes
            }
        ],
        meta: {
            auth: {roles: 2, redirect: {name: 'home'}, forbiddenRedirect: '/403'}
        }
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