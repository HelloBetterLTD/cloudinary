<picture class="image-load _js-image-load" data-w="{$Width}" data-h="{$Height}" style="height: {$Height}px;">
    <!--[if IE 9]><video style="display: none;"><![endif]-->
    <% if $Options %><% loop $Options %>
        <source srcset="{$URL}" media="{$MediaQuery}">
    <% end_loop %><% end_if %>
    <!--[if IE 9]></video><![endif]-->
    <img srcset="{$Options.First.URL}" alt="{$Title}">
    <% include ImagePreloader %>
</picture>