<template>
    <div>
        <div>
            <h1>Home</h1>
        </div>
        <div>
            <button @click="logout">Logout</button>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {

            }
        },
        created() {
            this.$store.dispatch('checkForToken');
        },
        methods: {
            logout() {
                localStorage.clear();
                this.$store.dispatch('clearUser');
                this.$router.push('/');
            }
        }
    }
</script>
