<template>
    <div id="home">
        <h1>Welcome to dashboard</h1>
        <div class="row mt-4">
            <div class="col-3">
                <div class="alert alert-dismissible alert-secondary">
                    <h3>Products</h3>
                    <p>Total: {{productsCount}}</p>
                </div>
            </div>
            <div class="col-3">
                <div class="alert alert-dismissible alert-success">
                    <h3>Customers</h3>
                    <p>Total: {{customersCount}}</p>
                </div>
            </div>
            <div class="col-3">
                <div class="alert alert-dismissible alert-info">
                    <h3>Suppliers</h3>
                    <p>Total: {{suppliersCount}}</p>
                </div>
            </div>
            <div class="col-3">
                <div class="alert alert-dismissible alert-danger">
                    <h3>Orders</h3>
                    <p>Total: {{ordersCount}}</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {ActiveNavbarItem} from "../event-active-navbar-item";

    export default {
        data() {
            return {
                productsCount: 0,
                customersCount: 0,
                suppliersCount: 0,
                ordersCount: 0,
            }
        },

        async created() {
            ActiveNavbarItem.$emit("clicked-event", 'home');

            try {
                let res = await fetch('/api/products');
                res = await res.json();
                this.productsCount = res.results.total || 0;

                res = await fetch('/api/orders');
                res = await res.json();
                this.ordersCount = res.results.total || 0;

                res = await fetch('/api/customers');
                res = await res.json();
                this.customersCount = res.results.length || 0;

                res = await fetch('/api/suppliers');
                res = await res.json();
                this.suppliersCount = res.results.length || 0;
            } catch (error) {
                console.log('error', error);
            }

        },
    }
</script>

<style scoped>

</style>