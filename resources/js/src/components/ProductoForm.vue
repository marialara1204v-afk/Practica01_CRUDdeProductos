<template>
  <div class="bg-white shadow rounded-lg p-6">
    <div class="mb-4">
      <h2 class="text-lg font-semibold">{{ mode === 'edit' ? 'Editar producto' : 'Nuevo producto' }}</h2>
      <p class="text-sm text-slate-500">Completa el formulario para {{ mode === 'edit' ? 'actualizar' : 'crear' }} un producto.</p>
    </div>

    <div v-if="status.message" :class="statusClass" class="mb-4 rounded border px-4 py-3 text-sm">
      {{ status.message }}
    </div>

    <form @submit.prevent="handleSubmit" class="space-y-4">
      <div>
        <label class="block text-sm font-medium text-slate-700">Nombre</label>
        <input
          v-model="form.nombre"
          type="text"
          class="mt-1 block w-full rounded border border-slate-300 bg-white px-3 py-2 text-sm shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
          required
        />
      </div>

      <div>
        <label class="block text-sm font-medium text-slate-700">Descripción</label>
        <textarea
          v-model="form.descripcion"
          class="mt-1 block w-full rounded border border-slate-300 bg-white px-3 py-2 text-sm shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
          rows="4"
        ></textarea>
      </div>

      <div class="grid gap-4 md:grid-cols-2">
        <div>
          <label class="block text-sm font-medium text-slate-700">Precio</label>
          <input
            v-model.number="form.precio"
            type="number"
            step="0.01"
            min="0"
            class="mt-1 block w-full rounded border border-slate-300 bg-white px-3 py-2 text-sm shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
            required
          />
        </div>

        <div>
          <label class="block text-sm font-medium text-slate-700">Stock</label>
          <input
            v-model.number="form.stock"
            type="number"
            min="0"
            class="mt-1 block w-full rounded border border-slate-300 bg-white px-3 py-2 text-sm shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
            required
          />
        </div>
      </div>

      <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-end">
        <button
          v-if="mode === 'edit'"
          type="button"
          class="rounded bg-slate-200 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-300"
          @click="handleCancel"
        >
          Cancelar
        </button>

        <button
          type="submit"
          class="rounded bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700"
        >
          {{ mode === 'edit' ? 'Guardar cambios' : 'Crear producto' }}
        </button>
      </div>
    </form>
  </div>
</template>

<script setup>
import { reactive, ref, watch, computed } from 'vue'
import { createProducto, updateProducto } from '../services/productoService'

const props = defineProps({
  product: {
    type: Object,
    default: null,
  },
})

const emit = defineEmits(['saved', 'cancel'])

const form = reactive({
  nombre: '',
  descripcion: '',
  precio: 0,
  stock: 0,
})

const status = ref({ message: '', type: '' })

const mode = computed(() => (props.product && props.product.id ? 'edit' : 'create'))

const resetForm = () => {
  form.nombre = ''
  form.descripcion = ''
  form.precio = 0
  form.stock = 0
}

watch(
  () => props.product,
  (producto) => {
    if (producto && producto.id) {
      form.nombre = producto.nombre || ''
      form.descripcion = producto.descripcion || ''
      form.precio = producto.precio ?? 0
      form.stock = producto.stock ?? 0
    } else {
      resetForm()
    }
    status.value = { message: '', type: '' }
  },
  { immediate: true }
)

const statusClass = computed(() => {
  return status.value.type === 'success'
    ? 'rounded border border-emerald-300 bg-emerald-50 text-emerald-700'
    : 'rounded border border-red-300 bg-red-50 text-red-700'
})

const handleSubmit = async () => {
  status.value = { message: '', type: '' }

  try {
    if (mode.value === 'edit' && props.product?.id) {
      await updateProducto(props.product.id, {
        nombre: form.nombre,
        descripcion: form.descripcion,
        precio: form.precio,
        stock: form.stock,
      })
      status.value = { message: 'Producto actualizado correctamente.', type: 'success' }
    } else {
      await createProducto({
        nombre: form.nombre,
        descripcion: form.descripcion,
        precio: form.precio,
        stock: form.stock,
      })
      status.value = { message: 'Producto creado correctamente.', type: 'success' }
      resetForm()
    }

    emit('saved')
  } catch (err) {
    status.value = {
      message: 'Error al guardar el producto. Verifica los datos e intenta nuevamente.',
      type: 'error',
    }
  }
}

const handleCancel = () => {
  resetForm()
  emit('cancel')
}
</script>
