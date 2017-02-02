<template>
	<div class="content-editor-container">
		<slot name="label"></slot>
		<slot name="editable-content"></slot>
		<input type="hidden" :name="name" :value="value" />
	</div>
</template>

<script>
	export default {
		data: function () {
			return {
				quill: null,
				value: null,
				name: null
			};
		},
		mounted: function () {			
			this.quill = new window.Quill(
				this.$el.querySelector('.editable-content'),
				{
					modules: {},
					theme: 'snow'
				}
			);
			
			this.name = this.quill.root.parentNode.getAttribute('name');

			this.updateValue();
			this.quill.on('text-change', this.updateValue);
		},
		methods: {
			updateValue: function () {
				this.value = this.quill.root.innerHTML;
			}
		}
	}
</script>