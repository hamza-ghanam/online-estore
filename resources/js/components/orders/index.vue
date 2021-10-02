<template>
    <div id="orders">
        <h2 class="mb-3">Orders Management</h2>
        <div class="float-right mb-4">
            <router-link class="btn btn-info" to="/orders/create">Add new order</router-link>
        </div>
        <table class="table table-hover mb-3">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Order Number</th>
                <th scope="col">Customer</th>
                <th scope="col">Total Amount</th>
                <th scope="col">Order Date</th>
                <th scope="col">Last Update</th>
                <th>Modify</th>
                <th>Delete</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(order, index) in orders" v-bind:key="order.id">
                <td>{{index + 1}}</td>
                <td>{{order.orderNumber}}</td>
                <td>{{order.customer.firstName + ' ' + order.customer.lastName}}</td>
                <td>{{order.totalAmount}}</td>
                <td>{{new Date(order.orderDate).toDateString()}}</td>
                <td>{{new Date(order.updated_at).toDateString()}}</td>
                <td>
                    <router-link :to="'/orders/'+order.id" class="alert-link">
                        <i class="far fa-1x fa-edit"></i>
                    </router-link>
                </td>
                <td>
                    <a href="#" @click="deleteOrder(order.id)" class="alert-link text-danger">
                        <i class="fas fa-1x fa-trash-alt"></i>
                    </a>
                </td>
            </tr>
            </tbody>
        </table>
        <nav aria-label="Page navigation example">
            <ul class="pagination">
                <li v-bind:class="[{disabled: !pagination.prev_page_url, 'page-item': true}]">
                    <a class="page-link" href="#" @click="fetchProducts(pagination.prev_page_url)">Previous</a>
                </li>
                <li class="page-item disabled">
                    <a class="page-link text-dark" href="#">
                        Page {{pagination.current_page}} of {{pagination.last_page}}
                    </a>
                </li>
                <li v-bind:class="[{disabled: !pagination.next_page_url, 'page-item': true}]">
                    <a class="page-link" href="#" @click="fetchProducts(pagination.next_page_url)">Next</a>
                </li>
            </ul>
        </nav>
    </div>
</template>

<script>
    import {ActiveNavbarItem} from "../../event-active-navbar-item";

    export default {
        data() {
            return {
                orders: [],
                pagination: {},
            }
        },

        async created() {
            ActiveNavbarItem.$emit("clicked-event", 'order');

            await this.fetchOrders();
        },

        methods: {
            async fetchOrders(page_url) {
                try {
                    const vm = this;
                    page_url = page_url || 'api/orders';

                    let res = await fetch(page_url);
                    res = await res.json();

                    if (res.code === 200) {
                        this.orders = res.results.data;
                        vm.makePagination({
                            current_page: res.results.current_page,
                            last_page: res.results.last_page,
                            next_page_url: res.results.next_page_url,
                            prev_page_url: res.results.prev_page_url,
                        });
                    }
                } catch (error) {
                    console.log('error', error);
                }
            },

            makePagination(meta) {
                const pagination = {
                    current_page: meta.current_page,
                    last_page: meta.last_page,
                    next_page_url: meta.next_page_url,
                    prev_page_url: meta.prev_page_url,
                };

                this.pagination = pagination;
            },

            async deleteOrder(id) {
                if (confirm('Are you sure?')) {
                    try {
                        let res = await fetch(`api/order/${id}`, {
                            method: 'DELETE'
                        });

                        res = await res.json();

                        if (res.code === 200) {
                            alert('Order has been successfully deleted.');
                            this.fetchOrders();
                        }
                    } catch (error) {
                        console.log('error', error);
                    }
                }
            },
        }
    }
</script>

<style scoped>

</style>