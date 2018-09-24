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

	$higo_router_id = $_GET['id'];
	$merchant_name = $_GET['name'];
	$from_date = $_GET['date'];

	$arr_from_date = getdate($from_date);

	$from = date('Y-m-d H:i:s', mktime(0, 0, 0, $arr_from_date['mon'], 1, $arr_from_date['year']));
	$to = date('Y-m-d H:i:s', mktime(0, 0, 0, $arr_from_date['mon'] + 1, 0, $arr_from_date['year']));

	$month = date('F', $from_date);
	$year = date('Y', $from_date);

	$prev_month = mktime(0, 0, 0, $arr_from_date['mon'] - 1, 1, $arr_from_date['year']);
	$next_month = mktime(0, 0, 0, $arr_from_date['mon'] + 1, 1, $arr_from_date['year']);

	/*============= Query For Visitor =============*/

	$sql_login = 'SELECT mac, DATE_FORMAT(`date`, "%Y-%m-%d") AS `date_format`, COUNT(id) AS count_data ';
	$sql_login .= 'FROM login ';
	$sql_login .= "WHERE higo_router_id = {$higo_router_id} AND date >= '{$from}' AND date <= '{$to}' ";
	$sql_login .= 'GROUP BY date, mac';
	$arr_login = (query_select($default->mysqli, $sql_login)) ? query_select($default->mysqli, $sql_login) : array();

	$arr_login_lookup = array();
	$arr_mac_lookup = array();

	foreach ($arr_login as $login)
	{
		$arr_mac_lookup[$login->date_format][$login->mac] = $login->mac;
		$arr_login_lookup[$login->date_format][$login->mac] = $login->count_data;
	}

	$sql_log = 'SELECT mac, DATE_FORMAT(`date`, "%Y-%m-%d") AS `date_format`, COUNT(id) AS count_data ';
	$sql_log .= 'FROM `log` ';
	$sql_log .= "WHERE higo_router_id = {$higo_router_id} AND date >= '{$from}' AND date <= '{$to}' ";
	$sql_log .= 'GROUP BY `date`, mac ';
	$sql_log .= 'ORDER BY `date_format` ASC';
	$arr_log = (query_select($default->mysqli, $sql_log)) ? query_select($default->mysqli, $sql_log) : array();

	$arr_log_lookup = array();

	foreach ($arr_log as $log)
	{
		$arr_mac_lookup[$log->date_format][$log->mac] = $log->mac;
		$arr_log_lookup[$log->date_format][$log->mac] = clone $log;
	}

	$sql_confirm = 'SELECT mac, DATE_FORMAT(`date`, "%Y-%m-%d") AS `date_format`, COUNT(id) AS count_data ';
	$sql_confirm .= 'FROM confirmation_page ';
	$sql_confirm .= "WHERE higo_router_id = {$higo_router_id} AND date >= '{$from}' AND date <= '{$to}' ";
	$sql_confirm .= 'GROUP BY date, mac';
	$arr_confirm = (query_select($default->mysqli, $sql_confirm)) ? query_select($default->mysqli, $sql_confirm) : array();

	$arr_confirm_lookup = array();

	foreach ($arr_confirm as $confirm)
	{
		$arr_mac_lookup[$confirm->date_format][$confirm->mac] = $confirm->mac;
		$arr_confirm_lookup[$confirm->date_format][$confirm->mac] = $confirm->count_data;
	}

	$sql_alogin = 'SELECT mac, DATE_FORMAT(`date`, "%Y-%m-%d") AS `date_format`, COUNT(id) AS count_data ';
	$sql_alogin .= 'FROM alogin ';
	$sql_alogin .= "WHERE higo_router_id = {$higo_router_id}  AND date >= '{$from}' AND date <= '{$to}' ";
	$sql_alogin .= 'GROUP BY `date`, mac';
	$arr_alogin = (query_select($default->mysqli, $sql_alogin)) ? query_select($default->mysqli, $sql_alogin) : array();

	$arr_alogin_lookup = array();

	foreach ($arr_alogin as $alogin)
	{
		$arr_mac_lookup[$alogin->date_format][$alogin->mac] = $alogin->mac;
		$arr_alogin_lookup[$alogin->date_format][$alogin->mac] = $alogin->count_data;
	}

	/*============= Query For Visitor =============*/

	/*============= Query For All Mac Address =============*/

	$sql_login2 = 'SELECT mac, COUNT(id) AS count_data ';
	$sql_login2 .= 'FROM login ';
	$sql_login2 .= "WHERE higo_router_id = {$higo_router_id} AND date >= '{$from}' AND date <= '{$to}' ";
	$sql_login2 .= 'GROUP BY mac';
	$arr_login2 = (query_select($default->mysqli, $sql_login2)) ? query_select($default->mysqli, $sql_login2) : array();

	$arr_mac2_lookup = array();

	foreach ($arr_login2 as $login2)
	{
	  $arr_mac2_lookup[$login2->mac] = $login2->mac;
	}

	$sql_log2 = 'SELECT mac, COUNT(id) AS count_data ';
	$sql_log2 .= 'FROM `log` ';
	$sql_log2 .= "WHERE higo_router_id = {$higo_router_id} AND date >= '{$from}' AND date <= '{$to}' ";
	$sql_log2 .= 'GROUP BY mac ';
	$arr_log2 = (query_select($default->mysqli, $sql_log2)) ? query_select($default->mysqli, $sql_log2) : array();

	foreach ($arr_log2 as $log2)
	{
	  $arr_mac2_lookup[$log2->mac] = $log2->mac;
	}

	$sql_confirm2 = 'SELECT mac, COUNT(id) AS count_data ';
	$sql_confirm2 .= 'FROM confirmation_page ';
	$sql_confirm2 .= "WHERE higo_router_id = {$higo_router_id} AND date >= '{$from}' AND date <= '{$to}' ";
	$sql_confirm2 .= 'GROUP BY mac';
	$arr_confirm2 = (query_select($default->mysqli, $sql_confirm2)) ? query_select($default->mysqli, $sql_confirm2) : array();

	foreach ($arr_confirm2 as $confirm2)
	{
	  $arr_mac2_lookup[$confirm2->mac] = $confirm2->mac;
	}

	$sql_alogin2 = 'SELECT mac, COUNT(id) AS count_data ';
	$sql_alogin2 .= 'FROM alogin ';
	$sql_alogin2 .= "WHERE higo_router_id = {$higo_router_id}  AND date >= '{$from}' AND date <= '{$to}' ";
	$sql_alogin2 .= 'GROUP BY mac';
	$arr_alogin2 = (query_select($default->mysqli, $sql_alogin2)) ? query_select($default->mysqli, $sql_alogin2) : array();

	foreach ($arr_alogin2 as $alogin2)
	{
	  $arr_mac2_lookup[$alogin2->mac] = $alogin2->mac;
	}

	/*============= Query For All Mac Address =============*/

	/*============= Query For Login By =============*/

	$sql_login_by = 'SELECT mac, type, DATE_FORMAT(`date`, "%Y-%m-%d") AS `date_format`, COUNT(id) AS count_data ';
	$sql_login_by .= 'FROM `log` ';
	$sql_login_by .= "WHERE higo_router_id = {$higo_router_id} AND date >= '{$from}' AND date <= '{$to}' ";
	$sql_login_by .= 'GROUP BY date,mac ';
	$sql_login_by .= 'ORDER BY `date_format` ASC';
	$arr_login_by = (query_select($default->mysqli, $sql_login_by)) ? query_select($default->mysqli, $sql_login_by) : array();

	$arr_mac_by_lookup = array();
	$arr_login_by_lookup = array();

	foreach ($arr_login_by as $login_by)
	{
		$arr_mac_by_lookup[$login_by->date_format][$login_by->mac] = $login_by->mac;
		$arr_login_by_lookup[$login_by->date_format][$login_by->mac] = $login_by->type;
	}

	$sql_login_by2 = 'SELECT mac, name, DATE_FORMAT(`date`, "%Y-%m-%d") AS `date_format`, COUNT(id) AS count_data ';
	$sql_login_by2 .= 'FROM `log` ';
	$sql_login_by2 .= "WHERE higo_router_id = {$higo_router_id} AND date >= '{$from}' AND date <= '{$to}' ";
	$sql_login_by2 .= 'GROUP BY `date`,mac ';
	$sql_login_by2 .= 'ORDER BY `date_format` ASC';
	$arr_login_by2 = (query_select($default->mysqli, $sql_login_by2)) ? query_select($default->mysqli, $sql_login_by2) : array();

	$arr_login_by2_lookup = array();

	foreach ($arr_login_by2 as $login_by2)
	{
		$arr_mac_by_lookup[$login_by2->date_format][$login_by2->mac] = $login_by2->mac;
		$arr_login_by2_lookup[$login_by2->date_format][$login_by2->mac] = $login_by2->name;
	}

	$sql_login_by3 = 'SELECT mac, email, DATE_FORMAT(`date`, "%Y-%m-%d") AS `date_format`, COUNT(id) AS count_data ';
	$sql_login_by3 .= 'FROM `log` ';
	$sql_login_by3 .= "WHERE higo_router_id = {$higo_router_id} AND date >= '{$from}' AND date <= '{$to}' ";
	$sql_login_by3 .= 'GROUP BY date,mac ';
	$sql_login_by3 .= 'ORDER BY `date_format` ASC';
	$arr_login_by3 = (query_select($default->mysqli, $sql_login_by3)) ? query_select($default->mysqli, $sql_login_by3) : array();

	$arr_login_by3_lookup = array();

	foreach ($arr_login_by3 as $login_by3)
	{
		$arr_mac_by_lookup[$login_by3->date_format][$login_by3->mac] = $login_by3->mac;
		$arr_login_by3_lookup[$login_by3->date_format][$login_by3->mac] = $login_by3->email;
	}

	$sql_login_by4 = 'SELECT mac, gender, DATE_FORMAT(`date`, "%Y-%m-%d") AS `date_format`, COUNT(id) AS count_data ';
	$sql_login_by4 .= 'FROM `log` ';
	$sql_login_by4 .= "WHERE higo_router_id = {$higo_router_id} AND date >= '{$from}' AND date <= '{$to}' ";
	$sql_login_by4 .= 'GROUP BY date,mac ';
	$sql_login_by4 .= 'ORDER BY `date_format` ASC';
	$arr_login_by4 = (query_select($default->mysqli, $sql_login_by4)) ? query_select($default->mysqli, $sql_login_by4) : array();

	$arr_login_by4_lookup = array();

	foreach ($arr_login_by4 as $login_by4)
	{
		$arr_mac_by_lookup[$login_by4->date_format][$login_by4->mac] = $login_by4->mac;
		$arr_login_by4_lookup[$login_by4->date_format][$login_by4->mac] = $login_by4->gender;
	}

	$sql_login_by5 = 'SELECT mac, phone, DATE_FORMAT(`date`, "%Y-%m-%d") AS `date_format`, COUNT(id) AS count_data ';
	$sql_login_by5 .= 'FROM `log` ';
	$sql_login_by5 .= "WHERE higo_router_id = {$higo_router_id} AND date >= '{$from}' AND date <= '{$to}' ";
	$sql_login_by5 .= 'GROUP BY date,mac ';
	$sql_login_by5 .= 'ORDER BY `date_format` ASC';
	$arr_login_by5 = (query_select($default->mysqli, $sql_login_by5)) ? query_select($default->mysqli, $sql_login_by5) : array();

	$arr_login_by5_lookup = array();

	foreach ($arr_login_by5 as $login_by5)
	{
		$arr_mac_by_lookup[$login_by5->date_format][$login_by5->mac] = $login_by5->mac;
		$arr_login_by5_lookup[$login_by5->date_format][$login_by5->mac] = $login_by5->phone;
	}

	$sql_login_by6 = 'SELECT mac, birthday, DATE_FORMAT(`date`, "%Y-%m-%d") AS `date_format`, COUNT(id) AS count_data ';
	$sql_login_by6 .= 'FROM `log` ';
	$sql_login_by6 .= "WHERE higo_router_id = {$higo_router_id} AND date >= '{$from}' AND date <= '{$to}' ";
	$sql_login_by6 .= 'GROUP BY date,mac ';
	$sql_login_by6 .= 'ORDER BY `date_format` ASC';
	$arr_login_by6 = (query_select($default->mysqli, $sql_login_by6)) ? query_select($default->mysqli, $sql_login_by6) : array();

	$arr_login_by6_lookup = array();

	foreach ($arr_login_by6 as $login_by6)
	{
		$arr_mac_by_lookup[$login_by6->date_format][$login_by6->mac] = $login_by6->mac;
		$arr_login_by6_lookup[$login_by6->date_format][$login_by6->mac] = $login_by6->birthday;
	}

	$sql_login_by7 = 'SELECT mac, username, DATE_FORMAT(`date`, "%Y-%m-%d") AS `date_format`, COUNT(id) AS count_data ';
	$sql_login_by7 .= 'FROM `log` ';
	$sql_login_by7 .= "WHERE higo_router_id = {$higo_router_id} AND date >= '{$from}' AND date <= '{$to}' ";
	$sql_login_by7 .= 'GROUP BY date,mac ';
	$sql_login_by7 .= 'ORDER BY `date_format` ASC';
	$arr_login_by7 = (query_select($default->mysqli, $sql_login_by7)) ? query_select($default->mysqli, $sql_login_by7) : array();

	$arr_login_by7_lookup = array();

	foreach ($arr_login_by7 as $login_by7)
	{
		$arr_mac_by_lookup[$login_by7->date_format][$login_by7->mac] = $login_by7->mac;
		$arr_login_by7_lookup[$login_by7->date_format][$login_by7->mac] = $login_by7->username;
	}

	$sql_login_by8 = 'SELECT mac, followers_count, DATE_FORMAT(`date`, "%Y-%m-%d") AS `date_format`, COUNT(id) AS count_data ';
	$sql_login_by8 .= 'FROM `log` ';
	$sql_login_by8 .= "WHERE higo_router_id = {$higo_router_id} AND date >= '{$from}' AND date <= '{$to}' ";
	$sql_login_by8 .= 'GROUP BY date,mac ';
	$sql_login_by8 .= 'ORDER BY `date_format` ASC';
	$arr_login_by8 = (query_select($default->mysqli, $sql_login_by8)) ? query_select($default->mysqli, $sql_login_by8) : array();

	$arr_login_by8_lookup = array();

	foreach ($arr_login_by8 as $login_by8)
	{
		$arr_mac_by_lookup[$login_by8->date_format][$login_by8->mac] = $login_by8->mac;
		$arr_login_by8_lookup[$login_by8->date_format][$login_by8->mac] = $login_by8->followers_count;
	}

	$sql_login_by9 = 'SELECT mac, friends_count, DATE_FORMAT(`date`, "%Y-%m-%d") AS `date_format`, COUNT(id) AS count_data ';
	$sql_login_by9 .= 'FROM `log` ';
	$sql_login_by9 .= "WHERE higo_router_id = {$higo_router_id} AND date >= '{$from}' AND date <= '{$to}' ";
	$sql_login_by9 .= 'GROUP BY date,mac ';
	$sql_login_by9 .= 'ORDER BY `date_format` ASC';
	$arr_login_by9 = (query_select($default->mysqli, $sql_login_by9)) ? query_select($default->mysqli, $sql_login_by9) : array();

	$arr_login_by9_lookup = array();

	foreach ($arr_login_by9 as $login_by9)
	{
		$arr_mac_by_lookup[$login_by->date_format][$login_by9->mac] = $login_by9->mac;
		$arr_login_by9_lookup[$login_by9->date_format][$login_by9->mac] = $login_by9->friends_count;
	}

	/*============= Query For Login By =============*/

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

	$arr_data = array();

	foreach ($arr_date as $date)
	{
		if (!isset($arr_mac_lookup[date('Y-m-d', $date)]))
		{
			$data = new stdClass();
			$data->date = date('Y-m-d', $date);
			$data->date_timestamp = $date;
			$data->mac = '';
			$arr_data[$data->date][] = clone $data;

			continue;
		}

		foreach ($arr_mac_lookup[date('Y-m-d', $date)] as $mac => $mac_lookup)
		{
			$data = new stdClass();
			$data->date = date('Y-m-d', $date);
			$data->date_timestamp = $date;
			$data->mac = $mac;
			$arr_data[$data->date][] = clone $data;
		}
	}

	/*========== Start Download Visit Sheet ==========*/

	require('phpexcel/PHPExcel.php');
	$phpexcel = new PHPExcel();
	$phpexcel->setActiveSheetIndex(0);
	$phpexcel->getActiveSheet()->setTitle('Download Visit');

	$row = 2;

	$phpexcel->getActiveSheet()->SetCellValue("B{$row}", $month);

	$next_row = $row + 1;
	$phpexcel->getActiveSheet()->mergeCells("B{$row}:L{$next_row}");
	$phpexcel->getActiveSheet()->getStyle("B{$row}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$phpexcel->getActiveSheet()->getStyle("B{$row}")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

	$row += 2;

	$phpexcel->getActiveSheet()->SetCellValue("B{$row}", 'No.');
	$phpexcel->getActiveSheet()->SetCellValue("C{$row}", 'Mac');
	$phpexcel->getActiveSheet()->SetCellValue("H{$row}", 'Visit');

	$phpexcel->getActiveSheet()->getStyle("B{$row}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$phpexcel->getActiveSheet()->getStyle("C{$row}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$phpexcel->getActiveSheet()->getStyle("H{$row}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

	$phpexcel->getActiveSheet()->getStyle("B{$row}")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	$phpexcel->getActiveSheet()->getStyle("C{$row}")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	$phpexcel->getActiveSheet()->getStyle("H{$row}")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

	$row += 1;
	$before_row = $row - 1;
	$phpexcel->getActiveSheet()->mergeCells("B{$before_row}:B{$row}");
	$phpexcel->getActiveSheet()->mergeCells("C{$before_row}:G{$row}");
	$phpexcel->getActiveSheet()->mergeCells("H{$before_row}:L{$row}");

	$first_row = $row + 1;
	$number = 0;
	$total_visit = 0;
	$average_visit = 0;
	$range_mac = 0;

	foreach ($arr_mac2_lookup as $mac2)
	{
		$row += 1;
		$number += 1;
		$visit = 0;

		$range_mac += ($mac2 != '' || $mac2 != 0) ? 1 : 0;

		$phpexcel->getActiveSheet()->SetCellValue("B{$row}", $number);
		$phpexcel->getActiveSheet()->SetCellValue("C{$row}", $mac2);

		$phpexcel->getActiveSheet()->mergeCells("C{$row}:G{$row}");
		$phpexcel->getActiveSheet()->getStyle("B{$row}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

		foreach ($arr_data as $data)
		{

			foreach ($data as $k => $v)
			{
					$visit += ($v->mac === $mac2) ? 1 : 0;
					$first_data_row = 0;

					$phpexcel->getActiveSheet()->SetCellValue("H{$row}", $visit.' hari');
					$phpexcel->getActiveSheet()->getStyle("H{$row}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$phpexcel->getActiveSheet()->mergeCells("H{$row}:L{$row}");
			}
		}
		$total_visit += $visit;
		$average_visit = ($visit != '' || $visit != 0) ? round($total_visit / $range_mac, 2) : 0;
	}

	$last_row = $row;

	/*========= Summary All Column =========*/

	$row += 1;

	$phpexcel->getActiveSheet()->SetCellValue("B{$row}", 'TOTAL');
	$phpexcel->getActiveSheet()->mergeCells("B{$row}:G{$row}");
	$phpexcel->getActiveSheet()->SetCellValue("H{$row}", $total_visit);
	$phpexcel->getActiveSheet()->mergeCells("H{$row}:L{$row}");
	$phpexcel->getActiveSheet()->getStyle("H{$row}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$phpexcel->getActiveSheet()->getStyle("H{$row}")->getNumberFormat()->setFormatCode('0.00');

	/*========= Summary All Column =========*/

	/*========= Average All Column =========*/

	$row += 1;

	$phpexcel->getActiveSheet()->SetCellValue("B{$row}", 'AVERAGE');
	$phpexcel->getActiveSheet()->mergeCells("B{$row}:G{$row}");
	$phpexcel->getActiveSheet()->SetCellValue("H{$row}", $average_visit);
	$phpexcel->getActiveSheet()->mergeCells("H{$row}:L{$row}");
	$phpexcel->getActiveSheet()->getStyle("H{$row}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$phpexcel->getActiveSheet()->getStyle("H{$row}")->getNumberFormat()->setFormatCode('0.00');

	/*========= Average All Column =========*/

	/*========== End Download Visit Sheet ==========*/

	$arr_data2 = array();

	foreach ($arr_date as $date)
	{
		if (!isset($arr_mac_by_lookup[date('Y-m-d', $date)]))
		{
			$data = new stdClass();
			$data->date = date('Y-m-d', $date);
			$data->date_timestamp = $date;
			$data->mac = '';
			$data->type = '';
			$data->name = '';
			$data->email = '';
			$data->gender = '';
			$data->phone = '';
			$data->birthday = '';
			$data->username = '';
			$data->followers_count= '';
			$data->friends_count = '';
			$arr_data2[$data->date][] = clone $data;

			continue;
		}

		foreach ($arr_mac_by_lookup[date('Y-m-d', $date)] as $mac_by => $mac_by_lookup)
		{
			$data = new stdClass();
			$data->date = date('Y-m-d', $date);
			$data->date_timestamp = $date;
			$data->mac = $mac_by;
			$data->type = (isset($arr_login_by_lookup[date('Y-m-d', $date)][$mac_by])) ? $arr_login_by_lookup[date('Y-m-d', $date)][$mac_by] : '';
			$data->name = (isset($arr_login_by2_lookup[date('Y-m-d', $date)][$mac_by])) ? $arr_login_by2_lookup[date('Y-m-d', $date)][$mac_by] : '';
			$data->email = (isset($arr_login_by3_lookup[date('Y-m-d', $date)][$mac_by])) ? $arr_login_by3_lookup[date('Y-m-d', $date)][$mac_by] : '';
			$data->gender = (isset($arr_login_by4_lookup[date('Y-m-d', $date)][$mac_by])) ? $arr_login_by4_lookup[date('Y-m-d', $date)][$mac_by] : '';
			$data->phone = (isset($arr_login_by5_lookup[date('Y-m-d', $date)][$mac_by])) ? $arr_login_by5_lookup[date('Y-m-d', $date)][$mac_by] : '';
			$data->birthday = (isset($arr_login_by6_lookup[date('Y-m-d', $date)][$mac_by])) ? $arr_login_by6_lookup[date('Y-m-d', $date)][$mac_by] : '';
			$data->username = (isset($arr_login_by7_lookup[date('Y-m-d', $date)][$mac_by])) ? $arr_login_by7_lookup[date('Y-m-d', $date)][$mac_by] : '';
			$data->followers_count = (isset($arr_login_by8_lookup[date('Y-m-d', $date)][$mac_by])) ? $arr_login_by8_lookup[date('Y-m-d', $date)][$mac_by] : '';
			$data->friends_count = (isset($arr_login_by9_lookup[date('Y-m-d', $date)][$mac_by])) ? $arr_login_by9_lookup[date('Y-m-d', $date)][$mac_by] : '';
			$arr_data2[$data->date][] = clone $data;
		}
	}

	/*========== Start Login By Sheet ==========*/

	$phpexcel->createSheet();
	$phpexcel->setActiveSheetIndex(1);
	$phpexcel->getActiveSheet()->setTitle('Login By');

	$row = 2;

	$phpexcel->getActiveSheet()->SetCellValue("B{$row}", $merchant_name);

	$next_row = $row + 1;
	$phpexcel->getActiveSheet()->mergeCells("B{$row}:T{$next_row}");
	$phpexcel->getActiveSheet()->getStyle("B{$row}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$phpexcel->getActiveSheet()->getStyle("B{$row}")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

	$row += 2;

	$phpexcel->getActiveSheet()->SetCellValue("B{$row}", 'Tanggal');
	$phpexcel->getActiveSheet()->SetCellValue("C{$row}", 'MAC');
	$phpexcel->getActiveSheet()->SetCellValue("D{$row}", 'Login By');
	$phpexcel->getActiveSheet()->SetCellValue("F{$row}", 'Nama');
	$phpexcel->getActiveSheet()->SetCellValue("H{$row}", 'Email');
	$phpexcel->getActiveSheet()->SetCellValue("J{$row}", 'Gender');
	$phpexcel->getActiveSheet()->SetCellValue("K{$row}", 'Phone');
	$phpexcel->getActiveSheet()->SetCellValue("M{$row}", 'Birthday');
	$phpexcel->getActiveSheet()->SetCellValue("O{$row}", 'Username');
	$phpexcel->getActiveSheet()->SetCellValue("Q{$row}", 'Followers');
	$phpexcel->getActiveSheet()->SetCellValue("S{$row}", 'Friends');

	$phpexcel->getActiveSheet()->getStyle("B{$row}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$phpexcel->getActiveSheet()->getStyle("C{$row}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$phpexcel->getActiveSheet()->getStyle("D{$row}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$phpexcel->getActiveSheet()->getStyle("E{$row}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
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

	$phpexcel->getActiveSheet()->getStyle("B{$row}")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	$phpexcel->getActiveSheet()->getStyle("C{$row}")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	$phpexcel->getActiveSheet()->getStyle("D{$row}")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	$phpexcel->getActiveSheet()->getStyle("E{$row}")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
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

	$row += 1;
	$before_row = $row - 1;
	$phpexcel->getActiveSheet()->mergeCells("B{$before_row}:B{$row}");
	$phpexcel->getActiveSheet()->mergeCells("C{$before_row}:C{$row}");
	$phpexcel->getActiveSheet()->mergeCells("D{$before_row}:E{$row}");
	$phpexcel->getActiveSheet()->mergeCells("F{$before_row}:G{$row}");
	$phpexcel->getActiveSheet()->mergeCells("H{$before_row}:I{$row}");
	$phpexcel->getActiveSheet()->mergeCells("J{$before_row}:J{$row}");
	$phpexcel->getActiveSheet()->mergeCells("K{$before_row}:L{$row}");
	$phpexcel->getActiveSheet()->mergeCells("M{$before_row}:N{$row}");
	$phpexcel->getActiveSheet()->mergeCells("O{$before_row}:P{$row}");
	$phpexcel->getActiveSheet()->mergeCells("Q{$before_row}:R{$row}");
	$phpexcel->getActiveSheet()->mergeCells("S{$before_row}:T{$row}");

	$first_row = $row + 1;
	$count_date = 0;
	$total_login = 0;
	$total_data = 0;
	$total_confirm = 0;
	$total_success = 0;

	foreach ($arr_data2 as $day2 => $data2)
	{
		$row += 1;
		$count_date += ($data2 == 0) ? 0 : 1;

		$phpexcel->getActiveSheet()->SetCellValue("B{$row}", date('d', strtotime($day2)));

		$phpexcel->getActiveSheet()->getStyle("B{$row}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

		$first_data_row = 0;

		foreach ($data2 as $k => $v)
		{
			$row += ($k == 0) ? 0 : 1;
			$first_data_row = ($k == 0) ? $row : $first_data_row;

			$phpexcel->getActiveSheet()->SetCellValue("C{$row}", $v->mac);
			$phpexcel->getActiveSheet()->SetCellValue("D{$row}", $v->type);
			$phpexcel->getActiveSheet()->SetCellValue("F{$row}", $v->name);
			$phpexcel->getActiveSheet()->SetCellValue("H{$row}", $v->email);
			$phpexcel->getActiveSheet()->SetCellValue("J{$row}", $v->gender);
			$phpexcel->getActiveSheet()->SetCellValue("K{$row}", $v->phone);
			$phpexcel->getActiveSheet()->SetCellValue("M{$row}", $v->birthday);
			$phpexcel->getActiveSheet()->SetCellValue("O{$row}", $v->username);
			$phpexcel->getActiveSheet()->SetCellValue("Q{$row}", $v->followers_count);
			$phpexcel->getActiveSheet()->SetCellValue("S{$row}", $v->friends_count);

			$phpexcel->getActiveSheet()->mergeCells("D{$row}:E{$row}");
			$phpexcel->getActiveSheet()->mergeCells("F{$row}:G{$row}");
			$phpexcel->getActiveSheet()->mergeCells("H{$row}:I{$row}");
			$phpexcel->getActiveSheet()->mergeCells("J{$row}:J{$row}");
			$phpexcel->getActiveSheet()->mergeCells("K{$row}:L{$row}");
			$phpexcel->getActiveSheet()->mergeCells("M{$row}:N{$row}");
			$phpexcel->getActiveSheet()->mergeCells("O{$row}:P{$row}");
			$phpexcel->getActiveSheet()->mergeCells("Q{$row}:R{$row}");
			$phpexcel->getActiveSheet()->mergeCells("S{$row}:T{$row}");

			$phpexcel->getActiveSheet()->getStyle("D{$row}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$phpexcel->getActiveSheet()->getStyle("F{$row}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$phpexcel->getActiveSheet()->getStyle("H{$row}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$phpexcel->getActiveSheet()->getStyle("J{$row}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$phpexcel->getActiveSheet()->getStyle("K{$row}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$phpexcel->getActiveSheet()->getStyle("M{$row}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$phpexcel->getActiveSheet()->getStyle("O{$row}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$phpexcel->getActiveSheet()->getStyle("Q{$row}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$phpexcel->getActiveSheet()->getStyle("S{$row}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		}
	}
	// var_dump($total_login);

	// $last_row = $row;
	//
	// $row += 1;
	//
	// $phpexcel->getActiveSheet()->SetCellValue("B{$row}", 'TOTAL');
	//
	// $phpexcel->getActiveSheet()->mergeCells("B{$row}:C{$row}");
	//
	// /*========= Summary All Column =========*/
	// $phpexcel->getActiveSheet()->SetCellValue("D{$row}", "=SUM(D{$first_row}:D{$last_row})");
	// $phpexcel->getActiveSheet()->SetCellValue("E{$row}", "=SUM(E{$first_row}:E{$last_row})");
	// $phpexcel->getActiveSheet()->SetCellValue("G{$row}", "=SUM(G{$first_row}:G{$last_row})");
	// $phpexcel->getActiveSheet()->SetCellValue("I{$row}", "=IF(D{$row} > 0, G{$row}/D{$row})");
	// $phpexcel->getActiveSheet()->getStyle("I{$row}")->getNumberFormat()->applyFromArray(array(
	// 	'code' => PHPExcel_style_NumberFormat::FORMAT_PERCENTAGE
	// ));
	// $phpexcel->getActiveSheet()->SetCellValue("F{$row}", "=IF(D{$row} > 0, E{$row}/D{$row}, 0)");
	// $phpexcel->getActiveSheet()->getStyle("F{$row}")->getNumberFormat()->applyFromArray(array(
	// 	'code' => PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE
	// ));
	// $phpexcel->getActiveSheet()->SetCellValue("H{$row}", "=IF(E{$row} > 0, G{$row}/E{$row}, 0)");
	//
	// $phpexcel->getActiveSheet()->getStyle("H{$row}")->getNumberFormat()->applyFromArray(array(
	// 	'code' => PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE
	// ));
	// $phpexcel->getActiveSheet()->SetCellValue("J{$row}", "=SUM(J{$first_row}:J{$last_row})");
	// $phpexcel->getActiveSheet()->SetCellValue("K{$row}", "=IF(G{$row} > 0, J{$row}/G{$row}, 0)");
	// $phpexcel->getActiveSheet()->getStyle("K{$row}")->getNumberFormat()->applyFromArray(array(
	// 	'code' => PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE
	// ));
	//
	// $phpexcel->getActiveSheet()->SetCellValue("L{$row}", "=IF(E{$row} > 0, J{$row}/E{$row}, 0)");
	// $phpexcel->getActiveSheet()->getStyle("L{$row}")->getNumberFormat()->applyFromArray(array(
	// 	'code' => PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE
	// ));
	//
	// $phpexcel->getActiveSheet()->SetCellValue("M{$row}", "=IF(D{$row} > 0, J{$row}/D{$row}, 0)");
	// $phpexcel->getActiveSheet()->getStyle("M{$row}")->getNumberFormat()->applyFromArray(array(
	// 	'code' => PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE
	// ));
	//
	// $row += 1;
	//
	// /*========= Average All Column =========*/
	// $phpexcel->getActiveSheet()->SetCellValue("B{$row}", 'AVERAGE');
	//
	// $phpexcel->getActiveSheet()->mergeCells("B{$row}:C{$row}");
	//
	// $phpexcel->getActiveSheet()->SetCellValue("D{$row}", "=AVERAGE(D{$first_row}:D{$last_row})");
	//
	// $phpexcel->getActiveSheet()->getStyle("D{$row}")->getNumberFormat()->setFormatCode('0.00');
	//
	// $phpexcel->getActiveSheet()->SetCellValue("E{$row}", "=AVERAGE(E{$first_row}:E{$last_row})");
	//
	// $phpexcel->getActiveSheet()->getStyle("E{$row}")->getNumberFormat()->setFormatCode('0.00');
	//
	// $phpexcel->getActiveSheet()->SetCellValue("G{$row}", "=AVERAGE(G{$first_row}:G{$last_row})");
	//
	// $phpexcel->getActiveSheet()->getStyle("G{$row}")->getNumberFormat()->setFormatCode('0.00');
	//
	// $phpexcel->getActiveSheet()->SetCellValue("J{$row}", "=AVERAGE(J{$first_row}:J{$last_row})");
	// $phpexcel->getActiveSheet()->getStyle("J{$row}")->getNumberFormat()->setFormatCode('0.00');

	/*========== End Login By Sheet ==========*/

	$writer = PHPExcel_IOFactory::createWriter($phpexcel, 'Excel5');
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="'.$merchant_name.' Download Visit - '.$month.' '.$year.'.xls"');
	header('Cache-Control: max-age=0');
	$writer->save('php://output');
?>
