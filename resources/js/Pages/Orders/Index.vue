<script setup lang="ts">
import { ref, onMounted } from 'vue';
import type { Order } from '@/types';
import { orderApi } from '@/services/api';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

const orders = ref<Order[]>([]);
const loading = ref(false);
const error = ref<string | null>(null);

const fetchOrders = async () => {
    loading.value = true;
    error.value = null;
    try {
        const response = await orderApi.getOrders();
        orders.value = response.data.data;
    } catch (e: any) {
        error.value = e.message || 'Failed to fetch orders';
        console.error('Error fetching orders:', e);
    } finally {
        loading.value = false;
    }
};

onMounted(() => {
    fetchOrders();
});

const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
};
</script>

<template>
    <Head title="Orders" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold text-gray-800">Order History</h2>
                <a :href="route('products.index')" class="text-primary-600 hover:text-primary-700">
                    Continue Shopping
                </a>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div v-if="loading" class="text-center py-8">
                    <p class="text-gray-500">Loading orders...</p>
                </div>

                <div v-else-if="error" class="text-center py-8">
                    <p class="text-danger">{{ error }}</p>
                </div>

                <div v-else-if="orders.length === 0" class="text-center py-12">
                    <p class="text-gray-500 text-lg mb-4">You haven't placed any orders yet</p>
                    <a
                        :href="route('products.index')"
                        class="inline-block bg-primary-500 hover:bg-primary-600 text-white font-medium py-2 px-6 rounded"
                    >
                        Start Shopping
                    </a>
                </div>

                <div v-else class="space-y-6">
                    <div
                        v-for="order in orders"
                        :key="order.id"
                        class="bg-white rounded-lg shadow-md overflow-hidden"
                    >
                        <!-- Order Header -->
                        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="text-sm text-gray-600">Order #{{ order.id }}</p>
                                    <p class="text-sm text-gray-500">{{ formatDate(order.created_at) }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm text-gray-600">Total</p>
                                    <p class="text-xl font-bold text-primary-600">${{ order.total_amount }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Order Items -->
                        <div class="px-6 py-4">
                            <div class="space-y-3">
                                <div
                                    v-for="item in order.items"
                                    :key="item.id"
                                    class="flex justify-between items-center py-2 border-b border-gray-100 last:border-0"
                                >
                                    <div class="flex-1">
                                        <p class="font-medium text-gray-800">{{ item.product.name }}</p>
                                        <p class="text-sm text-gray-500">
                                            Quantity: {{ item.quantity }} × ${{ item.price }}
                                        </p>
                                    </div>
                                    <p class="font-semibold text-gray-800">${{ item.subtotal }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
