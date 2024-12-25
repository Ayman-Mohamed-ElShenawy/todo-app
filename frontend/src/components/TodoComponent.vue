<template>
  <div class="bg-primary py-4">
    <div class="container">
      <div class="row">
        <div class="col-lg-8 mx-auto shadow bg-white rounded">
          <div class="todo-header mt-5">
            <div v-if="Object.keys(errors).length" class="alert alert-danger">
              <ul>
                <li v-for="(error, index) in errors" :key="index">
                  {{ error[0] }}
                </li>
              </ul>
            </div>
            <h4 class="text-center">
              <span class="text-primary">To-Do List</span>
            </h4>
            <form @submit.prevent="newTodo">
              <div class="todo-wrapper mt-3">
                <div class="col-7 mb-2 todo-title">
                  <label class="mb-2">Select Category</label>
                  <select
                    v-model="form.name"
                    name="category"
                    class="w-50 focus-ring focus-ring-danger mb-3 form-select border-color-none"
                  >
                  <option disabled value=''>select category</option>
                    <option value="1">Work</option>
                    <option value="2">Personal</option>
                    <option value="3">Urgent</option>
                  </select>
                  <div class="text text-danger my-2"></div>
                  <label class="mb-2">Todo title</label>
                  <input
                    v-model="form.title"
                    type="text"
                    class="form-control focus-ring-warning focus-ring border-color-none"
                    name="title"
                    title="Please fill out this field"
                    required
                  />
                  <div class="text text-danger my-2"></div>
                </div>
                <div class="todo-desc">
                  <label class="mb-2">Todo description</label>
                  <textarea
                    v-model="form.description"
                    type="text"
                    class="form-control border-color-none focus-ring focus-ring-info"
                    cols="10"
                    rows="5"
                    name="description"
                    title="Please fill out this field"
                    required
                  >
                  </textarea>
                  <div class="text text-danger my-2"></div>
                </div>
                <div class="spinner-btn-wrapper position-relative" v-if="creating">
                  <input
                    class="btn btn-primary form-control text-light btn-lg mt-2"
                    title="Add Todo"
                    type="submit"
                    value=" Creating"
                    ref="create-btn"
                  />
                  <div class="d-flex spinner gap-1 position-absolute">
                    <Spinner />
                    <Spinner />
                    <Spinner />
                  </div>
                </div>
                <div class="spinner-btn-wrapper" v-else>
                  <input
                    class="btn btn-primary form-control text-light btn-lg mt-2"
                    title="Add Todo"
                    type="submit"
                    value=" Create"
                    ref="create-btn"
                  />
                </div>
              </div>
              <div></div>
            </form>
          </div>
          <!---components-->
          <div class="pending-component-title">
            <ul class="nav nav-tabs mt-4">
              <li
                v-for="(component, index) in components"
                :key="index"
                :class="['nav-link ', { active: component.name === currentComponent }]"
                role="button"
                @click="switchComponent(component.name)"
              >
                {{ component.title }}
              </li>
            </ul>
            <div class="mt-3 todo-container position-relative">
              <component :is="currentComponent" ></component>
            </div>
          </div>
          <!---components-->
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import DeletedComponent from './DeletedComponent.vue'
import apiClient from '../api_client/client.js'
import Spinner from './spinner.vue'
import PendingComponent from './PendingComponent.vue'
 import useAuthStore from '../../src/stores/auth.js'
//  import {useRouter} from 'vue-router';
export default {
  components: { PendingComponent,  DeletedComponent, Spinner },
  data() {
    return {
      creating: false,
      errors: [],
      currentComponent: 'PendingComponent',
      components: [
        { title: 'Pending', name: 'PendingComponent' },
        { title: 'Deleted', name: 'DeletedComponent' },
      ],
      form: {
        name: '',
        title: '',
        description: '',
      },
    }
  },
 
  setup() {
    const auth = useAuthStore();
    return { auth };
  },
   mounted(){

   },
  methods: {
    switchComponent(name) {
      this.currentComponent = name
    },
    async newTodo() {
      try {
        this.creating = true
        const response = await apiClient.post('/todo', {
          name: this.form.name,
          title: this.form.title,
          description: this.form.description,
        })
        if (response && response.data) {
          this.creating = false
          alert(response.data.message);
          await this.auth.fetchTodos(); // Refresh the todos
          this.form.name = '';
          this.form.title = '';
          this.form.description = '';
          this.errors = '';
        }
      } catch (error) {
        if (error && error.response) {
          // console.log(error);
          this.errors = error.response.data.message
          this.creating = false
        } else {
          //
        }
      }
    },
   
  },
}
</script>

<style scoped>
.border-color-none:focus {
  border-color: white;
}
.spinner {
  top: 18px;
  right: 18rem;
}
</style>
