<template>
  <table class="table table-striped">
    <thead>
      <tr>
        <th>Name</th>
        <th>Type</th>
        <th>Description</th>
        <th>price</th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="product in products" :key="product.id">
        <td>{{ product.name }}</td>
        <td>{{ product.tyoe }}</td>
        <td>{{ product.description }}</td>
        <td>{{ product.price }}</td>
      </tr>
    </tbody>
  </table>
</template>

<script>
export default {

  mounted() {
    if (this.$root.products.length === 0) {
      axios.get('api/products')
        .then(response => {
          this.$root.products = response.data.data
          this.products = this.$root.products
        })
    } else {
      this.products = this.$root.products
    }
  },
};
</script>
