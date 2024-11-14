# OJS Theme by SLUB Dresden

...

# Install

...

# Contribute

This theme uses [Vite](https://vitejs.dev/) to build CSS/JS assets. Run the following commands to sync CSS/JS assets with Vite's HMR server while editing the theme.

Install dependencies.

```bash
composer install
npm install
```

Start Vite's development server.

```bash
npm run start
```

Build the assets for distribution.

```bash
npm run build
```

## Package

Create a `.tar.gz` package of this theme by running the following command in the directory above the theme's file.

```
tar -czf slubTheme.tar.gz --exclude-ignore=.tarignore slubTheme
```