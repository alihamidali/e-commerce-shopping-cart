import { defineStore } from 'pinia';
import { ref, computed } from 'vue';
import type { CartItem } from '@/types';
import { cartApi } from '@/services/api';

export const useCartStore = defineStore('cart', () => {
    const cartItems = ref<CartItem[]>([]);
    const loading = ref(false);
    const error = ref<string | null>(null);

    const cartTotal = computed(() => {
        return cartItems.value.reduce((total, item) => {
            return total + parseFloat(item.subtotal);
        }, 0).toFixed(2);
    });

    const cartCount = computed(() => {
        return cartItems.value.reduce((count, item) => count + item.quantity, 0);
    });

    const fetchCart = async () => {
        loading.value = true;
        error.value = null;
        try {
            const response = await cartApi.getCart();
            cartItems.value = response.data.data;
        } catch (e: any) {
            error.value = e.message || 'Failed to fetch cart';
            console.error('Error fetching cart:', e);
        } finally {
            loading.value = false;
        }
    };

    const addToCart = async (productId: number, quantity: number = 1) => {
        try {
            await cartApi.addToCart(productId, quantity);
            await fetchCart();
            return true;
        } catch (e: any) {
            error.value = e.response?.data?.message || 'Failed to add to cart';
            return false;
        }
    };

    const updateQuantity = async (itemId: number, quantity: number) => {
        try {
            await cartApi.updateQuantity(itemId, quantity);
            await fetchCart();
            return true;
        } catch (e: any) {
            error.value = e.response?.data?.message || 'Failed to update quantity';
            return false;
        }
    };

    const removeItem = async (itemId: number) => {
        try {
            await cartApi.removeItem(itemId);
            await fetchCart();
            return true;
        } catch (e: any) {
            error.value = e.message || 'Failed to remove item';
            return false;
        }
    };

    const clearCart = async () => {
        try {
            await cartApi.clearCart();
            cartItems.value = [];
            return true;
        } catch (e: any) {
            error.value = e.message || 'Failed to clear cart';
            return false;
        }
    };

    return {
        cartItems,
        loading,
        error,
        cartTotal,
        cartCount,
        fetchCart,
        addToCart,
        updateQuantity,
        removeItem,
        clearCart,
    };
});
