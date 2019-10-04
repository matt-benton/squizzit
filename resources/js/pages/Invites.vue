<template>
    <div>
        <navbar></navbar>
        <section class="section">
            <div class="card">
                <header class="card-header">
                    <p class="card-header-title">Quiz Invite!</p>
                </header>
                <div class="card-content">
                    <div class="content">
                        You have been invited to take a quiz!
                        
                        My Fancy Quiz

                        This is a quiz that is extra faaancy.
                    </div>
                </div>
                <footer class="card-footer">
                    <a href="#" class="card-footer-item">Take Quiz</a>
                </footer>
            </div>
        </section>
    </div>
</template>
<script>
import Navbar from '../components/Navbar.vue'

export default {
    data() {
        return {

        }
    },
    components: {
        'navbar': Navbar
    }
}
</script>