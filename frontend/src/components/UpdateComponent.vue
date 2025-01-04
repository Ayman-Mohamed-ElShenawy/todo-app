<template>
  <div class="container mt-5">
    <div class="row">
      <div class="col-lg-7 mx-auto">
        <div class="card">
          <div class="card-header">
            <h4 class="p-1">
              Update-Todo
              <router-link class="btn btn-danger float-end" to="/todo">back</router-link>
            </h4>
            <div class="card-body">
                <div v-if='Object.keys(errors).length' class='mb-2 alert alert-danger'>
                     <ul>
                        <li v-for='(error,index) in errors' :key="index">{{ error[0] }}</li>
                     </ul>
                </div>
              <form @submit.prevent="updateTodo"  autocomplete="off">
                <div class='mb-3'>
                    <label class='mb-2'>Update Category</label>
                    <select   v-model="form.name" class='w-50 focus-ring focus-ring-danger mb-3 form-select border-color-none' name="" id="">
                      <option disabled >select category</option>
                      <option value='1'>Work</option>
                    <option value='2'  >Personal</option>
                    <option value='3' >Urgent</option>
                
                </select>
                </div>
                <div class="mb-3">
                  <label for="">Title</label>
                  <input type="text" v-model='form.title' class="form-control focus-ring focus-ring-warning border-color-none" value="" name="title" />
                </div>
                <div class="mb-3">
                  <label for="">Description</label>
                  <input type="text" v-model='form.description' class="form-control focus-ring focus-ring-info border-color-none" value="" name="description" />
                </div>

                <div v-if='updating' class="mb-3 position-relative">
                  <input type="submit" class="btn btn-success w-50" value="Updating" />
                  <div class="d-flex spinner gap-1 position-absolute">
                  <Spinner />
                  <Spinner />
                  <Spinner />
                  </div>
                </div>
                <div v-else class="mb-3">
                  <input type="submit" class="btn btn-success w-50" value="Update" />
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script >
import { useRouter } from 'vue-router'; // Import useRouter
import apiClient from "../api_client/client.js";
import useAuthStore from "../../src/stores/auth.js";
import Spinner from './spinner.vue'


export default {
    components:{Spinner},
    setup(){
  
       const auth = useAuthStore();
       const router = useRouter(); // Get the router instance
       return{auth,router}
    },
  data() {
    return{
        form:{
            name:'',
            title:'',
            description:'',
        },
        errors:[],
        updating:false,
    }
  },
  mounted() {
    this.todoId();
  },
  methods: {
    async todoId() {
      try {
        const id = this.$route.params.id
        const response = await apiClient.get(`todo/${id}/edit`)
       
        if (response && response.data.message) {
          // console.log(response.data);
            this.form.name= response.data.message.category.name;
            this.form.title = response.data.message.title;
            this.form.description = response.data.message.description;
          
        }
      } catch (error) {
        if (error && error.response) {
        // console.log(error.response);
        }
      }
    },
    async updateTodo(){
        try {
            this.updating = true;
            const id = this.$route.params.id;
            const response = await apiClient.post(`updateTodo/${id}`,{
                name:this.form.name,
                title:this.form.title,
                description:this.form.description
            });
            if(response && response.data){
                // console.log(response.data);
                this.updating = false;
                this.errors = '';
                this.auth.fetchTodos();
                this.router.push('/todo');
            }
            
        } catch (error) {
            if(error && error.response){
              // console.log(error.response);
                this.updating = false;
                this.errors = error.response.data.message;
            }
        }
    }
  },
}
</script>
<style scoped>
.border-color-none:focus {
  border-color: white ;
}
.spinner {
    top: 6px;
    right: 22rem;
}
</style>