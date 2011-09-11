{* purpose of this template: footer for admin area *}

{if !isset($smarty.get.theme) || $smarty.get.theme ne 'Printer'}
<p style="text-align: center">
    Powered by <a href="http://modulestudio.de" title="Get the MOST out of Zikula!">ModuleStudio 0.5.2</a>
</p>
{adminfooter}
{elseif isset($smarty.get.func) && $smarty.get.func eq 'edit'}
{pageaddvar name='stylesheet' value='styles/core.css'}
{pageaddvar name='stylesheet' value='modules/MUTicket/style/style.css'}
{pageaddvar name='stylesheet' value='system/Theme/style/form/style.css'}
{pageaddvar name='stylesheet' value='themes/Andreas08/style/fluid960gs/reset.css'}
{capture assign='pageStyles'}
<style type="text/css">
    body {
        font-size: 70%;
    }
</style>
{/capture}
{pageaddvar name='header' value=$pageStyles}
{/if}

