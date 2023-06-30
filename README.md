
# Wordpress Boostrap 5 Starter theme
A Wordpress starter theme with Boostrap 5 and a touch of OOP 👌

## Installation
1. Copy theme's files to wp-content/themes

2. Customize theme details in style.css

3. Rename every functions, variables, classes, namespaces starting with **MyTheme**, **my_theme_**, **my-theme**

4. Install dependancies :

```bash
npm install;
composer install;
```

5. Customize everything

## Usage

- Lint and fix php :
```bash
composer php:lint;
compose php:fix;
```

- Lint and fix js :
```bash
npm run js:lint;
npm run js:fix;
```

- Watch css and js during development
```bash
npm run js:watch;
npm run css:watch;
```

- Build css and js for production
```bash
npm run js:build;
npm run css:build;
```