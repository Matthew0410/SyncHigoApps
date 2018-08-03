<?php
	set_time_limit(0);

	$default = new stdClass();
	$default->prod = TRUE;
	$default->online = TRUE;

	$default->server = 'localhost';

	$default->database = 'higo_router_wifi';
	$default->username = 'root';
	$default->password = '';

	$default->mysqli = new mysqli($default->server, $default->username, $default->password, $default->database);

	$default->now = mktime(0, 0, 0);
	$default->now_date = date('Y-m-d 00:00:00', $default->now);

	function query_select($mysqli, $sql, $obj = FALSE)
	{
		$query_sql = $mysqli->query($sql);

		if ($query_sql->num_rows === 0)
		{
			$query_sql->free_result();

			return NULL;
		}
		elseif ($obj && $query_sql->num_rows === 1)
		{
			$obj_data = $query_sql->fetch_object();
			$query_sql->free_result();

			return $obj_data;
		}
		else
		{
			if ($query_sql->field_count > 1)
			{
				$arr_data = array();

				while ($row = $query_sql->fetch_object())
				{
					$arr_data[] = clone $row;
				}

				$query_sql->free_result();

				return $arr_data;
			}
			else
			{
				$arr_data = array();

				while ($row = $query_sql->fetch_object())
				{
					foreach ($row as $k => $v)
					{
						$arr_data[] = $v;
					}
				}

				$query_sql->free_result();

				return $arr_data;
			}
		}
	}

	$from_date = $_GET['date'];

	$arr_from_date = getdate($from_date);

	$from = date('Y-m-d H:i:s', mktime(0, 0, 0, $arr_from_date['mon'], 1, $arr_from_date['year']));
	$to = date('Y-m-d H:i:s', mktime(0, 0, 0, $arr_from_date['mon'] + 1, 0, $arr_from_date['year']));

	$month = date('F', $from_date);
	$year = date('Y', $from_date);

	$prev_month = mktime(0, 0, 0, $arr_from_date['mon'] - 1, 1, $arr_from_date['year']);
	$next_month = mktime(0, 0, 0, $arr_from_date['mon'] + 1, 1, $arr_from_date['year']);

	$sql_higo_router = 'SELECT id, name ';
	$sql_higo_router .= 'FROM higo_router ';
	$sql_higo_router .= "WHERE status = 1 AND landing_page = 1 ";
	$sql_higo_router .= 'ORDER BY name';
	$arr_higo_router = (query_select($default->mysqli, $sql_higo_router)) ? query_select($default->mysqli, $sql_higo_router) : array();

	$arr_higo_router_id = array();

	foreach ($arr_higo_router as $higo_router)
	{
		$arr_higo_router_id[] = $higo_router->id;
	}

	$sql_login = 'SELECT higo_router_id, mac,DATE_FORMAT(`date`, "%Y-%m-%d") AS `date_format`, COUNT(id) AS count_data ';
	$sql_login .= 'FROM login ';
	$sql_login .= "WHERE higo_router_id IN (".implode(',', $arr_higo_router_id).") AND date >= '{$from}' AND date <= '{$to}' ";
	$sql_login .= 'GROUP BY higo_router_id, date,mac';
	$arr_login = (query_select($default->mysqli, $sql_login)) ? query_select($default->mysqli, $sql_login) : array();

  $arr_login_lookup = array();
	$arr_mac_lookup = array();

	foreach ($arr_login as $login)
	{
		$arr_mac_lookup[$login->higo_router_id][$login->date_format][$login->mac] = $login->mac;
		$arr_login_lookup[$login->higo_router_id][$login->date_format][$login->mac] = $login->count_data;
	}

	$sql_log = 'SELECT higo_router_id, mac, DATE_FORMAT(`date`, "%Y-%m-%d") AS `date_format`, COUNT(id) AS count_data ';
	$sql_log .= 'FROM `log` ';
	$sql_log .= "WHERE higo_router_id IN (".implode(',', $arr_higo_router_id).") AND date >= '{$from}' AND date <= '{$to}' ";
	$sql_log .= 'GROUP BY higo_router_id, mac, `date` ';
	$arr_log = (query_select($default->mysqli, $sql_log)) ? query_select($default->mysqli, $sql_log) : array();

	$arr_log_lookup = array();

	foreach ($arr_log as $log)
	{
		$arr_log_lookup[$log->higo_router_id][$log->date_format][$log->mac] = clone $log;
	}


	$sql_confirm = 'SELECT higo_router_id, mac, DATE_FORMAT(`date`, "%Y-%m-%d") AS `date_format`, COUNT(id) AS count_data ';
	$sql_confirm .= 'FROM confirmation_page ';
	$sql_confirm .= "WHERE higo_router_id IN (".implode(',', $arr_higo_router_id).") AND date >= '{$from}' AND date <= '{$to}' ";
	$sql_confirm .= 'GROUP BY higo_router_id, `date`, mac';
	$arr_confirm = (query_select($default->mysqli, $sql_confirm)) ? query_select($default->mysqli, $sql_confirm) : array();

	$arr_confirm_lookup = array();

	foreach ($arr_confirm as $confirm)
	{
		$arr_confirm_lookup[$confirm->higo_router_id][$confirm->date_format][$confirm->mac] = $confirm->count_data;
	}

	$sql_alogin = 'SELECT higo_router_id,  mac,DATE_FORMAT(`date`, "%Y-%m-%d") AS `date_format`, COUNT(id) AS count_data ';
	$sql_alogin .= 'FROM alogin ';
	$sql_alogin .= "WHERE higo_router_id IN (".implode(',', $arr_higo_router_id).") AND date >= '{$from}' AND date <= '{$to}' ";
	$sql_alogin .= 'GROUP BY higo_router_id, `date`, mac';
	$arr_alogin = (query_select($default->mysqli, $sql_alogin)) ? query_select($default->mysqli, $sql_alogin) : array();

	$arr_alogin_lookup = array();

	foreach ($arr_alogin as $alogin)
	{
		$arr_alogin_lookup[$alogin->higo_router_id][$alogin->date_format][$alogin->mac] = $alogin->count_data;
	}

	$arr_date = array();

	if ($from_date != '')
	{
		while ($from_date <= mktime(0, 0, 0, $arr_from_date['mon'] + 1, 0, $arr_from_date['year']))
		{
			$arr_date[] = $from_date;
			$arr_from_date = getdate($from_date);
			$from_date = mktime(0, 0, 0, $arr_from_date['mon'], $arr_from_date['mday'] + 1, $arr_from_date['year']);
		}
	}

	require('phpexcel/PHPExcel.php');

	//Start Non Unique Sheet
	$phpexcel = new PHPExcel();

	$phpexcel->setActiveSheetIndex(0);
	$phpexcel->getActiveSheet()->setTitle('Non Unique');

	$row = 2;

	$phpexcel->getActiveSheet()->SetCellValue("B{$row}", 'HIGO Merchants | '.$month.' '.$year);

	$next_row = $row + 1;
	$phpexcel->getActiveSheet()->mergeCells("B{$row}:M{$next_row}");
	$phpexcel->getActiveSheet()->getStyle("B{$row}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$phpexcel->getActiveSheet()->getStyle("B{$row}")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

	$row += 2;

	$phpexcel->getActiveSheet()->SetCellValue("B{$row}", 'NO');
	$phpexcel->getActiveSheet()->SetCellValue("C{$row}", 'Merchant');
	$phpexcel->getActiveSheet()->SetCellValue("F{$row}", 'PUB');
	$phpexcel->getActiveSheet()->SetCellValue("G{$row}", 'Data');
	$phpexcel->getActiveSheet()->SetCellValue("H{$row}", 'Rate 1');
	$phpexcel->getActiveSheet()->SetCellValue("I{$row}", 'TVC');
	$phpexcel->getActiveSheet()->SetCellValue("J{$row}", 'Rate 2');
	$phpexcel->getActiveSheet()->SetCellValue("K{$row}", 'SP');
	$phpexcel->getActiveSheet()->SetCellValue("L{$row}", 'Rate 3');
	$phpexcel->getActiveSheet()->SetCellValue("M{$row}", 'Rate 4');

	$phpexcel->getActiveSheet()->getStyle("B{$row}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$phpexcel->getActiveSheet()->getStyle("C{$row}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$phpexcel->getActiveSheet()->getStyle("F{$row}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$phpexcel->getActiveSheet()->getStyle("G{$row}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$phpexcel->getActiveSheet()->getStyle("H{$row}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$phpexcel->getActiveSheet()->getStyle("I{$row}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$phpexcel->getActiveSheet()->getStyle("J{$row}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$phpexcel->getActiveSheet()->getStyle("K{$row}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$phpexcel->getActiveSheet()->getStyle("L{$row}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$phpexcel->getActiveSheet()->getStyle("M{$row}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

	$phpexcel->getActiveSheet()->getStyle("B{$row}")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	$phpexcel->getActiveSheet()->getStyle("C{$row}")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	$phpexcel->getActiveSheet()->getStyle("F{$row}")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	$phpexcel->getActiveSheet()->getStyle("G{$row}")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	$phpexcel->getActiveSheet()->getStyle("H{$row}")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	$phpexcel->getActiveSheet()->getStyle("I{$row}")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	$phpexcel->getActiveSheet()->getStyle("J{$row}")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	$phpexcel->getActiveSheet()->getStyle("K{$row}")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	$phpexcel->getActiveSheet()->getStyle("L{$row}")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	$phpexcel->getActiveSheet()->getStyle("M{$row}")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

	$row += 1;
	$before_row = $row - 1;
	$phpexcel->getActiveSheet()->mergeCells("B{$before_row}:B{$row}");
	$phpexcel->getActiveSheet()->mergeCells("C{$before_row}:E{$row}");
	$phpexcel->getActiveSheet()->mergeCells("F{$before_row}:F{$row}");
	$phpexcel->getActiveSheet()->mergeCells("G{$before_row}:G{$row}");
	$phpexcel->getActiveSheet()->mergeCells("H{$before_row}:H{$row}");
	$phpexcel->getActiveSheet()->mergeCells("I{$before_row}:I{$row}");
	$phpexcel->getActiveSheet()->mergeCells("J{$before_row}:J{$row}");
	$phpexcel->getActiveSheet()->mergeCells("K{$before_row}:K{$row}");
	$phpexcel->getActiveSheet()->mergeCells("L{$before_row}:L{$row}");
	$phpexcel->getActiveSheet()->mergeCells("M{$before_row}:M{$row}");

	$number = 0;
	$total_login = 0;
	$total_data = 0;
	$total_confirm = 0;
	$total_success = 0;

	foreach ($arr_higo_router as $higo_router)
	{
		$row += 1;
		$number += 1;

		$phpexcel->getActiveSheet()->SetCellValue("B{$row}", $number);
		$phpexcel->getActiveSheet()->SetCellValue("C{$row}", $higo_router->name);

		$after_row = $row + 1;
		$phpexcel->getActiveSheet()->mergeCells("B{$row}:B{$after_row}");
		$phpexcel->getActiveSheet()->mergeCells("C{$row}:E{$after_row}");

		$phpexcel->getActiveSheet()->getStyle("B{$row}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$phpexcel->getActiveSheet()->getStyle("B{$row}")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

		$phpexcel->getActiveSheet()->getStyle("C{$row}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$phpexcel->getActiveSheet()->getStyle("C{$row}")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

		$count_date = 0;
		$count_login = 0;
		$count_data = 0;
		$count_confirm = 0;
		$count_success = 0;
		$count_rate = 0;
		$count_rate2 = 0;
		$count_rate3 = 0;
		$count_rate4 = 0;

		foreach ($arr_date as $date)
		{
			$count_date += (!isset($arr_login_lookup[$higo_router->id][date('Y-m-d', $date)])) ? 1 : 0;

			if (!isset($arr_mac_lookup[$higo_router->id][date('Y-m-d', $date)]))
			{
				$data = new stdClass();
				$data->date = date('Y-m-d', $date);
				$data->date_timestamp = $date;
				$data->mac = '';
				$data->login = 0;
				$data->data = 0;
				$data->confirm = 0;
				$data->success = 0;
				$arr_data[$data->date][] = clone $data;

				continue;
			}

      foreach ($arr_mac_lookup[$higo_router->id][date('Y-m-d', $date)] as $mac)
			{
				$count_date += (isset($arr_login_lookup[$higo_router->id][date('Y-m-d', $date)][$mac])) ? 1 : 0;
        $login = (isset($arr_login_lookup[$higo_router->id][date('Y-m-d', $date)][$mac])) ? $arr_login_lookup[$higo_router->id][date('Y-m-d', $date)][$mac] : 0;
        $data = (isset($arr_log_lookup[$higo_router->id][date('Y-m-d', $date)][$mac])) ? $arr_log_lookup[$higo_router->id][date('Y-m-d', $date)][$mac]->count_data : 0;
        $confirm = (isset($arr_confirm_lookup[$higo_router->id][date('Y-m-d', $date)][$mac])) ? $arr_confirm_lookup[$higo_router->id][date('Y-m-d', $date)][$mac] : 0;
        $success = (isset($arr_alogin_lookup[$higo_router->id][date('Y-m-d', $date)][$mac])) ? $arr_alogin_lookup[$higo_router->id][date('Y-m-d', $date)][$mac] : 0;

				// if (isset($arr_log_lookup[$higo_router->id][date('Y-m-d', $date)][$mac])) {
				// 	echo date('d', $date). ' &nbsp; &nbsp;&nbsp;'.$mac. ' '. $data .'<br>';
				// }

        $count_login += $login;
        $count_data += $data;
        $count_confirm += $confirm;
        $count_success += $success;

        $total_login += $login;
        $total_data += $data;
        $total_confirm += $confirm;
        $total_success += $success;

				$count_rate += ($login > 0) ? round(($data / $login) * 100) : 0;
				$count_rate2 += ($data > 0) ? round(($confirm / $data) * 100) : 0;
				$count_rate3 += ($confirm > 0) ? round(($success / $confirm) * 100) : 0;
				$count_rate4 += ($login > 0) ? round(($success / $login) * 100) : 0;
      }
		}

		// var_dump(isset($arr_login_lookup[$higo_router->id][date('Y-m-d', $date)][$mac]));

		$phpexcel->getActiveSheet()->SetCellValue("F{$row}", $count_login);
		$phpexcel->getActiveSheet()->SetCellValue("G{$row}", $count_data);
		$phpexcel->getActiveSheet()->SetCellValue("I{$row}", $count_confirm);
		$phpexcel->getActiveSheet()->SetCellValue("K{$row}", $count_success);

		$row += 1;

		$phpexcel->getActiveSheet()->SetCellValue("F{$row}", round($count_login / $count_date, 2));
		$phpexcel->getActiveSheet()->SetCellValue("G{$row}", round($count_data / $count_date, 2));
		$phpexcel->getActiveSheet()->SetCellValue("H{$row}", round($count_rate / $count_date) / 100);

		$phpexcel->getActiveSheet()->getStyle("H{$row}")->getNumberFormat()->applyFromArray(array(
			'code' => PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE
		));

		$phpexcel->getActiveSheet()->SetCellValue("I{$row}", round($count_confirm / $count_date, 2));
		$phpexcel->getActiveSheet()->SetCellValue("J{$row}", round($count_rate2 / $count_date) / 100);

		$phpexcel->getActiveSheet()->getStyle("J{$row}")->getNumberFormat()->applyFromArray(array(
			'code' => PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE
		));

		$phpexcel->getActiveSheet()->SetCellValue("K{$row}", round($count_success / $count_date, 2));
		$phpexcel->getActiveSheet()->SetCellValue("L{$row}", round($count_rate3 / $count_date) / 100);

		$phpexcel->getActiveSheet()->getStyle("L{$row}")->getNumberFormat()->applyFromArray(array(
			'code' => PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE
		));

		$phpexcel->getActiveSheet()->SetCellValue("M{$row}", round($count_rate4 / $count_date) / 100);

		$phpexcel->getActiveSheet()->getStyle("M{$row}")->getNumberFormat()->applyFromArray(array(
			'code' => PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE
		));
	}

	$row += 1;

	$phpexcel->getActiveSheet()->SetCellValue("F{$row}", $total_login);
	$phpexcel->getActiveSheet()->SetCellValue("G{$row}", $total_data);
	$phpexcel->getActiveSheet()->SetCellValue("I{$row}", $total_confirm);
	$phpexcel->getActiveSheet()->SetCellValue("K{$row}", $total_success);

	//End Non Unique Sheets

	/*========== Start Unique Sheets ==========*/

	$phpexcel->createSheet();
	$phpexcel->setActiveSheetIndex(1);
	$phpexcel->getActiveSheet()->setTitle('Unique');

	$row = 2;

	$phpexcel->getActiveSheet()->SetCellValue("B{$row}", 'HIGO Merchants | '.$month.' '.$year);

	$next_row = $row + 1;
	$phpexcel->getActiveSheet()->mergeCells("B{$row}:V{$next_row}");
	$phpexcel->getActiveSheet()->getStyle("B{$row}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$phpexcel->getActiveSheet()->getStyle("B{$row}")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

	$row += 2;

	$phpexcel->getActiveSheet()->SetCellValue("B{$row}", 'NO');
	$phpexcel->getActiveSheet()->SetCellValue("C{$row}", 'Merchant');
	$phpexcel->getActiveSheet()->SetCellValue("F{$row}", 'T Mac');
	$phpexcel->getActiveSheet()->SetCellValue("G{$row}", 'HT Mac');
	$phpexcel->getActiveSheet()->SetCellValue("H{$row}", 'H Mac');
	$phpexcel->getActiveSheet()->SetCellValue("I{$row}", '0 Mac');
	$phpexcel->getActiveSheet()->SetCellValue("J{$row}", 'Rate 4');
	$phpexcel->getActiveSheet()->SetCellValue("K{$row}", '> 0 Mac');
	$phpexcel->getActiveSheet()->SetCellValue("L{$row}", 'Rate 5');
	$phpexcel->getActiveSheet()->SetCellValue("M{$row}", '1 Mac');
	$phpexcel->getActiveSheet()->SetCellValue("N{$row}", 'Rate 6');
	$phpexcel->getActiveSheet()->SetCellValue("O{$row}", '> 1');
	$phpexcel->getActiveSheet()->SetCellValue("P{$row}", 'Rate 7');
	$phpexcel->getActiveSheet()->SetCellValue("Q{$row}", 'TB Mac');
	$phpexcel->getActiveSheet()->SetCellValue("R{$row}", 'Rate 8');
	$phpexcel->getActiveSheet()->SetCellValue("S{$row}", 'TVC');
	$phpexcel->getActiveSheet()->SetCellValue("T{$row}", 'Rate 9');
	$phpexcel->getActiveSheet()->SetCellValue("U{$row}", 'SP');
	$phpexcel->getActiveSheet()->SetCellValue("V{$row}", 'Rate 10');

	$phpexcel->getActiveSheet()->getStyle("B{$row}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$phpexcel->getActiveSheet()->getStyle("C{$row}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$phpexcel->getActiveSheet()->getStyle("F{$row}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$phpexcel->getActiveSheet()->getStyle("G{$row}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$phpexcel->getActiveSheet()->getStyle("H{$row}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$phpexcel->getActiveSheet()->getStyle("I{$row}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$phpexcel->getActiveSheet()->getStyle("J{$row}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$phpexcel->getActiveSheet()->getStyle("K{$row}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$phpexcel->getActiveSheet()->getStyle("L{$row}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$phpexcel->getActiveSheet()->getStyle("M{$row}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$phpexcel->getActiveSheet()->getStyle("N{$row}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$phpexcel->getActiveSheet()->getStyle("O{$row}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$phpexcel->getActiveSheet()->getStyle("P{$row}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$phpexcel->getActiveSheet()->getStyle("Q{$row}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$phpexcel->getActiveSheet()->getStyle("R{$row}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$phpexcel->getActiveSheet()->getStyle("S{$row}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$phpexcel->getActiveSheet()->getStyle("T{$row}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$phpexcel->getActiveSheet()->getStyle("U{$row}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$phpexcel->getActiveSheet()->getStyle("V{$row}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

	$phpexcel->getActiveSheet()->getStyle("B{$row}")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	$phpexcel->getActiveSheet()->getStyle("C{$row}")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	$phpexcel->getActiveSheet()->getStyle("F{$row}")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	$phpexcel->getActiveSheet()->getStyle("G{$row}")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	$phpexcel->getActiveSheet()->getStyle("H{$row}")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	$phpexcel->getActiveSheet()->getStyle("I{$row}")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	$phpexcel->getActiveSheet()->getStyle("J{$row}")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	$phpexcel->getActiveSheet()->getStyle("K{$row}")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	$phpexcel->getActiveSheet()->getStyle("L{$row}")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	$phpexcel->getActiveSheet()->getStyle("M{$row}")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	$phpexcel->getActiveSheet()->getStyle("N{$row}")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	$phpexcel->getActiveSheet()->getStyle("O{$row}")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	$phpexcel->getActiveSheet()->getStyle("P{$row}")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	$phpexcel->getActiveSheet()->getStyle("Q{$row}")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	$phpexcel->getActiveSheet()->getStyle("R{$row}")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	$phpexcel->getActiveSheet()->getStyle("S{$row}")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	$phpexcel->getActiveSheet()->getStyle("T{$row}")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	$phpexcel->getActiveSheet()->getStyle("U{$row}")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	$phpexcel->getActiveSheet()->getStyle("V{$row}")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

	$row += 1;
	$before_row = $row - 1;
	$phpexcel->getActiveSheet()->mergeCells("B{$before_row}:B{$row}");
	$phpexcel->getActiveSheet()->mergeCells("C{$before_row}:E{$row}");
	$phpexcel->getActiveSheet()->mergeCells("F{$before_row}:F{$row}");
	$phpexcel->getActiveSheet()->mergeCells("G{$before_row}:G{$row}");
	$phpexcel->getActiveSheet()->mergeCells("H{$before_row}:H{$row}");
	$phpexcel->getActiveSheet()->mergeCells("I{$before_row}:I{$row}");
	$phpexcel->getActiveSheet()->mergeCells("J{$before_row}:J{$row}");
	$phpexcel->getActiveSheet()->mergeCells("K{$before_row}:K{$row}");
	$phpexcel->getActiveSheet()->mergeCells("L{$before_row}:L{$row}");
	$phpexcel->getActiveSheet()->mergeCells("M{$before_row}:M{$row}");
	$phpexcel->getActiveSheet()->mergeCells("N{$before_row}:N{$row}");
	$phpexcel->getActiveSheet()->mergeCells("O{$before_row}:O{$row}");
	$phpexcel->getActiveSheet()->mergeCells("P{$before_row}:P{$row}");
	$phpexcel->getActiveSheet()->mergeCells("Q{$before_row}:Q{$row}");
	$phpexcel->getActiveSheet()->mergeCells("R{$before_row}:R{$row}");
	$phpexcel->getActiveSheet()->mergeCells("S{$before_row}:S{$row}");
	$phpexcel->getActiveSheet()->mergeCells("T{$before_row}:T{$row}");
	$phpexcel->getActiveSheet()->mergeCells("U{$before_row}:U{$row}");
	$phpexcel->getActiveSheet()->mergeCells("V{$before_row}:V{$row}");


	$number = 0;
	$total_login = 0;
	$total_htMac = 0;
	$total_hMac = 0;
	$total_success = 0;

	foreach ($arr_higo_router as $higo_router)
	{
	  $row += 1;
	  $number += 1;

	  $phpexcel->getActiveSheet()->SetCellValue("B{$row}", $number);
	  $phpexcel->getActiveSheet()->SetCellValue("C{$row}", $higo_router->name);

	  $after_row = $row + 1;
	  $phpexcel->getActiveSheet()->mergeCells("B{$row}:B{$after_row}");
	  $phpexcel->getActiveSheet()->mergeCells("C{$row}:E{$after_row}");

	  $phpexcel->getActiveSheet()->getStyle("B{$row}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	  $phpexcel->getActiveSheet()->getStyle("B{$row}")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

	  $phpexcel->getActiveSheet()->getStyle("C{$row}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	  $phpexcel->getActiveSheet()->getStyle("C{$row}")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

	  $count_date = 0;
	  $count_login = 0;
		$count_htMac = 0;
		$count_hMac = 0;

	  foreach ($arr_date as $date)
	  {
	    $count_date += (!isset($arr_login_lookup[$higo_router->id][date('Y-m-d', $date)])) ? 1 : 0;

	    if (!isset($arr_mac_lookup[$higo_router->id][date('Y-m-d', $date)]))
	    {
	      $data = new stdClass();
	      $data->date = date('Y-m-d', $date);
	      $data->date_timestamp = $date;
	      $data->mac = '';
	      $data->login = 0;
	      $data->data = 0;
	      $data->confirm = 0;
	      $data->success = 0;
	      $arr_data[$data->date][] = clone $data;

				$htMac = (!isset($arr_mac_lookup[$higo_router->id][date('Y-m-d', $date)][$mac])) ? 1 : 0;
				$count_htMac += $htMac;

				$total_htMac += $htMac;

	      continue;
	    }

	    foreach ($arr_mac_lookup[$higo_router->id][date('Y-m-d', $date)] as $mac)
	    {
	      $login2 = (isset($arr_mac_lookup[$higo_router->id][date('Y-m-d', $date)][$mac])) ? 1 : 0;

	      $count_login += $login2;

	      $total_login += $login2;
	    }
			$hMac = (isset($arr_mac_lookup[$higo_router->id][date('Y-m-d', $date)][$mac])) ? 1 : 0;
			$count_hMac += $hMac;
			$total_hMac += $hMac;
	  }

	  $phpexcel->getActiveSheet()->SetCellValue("F{$row}", $count_login);
		$phpexcel->getActiveSheet()->SetCellValue("G{$row}", $count_htMac);
		$phpexcel->getActiveSheet()->SetCellValue("H{$row}", $count_hMac);

	  $row += 1;

	  $phpexcel->getActiveSheet()->SetCellValue("F{$row}", round($count_login / $count_date, 2));

	  $phpexcel->getActiveSheet()->getStyle("H{$row}")->getNumberFormat()->applyFromArray(array(
	    'code' => PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE
	  ));

	  $phpexcel->getActiveSheet()->getStyle("J{$row}")->getNumberFormat()->applyFromArray(array(
	    'code' => PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE
	  ));

	  $phpexcel->getActiveSheet()->getStyle("L{$row}")->getNumberFormat()->applyFromArray(array(
	    'code' => PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE
	  ));

	  $phpexcel->getActiveSheet()->getStyle("M{$row}")->getNumberFormat()->applyFromArray(array(
	    'code' => PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE
	  ));
	}

	$row += 1;

	$phpexcel->getActiveSheet()->SetCellValue("F{$row}", $total_login);
	$phpexcel->getActiveSheet()->SetCellValue("G{$row}", $total_htMac);
	$phpexcel->getActiveSheet()->SetCellVAlue("H{$row}", $total_hMac);

	/*============== End Unique Sheet ==============*/

	$writer = PHPExcel_IOFactory::createWriter($phpexcel, 'Excel5');
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="HIGO Merchants - '.$month.' '.$year.'.xls"');
	header('Cache-Control: max-age=0');
	$writer->save('php://output');
?>
