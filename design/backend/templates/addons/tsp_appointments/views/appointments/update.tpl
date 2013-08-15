{if $appointment}
    {assign var="id" value=$appointment.id}
{else}
    {assign var="id" value=0}
{/if}

{capture name="mainbox"}

<div id="tspa_appointments">
    <form action="{""|fn_url}" method="post" name="appointment_update_form" class="form-horizontal form-edit ">
    <input type="hidden" name="appointment_id" value="{$id}" />
    <input type="hidden" name="selected_section" id="selected_section" value="{$smarty.request.selected_section}" />
       
    {capture name="tabsbox"}
    <div id="content_general">
        <fieldset>
             <div class="control-group">
                <label class="control-label">{__("user")}:</label>
                <div class="controls">
                    <a href="{"profiles.update?user_id=`$user_id`"|fn_url}">{$appointment.user.firstname} {$appointment.user.lastname}</a>
                </div>
            </div>
            
            <div class="control-group">
                <label class="control-label">{__("email")}:</label>
                <div class="controls">
                    {$appointment.user.email}
                </div>
            </div>
            
            <div class="control-group">
                <label class="control-label">{__("completed")}:</label>
                 <div class="controls">
                    {$appointment.date_completed|date_format:$settings.Appearance.date_format}
                </div>
            </div>
            
            <div class="control-group">
                <label class="control-label">{__("order")}:</label>
                <div class="controls">
                    <a href="{"orders.details?order_id=`$appointment.order_id`"|fn_url}">#{$appointment.order_id}</a>
                </div>
            </div>
            
            <div class="control-group">
                <label class="control-label">{__("product")}:</label>
                <div class="controls">
                    <a href="{"products.update?product_id=`$appointment.product_id`"|fn_url}">{$appointment.product.product} (Product Code: {$appointment.product.product_code})</a>
                 </div>
            </div>
            
            <hr>
            
            <div class="control-group">
                <label for="appointment_status" class="control-label cm-required">{__("tspa_appointment")} {__("status")}:</label>
                <div class="controls">
                    <select name="appointment_data[status]" id="appointment_status">
                        {foreach from=$appointment_statuses item="status" key="status_key"}
                            <option value="{$status_key}" {if $status_key == $appointment.status}selected="selected"{/if}>{$status}</option>
                        {/foreach}
                    </select>
                </div>
            </div>
            
            <div class="control-group">
                <label for="appointment_date" class="control-label cm-required">{$appointment.date.description}:</label>
                <div class="controls">
                    <div class="clearfix">
                        {include file="common/calendar.tpl" date_id="appointment_date" date_name="appointment_data[date]" start_year="`$smarty.const.TIME`" date_val=$appointment.date.value}
                    </div>
                </div>
            </div>
            
            <div class="control-group">
                <label for="appointment_time" class="control-label cm-required">{$appointment.time.description}:</label>
                <div class="controls">
                    <input type="text" name="appointment_data[time]" id="appointment_time" size="32" value="{$appointment.time.value}" class="input-text" />
                </div>
            </div>
            
            <div class="control-group">
                <label for="appointment_location" class="control-label cm-required">{$appointment.location.description}:</label>
                <div class="controls">
                    <select name="appointment_data[location]" id="appointment_location">
                        {foreach from=$appointment_locations item="location" key="option_id"}
                            <option value="{$option_id}" {if $option_id == $appointment.location.value}selected="selected"{/if}>{$location.variant_name}</option>
                        {/foreach}
                    </select>
                </div>
            </div>
            
            <div class="control-group">
                <label for="appointment_additional_info" class="control-label">{$appointment.additional_info.description}:</label>
                <div class="controls">
                    <textarea name="appointment_data[additional_info]" id="appointment_additional_info" size="32" class="input-textarea-long" />{$appointment.additional_info.value}</textarea>
                </div>
            </div>
        </fieldset>
    </div>
    {/capture}
    {include file="common/tabsbox.tpl" content=$smarty.capture.tabsbox active_tab=$smarty.request.selected_section track=true}
    </form>
</div>
{capture name="buttons"}
    {include file="buttons/save_cancel.tpl" but_name="dispatch[appointments.update]" but_role="submit-link" but_target_form="appointment_update_form" save=$id}
{/capture}

{if !$id}
    {assign var="title" value="{__("new")}  {__("tspa_appointment")}"}
{else}
    {assign var="title" value="{__("tspa_editing_appointment")} {__("for")} `$appointment.user.lastname`, `$appointment.user.firstname`"}
{/if}

{/capture}
{include file="common/mainbox.tpl" title=$title content=$smarty.capture.mainbox buttons=$smarty.capture.buttons select_languages=true}
