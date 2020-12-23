<template>
    <v-main>
        <div class="row justify-content-center align-items-center h-25 py-5">
            <div class="col-sm-4 p-5">
                <div class="text-h3 font-weight-medium">Hungry? No problem, we can fix that for you</div>
                <p class="text-h6 font-weight-normal my-3">Start ordering with Food@Home and get your favorite food delivered in minutes!</p>
                <div class="d-flex flex-row align-center">
                    <router-link class="btn btn-primary btn-lg text-white" to="/login">Order now</router-link>
                    <span class="mx-5">or</span>
                    <router-link class="text-h6 text-dark" to="/register">Create an account</router-link>
                </div>
            </div>
            <div class="col-sm-4 p-5">
                <img src="/storage/static/homepage/on_the_way.svg" alt="Food@Home" width="600px">
            </div>
        </div>
        <div class="shape-divider-bottom">
                <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
                    <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z" class="shape-fill"></path>
                </svg>
        </div>
        <div class="pt-5 text-center text-white" id="instructions-section">
            <div class="text-h2 font-weight-bold py-5 mx-auto">Ordering made easy</div>
            <div class="row justify-content-center align-items-center py-5">
                <div class="col-sm-4 py-5">
                    <img src="/storage/static/homepage/add_to_cart.svg" alt="Food@Home" width="300px">
                </div>
                <div class="col-sm-4">
                    <div class="text-h5">First</div><div class="text-h3">make your choice</div>
                </div>
            </div>
            <div class="row justify-content-center align-items-center py-5">
                <div class="col-sm-4">
                    <div class="text-h5">Then</div><div class="text-h3">Pay for your order</div>
                </div>
                <div class="col-sm-4 py-5">
                    <img src="/storage/static/homepage/payment.svg" alt="Food@Home" width="450px">
                </div>
            </div>
            <div class="row justify-content-center align-items-center py-5">
                <div class="col-sm-4 py-5">
                    <img src="/storage/static/homepage/delivery.svg" alt="Food@Home" width="450px">
                </div>
                <div class="col-sm-4">
                    <div class="text-h5">Wait a bit and</div><div class="text-h3">get it delivered</div>
                </div>
            </div>
            <div class="text-h3 pt-5">Ready to start?</div>
            <router-link class="btn btn-light btn-lg text-dark mt-5" to="/products">Order now</router-link>
        </div>
        <div class="shape-divider-top pt-5">
                <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
                    <path d="M985.66,92.83C906.67,72,823.78,31,743.84,14.19c-82.26-17.34-168.06-16.33-250.45.39-57.84,11.73-114,31.07-172,41.86A600.21,600.21,0,0,1,0,27.35V120H1200V95.8C1132.19,118.92,1055.71,111.31,985.66,92.83Z" class="shape-fill"></path>
                </svg>
        </div>
        <div class="text-center pb-5">Made with <span class="text-danger">❤</span> in <a href="https://www.ipleiria.pt/" target="_blank">IPLeiria</a></div>
    </v-main>
</template>

<script>
export default {

}
</script>

<style>

</style>