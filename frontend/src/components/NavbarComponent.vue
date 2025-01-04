<template>
  <div>
    <nav class="navbar navbar-expand-lg bg-body-white shadow">
      <div class="container">
        <router-link class="navbar-brand" to="/">Todo Home</router-link>
        <button
          class="navbar-toggler"
          type="button"
          data-bs-toggle="collapse"
          data-bs-target="#navbarSupportedContent"
          aria-controls="navbarSupportedContent"
          aria-expanded="false"
          aria-label="Toggle navigation"
        >
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <div class="navbar-nav ms-auto mb-2 mb-lg-0">
            <div v-if="auth.loggedIn" class="d-flex">
              <li class="nav-item">
                <router-link class="nav-link" to="/todo">Todo</router-link>
              </li>
              <button @click="logout" class="nav-link">Logout</button>
            </div>
            <div v-else class="d-flex">
              <li class="nav-item">
                <router-link class="nav-link" to="/register">Register</router-link>
              </li>
              <li>
                <router-link class="nav-link" to="/login">Login</router-link>
              </li>
            </div>
          </div>
        </div>
      </div>
    </nav>
  </div>
</template>

<script>
import apiClient from '../api_client/client.js'
import useAuthStore from '../../src/stores/auth.js'

export default {
  name: 'NavbarComponent',
  data() {
    return {}
  },
  setup() {
    const auth = useAuthStore()
    return { auth }
  },

  mounted() {},

  methods: {
    async logout() {
      try {
        const response = await apiClient.post('logout')
        if (response && response.data) {
          // console.log(response.data);
          localStorage.removeItem('token')
          this.auth.fetchUser()
          this.auth.resetState()
          this.$router.push({ name: 'login' })
        }
      } catch (error) {
        if (error && error.response) {
          // console.log(error.response)
        }
      }
    },
  },
}
</script>
