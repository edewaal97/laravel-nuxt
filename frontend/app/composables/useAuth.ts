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

  const setUser = (userdata: any) => {
    user.value = userdata
  }

  const clearUser = () => {
    user.value = null
  }

  const verifyLoginLink = async (userid: any, queryParams: any) => {
    const { $apiFetch } = useNuxtApp()
    try {
      const response = await $apiFetch(`/login/${userid}`, {
        query: queryParams
      })

      user.value = response.user

      return { success: true }
    } catch (e) {
      return { success: false }
    }
  }

  return {
    user,
    isLoggedIn,
    fetchUser,
    setUser,
    clearUser,
    verifyLoginLink
  }
}
