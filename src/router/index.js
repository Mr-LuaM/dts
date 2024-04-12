import { createRouter, createWebHistory } from 'vue-router'
import mobileNav from '/src/layouts/mobileNav.vue';
import webNav from '/src/layouts/mobileNav.vue';
import {jwtDecode} from 'jwt-decode';
import { useUserStore } from '@/stores/userStore';
//auth routes
const authRoutes = [
  {
    path: '/',
    name: 'startup',
    component: () => import('../views/auth/startUp.vue'),
  },
  {
    path: '/login',
    name: 'login',
    component: () => import('../views/auth/login.vue'),
  },
  {
    path: '/signup',
    name: 'signup',
    component: () => import('../views/auth/signup.vue'),
  },
  {
    path: '/verification',
    name: 'verification',
    component: () => import('../views/auth/verification.vue'),
  },
  
  {
    path: '/forgotpassword',
    name: 'forgotpassword',
    component: () => import('../views/auth/forgotPassword.vue'),
  },
  {
    path: '/reset-password',
    name: 'resetpassword',
    component: () => import('../views/auth/resetPassword.vue'),
    props: (route) => ({
      token: route.query.token,
      email: route.query.email
    })
  },
];

// Admin routes
const adminRoutes = [
  {
    path: '/admin',
    component: webNav,
    meta: { requiresAuth: true, role: 'dwcc.dts.admin' },
    children: [
    {
      path: 'dashboard',
      name: 'adminDashboard',
      component: () => import('../views/admin/dashboard.vue'),
    },
    
    ]
  },

];

// User routes
const userRoutes = [
  {
    path: '/user',
    component: mobileNav,
    meta: { requiresAuth: true, role: 'dwcc.dts.user' },
    children: [
      {
        path: 'dashboard', // Path: /user/dashboard
        name: 'userDashboard',
        component: () => import('../views/user/dashboard.vue'),
      },
      {
        path: 'events/:id',
        name: 'eventDetails',
        component: () => import('../views/user/eventDetails.vue') // Lazy load the component
      },
      {
        path: 'browse-ticket',
        name: 'browseTicket',
        component: () => import('../views/user/browseTicket.vue') // Lazy load the component
      },
      
    ],
  },
];



// UserEdit routes
const profileRoutes = [
   {
    path: '/profile',
    component: mobileNav,
    meta: { requiresAuth: true, role: 'dwcc.dts.user' },
    children: [
      {
        path: '', // Path: /user/profile
        name: 'userProfile',
        component: () => import('../views/profile/profile.vue'),
      },
      {
        path: 'edit', // Path: /profile/profile
        name: 'editUserProfile',
        component: () => import('../views/profile/editUserProfile.vue'),
      },
      {
        path: 'contacts/edit', // Path: /profile/profile
        name: 'editUserContacts',
        component: () => import('../views/profile/editUserContacts.vue'),
      },
      {
        path: 'address/edit', // Path: /profile/profile
        name: 'editUserAddress',
        component: () => import('../views/profile/editUserAddress.vue'),
      },
      {
        path: 'security/edit', // Path: /profile/profile
        name: 'editUserSecurity',
        component: () => import('../views/profile/editUserProfile.vue'),
      },
     
      
    ],
  },
];


//Catch-all route for 404 NotFound
const errorRoutes = [
  {
    path: '/:catchAll(.*)*',
    name: 'NotFound',
    component: () => import('../views/notFound.vue'),
  },
  {
    path: '/unauthorized',
    name: 'Unauthorized',
    component: () => import('../views/unauthorized.vue'),
  },
  
];

const routes = [...authRoutes, ...adminRoutes, ...userRoutes, ...profileRoutes,...errorRoutes ];


const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes,
});

router.beforeEach(async (to, from, next) => {
  const userStore = useUserStore();
  const token = userStore.token;
  // Check for first-time visit to the startup page
  if (to.name === 'startup') {
    if (localStorage.getItem('hasVisitedStartup')) {
      // If the user has visited the startup page before, redirect to login (or another appropriate page)
      next({ name: 'login' });
      return; // Prevent further execution
    } else {
      // Mark that the user has now visited the startup page
      localStorage.setItem('hasVisitedStartup', 'true');
    }
  }

  const isAuthenticated = userStore.isAuthenticated; 
  let userRole = null;
  if (token && typeof token === 'string') {
    try {
      const decoded = jwtDecode(token);
      userRole = decoded.role;
    } catch (error) {
      console.error("Error decoding token:", error);
    }
  }  

  if (to.meta.requiresAuth && !isAuthenticated) {
    await userStore.logout();
    next({ name: 'login' }); // Redirect to login if not authenticated
  } else if (to.meta.requiresAuth && to.meta.role && to.meta.role !== userRole) {
    next({ name: 'Unauthorized' }); // Redirect to an unauthorized page if roles don't match
  } else {
    next(); // Proceed as normal if authenticated or no specific auth requirements
  }
});


export default router;
