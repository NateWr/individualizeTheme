import { defineConfig } from 'vite'
import path from 'path'
import pkpThemePlugin from 'vite-pkp-theme'

const viteServer = './.vite.server.json'

export default defineConfig({
  plugins: [
    pkpThemePlugin({configFile: viteServer}),
  ],
  build: {
    manifest: true,
    rollupOptions: {
      input: path.resolve(__dirname, 'src', 'main.js'),
    },
  },
})
