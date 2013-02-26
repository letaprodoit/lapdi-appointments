{* $Id$ *}

{capture name="section"}

<form name="appointments_search_form" action="{""|fn_url}" method="get">

<table cellpadding="0" cellspacing="0" border="0" class="search-header">
<tr>
	<td class="search-field">
		<label for="elm_user_id">{$lang.user}:</label>
		<div class="break">
			<select name="user_id" id="elm_user_id">
				<option value="0" {if !$search.user_id}selected="selected"{/if}> -- </option>
				{html_options options=$user_list selected=$search.user_id}
			</select>
		</div>
	</td>
	<td class="search-field">
		<label for="elm_status">{$lang.status}:</label>
		<div class="break">
			<select name="status" id="elm_status">
				<option value=""> -- </option>
				<option value="O" {if $search.status == "O"}selected="selected"{/if}>{$lang.open}</option>
				<option value="S" {if $search.status == "S"}selected="selected"{/if}>{$lang.tspa_scheduled}</option>
				<option value="X" {if $search.status == "X"}selected="selected"{/if}>{$lang.cancelled}</option>
				<option value="C" {if $search.status == "C"}selected="selected"{/if}>{$lang.completed}</option>
			</select>
		</div>
	</td></td>
	<td class="buttons-container">
		{include file="buttons/search.tpl" but_name="dispatch[appointments.manage]" but_role="submit"}
	</td>
</tr>
</table>

{capture name="advanced_search"}

<div class="search-field">
	<label>{$lang.period}:</label>
	{include file="common_templates/period_selector.tpl" period=$search.period form_name="appointments_search_form" time_from=$search.time_from time_to=$search.time_to}
</div>

{/capture}

{include file="common_templates/advanced_search.tpl" content=$smarty.capture.advanced_search dispatch="appointments.manage" view_type="appointments"}

</form>
{/capture}
{include file="common_templates/section.tpl" section_content=$smarty.capture.section}
