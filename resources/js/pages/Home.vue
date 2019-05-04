<template>
    <div>
        <navbar></navbar>
        <div>
            <h1>Home</h1>
        </div>
    </div>
</template>

<script>
    import Navbar from '../components/Navbar.vue'

    export default {
        data() {
            return {

            }
        },
        created() {
            this.$store.dispatch('checkForToken');
        },
        components: {
            'navbar': Navbar
        }
    }
</script>
