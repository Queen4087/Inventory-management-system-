<template>
  <div class="space-y-6">
    <div class="flex items-center justify-between">
      <div>
        <h2 class="text-2xl font-bold text-gray-900">Categories</h2>
        <p class="text-sm text-gray-500">Manage product categories</p>
      </div>
      <Button @click="openCreateModal">Add Category</Button>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Name</th>
            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Description</th>
            <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase">Actions</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <tr v-for="cat in categories" :key="cat.id" class="hover:bg-gray-50">
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ cat.name }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ cat.description }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
              <button @click="openEditModal(cat)" class="text-blue-600 hover:text-blue-900 mr-4">Edit</button>
              <button @click="deleteCategory(cat.id)" class="text-red-600 hover:text-red-900">Delete</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <Modal v-model="isModalOpen" :title="isEditing ? 'Edit Category' : 'Add Category'">
      <form class="space-y-4" @submit.prevent="saveCategory">
        <Input label="Name *" v-model="form.name" required />
        <Input label="Description" v-model="form.description" />
      </form>
      <template #footer>
        <Button variant="secondary" @click="isModalOpen = false">Cancel</Button>
        <Button @click="saveCategory">Save</Button>
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

const categories = ref([])
const isModalOpen = ref(false)
const isEditing = ref(false)
const form = ref({ id: null, name: '', description: '' })

const fetchCategories = async () => {
  try {
    const res = await api.get('/categories')
    categories.value = res.data
  } catch (e) {
    console.error(e)
  }
}

const openCreateModal = () => {
  isEditing.value = false
  form.value = { id: null, name: '', description: '' }
  isModalOpen.value = true
}

const openEditModal = (cat) => {
  isEditing.value = true
  form.value = { ...cat }
  isModalOpen.value = true
}

const saveCategory = async () => {
  try {
    if (isEditing.value) {
      await api.put(`/categories/${form.value.id}`, form.value)
    } else {
      await api.post('/categories', form.value)
    }
    isModalOpen.value = false
    await fetchCategories()
  } catch (e) {
    alert('Error saving category')
  }
}

const deleteCategory = async (id) => {
  if (confirm('Are you sure?')) {
    await api.delete(`/categories/${id}`)
    await fetchCategories()
  }
}

onMounted(fetchCategories)
</script>
