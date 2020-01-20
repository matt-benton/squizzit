<template>
    <div class="container mx-auto bg-red-500">
        <navbar></navbar>
        <router-view></router-view>
    </div>
</template>

<script>
import Navbar from '../components/Navbar.vue'

export default {
    components: {
        'navbar': Navbar,
    }
}
</script>