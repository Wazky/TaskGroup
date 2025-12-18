// file: app/frontend/vite.config.js
import { defineConfig } from 'vite'
import react from '@vitejs/plugin-react'

export default defineConfig({
  plugins: [react()],
  server: {
    proxy: {
      // Redirige todas las peticiones que empiecen con /api
      '/api': {
        target: 'http://localhost/TaskGroup/app/api',
        changeOrigin: true,
        rewrite: (path) => path.replace(/^\/api/, '')
      },
      // O específico para auth
      '/auth': {
        target: 'http://localhost/TaskGroup/app/api',
        changeOrigin: true,
      }
    }
  }
})