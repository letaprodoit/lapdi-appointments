{capture name="mainbox"}

<form action="{""|fn_url}" method="post" name="manage_appointments_form">

{include file="common/pagination.tpl"}

{assign var="c_url" value=$config.current_url|fn_query_remove:"sort_by":"sort_order"}
{assign var="c_icon" value="<i class=\"exicon-`$search.sort_order_rev`\"></i>"}
{assign var="c_dummy" value="<i class=\"exicon-dummy\"></i>"}

{assign var="rev" value=$smarty.request.content_id|default:"pagination_contents"}

{if $appointments}
<table width="100%" class="table table-middle">
<thead>
<tr>
    <th  class="left">
        {include file="common/check_items.tpl" check_statuses=$simple_statuses}
    </th>
    <th width="40%"><a class="cm-ajax" href="{"`$c_url`&sort_by=appointment_id&sort_order=`$search.sort_order_rev`"|fn_url}" data-ca-target-id={$rev}>{__("tspa_appointment")}{if $search.sort_by == "appointment_id"}{$c_icon nofilter}{else}{$c_dummy nofilter}{/if}</a></th>
    <th width="15%"><a class="cm-ajax" href="{"`$c_url`&sort_by=status&sort_order=`$search.sort_order_rev`"|fn_url}" data-ca-target-id={$rev}>{__("status")}{if $search.sort_by == "status"}{$c_icon nofilter}{else}{$c_dummy nofilter}{/if}</a></th>
    <th width="15%"><a class="cm-ajax" href="{"`$c_url`&sort_by=user&sort_order=`$search.sort_order_rev`"|fn_url}" data-ca-target-id={$rev}>{__("customer")}{if $search.sort_by == "user"}{$c_icon nofilter}{/if}</a></th>
    <th width="15%"><a class="cm-ajax" href="{"`$c_url`&sort_by=date_created&sort_order=`$search.sort_order_rev`"|fn_url}" data-ca-target-id={$rev}>{__("date_created")}{if $search.sort_by == "date_created"}{$c_icon nofilter}{/if}</a></th>
    <th width="15%"><a class="cm-ajax" href="{"`$c_url`&sort_by=date_completed&sort_order=`$search.sort_order_rev`"|fn_url}" data-ca-target-id={$rev}>{__("date")} {__("completed")}{if $search.sort_by == "date_completed"}{$c_icon nofilter}{/if}</a></th>
</tr>
</thead>

{foreach from=$appointments key="id" item="appointment"}
<tr class="cm-row-status-{$appointment.color_status|lower}">
    <td class="left">
        <input type="checkbox" name="appointment_ids[]" value="{$appointment.id}" class="cm-item cm-item-status-{$appointment.status|lower}" /></td>
    <td>
        <a href="{"appointments.update?appointment_id=`$appointment.id`"|fn_url}">
        <span style="color:black;"><strong>{__("order")} #{$appointment.order_id}:</strong></span> Apppointment set for <strong>{$appointment.data.date}</strong> at <strong>{$appointment.data.time}</strong> for <strong>{$appointment.data.duration}</strong>. Location will be <strong>{$appointment.data.location}</strong>.
                        <br><strong>Note: </strong>{$appointment.data.additional_info|default:'None'}
        </a>
        {include file="views/companies/components/company_name.tpl" object=$appointment}
    </td>
    <td>
        {assign var="this_url" value=$config.current_url|escape:"url"}
        {include file="common/select_popup.tpl" suffix="o" id=$appointment.id status=$appointment.status items_status=$simple_statuses update_controller="appointments" notify=true status_target_id="`$rev`" extra="&return_url=`$this_url`" statuses=$statuses btn_meta="btn btn-info o-status-`$appointment.color_status` btn-small"|lower}
    </td>
    <td>{if $appointment.user_id}<a href="{"profiles.update?user_id=`$appointment.user_id`"|fn_url}">{/if}{$appointment.lastname} {$appointment.firstname}{if $appointment.user_id}</a>{/if}</td>
    <td>{if $appointment.date_created}{$appointment.date_created|date_format:"`$settings.Appearance.date_format` `$settings.Appearance.time_format`"}{/if}</td>
    <td>{if $appointment.date_completed}{$appointment.date_completed|date_format:"`$settings.Appearance.date_format` `$settings.Appearance.time_format`"}{/if}</td>
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