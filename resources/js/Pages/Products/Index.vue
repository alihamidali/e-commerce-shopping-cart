<script setup lang="ts">
import { onMounted } from 'vue';
import { useProductStore } from '@/stores/productStore';
import { useCartStore } from '@/stores/cartStore';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

const productStore = useProductStore();
const cartStore = useCartStore();

onMounted(() => {
    productStore.fetchProducts();
    cartStore.fetchCart();
});

const handleAddToCart = async (productId: number) => {
    const success = await cartStore.addToCart(productId, 1);
    if (success) {
        alert('Product added to cart successfully!');
    } else {
        alert(cartStore.error || 'Failed to add product to cart');
    }
};
</script>

<template>
    <Head title="Products" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold text-gray-800">Products</h2>
                <a :href="route('cart.index')" class="text-primary-600 hover:text-primary-700">
                    Cart ({{ cartStore.cartCount }})
                </a>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div v-if="productStore.loading" class="text-center py-8">
                    <p class="text-gray-500">Loading products...</p>
                </div>

                <div v-else-if="productStore.error" class="text-center py-8">
                    <p class="text-danger">{{ productStore.error }}</p>
                </div>

                <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    <div
                        v-for="product in productStore.products"
                        :key="product.id"
                        class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow"
                    >
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">{{ product.name }}</h3>
                        <p class="text-2xl font-bold text-primary-600 mb-4">${{ product.price }}</p>
                        <p class="text-sm text-gray-600 mb-4">
                            Stock: <span :class="product.stock_quantity <= 10 ? 'text-warning' : 'text-success'">
                                {{ product.stock_quantity }}
                            </span>
                        </p>
                        <button
                            @click="handleAddToCart(product.id)"
                            :disabled="product.stock_quantity === 0"
                            class="w-full bg-primary-500 hover:bg-primary-600 text-white font-medium py-2 px-4 rounded disabled:bg-gray-300 disabled:cursor-not-allowed transition-colors"
                        >
                            {{ product.stock_quantity === 0 ? 'Out of Stock' : 'Add to Cart' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
