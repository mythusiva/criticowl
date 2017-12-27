<?
	$numerator = $rating; //because it is already over 100
	$denominator = 10;
	$rating = 100 * ($numerator/$denominator); //to make the rating clean
?>
<?if($rating > 0 && $rating < 40):?>
<div class="progress progress-danger progress-striped">
  <div class="bar" style="width: <?=$rating?>%"><span class="badge badge-important"><strong><sup><?=$numerator?></sup>&frasl;<sub><?=$denominator?></sub></strong></span></div>
</div>
<?elseif($rating > 0 && $rating < 60):?>
<div class="progress progress-warning progress-striped">
  <div class="bar" style="width: <?=$rating?>%"><span class="badge badge-warning"><strong><sup><?=$numerator?></sup>&frasl;<sub><?=$denominator?></sub></strong></span></div>
</div>
<?elseif($rating > 0 && $rating < 90):?>
<div class="progress progress-success progress-striped">
  <div class="bar" style="width: <?=$rating?>%"><span class="badge badge-success"><strong><sup><?=$numerator?></sup>&frasl;<sub><?=$denominator?></sub></strong></span></div>
</div>
<?elseif($rating > 0 && $rating <= 100):?>
<div class="progress progress-info progress-striped">
  <div class="bar" style="width: <?=$rating?>%"><span class="badge badge-info"><strong><sup><?=$numerator?></sup>&frasl;<sub><?=$denominator?></sub></strong></span></div>
</div>
<?else:?>
<div class="progress progress-danger progress-striped">
  <div class="bar" style="width: <?=$rating?>%"><span class="badge badge-important"></div>
</div>
<?endif;?>