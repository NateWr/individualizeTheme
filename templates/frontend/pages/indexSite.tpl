{**
 * Site-level homepage (Unsupported)
 *
 * This template is shown on the site-wide homepage of
 * a multi-journal install. It displays a message that
 * the site-wide level is not supported by this theme.
 *
 * This bypasses the theme's styled layouts, preventing
 * the theme from throwing a fatal error if activated
 * by accident at the site level.
 *}
<!doctype html>
<html lang="{$currentLocale|replace:"_":"-"}" xml:lang="{$currentLocale|replace:"_":"-"}">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>{translate|escape key="plugins.themes.individualizeTheme.site.unsupported"}</title>
  <style>
    body,
    html {
      margin: 0;
      padding: 0;
      width: 100%;
      height: 100%;
    }
    .root {
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100%;
      padding-left: 1.5rem;
      padding-right: 1.5rem;
      font-family: system-ui, -apple-system, BlinkMacSystemFont, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
    }
    .message {
      max-width: 24rem;
      padding: 1.5rem;
      background: #eee;
      color: #000;
    }
  </style>
</head>

<body>
  <div class="root">
    <div class="message">
      <h1 class="title">
        {translate key="plugins.themes.individualizeTheme.site.unsupported"}
      </h1>
      <div class="desc">
        {translate key="plugins.themes.individualizeTheme.site.unsupported.description"}
      </div>
    </div>
  </div>
</body>

</html>