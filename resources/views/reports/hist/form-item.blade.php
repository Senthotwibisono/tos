@props(['md', 'id', 'label', 'value'])

<div {{ $attributes->
	merge(['class' => 'col-'.$md.' col-12']) }}>
	<div class="form-group">
		<label for="{{ $id }}" class="control-label">{{ $label }}</label>
		<div class="form-control">{{ !empty($value) ? $value : '.' }}</div>
	</div>
</div>