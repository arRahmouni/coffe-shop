<!-- views/dashboard/Categories.vue -->
<template>
    <div class="bg-white rounded-lg shadow-sm p-6">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Categories</h1>
            <button
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
                        <th>ID</th>
                        <th>Name</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <!-- Table Body -->
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="item in items" :key="item.id">
                        <td>{{ item.id }}</td>
                        <td>{{ item.name }}</td>
                        <td>
                            <span :class="statusClasses(item.status)">
                                {{ item.status }}
                            </span>
                        </td>
                        <td>
                            <!-- Action buttons -->
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
    </div>
</template>

<script setup>
    import usePaginationFetcher from "@/composables/usePaginationFetcher";
    import PaginationControls from "../components/PaginationControls.vue";

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
        "bg-green-100 text-green-800": status === "Active",
        "bg-red-100 text-red-800": status === "Inactive",
    });
</script>

<!--
                        <td class="px-6 py-4 whitespace-nowrap">
                            <button class="text-blue-500 hover:text-blue-700 mr-4">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                </svg>
                            </button>
                            <button class="text-red-500 hover:text-red-700">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </td>
-->
