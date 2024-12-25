import apiClient from '../api_client/client';
import { defineStore } from 'pinia';



const useAuthStore = defineStore('auth', ({
  state: () => ({
    todos: [],
    links: [],
    deletedTodos: [],
    user: null,
    loggedIn: false,
    sessionExpired:false,
  }),

  actions: {
    async fetchUser() {
      try {
        const response = await apiClient.get('/user');
        if (response && response.data) {
          this.loggedIn = true;
          this.user = response.data;
          this.sessionExpired=false;
        }
      } catch (error) {
         if (error.response && error.response.data) {
          this.sessionExpired=[];
        }
        if(error.response && error.response.status===401){
          this.sessionExpired = true;
        }
      }
    },
    async fetchTodos(url = "/todo") {
      try {
        const response = await apiClient.get(url)
        if (response && response.data) {
          this.todos = response.data.message.data||[];
          this.links = response.data.message.links||[];

        }
      } catch (error) {
        if (error && error.data) {
          // console.log(error);
        } else {
          //
        }
      }
    },
    resetState(){
      this.todos = [];
      this.links = [];
      this.user= null,
      this.loggedIn= false,
      this.sessionExpired = false;
    },
    async fetchDeletedTodos(url = '/deletedtodos') {
      try {
        const response = await apiClient.get(url);
        if (response && response.data) {
          this.deletedTodos = response.data.message.data;
          this.links = response.data.message.links;
          // console.log(response.data);
        }
      } catch (error) {
        if (error && error.response) {
          // console.log(error.response.data.message);

        }
      }
    },

  }
}));

export default useAuthStore;