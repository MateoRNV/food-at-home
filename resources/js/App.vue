<template>
  <div>

    <index></index>
    
    <router-link to="/products">Products</router-link>

    <router-view></router-view>
  </div>
</template>

<script>
import ProductComponent from './components/products'
import IndexComponent from './components/index'
export default {
  components: {
    'product': ProductComponent,
    'index': IndexComponent
  }
}
</script>