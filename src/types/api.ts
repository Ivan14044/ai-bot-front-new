// API Response types
export interface ApiError {
    message: string;
    errors?: Record<string, string[]>;
}

export interface ApiResponse<T = any> {
    data: T;
    message?: string;
}

// Auth types
export interface User {
    id: number;
    email: string;
    name?: string;
    lang?: string;
    extension_settings?: Record<string, any>;
}

export interface AuthResponse {
    token: string;
    user: User;
}

export interface LoginCredentials {
    email: string;
    password: string;
}

export interface RegisterCredentials {
    name: string;
    email: string;
    password: string;
    password_confirmation: string;
}

// Services types
export interface Service {
    id: number;
    name: string;
    description?: string;
    logo: string;
    price?: number;
    currency?: string;
}

// Cart types
export interface CartItem {
    id: number;
    name: string;
    price: number;
    quantity?: number;
}

// Subscription types
export interface Subscription {
    id: number;
    service_id: number;
    status: 'active' | 'cancelled' | 'expired';
    auto_renew: boolean;
    expires_at: string;
    created_at: string;
}

// Payment types
export interface PaymentResponse {
    url: string;
    session_id?: string;
}

export interface PaymentData {
    services: number[];
    subscriptionTypes: Record<number, string>;
    promocode?: string;
}

