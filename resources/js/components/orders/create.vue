<template>
    <div id="order-new">
        <h2 class="mb-3">Add new order</h2>
        <div class="row" v-if="errors.length">
            <div class="col-12">
                <div class="alert alert-dismissible alert-danger">
                    <strong>Please correct the following error(s):</strong>
                    <ul>
                        <li v-for="error in errors">{{ error }}</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-7">
                <form method="post" @submit.prevent="createOrder">
                    <fieldset>
                        <div class="form-group">
                            <label for="customer_id">Customer</label>
                            <select class="form-control" id="customer_id" name="customer_id"
                                    v-model="orderInputDto.customer_id">
                                <option v-for="customer in customers" v-bind:value="customer.id" :value="customer.id">
                                    {{customer.firstName + ' ' + customer.lastName}}
                                </option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="orderDate">Order date</label>
                            <input type="date" class="form-control" id="orderDate" v-model="orderInputDto.orderDate"
                                   name="orderDate"/>
                        </div>
                        <div class="form-group">
                            <label for="orderDate">Order items (select from products list below)</label>
                            <div v-if="!orderInputDto.orderItems.length">
                                <h4 class="text-danger no-items">
                                    No items yet!
                                </h4>
                            </div>
                            <div v-else>
                                <div class="form-group row" v-for="(orderItem, index) in orderInputDto.orderItems">
                                    <input type="hidden" v-model="orderItem.product_id"/>
                                    <div class="col-sm-3">
                                        <input type="text" readonly="readonly" class="form-control-plaintext"
                                               :id="'prodName_'+orderItem.product_id" v-model="orderItem.prodName">
                                    </div>
                                    <label :for="'quantity_'+orderItem.product_id"
                                           class="col-sm-2 col-form-label">Quantity</label>
                                    <div class="col-sm-2">
                                        <input type="number" class="form-control" min="1" step="1"
                                               :id="'quantity_'+orderItem.product_id" v-model="orderItem.quantity"
                                               @input="updateTotalAmount">
                                    </div>
                                    <label :for="'quantity_'+orderItem.product_id"
                                           class="col-sm-2 col-form-label">Unit price</label>
                                    <div class="col-sm-2">
                                        <input type="number" class="form-control" min="0.1" step="0.1"
                                               v-model="orderItem.unitPrice" @input="updateTotalAmount">
                                    </div>
                                    <div class="col-sm-1">
                                        <a href="#" @click="deleteOrderItem(index)" class="alert-link text-danger">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="totalAmount" class="col-sm-2 col-form-label">
                                    <strong>Total amount</strong>
                                </label>
                                <div class="col-sm-10">
                                    <input type="text" readonly="" class="form-control-plaintext" id="totalAmount"
                                           v-model="totalAmount">
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success mr-3">Save</button>
                        <router-link to="/orders" class="btn btn-dark">Cancel</router-link>
                    </fieldset>
                </form>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-12">
                <h3>Products list</h3>
                <table class="table table-hover mb-3">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Product Name</th>
                        <th scope="col">Unit Price</th>
                        <th scope="col">Supplier Company Name</th>
                        <th scope="col">Creation Date</th>
                        <th scope="col">Last Update</th>
                        <th>Add</th>
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
                            <a href="#" @click="addToOrderItems(product.id)" class="alert-link">
                                <i class="far fa-plus-square"></i>
                            </a>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</template>

