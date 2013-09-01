{* Purpose of this template: Display one certain current state within an external context *}
<div id="currentState{$currentState.id}" class="muticketexternalcurrentstate">
{if $displayMode eq 'link'}
    <p class="muticketexternallink">
    <a href="{modurl modname='MUTicket' type='user' func='display' ot='currentState' id=$currentState.id}" title="{$currentState.title|replace:"\"":""}">
    {$currentState.title|notifyfilters:'muticket.filter_hooks.currentstates.filter'}
    </a>
    </p>
{/if}
{checkpermissionblock component='MUTicket::' instance='::' level='ACCESS_EDIT'}
    {if $displayMode eq 'embed'}
        <p class="muticketexternaltitle">
            <strong>{$currentState.title|notifyfilters:'muticket.filter_hooks.currentstates.filter'}</strong>
        </p>
    {/if}
{/checkpermissionblock}

{if $displayMode eq 'link'}
{elseif $displayMode eq 'embed'}
    <div class="muticketexternalsnippet">
        {if $currentState.uploadIcon ne ''}
          <a href="{$currentState.uploadIconFullPathURL}" title="{$currentState.title|replace:"\"":""}"{if $currentState.uploadIconMeta.isImage} rel="imageviewer[currentstate]"{/if}>
          {if $currentState.uploadIconMeta.isImage}
              {thumb image=$currentState.uploadIconFullPath objectid="currentState-`$currentState.id`" preset=$currentStateThumbPresetUploadIcon tag=true img_alt=$currentState.title}
          {else}
              {gt text='Download'} ({$currentState.uploadIconMeta.size|muticketGetFileSize:$currentState.uploadIconFullPath:false:false})
          {/if}
          </a>
        {else}&nbsp;{/if}
    </div>

    {* you can distinguish the context like this: *}
    {*if $source eq 'contentType'}
        ...
    {elseif $source eq 'scribite'}
        ...
    {/if*}

    {* you can enable more details about the item: *}
    {*
        <p class="muticketexternaldesc">
            {if $currentState.description ne ''}{$currentState.description}<br />{/if}
        </p>
    *}
{/if}
</div>
