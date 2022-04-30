import VueRouter from 'vue-router'
import Login from './pages/login'
import Project from './pages/member/project'
import Members from './pages/member/members'
import Discussions from './components/discussions'
import Notes from './components/notes'
import Files from './components/files'
import Time from './components/time'
import Profile from './components/profile.vue'
import Activity from './components/activity.vue'
import MemberDashboard from './pages/member/dashboard'
import AdminDashboard from './pages/admin/dashboard'
import AdminMembers from './pages/admin/members'
import AdminClients from './pages/admin/clients'
import AdminEmails from './pages/admin/emails'
import AdminInvoices from './pages/admin/invoices'
import ClientDashboard from './pages/client/dashboard'
import ForgotPassword from './pages/forgot-password'
import laroute from './../../public/js/laroute'
import Vue from 'vue'


const routes = [
    {
        path: '/',
        name: 'home',
        component: Login,
        meta: {
            auth: false,
        }
    },
    {
        path: '/forgot-password',
        name: 'forgot.password',
        component: ForgotPassword,
        meta: {
        }
    },
    {
        path: '/profile',
        name: 'profile',
        component: Profile,
        meta: {
            auth: true
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
    {
        path: '/admin/emails',
        name: 'admin.emails',
        component: AdminEmails,
        meta: {
            auth: {roles: 1, redirect: {name: 'home'}, forbiddenRedirect: '/403'}
        }
    },
    {
        path: '/admin/invoices',
        name: 'admin.invoices',
        component: AdminInvoices,
        meta: {
            auth: {roles: 1, redirect: {name: 'home'}, forbiddenRedirect: '/403'}
        }
    },

    // MEMBER ROUTES
    {
        path: '/members',
        name: 'member.members',
        component: Members,
        meta: {
            auth: {roles: [2], redirect: {name: 'home'}, forbiddenRedirect: '/403'}        }
    },
    {
        path: '/projects',
        name: 'member.dashboard',
        component: MemberDashboard,
        meta: {
            auth: {roles: [1,2,3], redirect: {name: 'home'}, forbiddenRedirect: '/403'}        }
    },
    {
        path: '/projects/:project',
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
                component: Notes,
                meta: {
                    auth: {roles: [1,2,3], redirect: {name: 'home'}, forbiddenRedirect: '/403'}
                }
            },
            {
                path: 'files',
                name: 'project.files',
                component: Files,
                meta: {
                    auth: {roles: [1,2,3], redirect: {name: 'home'}, forbiddenRedirect: '/403'}
                }
            },
            {
                path: 'time',
                name: 'project.time',
                component: Time,
                meta: {
                    auth: {roles: [1,2], redirect: {name: 'home'}, forbiddenRedirect: '/403'}
                }
            },
            {
                path: 'activity',
                name: 'project.activity',
                component: Activity,
                meta: {
                    auth: {roles: [1,2], redirect: {name: 'home'}, forbiddenRedirect: '/403'}
                }
            },
        ],
        meta: {
            auth: {roles: [1,2,3], redirect: {name: 'home'}, forbiddenRedirect: '/403'}
        }
    },
    // CLIENT ROUTES
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

router.afterEach(async (to, from, next)  => {
    if (to.params.project) {
        let project = atob(to.params.project);
        project = JSON.parse(project);
        to.params.id = project.id;
    }
    next;
});

router.afterEach(async (to, from)  => {
    let queries = to.query;
    if (queries.hasOwnProperty('_project_user')) {
        Vue.auth.impersonate({
            url: laroute.route('take', {}),
            data: {
                user:queries._project_user
            },
            redirect: null
            });
    }
});
export default router
