{* purpose of this template: reusable display of standard fields *}
{if (isset($obj.createdUserId) && $obj.createdUserId) || (isset($obj.updatedUserId) && $obj.updatedUserId)}

<dl class="propertylist">
{if isset($obj.createdUserId) && $obj.createdUserId}
    {usergetvar name='uname' uid=$obj.createdUserId assign='cr_uname'}
    {usergetvar name='realname' uid=$obj.createdUserId assign='cr_realname'}
    {if $modvars.ZConfig.profilemodule ne ''}
        {* if we have a profile module link to the user profile *}
        {modurl modname=$modvars.ZConfig.profilemodule func='view' uname=$cr_uname assign='profileLink'}
        {assign var='profileLink' value=$profileLink|safetext}
        {if $cr_realname ne ''}
        {assign var='profileLink' value="<a href=\"`$profileLink`\">`$cr_realname`</a>"}
        {else}
        {assign var='profileLink' value="<a href=\"`$profileLink`\">`$cr_uname`</a>"}
        {/if}
    {else}
        {* else just show the user name *}
        {assign var='profileLink' value=$cr_uname}
    {/if}
    <dd>{gt text='Created by %1$s on %2$s' tag1=$profileLink tag2=$obj.createdDate|dateformat:datetimelong html=true}</dd>
{/if}
{* part for infos of updated when and by wich user deleted *}
</dl>
{/if}
