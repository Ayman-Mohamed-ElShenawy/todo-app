<template>
  <div>
    <div  v-if="fetchingTodos" >
      <loader/>
    </div>
    <div v-else-if='Object.keys(auth.todos).length'>
      <div
        v-for="(todo, index) in auth.todos"
        :key="index"
        class="my-2 pt-3 pb-4 position-relative fw-bold rounded border border-primary-subtle bg-primary-subtle text-capitalize"
      >
        <div class="todo-body px-2">
          <div class="d-flex align-items-center gap-1">
            <span>
              <input @click='updateTodoStatus(todo.id)'  :checked="todo.status==='completed'" type="checkbox" name="btnradio" id="btnradio1" />
            </span>
            <div>
              <span :class="{'text-decoration-line-through':todo.status==='completed'}" > {{ todo.title }}</span>

              <div  :class="{'description col-12 col-lg-10 text-decoration-line-through':todo.status==='completed'}">
                {{ todo.description }}
              </div>
            </div>
          </div>
          <div class="action position-absolute todo-btns">
            <router-link :to='{path:"/update/"+todo.id}' 
            name="update" class="btn btn-success me-1">
              <i class="fas fa-pen-to-square"></i>
            </router-link>
            <button @click='deleteTodos(todo.id)' name="delete" style="background: blue" class="btn text-white">
              <i class="fas fa-trash-can"></i>
            </button>
          </div>
        </div>
      </div>
      
         <!-- Pagination -->
      <div class="d-flex justify-content-center mt-3">
        <button
          v-for="(link, index) in auth.links"
          :key="index"
          :disabled="!link.url"
          @click="fetchPage(link.url)"
          class="btn btn-outline-primary mx-1"
          v-html="link.label"
        ></button>
      </div>    
    </div>
    <div v-else class="px-2">
      <h4 class="my-2 text-warning">No Pending Todos Found</h4>
    </div>
  </div>
</template>

<script>
//  import { onMounted, ref, watch } from 'vue'
import useAuthStore from '../../src/stores/auth.js'
import apiClient from '../api_client/client.js'
import Loader from './Loader.vue'
export default {
  components: { Loader },
  data() {
    return {
      completed:false,
      fetchingTodos: false,
    }
  },
  setup() {
    const auth = useAuthStore()
    return { auth }
  },
  mounted() {
    this.fetchTodos()
  },

  methods: {
    async updateTodos(id) {
      try {
        const response = await apiClient.put(`todo/${id}`)
        if (response && response.data) {
          // console.log(response.data.message)
          alert(response.data.message)
          await auth.fetchTodos()
        }
      } catch (error) {
        if (error && error.response) {
          // console.log(error.response.data.message)
        }
      }
    },
    async deleteTodos(id) {
      try {
        const response = await apiClient.delete(`todo/${id}`)
        if (response && response.data.message) {
          this.auth.todos = this.auth.todos.filter(todo=>todo.id !==id);
          await this.auth.fetchTodos()
          // console.log(response.data.message);
          alert(response.data.message)
        }
      } catch (error) {
        if (error && error.response) {
          // console.log(error.reponse.data.message);
        }
      }
    },
    async fetchTodos(url="/todo") {
      this.fetchingTodos = true
      try {
        await this.auth.fetchTodos(url)
      } catch (error) {
        
      }
      finally{

        this.fetchingTodos = false;
      }
    },
    async fetchPage(url) {
    if (url) {
      await this.fetchTodos(url); // Use your existing fetchTodos method
    }
  },
    async updateTodoStatus(id) {
      try {
        const response = await apiClient.put(`todostatus/${id}`)
        if (response && response.data) {
          await this.auth.fetchTodos();
          //
        }
      } catch (error) {
        if (error && error.response) {
          //
        }
      }
    },
    getTitle(status) {
      this.refresh()
      return status === 'pending' ? 'Mark as Completed' : 'Mark as Pending'
    },
    async refresh() {
      await this.auth.fetchTodos()
    },
  },
}
</script>

<style scoped>
.todo-btns {
  top: 2rem;
  right: 10px;
}
</style>
