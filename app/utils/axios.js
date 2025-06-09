import axios from 'axios'

export const useAxios = (loading = true) => {
    const axiosInstance = axios.create();

    const runtimeConfig = useRuntimeConfig()
    axiosInstance.defaults.baseURL = runtimeConfig.public.apiUrl
    axiosInstance.defaults.withCredentials = true

    return axiosInstance
}