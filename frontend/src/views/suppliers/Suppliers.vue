<template>
  <div class="space-y-6">
    <div class="flex items-center justify-between">
      <div>
        <h2 class="text-2xl font-bold text-gray-900">Suppliers</h2>
        <p class="text-sm text-gray-500">Manage your product suppliers</p>
      </div>
      <Button @click="openCreateModal">Add Supplier</Button>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Name</th>
            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Phone</th>
            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Email</th>
            <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase">Actions</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <tr v-for="sup in suppliers" :key="sup.id" class="hover:bg-gray-50">
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ sup.name }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ sup.phone }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ sup.email }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
              <button @click="openEditModal(sup)" class="text-blue-600 hover:text-blue-900 mr-4">Edit</button>
              <button @click="deleteSupplier(sup.id)" class="text-red-600 hover:text-red-900">Delete</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <Modal v-model="isModalOpen" :title="isEditing ? 'Edit Supplier' : 'Add Supplier'">
      <form class="space-y-4" @submit.prevent="saveSupplier">
        <Input label="Name *" v-model="form.name" required />
        <Input label="Phone" v-model="form.phone" />
        <Input label="Email" type="email" v-model="form.email" />
        <Input label="Address" v-model="form.address" />
      </form>
      <template #footer>
        <Button variant="secondary" @click="isModalOpen = false">Cancel</Button>
        <Button @click="saveSupplier">Save</Button>
      </template>
    </Modal>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import api from '../../utils/axios'
import Button from '../../components/ui/Button.vue'
import Input from '../../components/ui/Input.vue'
import Modal from '../../components/ui/Modal.vue'

const suppliers = ref([])
const isModalOpen = ref(false)
const isEditing = ref(false)
const form = ref({ id: null, name: '', phone: '', email: '', address: '' })

const fetchSuppliers = async () => {
  try {
    const res = await api.get('/suppliers')
    suppliers.value = res.data
  } catch (e) {
    console.error(e)
  }
}

const openCreateModal = () => {
  isEditing.value = false
  form.value = { id: null, name: '', phone: '', email: '', address: '' }
  isModalOpen.value = true
}

const openEditModal = (sup) => {
  isEditing.value = true
  form.value = { ...sup }
  isModalOpen.value = true
}

const saveSupplier = async () => {
  try {
    if (isEditing.value) {
      await api.put(`/suppliers/${form.value.id}`, form.value)
    } else {
      await api.post('/suppliers', form.value)
    }
    isModalOpen.value = false
    await fetchSuppliers()
  } catch (e) {
    alert('Error saving supplier')
  }
}

const deleteSupplier = async (id) => {
  if (confirm('Are you sure?')) {
    await api.delete(`/suppliers/${id}`)
    await fetchSuppliers()
  }
}

onMounted(fetchSuppliers)
</script>
