{capture name="mainbox"}

<form action="{""|fn_url}" method="post" name="manage_appointments_form">

{include file="common/pagination.tpl"}

{if $appointments}
{assign var="c_url" value=$config.current_url|fn_url}
<table width="100%" class="table table-middle">
<thead>
<tr>
    <th width="1%" class="center">
     {include file="common/check_items.tpl"}</th>
    <th width="20%"><a class="{$ajax_class}{if $search.sort_by == "user"} sort-link-{$search.sort_order}{/if}" href="{"`$c_url`&amp;sort_by=user&amp;sort_order=`$search.sort_order`"|fn_url}" rev="pagination_contents">{__("user")}</a></th>
    <th width="35%"><a class="{$ajax_class}" href="#" rev="pagination_contents">{__("tspa_appointment")}</a></th>
    <th width="15%" class="center"><a class="{$ajax_class}{if $search.sort_by == "date_created"} sort-link-{$search.sort_order}{/if}" href="{"`$c_url`&amp;sort_by=date_created&amp;sort_order=`$search.sort_order`"|fn_url}" rev="pagination_contents">{__("date_created")}</a></th>
    <th width="15%" class="center"><a class="{$ajax_class}{if $search.sort_by == "date_completed"} sort-link-{$search.sort_order}{/if}" href="{"`$c_url`&amp;sort_by=date_completed&amp;sort_order=`$search.sort_order`"|fn_url}" rev="pagination_contents">{__("date")} {__("completed")}</a></th>
    <th width="10%"><a class="{$ajax_class}{if $search.sort_by == "status"} sort-link-{$search.sort_order}{/if}" href="{"`$c_url`&amp;sort_by=status&amp;sort_order=`$search.sort_order`"|fn_url}" rev="pagination_contents">{__("status")}</a></th>
</tr>
</thead>

{foreach from=$appointments key="id" item="appointment"}
<tr class="cm-row-status-{$appointment.status|lower}">
    <td width="1%" class="center">
        <input type="checkbox" name="appointment_ids[]" value="{$appointment.id}" class="checkbox cm-item" /></td>
    <td><a href="{"profiles.update?user_id=`$appointment.user_id`"|fn_url}">{$appointment.lastname} {$appointment.firstname}</a></td>
    <td><a href="{"appointments.update?appointment_id=`$appointment.id`"|fn_url}">{$appointment.info|html_entity_decode:$smarty.const.ENT_COMPAT:"UTF-8"}</a></td>
    <td class="center">{if $appointment.date_created}{$appointment.date_created|date_format:"`$settings.Appearance.date_format` `$settings.Appearance.time_format`"}{/if}</td>
    <td class="center">{if $appointment.date_completed}{$appointment.date_completed|date_format:"`$settings.Appearance.date_format` `$settings.Appearance.time_format`"}{/if}</td>
    <td>
        {assign var="notify" value=true}
        {include file="addons/tsp_appointments/views/appointments/components/select_popup.tpl" id=$appointment.id status=$appointment.status object_id_name="appointment_id" hide_for_vendor=$runtime.company_id update_controller="appointments" notify=$notify}
    </td>
</tr>
{/foreach}
</table>
{else}
    <p class="no-items">{__("no_data")}</p>
{/if}

{include file="common/pagination.tpl"}
</form>
{/capture}

{capture name="buttons"}
    {capture name="tools_list"}
        {if $appointments}
            <li>{btn type="delete_selected" dispatch="dispatch[appointments.m_delete]" form="manage_appointments_form"}</li>
        {/if}
    {/capture}
    {dropdown content=$smarty.capture.tools_list}
{/capture}

{capture name="adv_buttons"}
    {btn type="add" title=__("tspa_appointment_add") href="order_management.new"}
{/capture}

{capture name="sidebar"}
    {capture name="content_sidebar"}
        <ul class="nav nav-list">
            <li><a href="{"addons.manage#grouptsp_appointments"|fn_url}"><i class="icon-cog"></i>{__("tspa_appointment")} {__("settings")}</a></li>
        </ul>
    {/capture}
    {include file="common/sidebox.tpl" content=$smarty.capture.content_sidebar title=__("settings")}
{/capture}

{include file="common/mainbox.tpl" title=__("tsp_appointments") content=$smarty.capture.mainbox buttons=$smarty.capture.buttons adv_buttons=$smarty.capture.adv_buttons sidebar=$smarty.capture.sidebar}