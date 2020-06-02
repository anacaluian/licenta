<template>
    <div>
        <b-navbar class="nav-css" v-if="$auth.check()" toggleable="lg" type="dark" variant="dark">
            <b-navbar-brand>
                <div class="row ml-4">
                    <router-link  class="home" to="/">
                        <h2>Projects</h2>
                    </router-link>
                    <b-button v-if="$auth.check(2) && $route.name == 'project'"  @click="$router.push({ name: 'member.dashboard'})" pill class="ml-3 outlined" variant="outline-secondary">
                      Projects
                    </b-button>
                </div>
            </b-navbar-brand>
            <b-navbar-toggle target="nav-collapse"></b-navbar-toggle>
            <b-collapse id="nav-collapse" is-nav>
                <!-- Right aligned nav items -->
                <b-navbar-nav class="ml-auto">
                    <b-nav-item-dropdown v-if="$auth.check()" :text="'Hello, ' + $auth.user().first_name" right>
                        <b-dropdown-item v-if="$auth.check(1)" v-for="(route, key) in routes.admin" v-bind:key="route.path">
                            <router-link class="user" :to="{ name : route.path }" :key="key">
                                {{route.name}}
                            </router-link>
                        </b-dropdown-item>
                        <b-dropdown-item v-if="$auth.check(2)" v-for="(route, key) in routes.member" v-bind:key="route.path">
                            <router-link class="user" :to="{ name : route.path }" :key="key">
                                {{route.name}}
                            </router-link>
                        </b-dropdown-item>
                        <b-dropdown-item class="user" v-if="$auth.check(3)" v-for="(route, key) in routes.client" v-bind:key="route.path">
                            <router-link :to="{ name : route.path }" :key="key">
                                {{route.name}}
                            </router-link>
                        </b-dropdown-item>
                        <b-dropdown-item  v-if="$auth.check(2)" v-for="(route, key) in routes.member_profile" v-bind:key="route.path">
                            <router-link class="user" :to="{ name : route.path }" :key="key">
                                {{route.name}}
                            </router-link>
                        </b-dropdown-item>
                        <b-dropdown-item  v-if="$auth.check(3)" v-for="(route, key) in routes.client_profile" v-bind:key="route.path">
                            <router-link  class="user" :to="{ name : route.path }" :key="key">
                                {{route.name}}
                            </router-link>
                        </b-dropdown-item>
                        <b-dropdown-item v-if="$auth.check()">
                            <a class="user" @click.prevent="$auth.logout()">Logout</a>
                        </b-dropdown-item>
                    </b-nav-item-dropdown>
                </b-navbar-nav>
            </b-collapse>
        </b-navbar>
        <sidebar-menu  v-if="$auth.check() && $auth.check(1)" :menu="adminMenu" :disableHover="true"  :collapsed="true"/>
        <sidebar-menu v-if="$auth.check() && $auth.check(2)" :menu="memberMenu"  :disableHover="true"  :collapsed="true"/>
    </div>
</template>
<script>
    export default {
        data() {
            return {
                memberMenu:[
                    {
                        title: 'Projects',
                        icon: 'fas fa-th-large',
                        href: { path: '/projects' }
                    },
                ],
                adminMenu:[
                {
                    title: 'Projects',
                    icon: 'fas fa-th-large',
                    href: { path: '/admin' }
                },
                {
                    title: 'Members',
                    icon: 'fas fa-users',
                    href: { path: '/admin/members' }
                },
                {
                    title: 'Clients',
                    icon: 'fas fa-address-book',
                    href: { path: '/admin/clients' }
                }

                ],
                routes: {
                    //HOME
                    home: [
                        {
                            name: 'Ubix',
                            path: 'home'
                        }
                    ],
                    // member_profile:[
                    //     {
                    //         name:'My Profile',
                    //         path:'member.profile'
                    //     }
                    // ],
                    // client_profile:[
                    //     {
                    //         name:'My Profile',
                    //         path:'home'
                    //     }
                    // ],
                }
            }
        },
    }
</script>
<style scoped>
    .outlined{
        border-color:transparent !important ;
        color: #67FFC8 !important;
    }

    .outlined:hover{
        border-color: #67FFC8 !important;
    }
    .unlogged{
        color: white;
        text-decoration: none !important;
    }
    .unlogged:hover {
        color: white;
        text-decoration: none !important;
    }
    .home{
        color: white;
        text-decoration: none !important;
    }
    .home:hover{
        color: white;
        text-decoration: none !important;
    }
    .user{
        color: black;
        text-decoration: none !important;
    }
    .user:hover{
        color: black;
        text-decoration: none !important;
    }
    .router-link-active{
        color: black;
        text-decoration: none !important;
    }
</style>
<style>

    .v-sidebar-menu{
        top:unset !important;
    }
    .v-sidebar-menu .vsm--mobile-item {
        background-color: black
    }

    .nav-css{
        background-color: #2c2e38 !important;
    }
    .v-sidebar-menu{
        background-color: #2c2e38 !important;
    }

    .router-link-exact-active > .vsm--icon{
        color: #67FFC8 !important;
        background-color: #2c2e38 !important;
    }

    .vsm--icon{
        color: #B9B9B9 !important;
        background-color: #2c2e38 !important;
    }

    .router-link-active h2{
        color: white !important;
    }

</style>