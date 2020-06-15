<template>
    <div>
        <b-navbar class="nav-css" v-if="$auth.check()" toggleable="lg" type="dark" variant="dark">
            <b-navbar-brand>
                <div class="row ml-2">
                    <router-link  class="home" to="/">
                        <h3 class="logo"><i class="fab fa-pushed  fa-2x"></i></h3>
                    </router-link>
                </div>
            </b-navbar-brand>
            <b-navbar-toggle target="nav-collapse"></b-navbar-toggle>
            <b-collapse id="nav-collapse" is-nav>
                <!-- Right aligned nav items -->
                <b-navbar-nav class="ml-auto">
                    <b-avatar v-if="$auth.user().profile_photo" :src="$auth.user().profile_photo"></b-avatar>
                    <b-avatar v-else  variant="primary"
                              :text="$auth.user().first_name[0].toUpperCase()+$auth.user().last_name[0].toUpperCase()"></b-avatar>
                    <b-nav-item-dropdown v-if="$auth.check()" :text="'Hello, ' + $auth.user().first_name" right>
                        <b-dropdown-item  v-if="$auth.check()">
                            <router-link  :to="{ path : '/profile' }">
                               My Profile
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
        <sidebar-menu v-if="$auth.check() && $auth.check(3)" :menu="clientMenu"  :disableHover="true"  :collapsed="true"/>
    </div>
</template>
<script>
    export default {
        data() {
            return {
                project_name:'',
                visible:false,
                memberMenu:[
                    {
                        title: 'Projects',
                        icon: 'fas fa-th-large',
                        href: { path: '/projects' }
                    },
                    {
                        title: 'Members',
                        icon: 'fas fa-users',
                        href: { path: '/members' }
                    },
                ],
                adminMenu:[
                {
                    title: 'Projects',
                    icon: 'fas fa-th-large',
                    href: { path: '/projects' }
                },
                {
                    title: 'Projects Management',
                    icon: 'fas fa-tasks',
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
                },
                {
                    title: 'Invoices',
                    icon: 'fas fa-file-invoice-dollar',
                    href: { path: '/admin/invoices' }
                },
                {
                    title: 'Emails',
                    icon: 'fas fa-envelope',
                    href: { path: '/admin/emails' }
                },
                ],
                clientMenu:[
                    {
                        title: 'Projects',
                        icon: 'fas fa-th-large',
                        href: { path: '/projects' }
                    },
                ],
                routes: {
                    //HOME
                    home: [
                        {
                            name: 'Ubix',
                            path: 'home'
                        }
                    ],
                    profile:{
                        name: 'profile',
                        path: 'profile'
                    }

                }
            }
        },
        mounted(){
            this.visible = false;
            this.$root.$on('project', data => {
                this.project_name = data;
                this.visible = true;
            });
        }
    }
</script>
<style scoped>
    .outlined{
        border-color:transparent !important ;
        color: #67FFC8 !important;
    }
    .logo{
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
    a{
        color: black;
        text-decoration: none !important;
    }
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

    .b-avatar-text > span{
        display: flex;
        justify-content: center;
        line-height: 2.5;
    }

</style>