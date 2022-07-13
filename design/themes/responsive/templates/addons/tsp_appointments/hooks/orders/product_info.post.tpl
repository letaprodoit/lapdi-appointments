{if $oi.extra.product_appointment}
<br>
<strong>{__("tspa_meeting_details")}:</strong>&nbsp;
{foreach from=$oi.extra.product_appointment key=k item=pa}
	{assign var="value" value=$pa.value}
	
	<br>{$pa.title}:&nbsp;{$value|unescape}
	{if !$smarty.foreach.pa.last},&nbsp;{/if}
{/foreach}
<br>
<br>
{/if}