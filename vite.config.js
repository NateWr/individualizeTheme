import { defineConfig } from 'vite'
import fs from 'fs'
import path from 'path'

const viteServer = './.vite.server.json'

const pkpThemePlugin = () => ({
  name: 'pkp-vite',
  configResolved({ mode }) {
    if (mode === 'production') {
      if (fs.existsSync(viteServer)) {
        fs.unlinkSync(viteServer)
      }
    }
  },
  configureServer(server) {
    server.httpServer?.once('listening', () => {
      const timer = setInterval(() => {
        const urls = server?.resolvedUrls
        if (urls) {
          fs.writeFileSync(viteServer, JSON.stringify(urls, null, 2))
          clearInterval(timer)
        }
      }, 100)
    })
  },
})

export default defineConfig({
  plugins: [
    pkpThemePlugin(),
  ],
  build: {
    manifest: true,
    rollupOptions: {
      input: path.resolve(__dirname, 'src', 'main.js'),
    },
  },
  theme: {
    extend: {
      screens: {
        '3xl': '1920px',
      },
    },
  },
})
