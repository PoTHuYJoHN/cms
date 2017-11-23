@include('pages.partials.commenting.form', ['item_id' => $item->id, 'item_type' => $itemType])

<div id="сomments" class="ajaxPagerContent" style="position: relative;">
	<div class="col-md-22 comment-block mt25 u-invisible u-noneBlock hidden"></div>

	@foreach($item->comments as $comment)
		@include('pages.partials.commenting.one-comment', compact('comment'))
	@endforeach
</div>
