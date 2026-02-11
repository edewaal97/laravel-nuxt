export const useAuth = () => {
  const user = useState<any | null>('auth-user', () => null)
  const isLoggedIn = computed(() => !!user.value)

  const fetchUser = async () => {
    const { $apiFetch } = useNuxtApp()
    try {
      user.value = await $apiFetch('/api/user')
    } catch (error) {
      user.value = null
    }
  }

  const setUser = (userdata: any)  => {
    user.value = userdata
  }

  const clearUser = () => {
    user.value = null
  }

  return {
    user,
    isLoggedIn,
    fetchUser,
    setUser,
    clearUser,
  }
}
