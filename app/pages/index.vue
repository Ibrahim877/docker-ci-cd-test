<script setup>
const {data: products} = await useAsyncData('get_products', async () => {
  return await useAxios()
      .get('products')
      .then(res => res.data?.products)
      .catch(err => console.log(err));
})
</script>
<template>
  <div class="container mt-5">
    <ProductsList :productData="products"/>
  </div>
</template>