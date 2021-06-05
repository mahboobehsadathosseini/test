<?php
session_start(); require_once "config.php";
$item_ax=1;
if (!PassTru($item_ax)) echo "<script>window.location='mainpage.php';</script>";
foreach($_GET  as $key => $value) ${$key}=$value;
foreach($_POST as $key => $value) ${$key}=$value;
$FileName="append_from_xlsx.php";
$temp_table="hokm_excel".$_SESSION['acc_user_id']."";
$cnt=$db->get_from_where("count(per_id)","$temp_table","1=1",0,0);
if($continue and (!$pers or !$wperiod)){$continue=0;$kind=1;echo "<script>alert('نوع شناسه افراد و دوره پرداخت مشخص شود');</script>";}
if($exit){$exit=0; echo "<script>window.location='ahkam_mah_excel.php?kind=0';</script>";}
 $mnArr=Array(
 0 => 'دائم',
 1 => '1ماهه',
 2 => '2ماهه',
 3 => '3ماهه',
 4 => '4ماهه',
 5 => '5ماهه',
 6 => '6ماهه',
 7 => '7ماهه',
 8 => '8ماهه',
 9 => '9ماهه',
 10=> '10ماهه',
 11=> '11ماهه',
 12=> '12ماهه',
 );
$perArr=array(
  0=>'-',
  1=>'کد ملی',
  2=>'کد پرسنلی',
 );

function get_org($orgset_)
{global $db,$tickorg,$userr,$dbn;
 $checklist= explode(",", $orgset_);
 if(!SuperUser()) $whereorg =" and org_code in (".$userr['org_id'].")";
 echo '
 <div id="obj0" style="display:none;" class="divhidden1">
 <div height="26" align=right>
  <img src="images/exit.png" onClick="sw()" height="23" width="23">
 </div>
 <hr bgcolor="#555555"/>
 <table border=0 width="100%" id="data_tbl">
  <tr>
   <td align=center>انتخاب سازمان</td>
  </tr>
  <tr valign=top>
   <td>
    <div style="height:300px;overflow:auto;width:100%">
     <table border="0" cellpadding="0" cellspacing="0" width="100%" dir=rtl >
      <tr>
       <th width=5%><input type="checkbox" value="on" onClick="if(this.value==\'on\') this.value=doNow(\'on\',\'orgk\'); else this.value=doNow(\'off\',\'orgk\');"></th>
       <th width=30%>کد</th>
       <th width=70%>عنوان</th>
      </tr>';
     $c_=$db->query("select * from org where dbn='$dbn' and 1=1 $whereorg order by org_code");
     while ($r_=$db->fetch_array($c_))
     {$chkd="";if (in_array($r_['org_code'], $checklist)) $chkd="checked";
      echo '
      <tr>
       <td><input id=orgk type="checkbox" name="tickorg['.$r_['org_code'].']" '.$chkd.' ></td>
       <td>'.$r_['org_code'].'</td>
       <td>'.$r_['org_titr'].'</td>
      </tr>';
     }
     echo '
     </table>
    <div>
   </td>
  </tr>
 </table>
 </div>';
 }
