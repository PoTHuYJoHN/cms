<div class="gallery-wrap">
	<?php
	use App\Repositories\FileRepository;

	foreach ($images as $image) : ?>
		<?
		list ($w, $h) = getimagesize(
			FileRepository::getRealUrl($image->type, $image->token, $image->size)
		);

		list ($wM, $hM) = getimagesize(
			FileRepository::getRealUrl($image->type, $image->token, $image->size)
		);
		?>
		<a class="gallery-item effect-ruby photo"
		   href="<?= FileRepository::getUrl($image, 'w2000') ?>"
		   itemprop="contentUrl"
		   data-title="<?= $image->name ?>"
		   data-size="<?= $w ?>x<?= $h ?>"
		   data-med="<?= FileRepository::getUrl($image, 'w800') ?>"
		   data-med-size="<?= $wM ?>x<?= $hM ?>">

			<p class="upper">View</p>
			<img src="<?= FileRepository::getUrl($image, 'news') ?>"
			     itemprop="thumbnail"
			     alt="<?= $image->text1 ?>"/>

		</a>
	<?php endforeach; ?>
</div>


<!--add template-->
