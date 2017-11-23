<div class="row mb20">
	<div class="col-xs-24 col-md-24">
		<div class="comment-block">
			<div class="u-flex--between-wrap">
				<span class="author">{{ $comment->name }}</span>
			</div>

			<div>
				{{ $comment->comment }}
			</div>
			<p>
				{{ $comment->created_at->toFormattedDateString() }} at {{ $comment->created_at->toTimeString() }}
			</p>
		</div>
	</div>
</div>
