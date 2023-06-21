import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import LoginView from '../views/LoginView.vue'
import RegisterView from '../views/RegisterView.vue'
import UserPostView from '../views/UserPostView.vue'
import PostDetails from '../views/PostDetails.vue'
import store from '../store'

const routes = [
  {
    path: '/',
    name: 'Home',
    component: HomeView,
  },
  {
    path: '/login',
    name: 'Login',
    component: LoginView,
    meta: { guest: true }
  },
  {
    path: '/register',
    name: 'register',
    component: RegisterView,
    meta: { guest: true }
  },
  // {
  //   path: '/posts',
  //   name: 'Posts',
  //   component: UserPostView,
  //   meta: { guest: true }
  // },
  {
    path: '/posts',
    name: 'posts',
    component: UserPostView,
    meta: { requiresAuth: true }
  },
  {
    path: '/PostDetails/:id',
    name: 'PostDetails',
    component: PostDetails,
    meta: { requiresAuth: true }
  },
]

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes
})
router.beforeEach((to, from, next) => {
  document.title = to.name
  next()
})
router.beforeEach((to, from, next) => {
  if (to.matched.some(record => record.meta.requiresAuth)) {
    if (!store.state.loggedIn) {
      next({
        path: '/login'
      })
    } else {
      next()
    }
  } else if (to.matched.some(record => record.meta.guest)) {
    if (!store.state.loggedIn) {
      next()
    } else {
      next({
        path: '/',
      })
    }
  } else {
    next()
  }
})




export default router
