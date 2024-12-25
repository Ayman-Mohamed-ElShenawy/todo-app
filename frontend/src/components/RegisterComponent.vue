<template>
  <div class="container mt-5">
    <div class="row">
      <div class="col-lg-7 mx-auto">
        <div class="card">
          <div class="card-header">
            <h4>Register</h4>
            <div class="card-body">
              <div v-if="Object.keys(errors).length" class="alert alert-danger">
                <ul>
                  <li v-for="(error, index) in errors" :key="index">{{ error }}</li>
                </ul>
              </div>
              <form @submit.prevent="register">
                <div class="mb-3">
                  <label for="">Name</label>
                  <input
                    type="text"
                    v-model="form.name"
                    class="mb-1 form-control"
                    placeholder="your name"
                  />
                </div>
                <div class="mb-3">
                  <label for="">Email</label>
                  <input
                    type="email"
                    v-model="form.email"
                    class="mb-1 form-control"
                    placeholder="yourmail@example.com"
                  />
                </div>
                <div class="mb-3">
                  <label for="">Password</label>
                  <input
                    type="password"
                    v-model="form.password"
                    class="mb-1 form-control"
                    placeholder="********"
                  />
                </div>
                <div class="mb-3">
                  <label for="">Confirm password</label>
                  <input
                    type="password"
                    v-model="form.password_confirmation"
                    class="mb-1 form-control"
                    placeholder="********"
                  />
                </div>

                <div class="mb-3">
                  <input type="submit" class="btn btn-primary" value="Register" />
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
import useAuthStore from '../stores/auth.js'

export default {
  data() {
    return {
      form: {
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
      },
      errors: [],
    }
  },
  setup() {
    const auth = useAuthStore()
    return { auth }
  },
  methods: {
    async register() {
      try {
        const response = await apiClient.post('register', {
          name: this.form.name,
          email: this.form.email,
          password: this.form.password,
          password_confirmation: this.form.password_confirmation,
        })
        if (response && response.data) {
          this.errors = []
          this.$router.push('/login')
        }
      } catch (error) {
        if (error && error.response) {
          this.errors = Object.values(error.response.data.errors||{}).flat()
        } else {
          this.errors = []
        }
      }
    },
  },
}
</script>
