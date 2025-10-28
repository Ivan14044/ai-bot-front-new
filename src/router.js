import { createRouter, createWebHistory } from 'vue-router';
const LoginPage = () => import('./components/auth/LoginPage.vue');
const RegisterPage = () => import('./components/auth/RegisterPage.vue');
const AuthCallback = () => import('./components/auth/AuthCallback.vue');
const MainPage = () => import('./pages/MainPage.vue');
const ProfilePage = () => import('./pages/account/ProfilePage.vue');
const ServicePage = () => import('./pages/ServicePage.vue');
const SubscriptionsPage = () => import('./pages/account/SubscriptionsPage.vue');
const SessionStart = () => import('./pages/SessionStart.vue');
const ForgotPasswordPage = () => import('./components/auth/ForgotPasswordPage.vue');
const ResetPasswordPage = () => import('./components/auth/ResetPasswordPage.vue');
const CheckoutPage = () => import('./pages/CheckoutPage.vue');
const ContentPage = () => import('./pages/ContentPage.vue');
const NotFound = () => import('./pages/NotFound.vue');
const ArticlesAll = () => import('./pages/articles/ArticlesAll.vue');
const ArticleDetails = () => import('./pages/articles/ArticleDetails.vue');
import { useAuthStore } from './stores/auth';
import { usePageStore } from './stores/pages';

const routes = [
    { path: '/', component: MainPage },
    { path: '/login', component: LoginPage, meta: { requiresGuest: true } },
    {
        path: '/register',
        component: RegisterPage,
        meta: { requiresGuest: true }
    },
    {
        path: '/forgot-password',
        component: ForgotPasswordPage,
        meta: { requiresGuest: true }
    },
    {
        path: '/reset-password/:token',
        component: ResetPasswordPage,
        meta: { requiresGuest: true },
        props: true
    },
    {
        path: '/auth/callback',
        component: AuthCallback
    },
    {
        path: '/profile',
        component: ProfilePage,
        meta: { requiresAuth: true }
    },
    {
        path: '/service/:id',
        component: ServicePage
    },
    {
        path: '/articles',
        component: ArticlesAll,
        meta: { isArticlesList: true }
    },
    {
        path: '/articles/page/:page',
        component: ArticlesAll,
        meta: { isArticlesList: true }
    },
    {
        path: '/categories/:id',
        component: ArticlesAll,
        meta: { isArticlesList: true }
    },
    {
        path: '/categories/:id/page/:page',
        component: ArticlesAll,
        meta: { isArticlesList: true }
    },
    {
        path: '/articles/:id',
        component: ArticleDetails
    },
    {
        path: '/checkout',
        component: CheckoutPage,
        meta: { requiresAuth: true }
    },
    {
        path: '/subscriptions',
        component: SubscriptionsPage,
        meta: { requiresAuth: true }
    },
    {
        path: '/session-start/:id?',
        component: SessionStart,
        meta: { requiresAuth: true }
    },
    {
        path: '/:slug(.*)*',
        name: 'dynamic',
        component: ContentPage,
        meta: { isDynamic: true }
    },
    {
        path: '/404',
        component: NotFound
    }
];

const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes,
    scrollBehavior(to, from, savedPosition) {
        // 1) Back/forward — use browser-saved position
        if (savedPosition) {
            try {
                sessionStorage.setItem('articlesUsedSavedPosition', '1');
            } catch (_) {}
            return savedPosition;
        }

        // Defer scrolling for article/category list pages until data is loaded in component
        if (to.meta && to.meta.isArticlesList) {
            return false;
        }

        // 2) Anchors — scroll to element (avoid smooth for Safari)
        if (to.hash) {
            return { el: to.hash, top: 0, left: 0, behavior: 'auto' };
        }

        // 3) Default — ensure layout is ready (Safari/Firefox quirks)
        return new Promise(resolve => {
            // Double RAF + timeout for Safari font reflow and header
            setTimeout(() => {
                requestAnimationFrame(() => {
                    requestAnimationFrame(() => {
                        resolve({ top: 0, left: 0, behavior: 'auto' });
                    });
                });
            }, 10);
        });
    }
});

router.beforeEach(async (to, from, next) => {
    const authStore = useAuthStore();
    if (!authStore.user && authStore.token) {
        await authStore.fetchUser();
    }

    if (to.meta.requiresAuth && !authStore.user) {
        return next({
            path: '/login',
            query: { redirect: to.fullPath }
        });
    } else if (to.meta.requiresGuest && authStore.user) {
        return next('/');
    }

    // Save the route user came from when first entering articles/categories list
    try {
        const wasInArticles = Boolean(from?.meta?.isArticlesList);
        const isGoingToArticles = Boolean(to?.meta?.isArticlesList);
        if (!wasInArticles && isGoingToArticles) {
            // store the entry point before entering the list
            sessionStorage.setItem('articlesEntryFrom', from?.fullPath || '/');
        }
    } catch (_) {
        // ignore storage errors (Safari private mode, etc.)
    }

    if (to.meta.isDynamic) {
        const slug = to.path.replace(/^\/|\/$/g, '');
        const pageStore = usePageStore();
        if (!pageStore.pages.length) {
            await pageStore.fetchData();
        }

        if (typeof pageStore.pages[slug] !== 'undefined') {
            pageStore.setPage(pageStore.pages[slug]);
            return next();
        } else {
            return next('/404');
        }
    }

    next();
});

export default router;
