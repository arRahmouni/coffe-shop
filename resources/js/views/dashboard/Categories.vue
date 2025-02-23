<!-- views/dashboard/Categories.vue -->
<template>
    <div class="bg-white rounded-lg shadow-sm p-6">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Categories</h1>
            <button
                @click="openCreateModal"
                class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600"
            >
                Add New
            </button>
        </div>

        <!-- Loading/Error States -->
        <div v-if="isLoading" class="text-center py-4">Loading...</div>
        <div v-if="error" class="text-center py-4 text-red-500">
            {{ error }}
        </div>

        <!-- Data Table -->
        <div v-if="!isLoading && !error">
            <table class="min-w-full divide-y divide-gray-200">
                <!-- Table Headers -->
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Image</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>

                <!-- Table Body -->
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="item in items" :key="item.id">
                        <td class="px-6 py-4 whitespace-nowrap">{{ item.id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <img
                                v-if="item.image"
                                :src="item.image"
                                :alt="item.name"
                                class="w-16 h-16 object-cover rounded"
                            >
                            <div v-else class="w-16 h-16 bg-gray-100 rounded flex items-center justify-center">
                                <span class="text-gray-400 text-xs">Coffe Shop</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ item.name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span :class="statusClasses(item.is_active)" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full">
                                {{ item.is_active ? "Active" : "Inactive" }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap space-x-2">
                            <!-- Edit Button -->
                            <button
                                @click="openEditModal(item)"
                                class="text-blue-500 hover:text-blue-700"
                                title="Edit"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                </svg>
                            </button>

                            <!-- Delete Button -->
                            <button
                                @click="confirmDelete(item.id)"
                                class="text-red-500 hover:text-red-700"
                                title="Delete"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>

            <!-- Reusable Pagination -->
            <PaginationControls
                :current-page="currentPage"
                :total-pages="totalPages"
                @next-page="nextPage"
                @prev-page="prevPage"
            />
        </div>

        <!-- Create Category Modal -->
        <div
            v-if="isCreateModalOpen"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center"
        >
            <div class="bg-white rounded-lg p-6 w-1/3">
                <h2 class="text-xl font-bold mb-4">Create Category</h2>
                <form @submit.prevent="createCategory">
                    <!-- Name Field -->
                    <div class="mb-4">
                        <label class="block text-gray-700 mb-2">Name</label>
                        <input
                            v-model="newCategory.name"
                            type="text"
                            class="w-full px-3 py-2 border rounded-lg"
                            @input="generateSlug"
                        />
                    </div>

                    <!-- Slug Field -->
                    <div class="mb-4">
                        <label class="block text-gray-700 mb-2">Slug</label>
                        <input
                            v-model="newCategory.slug"
                            type="text"
                            class="w-full px-3 py-2 border rounded-lg"
                        />
                    </div>

                    <!-- Active Status -->
                    <div class="mb-4">
                        <label class="flex items-center space-x-2">
                            <input
                                type="checkbox"
                                v-model="newCategory.is_active"
                                class="rounded text-blue-500"
                            />
                            <span class="text-gray-700">Active</span>
                        </label>
                    </div>

                    <!-- Image Upload Section -->
                    <ImageUpload
                        v-model="newCategory.image"
                        :field-name="'category_image'"
                        :label="'Category Image'"
                        :accepted-extensions="'image/jpeg, image/png'"
                        :max-size="10"
                        @file-selected="handleFileSelected"
                        @file-removed="handleFileRemoved"
                    >
                    </ImageUpload>

                    <!-- Form Actions -->
                    <div class="flex justify-end">
                        <button
                            type="button"
                            @click="closeCreateModal"
                            class="mr-2 px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg"
                        >
                            Cancel
                        </button>
                        <button
                            type="submit"
                            class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600"
                        >
                            Save
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Edit Category Modal -->
        <div
            v-if="isEditModalOpen"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center"
        >
            <div class="bg-white rounded-lg p-6 w-1/3">
                <h2 class="text-xl font-bold mb-4">Edit Category</h2>
                <form @submit.prevent="updateCategory">
                    <!-- Name Field -->
                    <div class="mb-4">
                        <label class="block text-gray-700 mb-2">Name</label>
                        <input
                            v-model="newCategory.name"
                            type="text"
                            class="w-full px-3 py-2 border rounded-lg"
                            required
                            @input="generateSlug"
                        />
                    </div>

                    <!-- Slug Field -->
                    <div class="mb-4">
                        <label class="block text-gray-700 mb-2">Slug</label>
                        <input
                            v-model="newCategory.slug"
                            type="text"
                            class="w-full px-3 py-2 border rounded-lg"
                            required
                        />
                    </div>

                    <!-- Active Status -->
                    <div class="mb-4">
                        <label class="flex items-center space-x-2">
                            <input
                                type="checkbox"
                                v-model="newCategory.is_active"
                                class="rounded text-blue-500"
                            />
                            <span class="text-gray-700">Active</span>
                        </label>
                    </div>

                    <!-- Image Upload with existing image -->
                    <ImageUpload
                        v-model="newCategory.image"
                        :field-name="'category_image'"
                        :label="'Category Image'"
                        :accepted-extensions="'image/jpeg, image/png'"
                        :max-size="10"
                        :existing-image="existingImageUrl"
                        @file-selected="handleFileSelected"
                        @file-removed="handleFileRemoved"
                    />

                    <!-- Form Actions -->
                    <div class="flex justify-end">
                        <button
                            type="button"
                            @click="closeEditModal"
                            class="mr-2 px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg"
                        >
                            Cancel
                        </button>
                        <button
                            type="submit"
                            class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600"
                        >
                            Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
    import usePaginationFetcher from "@/composables/usePaginationFetcher";
    import PaginationControls from "@/views/components/PaginationControls.vue";
    import { ref } from "vue";
    import axios from "axios";
    import { useToast } from "vue-toastification";
    import ImageUpload from "@/views/components/Form/ImageUpload.vue";
    import Swal from 'sweetalert2';
    import 'sweetalert2/src/sweetalert2.scss';

    const {
        items,
        isLoading,
        error,
        currentPage,
        totalPages,
        fetchData,
        nextPage,
        prevPage,
    } = usePaginationFetcher("categories");

    // Fetch data on mount
    fetchData();

    // Status styling (component-specific)
    const statusClasses = (status) => ({
        "bg-green-100 text-green-800": status === true,
        "bg-red-100 text-red-800": status === false,
    });

    const toast = useToast();
    const isCreateModalOpen = ref(false);
    const newCategory = ref({
        name: "",
        slug: "",
        is_active: true,
        image: null,
    });

    // Auto-generate slug from name
    const generateSlug = () => {
        newCategory.value.slug = newCategory.value.name
            .toLowerCase()
            .replace(/ /g, "-")
            .replace(/[^\w-]+/g, "");
    };

    const openCreateModal = () => {
        isCreateModalOpen.value = true;
    };

    const closeCreateModal = () => {
        isCreateModalOpen.value = false;
        newCategory.value = {
            name: "",
            slug: "",
            is_active: true,
            image: null,
        };
    };

    const createCategory = async () => {
        try {
            const formData = new FormData();
            formData.append("name", newCategory.value.name);
            formData.append("slug", newCategory.value.slug);
            formData.append("is_active", newCategory.value.is_active ? 1 : 0);
            if (newCategory.value.image) {
                formData.append("image", newCategory.value.image);
            }

            await axios.post("/categories", formData, {
                headers: {
                    "Content-Type": "multipart/form-data",
                },
            });

            toast.success("Category created successfully!");
            closeCreateModal();
            fetchData();
        } catch (error) {
            toast.error("Error creating category");
            console.error(error);
        }
    };

    const isEditModalOpen = ref(false);
    const editingCategoryId = ref(null);
    const existingImageUrl = ref(null);

    // Edit Category Functions
    const openEditModal = (category) => {
        editingCategoryId.value = category.id;
        existingImageUrl.value = category.image;
        newCategory.value = {
            name: category.name,
            slug: category.slug,
            is_active: category.is_active,
            image: null
        };
        isEditModalOpen.value = true;
    };

    const updateCategory = async () => {
        try {
            const formData = new FormData();
            formData.append("name", newCategory.value.name);
            formData.append("slug", newCategory.value.slug);
            formData.append("is_active", newCategory.value.is_active ? 1 : 0);
            formData.append("_method", "PUT");

            if (newCategory.value.image) {
                formData.append("image", newCategory.value.image);
            }

            await axios.post(`/categories/${editingCategoryId.value}`, formData, {
                headers: {
                    "Content-Type": "multipart/form-data",
                },
            });

            toast.success("Category updated successfully!");
            closeEditModal();
            fetchData();
        } catch (error) {
            toast.error("Error updating category");
            console.error(error);
        }
    };

    // Delete Category Function
    const confirmDelete = async (id) => {
        const result = await Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        });

        if (result.isConfirmed) {
            try {
                await axios.delete(`/categories/${id}`);

                Swal.fire(
                    'Deleted!',
                    'Category has been deleted.',
                    'success'
                );

                fetchData(); // Refresh the data
            } catch (error) {
                Swal.fire(
                    'Error!',
                    'Failed to delete category.',
                    'error'
                );
                console.error(error);
            }
        }
    };

    const closeEditModal = () => {
        isEditModalOpen.value = false;
        editingCategoryId.value = null;
        existingImageUrl.value = null;
        newCategory.value = {
            name: "",
            slug: "",
            is_active: true,
            image: null
        };
    };

    const handleFileSelected = ({ file, fieldName }) => {
        console.log('File selected for field:', fieldName, file);
    };

    const handleFileRemoved = (fieldName) => {
        console.log('File removed from field:', fieldName);
    };
</script>
