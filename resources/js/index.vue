<template>
    <div id="main">
        <header id="header">
            <navbar></navbar>
        </header>
        <div id="content ">
            <router-view></router-view>
        </div>
    </div>
</template>
<script>
    import navbar from './components/navbar'
    export default {
        components:{
            navbar
        },
        data() {
            return {
                //
            }
        },
    }
</script>