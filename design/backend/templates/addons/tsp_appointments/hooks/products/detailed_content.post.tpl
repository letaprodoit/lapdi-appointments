{include file="common/subheader.tpl" title=__("tspa_appointments_title") target="#tsp_appointments_fields"}
{include file="addons/tsp_appointments/views/appointments/components/update_fields.tpl" target_name="tsp_appointments_fields" field_id_prefix="" type="product" array_name="product_data" record=$product_data fields=$tspa_product_addon_fields}