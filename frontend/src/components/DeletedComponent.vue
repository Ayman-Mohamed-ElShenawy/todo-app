<template>
  <div class="container">
    <div class="row">
      <div class="col-lg-12">
        <div v-if="fetchDeleted">
          <loader />
        </div>
      
        <div v-else-if='Object.keys(auth.deletedTodos).length'>
          <div
            v-for="(todo, index) in auth.deletedTodos"
            :key="index"
            class="my-2 pt-3 pb-4 position-relative fw-bold rounded border border-primary-subtle bg-primary-subtle text-capitalize"
          >
            <div class="todo-body px-2">
              <div class="align-items-center gap-1">
                <span class="title"> {{ todo.title }} </span>

                <div class="description col-12 col-lg-10">
                  {{ todo.description }}
                </div>
              </div>
              <div class="action position-absolute todo-btns">
                <button
                  @click="restoreTodos(todo.id)"
                  name="update"
                  title="Restore"
                  class="btn btn-success me-1"
                >
                  <i class="fas fa-arrow-rotate-right"></i>
                </button>
                <button
                  @click="PermenantlyDelete(todo.id)"
                  title="Permenantly Delete"
                  name="delete"
                  style="background: blue"
                  class="btn text-white"
                >
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
        <div v-else>
          <h4 class="text-info">Here you will find your deleted todos</h4>
        </div>
       
      </div>
    </div>
  </div>
</template>

<script>
import useAuthStore from '../../src/stores/auth.js'
import apiClient from '../api_client/client.js'
import Loader from './Loader.vue'
export default {
  components: {
    Loader,
  },
  data() {
    return {
      fetchDeleted: false,
    }
  },
  setup() {
    const auth = useAuthStore()
    return { auth }
  },
  mounted() {
   this.fetchDeletedTodos();
  },
  methods: {
    async fetchDeletedTodos(url='deletedtodos') {
      this.fetchDeleted = true
      try {
       const res= await this.auth.fetchDeletedTodos(url)
        if (res && res.data) {
          // console.log(res.data)
          this.fetchDeleted = false
        }
      } catch (error) {
        // console.log(error.response);
        this.fetchDeleted = false
      } finally {
        this.fetchDeleted = false
      }
    },
    async PermenantlyDelete(id) {
      try {
        const res = await apiClient.delete(`deleteforever/${id}`)
        if (res && res.data) {
          this.auth.deletedTodos = this.auth.deletedTodos.filter(todo=>todo.id !== id);
          this.fetchDeletedTodos()
          alert(res.data.message)
            // console.log(res.data)
        }
      } catch (error) {
        if (error && error.response) {
            // console.log(error.response)
        }
      }
    },
    async restoreTodos(id) {
      try {
        const res = await apiClient.post(`restoretodos/${id}`)
        if (res && res.data) {
           // Remove the restored todo from the local state
      this.auth.deletedTodos = this.auth.deletedTodos.filter(todo => todo.id !== id);
        await this.fetchDeletedTodos()
          alert(res.data.message)
          // console.log(res.data.message)
        }
      } catch (error) {
        if (error && error.response) {
          // console.log(error.response.data.message)
        }
      }
    },
    async fetchPage(url) {
    if (url) {
      await this.fetchDeletedTodos(url); // Use your existing fetchTodos method
    }
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
