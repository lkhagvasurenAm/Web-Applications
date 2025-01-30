<?
include("../../config.inc.php");
$PERMISSIONS = array("CUST_INFO_CHANGE_VIEW", "CUST_INFO_CHANGE_VIEW_BRCH");
include("../checkpermission.inc.php");
include("../writelog.inc.php");
include("../../functions.inc.php");

$options = array("ALLSELECT" => "Бүх төрөл", "SEGCODE" => "Сегмент", "COUNTRYCODE" => "Улс", "CUSTMIG" => "Харилцагч нэгтгэх", "CUSTACNTMIG" => "Харилцагчийн данс нэгтгэх", "STATUSCODE" => "Харилцагчийн төлөв өөрчлөх", "RELATION" => "Харилцагчийн хамаарал", "CUSTINFO" => "Холбоо барих үндсэн мэдээлэл өөрчилсөн", "CUSTPHOTO" => "Зураг, гарын үсэг өөрчилсөн");
$option_txncodes = array("SEGCODE" => "13160002", "CUSTNO" => "13110431,13110433", "COUNTRYCODE" => "13160002", "CUSTMIG" => "13160151", "CUSTACNTMIG" => "13160153", "STATUSCODE" => "13160005", "RELATION" => "13160143,13160141,13160142");
$option_isaccount = array("SEGCODE" => 0, "CUSTNO" => 1, "CUSTACNTMIG" => 1);
$option_table = array("SEGCODE" => "13160002", "COUNTRYCODE" => "13160002", "CUSTMIG" => "13160151", "CUSTACNTMIG" => "13160153");


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Харилцагч</title>
    <LINK href="../../style.css" type=text/css rel=stylesheet>
    <SCRIPT>
        document.onclick = function() {
            document.getElementById("popFrame").style.visibility = "hidden";
        }
    </SCRIPT>
</head>

