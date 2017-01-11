
<template>
	<div class="panel-icon">
		<slot></slot>
		<transition name="status-indicator">
			<div v-if="status === 'pending'" class="status-indicator status-indicator-pending" key="pending">
				<i class="mdi mdi-cloud-upload"></i>
			</div>
			<div v-else-if="status === 'complete'" class="status-indicator status-indicator-complete" key="complete">
				<i class="mdi mdi-thumb-up"></i>
			</div>
			<div v-else-if="status === 'error'" class="status-indicator status-indicator-error" key="error">
				<i class="mdi mdi-thumb-down"></i>
			</div>
		</transition>
	</div>
</template>

<script>
	export default {
		props: ['status']
	}
</script>