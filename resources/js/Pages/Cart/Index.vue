<script setup lang="ts">
import { onMounted } from 'vue';
import { useCartStore } from '@/stores/cartStore';
import { router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

const cartStore = useCartStore();

onMounted(() => {
    cartStore.fetchCart();
});

const handleUpdateQuantity = async (itemId: number, quantity: number) => {
    await cartStore.updateQuantity(itemId, quantity);
};

const handleRemoveItem = async (itemId: number) => {
    if (confirm('Are you sure you want to remove this item?')) {
        await cartStore.removeItem(itemId);
    }
};

const handleCheckout = async () => {
    try {
        const response = await fetch('/api/orders', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
        });

        if (response.ok) {
            alert('Order placed successfully!');
            router.visit('/orders');
        } else {
            const data = await response.json();
            alert(data.message || 'Failed to place order');
        }
    } catch (error) {
        alert('Failed to place order');
    }
};
</script>

<template>
    <Head title="Shopping Cart" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold text-gray-800">Shopping Cart</h2>
                <a :href="route('products.index')" class="text-primary-600 hover:text-primary-700">
                    Continue Shopping
                </a>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div v-if="cartStore.loading" class="text-center py-8">
                    <p class="text-gray-500">Loading cart...</p>
                </div>

                <div v-else-if="cartStore.cartItems.length === 0" class="text-center py-12">
                    <p class="text-gray-500 text-lg mb-4">Your cart is empty</p>
                    <a
                        :href="route('products.index')"
                        class="inline-block bg-primary-500 hover:bg-primary-600 text-white font-medium py-2 px-6 rounded"
                    >
                        Browse Products
                    </a>
                </div>

                <div v-else class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Cart Items -->
                    <div class="lg:col-span-2 space-y-4">
                        <div
                            v-for="item in cartStore.cartItems"
                            :key="item.id"
                            class="bg-white rounded-lg shadow-md p-6 flex items-center gap-4"
                        >
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-gray-800">{{ item.product.name }}</h3>
                                <p class="text-gray-600">${{ item.product.price }} each</p>
                            </div>

                            <div class="flex items-center gap-4">
                                <div class="flex items-center gap-2">
                                    <button
                                        @click="handleUpdateQuantity(item.id, item.quantity - 1)"
                                        :disabled="item.quantity <= 1"
                                        class="bg-gray-200 hover:bg-gray-300 disabled:bg-gray-100 disabled:cursor-not-allowed px-3 py-1 rounded"
                                    >
                                        -
                                    </button>
                                    <span class="text-lg font-medium px-4">{{ item.quantity }}</span>
                                    <button
                                        @click="handleUpdateQuantity(item.id, item.quantity + 1)"
                                        :disabled="item.quantity >= item.product.stock_quantity"
                                        class="bg-gray-200 hover:bg-gray-300 disabled:bg-gray-100 disabled:cursor-not-allowed px-3 py-1 rounded"
                                    >
                                        +
                                    </button>
                                </div>

                                <p class="text-lg font-bold text-primary-600 w-24 text-right">
                                    ${{ item.subtotal }}
                                </p>

                                <button
                                    @click="handleRemoveItem(item.id)"
                                    class="text-danger hover:text-red-700"
                                >
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Order Summary -->
                    <div class="lg:col-span-1">
                        <div class="bg-white rounded-lg shadow-md p-6 sticky top-4">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Order Summary</h3>

                            <div class="space-y-2 mb-4">
                                <div class="flex justify-between text-gray-600">
                                    <span>Items ({{ cartStore.cartCount }})</span>
                                    <span>${{ cartStore.cartTotal }}</span>
                                </div>
                                <div class="flex justify-between text-gray-600">
                                    <span>Shipping</span>
                                    <span>Free</span>
                                </div>
                            </div>

                            <div class="border-t pt-4 mb-6">
                                <div class="flex justify-between text-xl font-bold">
                                    <span>Total</span>
                                    <span class="text-primary-600">${{ cartStore.cartTotal }}</span>
                                </div>
                            </div>

                            <button
                                @click="handleCheckout"
                                class="w-full bg-primary-500 hover:bg-primary-600 text-white font-medium py-3 px-4 rounded transition-colors"
                            >
                                Proceed to Checkout
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