<body>
    <IFRAME id=popFrame style="BORDER-RIGHT: 1px ridge; BORDER-TOP: 1px ridge; Z-INDEX: 65535; VISIBILITY: hidden; BORDER-LEFT: 1px ridge; BORDER-BOTTOM: 1px ridge; POSITION: absolute; left: 352px; top: 65px;" name=popFrame src="../../popcjs.htm" frameBorder=0 scrolling=no></IFRAME>
    <table border="0" cellpadding="2" width="650" style="font-size:10px">
        <form name="form" action="#" enctype="multipart/form-data" method="post">

            <tr>
                <td align="right">Салбар:</td>
                <td>
                    <select name="vBrchNo" class="box">
                        <?
                        if (@$ALLOWEDPERMISSIONS['CUST_INFO_CHANGE_VIEW'] == 1) {
                        ?>
                            <option value="">[Бүх салбар]</option>
                        <? }

                        if (@$ALLOWEDPERMISSIONS['CUST_INFO_CHANGE_VIEW_BRCH'] == 1) $sqlbrch = "SELECT a.* FROM gb.pabrch a where a.brchno in (" . getBranchs($_SESSION['BRANCHNO']) . ") order by a.brchno";
                        else $sqlbrch = "SELECT a.* FROM gb.pabrch a order by a.brchno";

                        $result = ociparse($connectiongbsb, $sqlbrch);
                        ociexecute($result, OCI_DEFAULT);
                        while (ocifetch($result)) { ?>
                            <option value="<?= ociresult($result, "BRCHNO") ?>" <? if ($vBrchNo == ociresult($result, "BRCHNO")) echo " selected "; ?>><?= ociresult($result, "BRCHNO") ?> - <?= ociresult($result, "BRCHNAME") ?></option>
                        <?
                        } ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td width="50%" align="right">Өөрчилсөн огноо:</td>
                <td>
                    <input name="startdate" size="10" class="box" id=startdate value="<?= (@$startdate != "" ? @$startdate : date("Y/m/d")) ?>" maxlength="10">
                    <input type="button" value=" v " class="btn" onClick="popFrame.fPopCalendar('startdate','startdate',event); return false;"> -
                    <input name="enddate" size="10" class="box" id=enddate value="<?= (@$enddate != "" ? @$enddate : date("Y/m/d")) ?>" maxlength="10">
                    <input type="button" value=" v " class="btn" onClick="popFrame.fPopCalendar('enddate','enddate',event); return false;">
                    <font color="#FF0000">*</font>
                </td>
            </tr>
            <tr>
                <td align="right" valign="top" nowrap>Харилцагчийн дугаар: </td>
                <td><textarea name="customerlist" class="box" id="customerlist" style="height:90; width:60"><?= $customerlist ?></textarea></td>
            </tr>
            <tr>
                <td align="right" valign="top" nowrap>Дансны дугаар: </td>
                <td><textarea name="accountlist" class="box" id="accountlist" style="height:90; width:60"><?= $accountlist; ?></textarea></td>
            </tr>
            <tr>
                <td align="right" valign="top" nowrap>Теллер дугаар: </td>
                <td><textarea name="tellerno" class="box" id="tellerno" style="height:30; width:20"><?= $tellerno; ?></textarea>
                    <img src="../images/ico_help.gif" title="Теллерийн жагсаалтыг ТАСЛАЛААР тусгаарлаж оруулна уу" width="15" height="15" longdesc="http://www.tdbm.mn">
                </td>
            </tr>
            <tr>
                <td align="right" valign="top" nowrap>Өөрчилсөн талбар</td>
                <td>
                    <select name="changeField" class="box">
                        <?
                        foreach ($options as $key => $option) {
                        ?>
                            <option value="<?= $key ?>" <? if ($key == $changeField) echo "selected"; ?>><?= $option ?></option>
                        <?
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td align="right" valign="top" nowrap>Хуучин(Кодоор):</td>
                <td><input name="oldvalue" class="box" id="oldvalue" size="10" value="<?= $oldvalue; ?>"></td>
            </tr>
            <tr>
                <td align="right" valign="top" nowrap>Шинэ(Кодоор):</td>
                <td><input name="newvalue" class="box" id="newvalue" size="10" value="<?= $newvalue; ?>"></td>
            </tr>
            <tr>
            <tr>
                <td align="right" valign="top" nowrap>Автомат теллер хасах </td>
                <td><input type="checkbox" name="autotel" class="box" id="autotel" size="10" value=""></td>
            </tr>
            <td></td>
            <td> <input type="submit" name="sub" value="Хайх"> </td>
            </tr>
            <tr>
                <td colspan="2">
                    <ul>
                        <li>
                            <font color="#FF0000">*</font>-р тэмдэглэгдсэн талбаруудыг заавал оруулах шаардлагатайг анхаарна уу!
                        </li>
                        <li>Жагсаалтыг < ENTER> авч тусгаарлан доош цувуулан бичнэ.</li>
                    </ul>
                </td>
            </tr>
        </form>
    </table>

    <?




    if (isset($sub)) {


    ?>
        <hr color="#E8E8E8" size="3">
        <a class="NotPrint" href="#" onClick="document.getElementById('txtData').value=document.getElementById('tblData').innerHTML; document.getElementById('frmData').submit(); return false;" title="Excel pvv тайлангийн мэдээллийг гаргах">Хадгалах (Excel рvv)</a>
        <form name="frmData" id="frmData" action="../export.php" target="_blank" method="post" enctype="multipart/form-data" style="display:none"><textarea name="txtData" id="txtData"></textarea></form>
        <div id="tblData">
            <table cellpadding="2" cellspacing="0" border="1" style="border-collapse:collapse;" width="1200">
		<br>
                <?= @$startdate . "--" . @$enddate; ?> тайлан<br>
                <?
                if ($_SESSION["EMPID"] == '26892') {
                    print_r($connectiongb96);
                }
                $select_custs = "";
                $pCustLst = explode("\r\n", $customerlist);
                foreach ($pCustLst as $pCustNo)
                    if ($pCustNo != "")
                        $select_custs .= "'$pCustNo', ";
                if ($select_custs != "") $select_custs = rtrim($select_custs, ", ");

                $pAcntLst = explode("\r\n", $accountlist);
                foreach ($pAcntLst as $pAcntNo)
                    if ($pAcntNo != "")
                        $acntno .= "'$pAcntNo', ";
                if ($acntno != "") $acntno = rtrim($acntno, ", ");
		 $start_date = date_create($startdate);
                $end_date = date_create($enddate);
                ?>
                <!--Begin Header-->
                <? if ($changeField == "CUSTINFO") { ?>
                    <!--<tr>
    <td bgcolor="#dedede" rowspan="2">№</td>
    <td bgcolor="#dedede" rowspan="2">Салбар</td>
    <td bgcolor="#dedede" rowspan="2">Харилцагчийн<br />дугаар</td>
    <td bgcolor="#dedede" rowspan="2">Харилцагчийн<br />нэр</td>
    <td bgcolor="#dedede" rowspan="2">Өөрчилсөн<br />огноо</td>
    <td bgcolor="#dedede" colspan="2" rowspan="2">Гар утас</td>
    <td bgcolor="#dedede" colspan="2" rowspan="2">И-мэйл/</td>
    <td bgcolor="#dedede" rowspan="2">Өөрчилсөн<br />теллер</td>
    <td bgcolor="#dedede" rowspan="2">Үйлдэл</td>
</tr>
        <tr>
            <td bgcolor="#dedede">№</td>
            <td bgcolor="#dedede">Салбар</td>
            <td bgcolor="#dedede">Салбар</td>
            <td bgcolor="#dedede">Салбар</td>
        </tr>

-->
                    <tr>
                        <td rowspan="2" align="center" bgcolor="#dedede">№</td>
                        <td rowspan="2" align="center" bgcolor="#dedede">Салбар</td>
                        <td rowspan="2" align="center" bgcolor="#dedede">Харилцагчийн<br />дугаар</td>
                        <td rowspan="2" align="center" bgcolor="#dedede">Харилцагчийн<br />нэр</td>
                        <td rowspan="2" align="center" bgcolor="#dedede">Өөрчилсөн<br />огноо</td>
                        <td colspan="2" align="center" bgcolor="#dedede">Гар утас</td>
                        <td colspan="2" align="center" bgcolor="#dedede">И-мэйл</td>
                        <td rowspan="2" align="center" bgcolor="#dedede">Өөрчилсөн<br />теллер</td>
                        <td rowspan="2" align="center" bgcolor="#dedede">Үйлдэл</td>
                    </tr>
                    <tr>
                        <td align="center" bgcolor="#dedede">Шинэ</td>
                        <td align="center" bgcolor="#dedede">Хуучин</td>
                        <td align="center" bgcolor="#dedede">Шинэ</td>
                        <td align="center" bgcolor="#dedede">Хуучин</td>
                    </tr>
                <? } elseif ($changeField == "CUSTPHOTO") { ?>
                    <tr>
                        <th bgcolor="#dedede">№</th>
                        <th bgcolor="#dedede">Салбар</th>
                        <th bgcolor="#dedede">Харилцагчийн<br />дугаар</th>
                        <th bgcolor="#dedede">Харилцагчийн<br />нэр</th>
                        <th bgcolor="#dedede">Өөрчилсөн<br />огноо</th>
                        <th bgcolor="#dedede" nowrap="nowrap">Өөрчилсөн талбар</th>
                        <th bgcolor="#dedede">Өөрчилсөн<br />теллер</th>
                        <th bgcolor="#dedede">Үйлдэл</th>
                    </tr> <? } else { ?>
                    <tr>
                        <th bgcolor="#dedede">№</th>
                        <th bgcolor="#dedede">Салбар</th>
                        <th bgcolor="#dedede">Харилцагчийн<br />дугаар</th>
                        <? if ($option_isaccount[$changeField] == 1) { ?>
                            <th bgcolor="#dedede">Дансны<br />дугаар</th>
                        <? } ?>
                        <th bgcolor="#dedede">Өөрчилсөн<br />огноо</th>
                        <? if ($changeField == "RELATION") { ?>
                            <th bgcolor="#dedede" nowrap="nowrap">Хуучин /гишүүн/</th>
                            <th bgcolor="#dedede" nowrap="nowrap">Шинэ /гишүүн/</th>
                            <th bgcolor="#dedede" nowrap="nowrap">Хуучин /төрөл/</th>
                            <th bgcolor="#dedede" nowrap="nowrap">Шинэ /төрөл/</th>
                        <? } else { ?>
                            <th bgcolor="#dedede">Хуучин</th>
                            <th bgcolor="#dedede">Шинэ</th>
                        <? } ?>
                        <th bgcolor="#dedede">Өөрчилсөн<br />теллер</th>
                        <th bgcolor="#dedede">Үйлдэл</th>
                        <? if ($option_txncodes[$changeField] == '13160005') { ?>
                            <th bgcolor="#dedede">Тайлбар</th>
                        <? } ?>
                    </tr>

                <? } ?>
                <!--End Header-->
                <?
                $sqlOption = "select * from lg.lgtableoption order by id";
                $resOption =     ociparse($connectiongb96, $sqlOption);
                ociexecute($resOption, OCI_DEFAULT);
                while (ocifetch($resOption)) {			
                    $sqlTable = "select id from 
			  (
			  SELECT '" . ociresult($resOption, "ID") . "' id ,
			  REGEXP_SUBSTR ('" . ociresult($resOption, "TXNS") . "','[^,]+',1,LEVEL) txn
			  FROM dual
			  CONNECT BY  regexp_substr('" . ociresult($resOption, "TXNS") . "', '[^,]+', 1, LEVEL) IS NOT NULL
			
			  Order by length(replace(txn,'%')) desc, txn desc
			  ) tbl where '" . $option_table[$changeField] . "' like tbl.txn";
                    $resultTable = ociparse($connectiongb96, $sqlTable);
                    ociexecute($resultTable, OCI_DEFAULT);
                    if (ocifetch($resultTable)) {
                        $table1 = "lg.lgtxn" . ociresult($resultTable, "ID");
                        $table2 = "lg.lgtxn" . ociresult($resultTable, "ID") . "details";
                    }
                }
                $sql = "select t.brchno, to_char(t.logdate,'YYYY/MM/DD HH24:MI:SS') logdate, t.userno, t.reserved2,t.reserved1,t.txncode, t.txndesc, d.value, d.newvalue
				from $table1 t 
				left join $table2 d on t.sequence=d.sequence 
			where    d.key='";
                $sql .= $changeField;
                $sql .= "' and t.txncode in (" . $option_txncodes[$changeField] . ") and reserved2 is not null ";
                if ($vBrchNo) $sql .= " and t.brchno=$vBrchNo ";
                $sql .= " and t.logdate between to_date('" . $startdate . " 00:00:00','yyyy/mm/dd HH24:MI:SS') and to_date('" . $enddate . " 23:59:59','yyyy/mm/dd HH24:MI:SS') ";
                if ($select_custs) $sql .= " and t.reserved2 in($select_custs) ";
                if ($tellerno) $sql .= " and t.userno in ($tellerno)";
                if ($oldvalue) $sql .= " and d.value = '$oldvalue' ";
                if ($newvalue) $sql .= " and d.newvalue = '$newvalue' ";
                if ($acntno) $sql .= " and t.reserved1 in ($acntno) ";
                $sql .= " order by t.brchno asc, t.logdate desc";

                if ($changeField == "CUSTMIG" || $changeField == "CUSTACNTMIG") {
                    $sql = "select   to_char(t.logdate,'YYYY/MM/DD HH24:MI:SS') logdate, t.brchno, t.userno, t.reserved1, t.txncode, t.txndesc, d.value, d.newvalue
					from lg.lgtxn2 t 
					left join lg.lgtxn2details d on t.sequence=d.sequence 
				where    d.key='CUSTNO'";
                    $sql .= " and t.txncode in (" . $option_txncodes[$changeField] . ")";
                    if ($vBrchNo) $sql .= " and t.brchno=$vBrchNo ";
                    $sql .= " and t.logdate between to_date('" . $startdate . " 00:00:00','yyyy/mm/dd HH24:MI:SS') and to_date('" . $enddate . " 23:59:59','yyyy/mm/dd HH24:MI:SS') ";
                    if ($select_custs) $sql .= " and d.value in($select_custs) ";
                    if ($tellerno) $sql .= " and t.userno in ($tellerno)";
                    if ($oldvalue) $sql .= " and d.value = '$oldvalue' ";
                    if ($newvalue) $sql .= " and d.newvalue = '$newvalue' ";
                    if ($acntno) $sql .= " and t.reserved1 in ($acntno) ";
                    $sql .= " group by t.logdate, t.brchno, t.userno,t.reserved1, t.txncode, t.txndesc, d.value, d.newvalue  order by t.brchno asc, t.logdate desc";
                }
                if ($changeField == "STATUSCODE") {
                    $sql = "
			select a.*, (SELECT newvalue FROM lg.lgtxn5details WHERE SEQUENCE=A.SEQUENCE AND KEY='RESERVED') descr 
			from(	
						select   to_char(t.logdate,'YYYY/MM/DD HH24:MI:SS') logdate, t.brchno, t.userno, t.reserved2, t.txncode, t.txndesc, d.value, d.newvalue, d.sequence
						from lg.lgtxn5 t 
						inner join lg.lgtxn5details d on t.sequence=d.sequence 
						where    d.key = 'STATUSCODE' and t.resulttype=0 ";
                    $sql .= " and t.txncode in (" . $option_txncodes[$changeField] . ")";
                    if ($vBrchNo) $sql .= " and t.brchno=$vBrchNo ";
                    $sql .= " and t.logdate between to_date('" . $startdate . " 00:00:00','yyyy/mm/dd HH24:MI:SS') and to_date('" . $enddate . " 23:59:59','yyyy/mm/dd HH24:MI:SS') ";
                    if ($select_custs) $sql .= " and t.reserved2 in($select_custs) ";
                    if ($tellerno) $sql .= " and t.userno in ($tellerno)";
                    if ($oldvalue != "") $sql .= " and d.value = '$oldvalue' ";
                    if ($newvalue != "") $sql .= " and d.newvalue = '$newvalue' ";
                    if ($acntno) $sql .= " and t.reserved1 in ($acntno) ";
                    $sql .= " group by t.logdate, t.brchno, t.userno,t.reserved2, t.txncode, t.txndesc, d.value, d.newvalue, d.sequence ) a order by a.brchno asc, a.logdate desc";
                }
                if ($changeField == "RELATION") {
                    $sql = "
			SELECT  t.brchno,t.txndesc,t.reserved2,to_char(t.logdate,'YYYY/MM/DD HH24:MI:SS') logdate,t.userno,b.value oldCust,b.newvalue newCust,b1.value,b1.newvalue
			  FROM lg.lgtxn5 t 
			  LEFT JOIN lg.lgtxn5details b ON t.sequence = b.sequence and b.key='CUSTNO2'
			  LEFT JOIN lg.lgtxn5details b1 ON t.sequence = b1.sequence and b1.key='RELTYPECODE'
			WHERE 1=1 ";
                    $sql .= " and t.txncode in (" . $option_txncodes[$changeField] . ")";
                    if ($vBrchNo) $sql .= " and t.brchno=$vBrchNo ";
                    $sql .= " and t.logdate between to_date('" . $startdate . " 00:00:00','yyyy/mm/dd HH24:MI:SS') and to_date('" . $enddate . " 23:59:59','yyyy/mm/dd HH24:MI:SS') ";
                    if ($select_custs) $sql .= " and t.reserved2 in($select_custs) ";
                    if ($tellerno) $sql .= " and t.userno in ($tellerno)";
                    /*
						if($oldvalue!="") $sql.= " and d.value = '$oldvalue' ";
						if($newvalue!="") $sql.= " and d.newvalue = '$newvalue' ";
						if($acntno) $sql.= " and t.reserved1 in ($acntno) ";*/
                    $sql .= " order by t.brchno asc,t.reserved2, t.logdate desc";
                }
                if ($changeField == "CUSTINFO") {
                    $sql = "
			select q.*,
			 case when q.oldphone is null and q.NEWPHONE is not null then 'Нэмсэн'
                 when q.oldphone is not null and q.NEWPHONE is not null then 'Зассан'
                 when q.oldphone is not null and q.NEWPHONE is null then 'Устгасан'
                 when q.OLDMAIL is null and q.NEWMAIL is not null then 'Нэмсэн'
                 when q.OLDMAIL is not null and q.NEWMAIL is not null then 'Зассан'
                 when q.OLDMAIL is not null and q.NEWMAIL is null then 'Устгасан' end TXNDESC 
            from (select a.brchno,
                        a.RESERVED2,
                        to_char(a.logdate,'YYYY/MM/DD HH24:MI:SS') logdate,
                        decode(b.key,'HOMEPHONE', B.VALUE) oldphone,
                        decode(b.key,'HOMEPHONE', B.NEWVALUE) newphone,
                        decode(b.key,'EMAIL', B.VALUE) oldmail,
                        decode(b.key,'EMAIL', B.NEWVALUE) newmail,
                        a.userno                             
                from LG.LGTXN5 a
                left join LG.LGTXN5DETAILS b on b.SEQUENCE = a.SEQUENCE     
                 where A.LOGDATE BETWEEN to_date('" . $startdate . " 00:00:00','yyyy/mm/dd HH24:MI:SS') and to_date('" . $enddate . " 23:59:59','yyyy/mm/dd HH24:MI:SS')
                    and b.key in ('EMAIL', 'HOMEPHONE') and a.txncode  = '13160002' ) q where 1 = 1 ";
                    if ($vBrchNo) $sql .= " and brchno=$vBrchNo ";
                    if ($select_custs) $sql .= " and reserved2 in($select_custs) ";
                    if ($tellerno) $sql .= " and userno in ($tellerno)";
                    $sql .= " order by logdate asc";
                }
                if ($changeField == "CUSTPHOTO") {
                    $sql = "select * from (select distinct to_char(a.logdate,'YYYY/MM/DD HH24:MI:SS') logdate,
                                A.BRCHNO,
                                A.RESERVED2,
                                A.TXNCODE,
                                'Гарын үсэг' types,
                                'Устгасан' TXNDESC,
                                a.userno
                from  LG.LGTXN2 a
                left join LG.LGTXN2DETAILS b on b.SEQUENCE = a.SEQUENCE
                where A.LOGDATE BETWEEN to_date('2019/10/02 00:00:00','yyyy/mm/dd HH24:MI:SS') and to_date('2019/10/03 23:59:59','yyyy/mm/dd HH24:MI:SS')
                       and a.txncode  in ('13160103') and reserved2 is not null and reserved2 is not null and txndesc not like '%Гарын үсэг шалгах%'
                       " . ($vBrchNo != "" ? " and a.brchno=$vBrchNo" : "") . "
                        " . ($select_custs != "" ? " and a.reserved2 in($select_custs)" : "") . "
                         " . ($tellerno != "" ? " and a.userno in ($tellerno)" : "") . "
                union all
                select distinct to_char(a.logdate,'YYYY/MM/DD HH24:MI:SS') logdate,
                                A.BRCHNO,
                                A.RESERVED2,
                                A.TXNCODE,
                                case when a.txncode  in ('13160101','13160102') then 'Гарын үсэг'
                                     when a.txncode in ('13160113','13160112','13160111') then 'Зураг' end types,
                                case when a.txncode  in ('13160101','13160111') then 'Нэмсэн'
                                     when a.txncode in ('13160102','13160112') then 'Зассан'
                                     when a.txncode in ('13160113') then 'Устгасан' end TXNDESC,
                                a.userno
                from LG.LGTXN5 a
                left join LG.LGTXN5DETAILS b on b.SEQUENCE = a.SEQUENCE
                where A.LOGDATE BETWEEN to_date('" . $startdate . " 00:00:00','yyyy/mm/dd HH24:MI:SS') and to_date('" . $enddate . " 23:59:59','yyyy/mm/dd HH24:MI:SS')
                     and a.txncode  in ('13160101','13160102','13160113','13160112','13160111')
                     " . ($vBrchNo != "" ? " and a.brchno=$vBrchNo" : "") . "
                        " . ($select_custs != "" ? " and a.reserved2 in($select_custs)" : "") . "
                         " . ($tellerno != "" ? " and a.userno in ($tellerno)" : "") . "
                    ) order by  logdate asc";
                }
                if ($_SESSION["EMPID"] == 1882 || $_SESSION["EMPID"] == 20178 || $_SESSION["EMPID"] == 26892 || $_SESSION["EMPID"] == 18949) {
                    echo "<pre>" . $sql . "</pre>";
                }
                $result = ociparse($connectiongb96, $sql);
                ociexecute($result, OCI_DEFAULT);
                $count = 0;
                while (ocifetch($result)) {
                    $logdate = date_create(ociresult($result, "LOGDATE"));
                    $count++;
                    $bgcolor = "FFF";
                ?>
                    <tr bgcolor="#<?= $bgcolor ?>">
                        <td align="center"><?= $count ?></td>
                        <td align="center"><?= ociresult($result, "BRCHNO") ?></td>
                        <? if ($changeField == "SEGCODE" || $changeField == "COUNTRYCODE" || $changeField == "CUSTMIG" || $changeField == "CUSTACNTMIG" || $changeField == "RELATION" || $changeField == "STATUSCODE") { ?>
                            <td align="center">
                                <?
                                if ($changeField == "CUSTMIG" || $changeField == "CUSTACNTMIG") echo ociresult($result, "VALUE");
                                else echo ociresult($result, "RESERVED2");
                                ?>
                            </td>
                            <? if ($option_isaccount[$changeField] == 1) { ?>
                                <td align="center"><?= ociresult($result, "RESERVED1") ?></td>
                            <? } ?>
                            <td align="center"><?= ociresult($result, "LOGDATE") ?></td>
                            <?
                            if ($changeField == "RELATION") { ?>
                                <td align="center"><?= ociresult($result, "OLDCUST") ?></td>
                                <td align="center"><?= ociresult($result, "NEWCUST") ?></td>
                                <td align="left">
                                    <?
                                    echo ociresult($result, "VALUE");
                                    $sqlrel = "select reltypename from GB.CUSTRELTYPE where reltypecode=" . ociresult($result, "VALUE");
                                    $resultrel = ociparse($connectiongb, $sqlrel);
                                    ociexecute($resultrel, OCI_DEFAULT);
                                    if (ocifetch($resultrel)) echo " - " . ociresult($resultrel, "RELTYPENAME");
                                    ?>
                                </td>
                                <td align="left"><?
                                                    echo ociresult($result, "NEWVALUE");
                                                    $sqlrel2 = "select reltypename from GB.CUSTRELTYPE where reltypecode =" . ociresult($result, "NEWVALUE");
                                                    $resultrel2 = ociparse($connectiongb, $sqlrel2);
                                                    ociexecute($resultrel2, OCI_DEFAULT);
                                                    if (ocifetch($resultrel2)) echo " - " . ociresult($resultrel2, "RELTYPENAME");
                                                    ?></td>
                            <? } else { ?>
                                <td align="center">
                                    <?
                                    echo ociresult($result, "VALUE");
                                    if ($changeField == "SEGCODE") {
                                        $sqlSeg = "select * from gb.paseg where segcode = " . ociresult($result, "VALUE");
                                        $resultSeg = ociparse($connectiongb, $sqlSeg);
                                        ociexecute($resultSeg, OCI_DEFAULT);
                                        if (ocifetch($resultSeg)) echo " - " . ociresult($resultSeg, "SEGNAME");
                                    }
                                    if ($changeField == "COUNTRYCODE") {
                                        $sqlCountry = "select * from gb.pacountry where countrycode = " . ociresult($result, "VALUE");
                                        $resultCountry = ociparse($connectiongb, $sqlCountry);
                                        ociexecute($resultCountry, OCI_DEFAULT);
                                        if (ocifetch($resultCountry)) echo " - " . ociresult($resultCountry, "COUNTRYNAME");
                                    }
                                    ?>
                                </td>
                                <td align="center">
                                    <?
                                    echo ociresult($result, "NEWVALUE");
                                    if ($changeField == "SEGCODE") {
                                        $sqlSeg = "select * from gb.paseg where segcode = " . ociresult($result, "NEWVALUE");
                                        $resultSeg = ociparse($connectiongb, $sqlSeg);
                                        ociexecute($resultSeg, OCI_DEFAULT);
                                        if (ocifetch($resultSeg)) echo " - " . ociresult($resultSeg, "SEGNAME");
                                    }
                                    if ($changeField == "COUNTRYCODE") {
                                        $sqlCountry = "select * from gb.pacountry where countrycode = " . ociresult($result, "NEWVALUE");
                                        $resultCountry = ociparse($connectiongb, $sqlCountry);
                                        ociexecute($resultCountry, OCI_DEFAULT);
                                        if (ocifetch($resultCountry)) echo " - " . ociresult($resultCountry, "COUNTRYNAME");
                                    }
                                    ?>
                                </td>
                            <? } ?>
                            <td align="center">
                                <?
                                echo ociresult($result, "USERNO");
                                $sqlCust = "select * from se.seuser where userno = " . ociresult($result, "USERNO");
                                $resultCust = ociparse($connectiongb, $sqlCust);
                                ociexecute($resultCust, OCI_DEFAULT);
                                if (ocifetch($resultCust)) echo " - " . ociresult($resultCust, "USERNAME");
                                ?>
                            </td>
                            <td align="center"><?= checkPAN(ociresult($result, "TXNDESC")) ?></td>
                            <?
                            if ($changeField == "STATUSCODE") { ?>
                                <td align="center"><?= ociresult($result, "DESCR") ?></td>
                            <? } ?>
                        <? } else { ?>

                            <td align="right"><?= ociresult($result, "RESERVED2") ?></td>
                            <td align="right">
                                <?
                                $sqlCust = "select pkg_functions.hidecust(c.custno, " . $_SESSION['TELLERNO'] . ") hidecust, c.custname from gb.cust c where custno = " . ociresult($result, "RESERVED2");
                                $resultCust = ociparse($connectiongbsb, $sqlCust);
                                ociexecute($resultCust);
                                if (ocifetch($resultCust))
                                    if (ociresult($resultCust, "HIDECUST") == 0) {
                                        echo '*******';
                                    } else  echo ociresult($resultCust, "CUSTNAME");
                                ?>
                            </td>
                            <td align="center"><?= ociresult($result, "LOGDATE") ?></td>
                            <? if ($changeField == "CUSTINFO") { ?>
                                <td align="right"><?= ociresult($result, "OLDPHONE") ?></td>
                                <td align="right"><?= ociresult($result, "NEWPHONE") ?></td>
                                <td align="right"><?= ociresult($result, "OLDMAIL") ?></td>
                                <td align="right"><?= ociresult($result, "NEWMAIL") ?></td>
                            <? }
                            if ($changeField == "CUSTPHOTO") { ?>

                                <td align="right"><?= ociresult($result, "TYPES") ?></td>
                            <? } ?>
                            <td align="right">
                                <?
                                echo ociresult($result, "USERNO");
                                $sqltlr = "select * from se.seuser where userno = " . ociresult($result, "USERNO");
                                $resTlr = ociparse($connectiongb96, $sqltlr);
                                ociexecute($resTlr);
                                if (ocifetch($resTlr)) echo " - " . ociresult($resTlr, "USERNAME");
                                ?>
                            </td>
                            <td align="center"><?= checkPAN(ociresult($result, "TXNDESC")) ?></td>

                        <? } ?>
                    </tr>
                <?    } ?>
            </table>

        <? } ?>
        </div>
</body>

</html>
<? @eval($gOraSessClose); ?>
