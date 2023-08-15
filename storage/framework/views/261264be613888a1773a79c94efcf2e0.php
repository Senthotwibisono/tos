<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag; ?>
<?php foreach($attributes->onlyProps(['md', 'id', 'label', 'value']) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $attributes = $attributes->exceptProps(['md', 'id', 'label', 'value']); ?>
<?php foreach (array_filter((['md', 'id', 'label', 'value']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $__defined_vars = get_defined_vars(); ?>
<?php foreach ($attributes as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
} ?>
<?php unset($__defined_vars); ?>

<div <?php echo e($attributes->
	merge(['class' => 'col-'.$md.' col-12'])); ?>>
	<div class="form-group">
		<label for="<?php echo e($id); ?>" class="control-label" style=""><?php echo e($label); ?></label>
		<div class="form-control"><?php echo e(!empty($value) ? $value : '.'); ?></div>
	</div>
</div><?php /**PATH C:\xampp\htdocs\tos\resources\views/components/form-item.blade.php ENDPATH**/ ?>