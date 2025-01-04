import axios from 'axios';
import router from '@/router'; // Import your router instance

const apiClient = axios.create({
    baseURL: 'http://127.0.0.1:8000/api/',
    // baseURL: 'http://todoapp.byethost3.com/api/',
    headers: {
        "Accept": "application/json",
        'Content-Type': 'application/json',
    },
});
// Add a request interceptor to include the token automatically
apiClient.interceptors.request.use(
    (config) => {
        // Get the token from localStorage
        const token = localStorage.getItem('token');  // Retrieve the token from localStorage

        // If a token exists, include it in the Authorization header
        if (token) {
            config.headers['Authorization'] = `Bearer ${token}`;
        }

        return config;  // Return the updated config
    },
    (error) => {
        // Handle any request errors
        return Promise.reject(error);
    }
);
// Add a response interceptor to handle token expiration
apiClient.interceptors.response.use(
    (response) => response, // Return the response if no errors
    (error) => {
        if (error.response && error.response.status === 401) {
            // Token expired or unauthorized
            localStorage.removeItem('token'); // Clear the expired token
            router.push('/login') // Redirect to login page
        }
        return Promise.reject(error); // Pass other errors to the caller
    }
);



export default apiClient;