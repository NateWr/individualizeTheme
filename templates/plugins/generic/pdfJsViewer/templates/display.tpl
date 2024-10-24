{extends file="frontend/layout-galley.tpl"}

{block name="content"}

  <iframe
    name="pdfFrame"
    id="pdfFrame"
    src=""
    title="{$galleyTitle|escape}"
    class="layout-galley-iframe"
    allowfullscreen
    webkitallowfullscreen
  ></iframe>


  <script type="text/javascript">
    var el = document.getElementById('pdfFrame');
    if (el) {
        el.setAttribute('src', '{$pluginUrl}/pdf.js/web/viewer.html?file=' + encodeURIComponent({$pdfUrl|json_encode}));
    }
  </script>

{/block}