# Individualize Theme by SLUB Dresden

A custom theme for OJS to allow editors to customize the design and layout of their journal.

# Install

...

# Contribute

This theme uses [Vite](https://vitejs.dev/) to build CSS/JS assets. Run the following commands to sync CSS/JS assets with Vite's HMR server while editing the theme.

Install dependencies.

```bash
npm install
```

Start Vite's development server.

```bash
npm run start
```

## Package

Build the assets for distribution.

```bash
npm run build
```

Create a `.tar.gz` package of this theme by running the following command in the directory above the theme's file.

```
tar -czf individualizeTheme.tar.gz --exclude-ignore=.tarignore individualizeTheme
```