if($add_xlsx)
{require('add_excel/library/php-excel-reader/excel_reader2.php');
 require('add_excel/library/SpreadsheetReader.php');
 
 $mimes = array('application/vnd.ms-excel','text/xls','text/xlsx','application/vnd.oasis.opendocument.spreadsheet','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

 if(in_array($_FILES["file"]["type"],$mimes))
 { $uploadFilePath = 'add_excel/uploads/'.basename($_FILES['file']['name']);
   move_uploaded_file($_FILES['file']['tmp_name'], $uploadFilePath);
  $data = new SpreadsheetReader($uploadFilePath);
   $totalSheet = count($data->sheets());
   echo '<table align=center border=1 width=60%>';
   $db->query("DROP TABLE `hokm_excel".$_SESSION['acc_user_id']."` ");
   $sql = "CREATE TABLE hokm_excel".$_SESSION['acc_user_id']." (
   `row_id` INT NOT NULL ,
   `per_id` INT NOT NULL ,
   `emp_kind` INT NOT NULL ,
   `org_id` INT NOT NULL ,
   PRIMARY KEY (row_id)
   ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci";
   $db->query($sql);
   $title=$value="";$i=0;
   foreach ($data as $k=>$v )
   {$value="";
  
    foreach($v as $kk=>$vv)
	{ 
     if($i==0)
     {
	  $sql1 = "ALTER TABLE hokm_excel".$_SESSION['acc_user_id']." ADD `$vv` VARCHAR(100)";
	  $db->query($sql1);
  	  $title.=",".$vv.""; 
      if (substr($vv,0,1)=='p')	
		$param.="".substr($vv,1,strlen($vv)).",";  
     }
     else {$vv=bug($vv);
  	 $value.=",'".$vv."'"; 
     }
    }
    if($i>0)
	{
  	 $sql2 = "insert into hokm_excel".$_SESSION['acc_user_id']." (row_id,per_id,emp_kind,org_id $title) value ('$i','0','0','0' $value) " ;
     // echo "<br>".$sql2."<br>";
	$cur=$db->query($sql2);
    }
    $i++;
   }$j=0;
   $param=substr($param,0,strlen($param)-1);
      $_SESSION['param']=$param;
    
  }
  if($cur){unlink($uploadFilePath);echo"<script>alert('فایل دریافت شد');window.location='ahkam_mah_excel.php?kind=1'</script>";}
  else echo"<script>alert('فایل دریافت نشد، مجدداً بارگذاری نمایید');window.location='ahkam_mah_excel.php?kind=0'</script>";
}

echo '
<body scroll=no topmargin=0 leftmargin=0 marginheight=0 marginwidth=0 dir=rtl>
<meta content="text/html; charset=UTF-8" http-equiv=content-type>
<link href="style.css" type="text/css" rel="stylesheet">
<style>
 table#data_tbl td {border:1px solid #A4A4A4;}
 table#data_tbl tr {height:30px;line-height:30px;text-align:center;font:normal 17px BMitra;color:#000;vertical-align:middle;cursor:pointer}
   .divhidden1{
   width:300px;
   height:auto;
   border:1px solid #C3C3C3;
   border-radius:12px;
   background:#E8E8E8;
   padding:10px;
   position:absolute;
   top:50%;
   left:50%;
   margin:-20% 0 0 -100px;
   box-shadow: 0px 10px 5px #888888;
   z-index:200;
   }
</style>
  <script>
  function doNow(ch,_id_)
  {if(ch=="on") ck=1; else ck=0;
   void(d=document);
   void(el=d.getElementsByTagName("INPUT"));
   for(i=0;i<el.length;i++) if (el[i].id==_id_) void(el[i].checked=ck)
   if(ch=="on") ch="off"; else if(ch=="off")ch="on";
   return ch;
  }
  </script>
  <script>
  function sw(){
   var t=obj0.style.display;
   if (t=="none") obj0.style.display="";
   else obj0.style.display="none";
  } 
  </script>
<form enctype="multipart/form-data" method=post name=myform>';
echo get_org($worg);


 if(!$continue and !$cnt and !$kind)
 {

echo'
 <div align=center>
  <table border=0 width=60% cellpadding=0 cellspacing=0 style="position:relative;top:10" bordercolor=#bccc89>
   <tr>
    <td>
	 <table width="100%" border="0" cellspacing="0" cellpadding="0">
	  <caption onClick="window.location=\'my_frame.php\'" style="cursor:pointer;">
       ارسال فایل اکسل ماهانه پارامتر پرسنل
	  </caption>
	 </table>
	</td>
   </tr>
   <tr>
    <td>

	  <table align=cener width=100% border=0  cellpadding=0 cellspacing=0 id="data_tbl" >
	   <tr>
		<th rowspan=2><span style="color:#DC143C;font-size:20;">*</span>code</th>		
		<th rowspan=2>pکد پارامتر</th>
		<th colspan=2><span style="color:#DC143C;font-size:20;">*</span>درصورت حکمي بودن پارامتر</th>
	   </tr>
	   <tr>
		<th>date</th>
		<th>end_date</th>
	   </tr>
	   <tr>
		<td>کد پرسنلی یا کد ملی افراد</td>
		<td>p10</td>
		<td>تاريخ اجراي حکم<br>13940101</td>
		<td>تاريخ انقضاي حکم<br>13941229</td>
	   </tr>
     </table>
    </td>
   </tr>
   <tr>';
   echo'
    <td align=center class="table-footer_me">
     <table border=0 width=100% cellpadding=0 cellspacing=0>
      <tr align=center>
       <td  dir=ltr>
	    <input type="submit" name="add_xlsx" value="بارگذاری فایل اکسل" title="برای بارگذاری فایل اکسل کلیک نمایید"  style="width:130px">
		<input type="file" name="file" style="width:300px;text-align:center;">
  	   </td>
	   <td>&nbsp;</td>
      </tr>
     </table>
    </td>
   </tr>
 </table>';
}
 if($kind==1 and !$continue)
 {

echo'
 <div align=center>
  <table border=0 width=60% cellpadding=0 cellspacing=0 style="position:relative;top:10" bordercolor=#bccc89>
   <tr>
    <td>
	 <table width="100%" border="0" cellspacing="0" cellpadding="0">
	  <caption onClick="window.location=\'my_frame.php\'" style="cursor:pointer;">
       ارسال فایل اکسل ماهانه پارامتر پرسنل
	  </caption>
	 </table>
	</td>
   </tr>
   <tr>
    <td>

	  <table align=cener width=100% border=0  cellpadding=0 cellspacing=0 id="data_tbl" >
	   <tr>
	    <th><span style="color:#DC143C;font-size:20;">*</span>دوره پرداخت</th>
		<th>ماه</th>
		<th><span style="color:#DC143C;font-size:20;">*</span>نوع شناسه افراد </th>
		<th>سازمان</th>
	   </tr>
	   <tr>
	    <td>'.listbox("wperiod",$wperiod,"period_id","name","hr_periods","dbn='$dbn' and org='$hr_org_id' and status<>4","class='texti' ").'ميباشد.</td>
	    <td> '.static_listbox("month_numbers",$month_numbers,"mnArr",1,"class='texti'").'</td>
	    <td> '.static_listbox("pers",$pers,"perArr",1,"class='texti'").'</td>
	    <td><input type="button" style="text-align:right"  onClick="sw()" />
	  </td>	 
	 </tr>
     </table>
    </td>
   </tr>
   <tr>';
   echo'
    <td align=center class="table-footer_me">
     <table border=0 width=100% cellpadding=0 cellspacing=0>
      <tr align=center>
       <td  dir=ltr>
	    <input type="submit" name="continue" value="تایید نهایی"  style="width:130px">
		</td>
	   <td>&nbsp;</td>
      </tr>
     </table>
    </td>
   </tr>
 </table>';
}
  if($cnt and $continue)
   { $kind=0; $i=0;$error_per='';$error_noact='';$error_date='';   $error_period_id='';
     if($pers==1)
	    $db->query("update $temp_table set per_id=(select per_id from hr_personel where dbn='$dbn' and national_code=$temp_table.code),emp_kind=(select emp_kind from hr_personel where dbn='$dbn' and national_code=$temp_table.code)");
	    else
		$db->query("update $temp_table set per_id=(select per_id from hr_personel where dbn='$dbn' and per_code=$temp_table.code),emp_kind=(select emp_kind from hr_personel where dbn='$dbn' and per_code=$temp_table.code)");
	    $sql3=$db->query("select parameter_id,emp_kind,noactive,hokm,concat(form_kind,',',serial)g_fs from hr_templates where dbn='$dbn' and iact=1 and parameter_id in(".$_SESSION['param'].")") ;
		while($row=$db->fetch_array($sql3))
	   {
		$NoativeArr[$row['parameter_id']][$row['emp_kind' ]]=$row['noactive'];
		$HokmArr[$row['parameter_id']][$row['emp_kind' ]]=$row['hokm'];
		if($row['hokm'])$hokm_ok.=$row['parameter_id'].',';
		$GpArr[$row['parameter_id']][$row['emp_kind']]=$row['g_fs'];
       }
	   $hokm_ok=substr($hokm_ok,0,strlen($hokm_ok)-1);
	   $paramArr=explode(',',$_SESSION['param']);
	   if ($tickorg)  $worg=implode(',',array_keys($tickorg));
	   $org_status=$db->get_from_where("count(*)","hr_periods","dbn='$dbn' and org in($worg) and period_id='$wperiod' and status<4",0,0);
		if($org_status!=count(array_keys($tickorg))) 
		{echo "<script>alert('وضعیت دوره در سازمانها بررسی شود');window.location='ahkam_mah_excel.php?kind=0';</script>";}
	   $q="select *,(select org from hr_org where dbn='$dbn' and per_id=$temp_table.per_id and end_period='999999')org from $temp_table  ";
	   $res=$db->query($q); 
	  
	  echo'
	 <table align=center width=50% cellpadding=0 cellspacing=0  border=0 style="position=relative;top:20">
	  <tr>
	   <td>
        <caption>
       ارسال فایل اکسل ماهانه پارامتر پرسنل
	  </caption>
         
	   </td>
	  </tr>	  
	  <tr valign=top>
	   <td>
	  <div style="height:300px;overflow:auto;width:100%">
      <table width=100% border=0 dir=rtl cellpadding=0 cellspacing=0 id="data_tbl">';
		$hokm_date=$ActiveMonth.'01';$cntr=0;$errorArr[]="";$yy="";$vv="";
	    while($r=$db->fetch_array($res))
		 { $per_id=$r['per_id'];$org=$r['org'];$exec_date=$r['date'];
	       $end_date=$r['end_date'];$exec_period=substr($r['date'],0,6);
		   $emp_kind=$r['emp_kind'];$code=$r['code'];	   
	       switch($per_id)
	       {case 0:$errorArr['cod'].=$code.',';break;
		    
			case $per_id and !$org:$errorArr['codorg'].=$code.',';break;
			case $per_id and   !in_array($org,array_keys($tickorg)):$errorArr['org'].=$org.',';break; 
            case $per_id :
             $cntr++;			
			 foreach($paramArr as $k=>$v)
		    { $noact=$NoativeArr[$v][$emp_kind];
			  $hokm=$HokmArr[$v][$emp_kind]; 
			  $hokm_no=$db->get_from_where("max(hokm_no)","hr_ahkam","dbn='$dbn' and per_id='$per_id' and exec_date<='$exec_date' and parameter_id='$v'");  
		      if($hokm and(!right(3107) or !$exec_date or strlen($exec_date)!=8 or $exec_date>$yyyymmdd)) $hokm=2;
			  elseif($hokm and $r['p'.$v]==0 and !$hokm_no) $hokm=3;
			  else if(!in_array($v,array_keys($GpArr)))$hokm=4;
              switch($hokm and !$noact)		  
			  {case 0 :
			   $vv.="('$dbn','$wperiod','$per_id','$org','$emp_kind',".$GpArr[$v][$emp_kind].",'$v','".$r['p'.$v]."','$month_numbers','0','0'),";break;
			   case 1:
			   $hokm_no++;
			   $vv.="('$dbn','$wperiod','$per_id','$org','$emp_kind',".$GpArr[$v][$emp_kind].",'$v','".$r['p'.$v]."','0','$exec_date','$end_date'),";
		       $yy.="('$dbn','$per_id','$org','$exec_period','$exec_date','$end_date','$hokm_date','$hokm_no','$v','".$r['p'.$v]."'),";
			   break;
			   case 2 :$errorArr['hokmdate'].=$v.',';break;
			   case 3 :$errorArr['hokmrls'].=$v.',';break;
			   case 4 :$errorArr['hokmparam'].=$v.',';break;
			   //case $noact :$errorArr['noact'].=$v.',';break;
				  
			  }
			
		  
		    }break;  
			   
		   }
	
		    
		          
		  
		   }

		   echo'
		<tr>
		 <td> '.$cntr.' پرسنل ثبت شد</td>
		</tr>';
		 
		foreach($errorArr as $kkk=>$vvv)   
		{switch($kkk)
		{case 'cod':$error="کد پرسنلی یا کد ملی اشتباه است ";break; 
		 case 'codorg':$error="سازمان این افراد اشتباه است ";break; 
         case 'org':$error=" این سازمان در سازمان انتخابی وجود ندارد";break; 	
         case 'hokmdate':$error="تاریخ حکم اشتباه است ";break; 
		 case 'hokmrls':$error="حکم از ابتدا وجود نداشته است";break; 
		 case 'noact':	$error="این پارامتر غیر فعال است";break; 
		 case 'hokmparam':	$error="پارامتر در یکی از نوع استخدام  نیست";break; 	 
			
		}
		echo'
		<tr>
		 <td>'.$error.'=>'.$vvv.'</td>
		</tr>';
		}$check=0;
		$db->query("delete from hr_data where dbn='$dbn' and period_id='$wperiod' and per_id in(select per_id from $temp_table) and parameter_id in (".$_SESSION['param'].")");
	    $db->query("delete from hr_ahkam where dbn='$dbn' and hokm_date='$hokm_date' and per_id in(select per_id from $temp_table) and parameter_id in ($hokm_ok)");
	    if ($vv)
	    {$qq="insert into   hr_data(dbn,period_id,per_id,org_id,emp_kind,form_kind,serial,parameter_id,rls,month_numbers,exec_date,end_date) values";
		 $qq.=substr($vv,0,strlen($vv)-1);
		 
	     $db->query($qq);
		 $check++;
	    }
		if ($yy)
	    {$qqq="insert into hr_ahkam (dbn,per_id,org_id,exec_period,exec_date,exp_date,hokm_date,hokm_no,
	    parameter_id,rls)values";
		 $qqq.=substr($yy,0,strlen($yy)-1);
	     $db->query($qqq); echo $qqq;
		 $check++;
	    }
		$db->query("drop table $temp_table");$vv=0;$yy=0;
	    if($check){$check=0;echo "<script>alert('اتمام عمليات');</script>";}	
		 
		 echo'
		 <tr>
		    <td align=center class="table-footer_me">
     <table border=0 width=100% cellpadding=0 cellspacing=0>
      <tr align=center>
       <td  dir=ltr>
	    <input type="submit" name="exit" value="بازگشت"  style="width:130px">
		</td>
	   <td>&nbsp;</td>
      </tr>
     </table>
    </td>
		 </tr>
		 </table>
		</div>
	   </td>
	  </tr>

	 </table>
	 </div>';
   }  
	echo'
 </div>
</form>
</body>';