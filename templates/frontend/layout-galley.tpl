<!doctype html>
<html lang="{$currentLocale|replace:"_":"-"}" xml:lang="{$currentLocale|replace:"_":"-"}">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>{title|strip_tags value=$galleyTitle}</title>
	{load_header context="frontend"}
	{load_stylesheet context="frontend"}
</head>

<body
  dir="{$currentLocaleLangDir|escape|default:"ltr"}"
>
  <main>

    <header class="layout-galley-header">
      <div class="breadcrumb">
        <a
          class="breadcrumb-item tab-focus"
          href="{$parentUrl}"
        >
          {include file="frontend/icons/arrow-left.svg"}
          {$title}
        </a>
      </div>
      {if $pdfUrl}
        <a
          class="button"
          href="{$pdfUrl}"
        >
          {include file="frontend/icons/download.svg"}
          {translate key="common.download"}
        </a>
      {/if}
    </header>

    {if !$isLatestPublication}
      <div class="layout-galley-outdated">
        {$datePublished}
      </div>
    {/if}

    {block name="content"}{/block}

  </main>
  {load_script context="frontend"}
</html>