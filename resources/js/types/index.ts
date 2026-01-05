export interface Product {
    id: number;
    name: string;
    price: string;
    stock_quantity: number;
    created_at?: string;
    updated_at?: string;
}

export interface CartItem {
    id: number;
    product: Product;
    quantity: number;
    subtotal: string;
    created_at?: string;
    updated_at?: string;
}

export interface OrderItem {
    id: number;
    product: Product;
    quantity: number;
    price: string;
    subtotal: string;
}

export interface Order {
    id: number;
    user_id: number;
    total_amount: string;
    items: OrderItem[];
    created_at: string;
    updated_at: string;
}

export interface ApiResponse<T> {
    data: T;
}
