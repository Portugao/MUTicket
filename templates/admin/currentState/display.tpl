{* purpose of this template: current states display view in admin area *}
{include file='admin/header.tpl'}
<div class="muticket-currentstate muticket-display">
{gt text='Current state' assign='templateTitle'}
{assign var='templateTitle' value=$currentState.title|default:$templateTitle}
{pagesetvar name='title' value=$templateTitle|@html_entity_decode}
<div class="z-admin-content-pagetitle">
    {icon type='display' size='small' __alt='Details'}
    <h3>{$templateTitle|notifyfilters:'muticket.filter_hooks.currentstates.filter'}{icon id='itemactionstrigger' type='options' size='extrasmall' __alt='Actions' class='z-pointer z-hide'}</h3>
</div>


<dl>
    <dt>{gt text='Description'}</dt>
    <dd>{$currentState.description}</dd>
    <dt>{gt text='Upload icon'}</dt>
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
    
</dl>
{include file='admin/include_standardfields_display.tpl' obj=$currentState}

{if !isset($smarty.get.theme) || $smarty.get.theme ne 'Printer'}
    {* include display hooks *}
    {notifydisplayhooks eventname='muticket.ui_hooks.currentstates.display_view' id=$currentState.id urlobject=$currentUrlObject assign='hooks'}
    {foreach key='providerArea' item='hook' from=$hooks}
        {$hook}
    {/foreach}
    {if count($currentState._actions) gt 0}
        <p id="itemactions">
        {foreach item='option' from=$currentState._actions}
            <a href="{$option.url.type|muticketActionUrl:$option.url.func:$option.url.arguments}" title="{$option.linkTitle|safetext}" class="z-icon-es-{$option.icon}">{$option.linkText|safetext}</a>
        {/foreach}
        </p>
        <script type="text/javascript">
        /* <![CDATA[ */
            document.observe('dom:loaded', function() {
                muticketInitItemActions('currentState', 'display', 'itemactions');
            });
        /* ]]> */
        </script>
    {/if}
{/if}

</div>
{include file='admin/footer.tpl'}

