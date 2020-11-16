<template>
  <div>

    <div>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark py-4">
      <div class="container-xl">
        <a class="navbar-brand" href="#">Food@Home</a>
        <button
          class="navbar-toggler"
          type="button"
          data-toggle="collapse"
          data-target="#navbarsExample07XL"
          aria-controls="navbarsExample07XL"
          aria-expanded="false"
          aria-label="Toggle navigation"
        >
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarsExample07XL">
          <ul class="navbar-nav ml-auto">
            <li class="nav-item active">
              <a class="nav-link" href="#"
                >Home <span class="sr-only">(current)</span></a
              >
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">Menu</a>
            </li>
            <li class="nav-item dropdown">
              <a
                class="nav-link dropdown-toggle"
                href="#"
                id="dropdown07XL"
                data-toggle="dropdown"
                >Jose</a
              >
              <div class="dropdown-menu" aria-labelledby="dropdown07XL">
                <a class="dropdown-item" href="#">Orders</a>
                <a class="dropdown-item" href="#">Account</a>
                <a class="dropdown-item" href="#">Logout</a>
              </div>
            </li>
          </ul>
        </div>
      </div>
    </nav>
    <div class="container">
      <h1>Hello, world!</h1>
    </div>
</div>  

    <index></index>
    
    <router-link to="/products">Products</router-link>

    <router-view></router-view>
  </div>
</template>

<script>
import ProductComponent from './components/products'
export default {
  components: {
    'product': ProductComponent,
  }
}
</script>