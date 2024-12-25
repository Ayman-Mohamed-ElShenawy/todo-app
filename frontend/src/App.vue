<template>
  <div>
    <NavbarComponent />
    <div class="mt-5 text-center">
      <h2 v-if='!auth.loggedIn' class="text-secondary text-capitalize">
        welcome to Todo task maker please register or login to continue
      </h2>
    </div>
    <router-view />
  </div>
</template>
<script>
import apiClient from './api_client/client.js'
import { RouterView } from 'vue-router'
import NavbarComponent from './components/NavbarComponent.vue'
import useAuthStore from './stores/auth.js';
export default {
  components: { NavbarComponent, RouterView },
  name: 'app',
 
setup(){
  const auth = useAuthStore();
   return {auth};
 },
 mounted(){
this.auth.fetchUser();
 },
  data() {
    return {}
  },
  methods: {
    async fetchUser() {
      try {
        const response = await apiClient.get('/user')
        if (response && response.data) {
        }
      } catch (error) {
        if (error && error.response) {
          //
        } else {
          //
        }
      }
    },
  },
}
</script>
    
