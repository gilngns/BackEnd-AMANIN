import { defineConfig } from 'vite';
import vue from '@vitejs/plugin-vue';
import laravel from 'vite-plugin-laravel';

export default defineConfig({
  plugins: [
    vue(),
    laravel(),
  ],
  build: {
    manifest: true,  // Pastikan ini diatur ke true
    outDir: 'public/build', // Output di folder public/build
  },
  server: {
    proxy: {
      '/app': 'http://localhost',  // Sesuaikan dengan URL backend jika diperlukan
    },
  },
});
