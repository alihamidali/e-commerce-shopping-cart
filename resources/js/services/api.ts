import axios from 'axios';
import type { Product, CartItem, Order } from '@/types';

const api = axios.create({
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
    },
});

export const productApi = {
    getAll: () => api.get<{ data: Product[] }>('/api/products'),
    get: (id: number) => api.get<{ data: Product }>(`/api/products/${id}`),
    create: (data: Partial<Product>) => api.post<{ data: Product }>('/api/products', data),
    update: (id: number, data: Partial<Product>) => api.put<{ data: Product }>(`/api/products/${id}`, data),
    delete: (id: number) => api.delete(`/api/products/${id}`),
};

export const cartApi = {
    getCart: () => api.get<{ data: CartItem[] }>('/api/cart'),
    addToCart: (product_id: number, quantity: number) =>
        api.post<{ data: CartItem }>('/api/cart', { product_id, quantity }),
    updateQuantity: (id: number, quantity: number) =>
        api.patch<{ data: CartItem }>(`/api/cart/${id}`, { quantity }),
    removeItem: (id: number) => api.delete(`/api/cart/${id}`),
    clearCart: () => api.delete('/api/cart'),
    getTotal: () => api.get<{ total: string }>('/api/cart/total'),
};

export const orderApi = {
    getOrders: () => api.get<{ data: Order[] }>('/api/orders'),
    getOrder: (id: number) => api.get<{ data: Order }>(`/api/orders/${id}`),
    createOrder: () => api.post<{ data: Order }>('/api/orders'),
};
