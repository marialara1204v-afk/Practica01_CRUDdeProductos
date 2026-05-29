<template>
  <div class="bg-white shadow rounded-lg p-6">
    <div class="flex items-center justify-between mb-4">
      <div>
        <h2 class="text-lg font-semibold">Productos</h2>
        <p class="text-sm text-slate-500">Lista de productos disponibles.</p>
      </div>
      <button
        type="button"
        class="text-sm font-medium text-blue-600 hover:text-blue-800"
        @click="loadProductos"
      >
        Recargar
      </button>
    </div>

    <div v-if="error" class="mb-4 rounded border border-red-300 bg-red-50 p-3 text-sm text-red-700">
      {{ error }}
    </div>

    <div v-if="loading" class="py-10 text-center text-sm text-slate-500">
      Cargando productos...
    </div>

    <div v-else>
      <table class="min-w-full divide-y divide-slate-200 text-sm">
        <thead class="bg-slate-50 text-slate-600 uppercase tracking-[0.12em] text-xs">
          <tr>
            <th class="px-4 py-3 text-left">Nombre</th>
            <th class="px-4 py-3 text-left">Descripción</th>
            <th class="px-4 py-3 text-right">Precio</th>
            <th class="px-4 py-3 text-right">Stock</th>
            <th class="px-4 py-3 text-right">Acciones</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-200">
          <tr v-for="producto in products" :key="producto.id">
            <td class="px-4 py-3">{{ producto.nombre }}</td>
            <td class="px-4 py-3 text-slate-600">{{ producto.descripcion }}</td>
            <td class="px-4 py-3 text-right">{{ formatoPrecio(producto.precio) }}</td>
            <td class="px-4 py-3 text-right">{{ producto.stock }}</td>
            <td class="px-4 py-3 text-right space-x-2">
              <button
                type="button"
                class="rounded px-3 py-1 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700"
                @click="handleEdit(producto)"
              >
                Editar
              </button>
              <button
                type="button"
                class="rounded px-3 py-1 text-sm font-medium text-white bg-red-600 hover:bg-red-700"
                @click="handleDelete(producto)"
              >
                Eliminar
              </button>
            </td>
          </tr>
          <tr v-if="products.length === 0">
            <td colspan="5" class="px-4 py-8 text-center text-slate-500">No hay productos registrados.</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue'
import { getProductos, deleteProducto } from '../services/productoService'

const props = defineProps({
  refreshKey: {
    type: [String, Number],
    default: null,
  },
})

const emit = defineEmits(['edit', 'refresh'])

const products = ref([])
const loading = ref(false)
const error = ref('')

const formatoPrecio = (value) => {
  return new Intl.NumberFormat('es-ES', {
    style: 'currency',
    currency: 'EUR',
    minimumFractionDigits: 2,
  }).format(value)
}

const loadProductos = async () => {
  loading.value = true
  error.value = ''

  try {
    const response = await getProductos()
    products.value = response.data
  } catch (err) {
    error.value = 'Error al cargar los productos.'
  } finally {
    loading.value = false
  }
}

const handleEdit = (producto) => {
  emit('edit', producto)
}

const handleDelete = async (producto) => {
  const confirmed = window.confirm(`¿Eliminar el producto ${producto.nombre}?`)
  if (!confirmed) {
    return
  }

  loading.value = true
  error.value = ''

  try {
    await deleteProducto(producto.id)
    emit('refresh')
    await loadProductos()
  } catch (err) {
    error.value = 'Error al eliminar el producto.'
  } finally {
    loading.value = false
  }
}

onMounted(loadProductos)
watch(() => props.refreshKey, loadProductos)
</script>
