<template>
  <div class="container mt-5">
    <div class="row">
      <div class="col-lg-6 mx-auto">
        <div class="card">
          <div class="card-header">
            <h4>Login</h4>
            <div class="card-body">
              <div v-if="Object.keys(errors).length" class="alert alert-danger">
                <ul>
                  <li v-for="(error, index) in errors" :key='index'>{{ error }}</li>
                </ul>
              </div>
              <form @submit.prevent="login">
                <div class="mb-3">
                  <label for="">Email</label>
                  <input
                    type="email"
                    v-model="form.email"
                    class="form-control"
                    placeholder="yourmail@example.com"
                  />
                </div>
                <div class="mb-3">
                  <label for="">Password</label>
                  <input
                    type="password"
                    v-model="form.password"
                    class="form-control"
                    placeholder="********"
                  />
                </div>
                <div class="mb-3">
                  <input type="submit" class="btn btn-primary" value="Login" />
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import apiClient from '../api_client/client.js'

import useAuthStore from '../../src/stores/auth.js'
export default {
  setup() {
    const auth = useAuthStore()
    return { auth }
  },

  data() {
    return {
      form: {
        email: '',
        password: '',
      },
      errors: [],
    }
  },
   mounted() {
  
  },
  methods: {
    async login() {
      try {
        const response = await apiClient.post('login', {
          email: this.form.email,
          password: this.form.password,
        })
        if (response && response.data.token) {
          // console.log(response.data);
          localStorage.setItem('token', response.data.token)
          this.errors = []
          this.auth.fetchUser()
          this.$router.push('/todo')
        }
      } catch (error) {
        if (error && error.response) {
          // console.log(error.response);
          this.errors = Object.values(error.response.data.errors).flat();
        
        }
        else{
          this.errors = [];
        }
      }
    },
 
  },
}
</script>
