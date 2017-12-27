<!DOCTYPE html>
<html>

<head>
<style>
table,th,td
{
border:1px solid #646464;
border-collapse:collapse;
}
th,td
{
padding:5px;
}
th
{
text-align:left;
}
</style>
</head>

<body>
<table>	
	<tr>
		<?foreach ($headings as $h):?>
			<th><?=$h?></th>
		<?endforeach;?>
	</tr>
	<?foreach ($data as $row):?>
	<tr>
		<?foreach ($row as $value):?>
			<td><?=$value?></td>
		<?endforeach;?>
	</tr>
	<?endforeach;?>
</table>
<br />
Total Rows: <?=count($data);?>
<br />
<br />
Regards,<br />
MS-Prime Server
</body>