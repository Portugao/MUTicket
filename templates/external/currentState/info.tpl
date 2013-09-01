{* Purpose of this template: Display item information for previewing from other modules *}
<dl id="currentState{$currentState.id}">
<dt>{$currentState.title|notifyfilters:'muticket.filter_hooks.currentstates.filter'|htmlentities}</dt>
<dd>{if $currentState.uploadIcon ne ''}
  <a href="{$currentState.uploadIconFullPathURL}" title="{$currentState.title|replace:"\"":""}"{if $currentState.uploadIconMeta.isImage} rel="imageviewer[currentstate]"{/if}>
  {if $currentState.uploadIconMeta.isImage}
      {thumb image=$currentState.uploadIconFullPath objectid="currentState-`$currentState.id`" preset=$currentStateThumbPresetUploadIcon tag=true img_alt=$currentState.title}
  {else}
      {gt text='Download'} ({$currentState.uploadIconMeta.size|muticketGetFileSize:$currentState.uploadIconFullPath:false:false})
  {/if}
  </a>
{else}&nbsp;{/if}
</dd>
{if $currentState.description ne ''}<dd>{$currentState.description}</dd>{/if}
</dl>
