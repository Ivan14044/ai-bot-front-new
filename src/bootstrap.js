import axios from 'axios';
import i18n from './i18n';

const apiBase = import.meta.env.VITE_API_BASE || 'https://api.subcloudy.com';

axios.defaults.baseURL = apiBase;
axios.defaults.withCredentials = true;
axios.defaults.timeout = 30000; // 30 seconds timeout
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Request interceptor - adds locale header
axios.interceptors.request.use((config) => {
    const currentLocale = i18n?.global?.locale?.value ?? i18n?.global?.locale;
    if (currentLocale) {
        const headers = config.headers;
        if (headers && typeof headers.set === 'function') {
            if (!headers.has('X-Locale')) {
                headers.set('X-Locale', currentLocale);
            }
        } else {
            config.headers = {
                ...(headers || {}),
                'X-Locale': headers?.['X-Locale'] ?? currentLocale
            };
        }
    }
    return config;
});

// Response interceptor - handles errors globally
axios.interceptors.response.use(
    (response) => response,
    async (error) => {
        const originalRequest = error.config;

        // Handle 401 Unauthorized - token might be expired
        if (error.response?.status === 401 && !originalRequest._retry) {
            originalRequest._retry = true;
            
            // Try to refresh token or logout
            try {
                const authStore = await import('./stores/auth').then(m => m.useAuthStore());
                if (authStore.token) {
                    await authStore.fetchUser();
                    // Retry the original request with new token
                    originalRequest.headers['Authorization'] = `Bearer ${authStore.token}`;
                    return axios(originalRequest);
                }
            } catch (refreshError) {
                console.error('Token refresh failed:', refreshError);
                // Redirect to login if refresh fails
                if (window.location.pathname !== '/login') {
                    window.location.href = '/login';
                }
            }
        }

        // Handle network errors
        if (!error.response) {
            console.error('Network error:', error.message);
        }

        return Promise.reject(error);
    }
);

export default axios;
