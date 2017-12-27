<style>
	table {
		text-align: center;
	}
</style>
<table  border="1">
<tr>
	<th>Title</th>
	<th>Release Date</th>
	<th>DVD Date Set</th>
	<th>BluRay Date Set</th>
</tr>
<?foreach($list as $l):?>
<tr>
	<td><?=$l['name']?></td>
	<td><?=$l['release_date']?></td>
	<td><?=(empty($l['dvd_release_date']))?'N':'Y'?></td>
	<td><?=(empty($l['bluray_release_date']))?'N':'Y'?></td>
</tr>
<?endforeach;?>
</table>