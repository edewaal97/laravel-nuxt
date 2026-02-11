export default defineNuxtRouteMiddleware((to, from) => {
  const { isLoggedIn } = useAuth()

  if(isLoggedIn.value) {
    if (to.path !== '/') {
      return navigateTo('/')
    }
  }
})
