<template>
  <div class="min-h-screen bg-slate-50 text-slate-900 py-8">
    <div class="mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8">
      <header class="mb-8">
        <h1 class="text-3xl font-semibold">Administración de Productos</h1>
        <p class="mt-2 text-sm text-slate-600">Crea, edita y elimina productos usando la API de Laravel.</p>
      </header>

      <div class="grid gap-8 lg:grid-cols-[1.4fr_0.9fr]">
        <ProductosList :key="refreshKey" @edit="handleEdit" @refresh="handleRefresh" />
        <ProductoForm :product="selectedProduct" @saved="handleSaved" @cancel="handleCancel" />
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import ProductosList from './components/ProductosList.vue'
import ProductoForm from './components/ProductoForm.vue'

const selectedProduct = ref(null)
const refreshKey = ref(0)

const handleEdit = (producto) => {
  selectedProduct.value = producto
}

const handleSaved = () => {
  selectedProduct.value = null
  refreshKey.value += 1
}

const handleRefresh = () => {
  refreshKey.value += 1
}

const handleCancel = () => {
  selectedProduct.value = null
}
</script>
