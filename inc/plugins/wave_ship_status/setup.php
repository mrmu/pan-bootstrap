<?php

// 取得出貨狀態 wording
function pm_get_ship_status_wording($code) {
	return Wave_Ship_Status_Utils::get_ship_status_wording($code);
}