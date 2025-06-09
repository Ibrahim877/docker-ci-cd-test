const backendUrl = 'http://127.0.0.1:8000'
const frontUrl = 'http://localhost:3001'

// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
    compatibilityDate: '2025-05-15',
    devtools: {enabled: true},
    app: {
        head: {
            meta: [
                {name: 'viewport', content: 'width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no'}
            ],
            title: 'Basic Product Crud',
            link: [
                {
                    rel: 'stylesheet',
                    href: '/assets/common/sweetalert2/sweetalert2.min.css'
                },
                {
                    rel: 'stylesheet',
                    href: 'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap'
                },
                {
                    rel: 'stylesheet',
                    href: 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css'
                },
                {
                    rel: 'stylesheet',
                    href: '/assets/common/bootstrap/css/bootstrap.min.css'
                }
            ],
            script: [
                {
                    src: '/assets/common/jquery/jquery.min.js',
                },
                {
                    src: '/assets/common/bootstrap/js/bootstrap.bundle.min.js'
                },
            ]
        }
    },
    runtimeConfig: {
        public: {
            backendUrl: process.env.NUXT_PUBLIC_BACKEND_URL || 'http://localhost:7000',
            baseURL: (process.env.NUXT_PUBLIC_BACKEND_URL || 'http://localhost:8000') + '/api/',
            apiUrl: (process.env.NUXT_PUBLIC_BACKEND_URL || 'http://localhost:8000') + '/api/',
            siteUrl: process.env.NUXT_PUBLIC_SITE_URL || 'http://localhost:3000',
        },
    },
})