<script>
    import {ActiveNavbarItem} from "../../event-active-navbar-item";

    export default {
        data() {
            return {
                customers: [],
                products: [],
                addedProducts: [],
                errors: [],
                orderItem: {
                    prodName: '',
                    product_id: '',
                    quantity: 0,
                    unitPrice: 0,
                },

                orderInputDto: {
                    customer_id: '',
                    orderDate: '',
                    orderItems: [],
                },

                totalAmount: 0,
            }
        },

        async created() {
            ActiveNavbarItem.$emit("clicked-event", 'order');
            await this.fetchCustomers();
            await this.fetchProducts();
        },

        methods: {
            async fetchCustomers() {
                try {
                    let res = await fetch('/api/customers');
                    res = await res.json();

                    if (res.code === 200) {
                        this.customers = res.results;
                    }
                } catch (error) {
                    console.log('error', error);
                }
            },

            async fetchProducts() {
                try {
                    const page_url = '/api/products/all';

                    let res = await fetch(page_url);
                    res = await res.json();

                    if (res.code === 200) {
                        this.products = res.results;
                    }
                } catch (error) {
                    console.log('error', error);
                }
            },

            addToOrderItems(productId) {
                const product = this.products.find((prod) => prod.id === productId);
                this.orderInputDto.orderItems.push({
                    prodName: product.productName,
                    product_id: product.id,
                    quantity: 0,
                    unitPrice: product.unitPrice,
                });

                this.addedProducts.push(product);
                this.products = this.products.filter((prod) => {
                    return prod.id !== product.id;
                })
                    .sort((prod1, prod2) => (prod1.created_at < prod2.created_at ? 1 : -1));

            },

            deleteOrderItem(index) {
                const deletedItem = this.orderInputDto.orderItems.splice(index, 1)[0];
                const restoredProduct = this.addedProducts.find((prod) => prod.id === deletedItem.product_id);
                this.products.push(restoredProduct);
                this.addedProducts = this.addedProducts.filter((prod) => {
                    return prod.id !== restoredProduct.id;
                });

                this.products = this.products.sort((prod1, prod2) => (prod1.created_at < prod2.created_at ? 1 : -1));
            },

            updateTotalAmount() {
                this.totalAmount = 0;
                if (this.orderInputDto.orderItems.length) {
                    this.orderInputDto.orderItems.forEach((item) => {
                        this.totalAmount += (item.quantity * item.unitPrice);
                    });
                }

                this.totalAmount = this.totalAmount.toFixed(2);
            },

            async createOrder() {
                this.errors = [];

                if (!this.orderInputDto.customer_id) {
                    this.errors.push('Customer is required.');
                }
                if (!this.orderInputDto.orderDate) {
                    this.errors.push('Order date is required.');
                }
                if (!this.orderInputDto.orderItems.length) {
                    this.errors.push('Order should have at least one item.');
                }

                // Check duplicated products
                const seen = new Set();
                var hasDuplicates = this.orderInputDto.orderItems.some((orderItem) => {
                    return seen.size === seen.add(orderItem.product_id).size;
                });

                if (hasDuplicates) {
                    this.errors.push('Product should appear once in an order.');
                }

                if (this.orderInputDto.orderItems.length) {
                    for (const item of this.orderInputDto.orderItems) {
                        if (!item.quantity || item.quantity === 0) {
                            this.errors.push('Item quantity is required and should be positive.');
                            break;
                        }

                        if (!item.unitPrice || item.unitPrice === 0) {
                            this.errors.push('Item unit price is required and should be positive.');
                            break;
                        }
                    }
                }

                if (!this.errors.length) {
                    for (const item of this.orderInputDto.orderItems) {
                        delete item.prodName;
                    }

                    try {
                        let res = await fetch(`/api/order/store`, {
                            method: 'POST',
                            body: JSON.stringify(this.orderInputDto),
                            headers: {
                                'Content-Type': 'application/json',
                            },
                        });

                        res = await res.json();

                            if (res.code === 201) {
                            alert('Order has been successfully created');
                            this.$router.push('/orders');
                        } else if (res.code === 404 || res.code === 500) {
                            this.errors.push(res.msg);
                        } else {
                            if (res.results && !Array.isArray(res.results)) {
                                res.results = Object.values(res.results) || [];
                            }

                            for (let i = 0; i < res.results.length; i++) {
                                if (Array.isArray(res.results[i])) {
                                    for (let j = 0; j < res.results[i].length; j++) {
                                        this.errors.push(res.results[i][j]);
                                    }
                                } else {
                                    this.errors.push(res.results[i]);
                                }
                            }
                        }
                    } catch (e) {
                        console.log('Error: ', e)
                    }
                }
            }
        }
    }
</script>

<style scoped>
    .alert-link {
        font-size: 20px !important;
    }

    .no-items {
        text-align: center;
    }
</style>