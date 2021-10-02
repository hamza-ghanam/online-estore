import AppIndex from './components/index.vue';

import ProductsIndex from './components/products/index.vue';
import ProductsCreate from './components/products/create.vue';
import ProductsEdit from './components/products/edit.vue';

import OrdersIndex from './components/orders/index.vue';
import OrdersCreate from './components/orders/create.vue';
import OrdersEdit from './components/orders/edit.vue';

export const routes = [
    {
        path: '/',
        name: 'AppIndex',
        component: AppIndex
    },
    {
        path: '/products',
        name: 'ProductsIndex',
        component: ProductsIndex
    },
    {
        path: '/products/create',
        name: 'ProductCreate',
        component: ProductsCreate
    },
    {
        path: '/products/:id',
        name: 'ProductUpdate',
        component: ProductsEdit
    },
    {
        path: '/orders',
        name: 'OrdersIndex',
        component: OrdersIndex
    },
    {
        path: '/orders/create',
        name: 'OrdersCreate',
        component: OrdersCreate
    },
    {
        path: '/orders/:id',
        name: 'OrdersUpdate',
        component: OrdersEdit
    },
];