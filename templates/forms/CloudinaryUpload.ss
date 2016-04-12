<input $AttributesHTML />


<input type="hidden" name="{$Name}[id]" value="{$ImageRecord.ID}">
<input type="hidden" name="{$Name}[format]" value="{$ImageRecord.Format}">
<input type="hidden" name="{$Name}[size]" value="{$ImageRecord.Size}">
<input type="hidden" name="{$Name}[width]" value="{$ImageRecord.Width}">
<input type="hidden" name="{$Name}[height]" value="{$ImageRecord.Height}">
<input type="hidden" name="{$Name}[resource_type]" value="{$ImageRecord.ResourceType}">

<div class="upload-area <% if $ImageRecord.exists %>upload-area--visible<% end_if %> _js-additional-fields">
    <!-- div class="upload-area__field">
        <input type="file" name="{$Name}[file]">
    </div -->
    <div class="upload-area__field">
        <input type="text" class="text" name="{$Name}[title]" value="{$ImageRecord.Title}" placeholder="Title">
    </div>
    <div class="upload-area__field">
        <input type="text" class="text" name="{$Name}[filename]" value="{$ImageRecord.FileName}" placeholder="File name">
    </div>
</div>
