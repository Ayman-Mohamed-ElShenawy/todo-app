import { createRouter, createWebHistory } from 'vue-router';
import RegisterComponent from '@/components/RegisterComponent.vue';
import LoginComponent from '@/components/LoginComponent.vue';
import TodoComponent from '@/components/TodoComponent.vue';
import UpdateComponent from '@/components/UpdateComponent.vue';
import DeletedComponent from '@/components/DeletedComponent.vue';
import PendingComponent from '@/components/PendingComponent.vue';
import apiClient from "@/api_client/client.js";
const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [

    {
      path: '/register',
      component: RegisterComponent,
      name: 'register',
    },
    {
      path: '/login',
      component: LoginComponent,
      name: 'login',
    },
    {
      path: '/todo',
      component: TodoComponent,
      name: 'todo',
      meta: { requiresAuth: true },
    },
    {
      path: '/pending',
      component: PendingComponent,
      name: 'pending',
      meta: { requiresAuth: true },
    },
    {
      path: '/update/:id',
      component: UpdateComponent,
      name: 'update',
      meta: { requiresAuth: true },
    },
 
    {
      path: '/deleted/:id',
      component: DeletedComponent,
      name: 'deleted',
      meta: { requiresAuth: true },
    },
  ]
})

router.beforeEach(async (to, from, next) => {
  const token = localStorage.getItem('token');

  // Handle routes requiring authentication
  if (to.matched.some(record => record.meta.requiresAuth)) {
    if (token) {
      try {
        await apiClient.get('/user'); // Validate token with API
        next(); // Proceed to the route
      } catch (error) {
        localStorage.removeItem('token'); // Clear invalid token
        next('/login'); // Redirect to login
      }
    } else {
      next('/login'); // Redirect to login if no token
    }
  } 
  // Handle public routes for unauthenticated users
  else if (['register', 'login'].includes(to.name)) {
    if (token) {
      next('/todo'); // Redirect logged-in users to a default page
    } else {
      next(); // Allow unauthenticated users
    }
  } 
  // Handle other public routes
  else {
    next(); // Proceed to the route
  }
});

export default router;



