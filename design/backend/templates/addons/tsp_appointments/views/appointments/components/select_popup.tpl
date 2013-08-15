{if $display == "text"}
	<span class="view-status">
		{if $status == "O"}
			{$lang.open}
		{elseif $status == "S"}
			{$lang.tspa_scheduled}
		{elseif $status == "X"}
			{$lang.cancelled}
		{elseif $status == "C"}
			{$lang.completed}
		{/if}
	</span>
{else}
	{assign var="prefix" value=$prefix|default:"select"}
	<div class="select-popup-container {$popup_additional_class}">
		{if !$hide_for_vendor}
		<div {if $id}id="sw_{$prefix}_{$id}_wrap"{/if} class="{if $statuses[$status].color}selected-status-base{else}selected-status status-{if $suffix}{$suffix}-{/if}{$status|lower}{/if}{if $id} cm-combo-on cm-combination{/if}">
			<a {if $id}class="cm-combo-on{if !$popup_disabled} cm-combination{/if}"{/if}>
		{/if}
			{if $items_status}
				{if !$items_status|is_array}
					{assign var="items_status" value=$items_status|fn_from_json}
				{/if}
				{$items_status.$status}
			{else}
				{if $status == "O"}
					{$lang.open}
				{elseif $status == "S"}
					{$lang.tspa_scheduled}
				{elseif $status == "X"}
					{$lang.cancelled}
				{elseif $status == "C"}
					{$lang.completed}
				{/if}
			{/if}
		{if !$hide_for_vendor}
			</a>
			{if $statuses[$status].color}
			<span class="selected-status-icon" style="background-color: #{$statuses[$status].color}">&nbsp;</span>
			{/if}

		</div>
		{/if}
		{if $id && !$hide_for_vendor}
			{assign var="_update_controller" value=$update_controller|default:"tools"}
			{if $table && $object_id_name}{capture name="_extra"}&amp;table={$table}&amp;id_name={$object_id_name}{/capture}{/if}
			<div id="{$prefix}_{$id}_wrap" class="popup-tools cm-popup-box cm-smart-position hidden">
				<div class="status-scroll-y">
				<ul class="cm-select-list">
				{if $items_status}
					{foreach from=$items_status item="val" key="st"}
					<li><a class="{if $confirm}cm-confirm {/if}status-link-{$st|lower} {if $status == $st}cm-active{else}cm-ajax{/if}"{if $status_rev} rev="{$status_rev}"{/if} href="{"`$_update_controller`.update_status?id=`$id`&amp;status=`$st``$smarty.capture._extra``$extra`"|fn_url}" onclick="return fn_check_object_status(this, '{$st|lower}', '{if $statuses}{$statuses[$st].color|default:''}{/if}');" name="update_object_status_callback">{$val}</a></li>
					{/foreach}
				{else}
					<li><a class="{if $confirm}cm-confirm {/if}status-link-o {if $status == "O"}cm-active{else}cm-ajax{/if}"{if $status_rev} rev="{$status_rev}"{/if} href="{"`$_update_controller`.update_status?`$object_id_name`=`$id`&amp;status=O`$dynamic_object`"|fn_url}" onclick="return fn_check_object_status(this, 'o', '');" name="update_object_status_callback">{$lang.open}</a></li>
					<li><a class="{if $confirm}cm-confirm {/if}status-link-u {if $status == "S"}cm-active{else}cm-ajax{/if}"{if $status_rev} rev="{$status_rev}"{/if} href="{"`$_update_controller`.update_status?`$object_id_name`=`$id`&amp;status=S`$dynamic_object`"|fn_url}" onclick="return fn_check_object_status(this, 'u', '');" name="update_object_status_callback">{$lang.tspa_scheduled}</a></li>
					<li><a class="{if $confirm}cm-confirm {/if}status-link-d {if $status == "X"}cm-active{else}cm-ajax{/if}"{if $status_rev} rev="{$status_rev}"{/if} href="{"`$_update_controller`.update_status?`$object_id_name`=`$id`&amp;status=X`$dynamic_object`"|fn_url}" onclick="return fn_check_object_status(this, 'd', '');" name="update_object_status_callback">{$lang.cancelled}</a></li>
					<li><a class="{if $confirm}cm-confirm {/if}status-link-s {if $status == "C"}cm-active{else}cm-ajax{/if}"{if $status_rev} rev="{$status_rev}"{/if} href="{"`$_update_controller`.update_status?`$object_id_name`=`$id`&amp;status=C`$dynamic_object`"|fn_url}" onclick="return fn_check_object_status(this, 's', '');" name="update_object_status_callback">{$lang.completed}</a></li>
 				{/if}
				</ul>
				</div>
				{capture name="list_items"}
				{if $notify}
					<li class="select-field">
						<input type="checkbox" name="__notify_user" id="{$prefix}_{$id}_notify" value="Y" class="checkbox" checked="checked" onclick="$('input[name=__notify_user]').attr('checked', this.checked);" />
						<label for="{$prefix}_{$id}_notify">{$notify_text|default:$lang.notify_customer}</label>
					</li>
				{/if}
				{if $notify_department}
					<li class="select-field notify-department">
						<input type="checkbox" name="__notify_department" id="{$prefix}_{$id}_notify_department" value="Y" class="checkbox" checked="checked" onclick="$('input[name=__notify_department]').attr('checked', this.checked);" />
						<label for="{$prefix}_{$id}_notify_department">{$lang.notify_orders_department}</label>
					</li>
				{/if}
				
				{if $notify_supplier}
					<li class="select-field notify-department">
						<input type="checkbox" name="__notify_supplier" id="{$prefix}_{$id}_notify_supplier" value="Y" class="checkbox" checked="checked" onclick="$('input[name=__notify_supplier]').attr('checked', this.checked);" />
						<label for="{$prefix}_{$id}_notify_supplier">{if $smarty.const.PRODUCT_TYPE == "MULTIVENDOR" || $smarty.const.PRODUCT_TYPE == "ULTIMATE"}{$lang.notify_vendor}{else}{$lang.notify_supplier}{/if}</label>
					</li>
				{/if}
				
				{/capture}
				
				{if $smarty.capture.list_items|trim}
				<ul class="cm-select-list select-list-tools">
					{$smarty.capture.list_items}
				</ul>
				{/if}
			</div>
			{if !$smarty.capture.avail_box}
			<script type="text/javascript">
			//<![CDATA[
			{literal}
			function fn_check_object_status(obj, status, color) 
			{
				if ($(obj).hasClass('cm-active')) {
					$(obj).removeClass('cm-ajax');
					return false;
				}
				fn_update_object_status(obj, status, color);
				return true;
			}
			function fn_update_object_status_callback(data, params) 
			{
				if (data.return_status && params.obj) {
					var color = data.color ? data.color : '';
					fn_update_object_status(params.obj, data.return_status.toLowerCase(), color);
				}
			}
			function fn_update_object_status(obj, status, color)
			{
				var upd_elm_id = $(obj).parents('.cm-popup-box:first').attr('id');
				var upd_elm = $('#' + upd_elm_id);
				upd_elm.hide();
				$(obj).attr('href', fn_query_remove($(obj).attr('href'), ['notify_user', 'notify_department']));
				if ($('input[name=__notify_user]:checked', upd_elm).length) {
					$(obj).attr('href', $(obj).attr('href') + '&notify_user=Y');
				}
				if ($('input[name=__notify_department]:checked', upd_elm).length) {
					$(obj).attr('href', $(obj).attr('href') + '&notify_department=Y');
				}
				
				if ($('input[name=__notify_supplier]:checked', upd_elm).length) {
					$(obj).attr('href', $(obj).attr('href') + '&notify_supplier=Y');
				}
				
				$('.cm-select-list li a', upd_elm).removeClass('cm-active').addClass('cm-ajax');
				$('.status-link-' + status, upd_elm).addClass('cm-active');
				$('#sw_' + upd_elm_id + ' a').text($('.status-link-' + status, upd_elm).text());
				if (color) {
					$('#sw_' + upd_elm_id).removeAttr('class').addClass('selected-status-base ' + $('#sw_' + upd_elm_id + ' a').attr('class'));
					$('#sw_' + upd_elm_id).children('.selected-status-icon:first').css('background-color', '#' + color);
				} else {
					{/literal}
					$('#sw_' + upd_elm_id).removeAttr('class').addClass('selected-status status-{if $suffix}{$suffix}-{/if}' + status + ' ' + $('#sw_' + upd_elm_id + ' a').attr('class'));
					{literal}
				}
			}
			{/literal}
			//]]>
			</script>
			{capture name="avail_box"}Y{/capture}
			{/if}
		{/if}
	</div>
{/if}