<template>
    <div>
        <form @submit.prevent="createUser">
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" v-model="user.name" :class="{ 'is-invalid': errors.name }" required>
                <div class="invalid-feedback" v-if="errors.name">{{ errors.name[0] }}</div>
            </div>
            <div class="mb-3">
                <label for="age" class="form-label">Age</label>
                <input type="number" class="form-control" id="age" v-model="user.age" :class="{ 'is-invalid': errors.age }" required>
                <div class="invalid-feedback" v-if="errors.age">{{ errors.age[0] }}</div>
            </div>
            <div class="mb-3">
                <label for="address" class="form-label">Address</label>
                <input type="text" class="form-control" id="address" v-model="user.address" :class="{ 'is-invalid': errors.address }" required>
                <div class="invalid-feedback" v-if="errors.address">{{ errors.address[0] }}</div>
            </div>
            <div class="mb-3">
                <label for="city" class="form-label">City</label>
                <input type="text" class="form-control" id="city" v-model="user.city" :class="{ 'is-invalid': errors.city }" required>
                <div class="invalid-feedback" v-if="errors.city">{{ errors.city[0] }}</div>
            </div>
            <div class="mb-3">
                <label for="state" class="form-label">State</label>
                <input type="text" class="form-control" id="state" v-model="user.state" :class="{ 'is-invalid': errors.state }" required>
                <div class="invalid-feedback" v-if="errors.state">{{ errors.state[0] }}</div>
            </div>
            <div class="mb-3">
                <label for="country" class="form-label">Country</label>
                <input type="text" class="form-control" id="country" v-model="user.country" :class="{ 'is-invalid': errors.country }" required>
                <div class="invalid-feedback" v-if="errors.country">{{ errors.country[0] }}</div>
            </div>
            <div class="mb-3">
                <label for="zip" class="form-label">Zip Code</label>
                <input type="text" class="form-control" id="zip" v-model="user.zip" :class="{ 'is-invalid': errors.zip }" required>
                <div class="invalid-feedback" v-if="errors.zip">{{ errors.zip[0] }}</div>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>

    </div>
</template>

<script>
import { Modal } from 'bootstrap';
import axios from 'axios';

export default {
    data() {
        return {
            user: {
                name: '',
                age: '',
                address: '',
                city: '',
                state: '',
                zip: '',
            },
            errors: {},
        };
    },
    methods: {
        openModal() {
            const modal = new Modal(document.getElementById('userCreationModal'));
            modal.show();
        },
        createUser() {
            this.errors = {};
            const response = axios.post('/api/users', this.user)
                .then(response => {
                    console.log('User created successfully:', response.data);
                    this.$emit('user-created', response.data); // Emit event to update parent component if needed

                })
                .catch(error => {
                    console.log(error.response.status);
                    if (error.response && error.response.status === 422) {
                        this.errors = error.response.data.errors;
                    } else {
                        console.error('There was an error creating the user:', error);
                    }
                });
        },
    }
}
</script>

<style>
    .invalid-feedback {
        color: #dc3545;
    }
</style>