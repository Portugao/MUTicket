{* purpose of this template: header for admin area *}

{pageaddvar name='javascript' value='prototype'}
{pageaddvar name='javascript' value='validation'}
{pageaddvar name='javascript' value='zikula'}
{pageaddvar name='javascript' value='livepipe'}
{pageaddvar name='javascript' value='zikula.ui'}
{pageaddvar name='javascript' value='zikula.imageviewer'}
{pageaddvar name='javascript' value='modules/MUTicket/javascript/MUTicket.js'}

{if !isset($smarty.get.theme) || $smarty.get.theme ne 'Printer'}
{adminheader}
{/if}
<div class="z-informationmsg z-formnote">{gt text="Notice: If there is no supporter present, the support  in the frontend is disabled for costumers!"}</div>
