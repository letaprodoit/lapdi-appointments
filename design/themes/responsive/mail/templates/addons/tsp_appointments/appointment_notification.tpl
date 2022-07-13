{* $Id$ *}
                                                   
{include file="common/letter_header.tpl"}

{__("dear")} {if $appointment.user.firstname}{$appointment.user.firstname}{else}{$appointment.user.user_type|fn_get_user_type_description|lower|escape}{/if},<br><br>

{assign var="order_url" value="orders.details?order_id=`$appointment.order.order_id`"|fn_url:'C':'http':'&'}

{__("tspa_appointment_notification_msg")|replace:"[appointment_status]":"<strong>`$appointment.status`</strong>"|replace:"[product_name]":"<strong>`$appointment.product.product`</strong>"|replace:"[order_id]":"<a href='`$order_url`'><strong>`$appointment.order.order_id`</strong></a>"|replace:"[appointment_info]":"<br><br>`$appointment.info`"|html_entity_decode:$smarty.const.ENT_QUOTES:"UTF-8"}<br><br>

{include file="common/letter_footer.tpl"}