<template>
    <div id="prod-new">
        <h2 class="mb-3">Add new product</h2>
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
            <div class="col-4">
                <form method="post" @submit.prevent="createProduct">
                    <fieldset>
                        <div class="form-group">
                            <label for="productName">Product Name</label>
                            <input type="text" class="form-control" id="productName" name="productName"
                                   v-model="productInputDto.productName" placeholder="Enter product name">
                        </div>
                        <div class="form-group">
                            <label for="unitPrice">Unit Price</label>
                            <input type="number" min="0.1" step="0.1" class="form-control" id="unitPrice"
                                   v-model="productInputDto.unitPrice" name="unitPrice" placeholder="Enter unit price">
                        </div>
                        <div class="form-group">
                            <label for="supplier_id">Supplier</label>
                            <select class="form-control" id="supplier_id" name="supplier_id"
                                    v-model="productInputDto.supplier_id">
                                <option v-for="supplier in suppliers" v-bind:value="supplier.id" :value="supplier.id">
                                    {{supplier.companyName}} ({{supplier.contactName}})
                                </option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success mr-3">Save</button>
                        <router-link to="/products" class="btn btn-dark">Cancel</router-link>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    import { ActiveNavbarItem } from "../../event-active-navbar-item";

    export default {
        data() {
            return {
                suppliers: [],
                errors: [],
                productInputDto: {
                    productName: '',
                    unitPrice: 0,
                    supplier_id: '',
                },
            }
        },

        async created() {
            ActiveNavbarItem.$emit("clicked-event", 'product');

            await this.fetchSuppliers();
        },

        methods: {
            async fetchSuppliers() {
                try {
                    let res = await fetch('/api/suppliers');
                    res = await res.json();

                    if (res.code === 200) {
                        this.suppliers = res.results;
                    }
                } catch (error) {
                    console.log('error', error);
                }
            },

            async createProduct() {
                this.errors = [];

                if (!this.productInputDto.productName) {
                    this.errors.push('Product name is required.');
                }
                if (!this.productInputDto.unitPrice) {
                    this.errors.push('Product unit price is required.');
                }
                if (!this.productInputDto.supplier_id) {
                    this.errors.push('Product supplier is required.');
                }
                if (this.productInputDto.unitPrice && (isNaN(this.productInputDto.unitPrice) || this.productInputDto.unitPrice < 0)) {
                    this.errors.push('Unit price must be a positive number.');
                }

                if (!this.errors.length) {
                    try {
                        let res = await fetch(`/api/product/store`, {
                            method: 'POST',
                            body: JSON.stringify(this.productInputDto),
                            headers: {
                                'Content-Type': 'application/json',
                            },
                        });

                        res = await res.json();

                        if (res.code === 201) {
                            alert('Product has been successfully created');
                            this.$router.push('/products');
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

</style>