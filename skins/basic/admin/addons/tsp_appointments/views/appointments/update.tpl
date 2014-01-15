{* $Id$ *}

{capture name="mainbox"}

{if $appointment}

<form action="{""|fn_url}" method="post" class="{$form_class}" id="appointment_update_form" enctype="multipart/form-data"> {* appointment update form *}
{* class="cm-form-highlight"*}
<input type="hidden" name="fake" value="1" />
<input type="hidden" name="selected_section" id="selected_section" value="{$smarty.request.selected_section}" />
<input type="hidden" name="appointment_id" value="{$appointment.id}" />

{** General info section **}
<div id="content_detailed"> {* content detailed *}

<div class="form-field">
	<label>{$lang.user}:</label>
	<a href="{"profiles.update?user_id=`$user_id`"|fn_url}">{$appointment.user.firstname} {$appointment.user.lastname}</a>
</div>

<div class="form-field">
	<label>{$lang.email}:</label>
	{$appointment.user.email}
</div>

<div class="form-field">
	<label>{$lang.completed}:</label>
	{$appointment.date_completed|date_format:$settings.Appearance.date_format}
</div>

<div class="form-field">
	<label>{$lang.order}:</label>
	<a href="{"orders.details?order_id=`$appointment.order_id`"|fn_url}">#{$appointment.order_id}</a>
</div>

<div class="form-field">
	<label>{$lang.product}:</label>
	<a href="{"products.update?product_id=`$appointment.product_id`"|fn_url}">{$appointment.product.product} (Product Code: {$appointment.product.product_code})</a>
</div>

<hr>

<div class="form-field">
	<label for="apointment_status" class="cm-required">{$lang.tspa_appointment} {$lang.status}:</label>
	<select name="appointment_data[status]" id="apointment_status">
		{foreach from=$appointment_statuses item="status" key="status_key"}
			<option value="{$status_key}" {if $status_key == $appointment.status}selected="selected"{/if}>{$status}</option>
		{/foreach}
	</select>
</div>

<div class="form-field">
	<label for="apointment_date" class="cm-required">{$appointment.date.description}:</label>
	<div class="clearfix">
		{include file="common_templates/calendar.tpl" date_id="appointment_date" date_name="appointment_data[date]" start_year="`$smarty.const.TIME`" date_val=$appointment.date.value}
	</div>
</div>

<div class="form-field">
	<label for="apointment_time" class="cm-required">{$appointment.time.description}:</label>
	<input type="text" name="appointment_data[time]" id="apointment_time" size="32" value="{$appointment.time.value}" class="input-text" />
</div>

<div class="form-field">
	<label for="apointment_location" class="cm-required">{$appointment.location.description}:</label>
	<select name="appointment_data[location]" id="apointment_location">
		{foreach from=$appointment_locations item="location" key="option_id"}
			<option value="{$option_id}" {if $option_id == $appointment.location.value}selected="selected"{/if}>{$location.variant_name}</option>
		{/foreach}
	</select>
</div>

{** Form submit section **}

<div class="buttons-container cm-toggle-button buttons-bg">
	{include file="buttons/save_cancel.tpl" but_name="dispatch[appointments.update]"}
</div>
{** /Form submit section **}

{/if}
{/capture}

</form> {* /product update form *}


{include file="common_templates/mainbox.tpl" title="`$lang.tspa_editing_appointment`:&nbsp; `$appointment.user.lastname`, `$appointment.user.firstname`" content=$smarty.capture.mainbox select_languages=true}
