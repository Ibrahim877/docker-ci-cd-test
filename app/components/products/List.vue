<script setup>
import {useProductList} from "~/composables/ProductComposable.js";

const props = defineProps({
  productData: {
    required: true,
  }
})

const {
  products,
  newProduct,
  selectedProduct,
  selectImage,
  selectProduct,
  save,
  removeProduct
} = useProductList(props.productData)

</script>
<template>
  <ProductsForm :selectedProduct="selectedProduct" :selectImage="selectImage" :save="save"/>

  <h2 class="mb-4">Məhsullar</h2>
  <button @click.prevent="selectProduct(newProduct); useModal('product_save_modal')" class="btn btn-primary mb-3">
    Yeni Məhsul
  </button>


  <!-- Product Table -->
  <table class="table table-bordered">
    <thead>
    <tr>
      <th>#</th>
      <th>Ad</th>
      <th>Qiymət</th>
      <th>Stok</th>
      <th>Şəkil</th>
      <th>Əməliyyatlar</th>
    </tr>
    </thead>
    <tbody>
    <tr v-for="(product, index) in products.data" :key="product.id">
      <td>{{ index + 1 }}</td>
      <td>{{ product.name }}</td>
      <td>{{ product.price }} ₼</td>
      <td>{{ product.stock }}</td>
      <td>
        <img :src="useFileUrl(product.image)" alt="" width="50" height="50"/>
      </td>
      <td>
        <button @click.prevent="selectProduct(product); useModal('product_save_modal')"
                class="btn btn-sm btn-warning me-2">Redaktə
        </button>
        <button @click.prevent="removeProduct(product.id)" class="btn btn-sm btn-danger">Sil</button>
      </td>
    </tr>
    </tbody>
  </table>
</template>