<template>
    <div id="prods">
        <h2 class="mb-3">Products Management</h2>
        <div class="float-right mb-4">
            <router-link class="btn btn-info" to="/products/create">Add new product</router-link>
        </div>
        <table class="table table-hover mb-3">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Product Name</th>
                <th scope="col">Unit Price</th>
                <th scope="col">Supplier Company Name</th>
                <th scope="col">Creation Date</th>
                <th scope="col">Last Update</th>
                <th>Modify</th>
                <th>Delete</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(product, index) in products" v-bind:key="product.id">
                <td>{{index + 1}}</td>
                <td>{{product.productName}}</td>
                <td>{{product.unitPrice}}</td>
                <td>{{product.supplier.companyName}}</td>
                <td>{{new Date(product.created_at).toDateString()}}</td>
                <td>{{new Date(product.updated_at).toDateString()}}</td>
                <td>
                    <router-link :to="'/products/'+product.id" class="alert-link">
                        <i class="far fa-1x fa-edit"></i>
                    </router-link>
                </td>
                <td>
                    <a href="#" @click="deleteProduct(product.id)" class="alert-link text-danger">
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
    import { ActiveNavbarItem } from "../../event-active-navbar-item";

    export default {
        data() {
            return {
                products: [],
                productId: '',
                pagination: {},
            }
        },

        async created() {
            ActiveNavbarItem.$emit("clicked-event", 'product');

            await this.fetchProducts();
        },

        methods: {
            async fetchProducts(page_url) {
                try {
                    const vm = this;
                    page_url = page_url || '/api/products';

                    let res = await fetch(page_url);
                    res = await res.json();

                    if (res.code === 200) {
                        this.products = res.results.data;
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

            async deleteProduct(id) {
                if (confirm('Are you sure?')) {
                    try {
                        let res = await fetch(`api/product/${id}`, {
                            method: 'DELETE'
                        });

                        res = await res.json();

                        if (res.code === 200) {
                            alert('Product has been successfully deleted.');
                            this.fetchProducts();
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