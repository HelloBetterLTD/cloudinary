<picture class="image-load _js-image-load" data-w="{$Width}" data-h="{$Height}" style="height: {$Height}px;">
    <!--[if IE 9]><video style="display: none;"><![endif]-->
    <% if $Options %><% loop $Options %>
        <source data-srcset="{$URL}" media="{$MediaQuery}">
    <% end_loop %><% end_if %>
    <!--[if IE 9]></video><![endif]-->
    <img data-srcset="{$Options.First.URL}" alt="{$Title}">

    <span class="sk-fading-circle">
        <span class="sk-circle1 sk-circle"></span>
        <span class="sk-circle2 sk-circle"></span>
        <span class="sk-circle3 sk-circle"></span>
        <span class="sk-circle4 sk-circle"></span>
        <span class="sk-circle5 sk-circle"></span>
        <span class="sk-circle6 sk-circle"></span>
        <span class="sk-circle7 sk-circle"></span>
        <span class="sk-circle8 sk-circle"></span>
        <span class="sk-circle9 sk-circle"></span>
        <span class="sk-circle10 sk-circle"></span>
        <span class="sk-circle11 sk-circle"></span>
        <span class="sk-circle12 sk-circle"></span>
    </span>
</picture>