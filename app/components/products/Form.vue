<script setup>
defineProps({
  selectedProduct: {
    type: Object,
    required: true
  },
  selectImage: {
    type: Function,
    required: true
  },
  save: {
    type: Function,
    required: true
  }
})
</script>
<template>
  <!-- Create Modal -->
  <div class="modal fade" id="product_save_modal" tabindex="-1">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <form @submit.prevent="save">
          <div class="modal-header">
            <h5 class="modal-title">Yeni Məhsul</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body row g-3">
            <div class="col-md-6">
              <label>Ad</label>
              <input v-model="selectedProduct.name" type="text" class="form-control" required>
            </div>
            <div class="col-md-6">
              <label>Qiymət (₼)</label>
              <input v-model.number="selectedProduct.price" type="number" class="form-control" required>
            </div>
            <div class="col-md-6">
              <label>Stok</label>
              <input v-model.number="selectedProduct.stock" type="number" class="form-control" required>
            </div>
            <div class="col-md-6">
              <label>Şəkil</label>
              <input type="file" @change="selectImage" class="form-control" accept="image/*">
            </div>
            <div class="col-12">
              <label>Açıqlama</label>
              <textarea v-model="selectedProduct.description" class="form-control" rows="3"></textarea>
            </div>
          </div>
          <div class="modal-footer">
            <button class="btn btn-secondary" @click.prevent="useModal('product_save_modal', false)">Bağla</button>
            <button class="btn btn-primary" type="submit">Yadda saxla</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>