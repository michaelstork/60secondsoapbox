@extends('layouts.app')

@section('content')

	<div class="container vue-mount">
		<div class="panel-container">
			<div v-bind:style="panelStyle" class="panel-track">
				<component v-for="(panel, p) in layout.panels"
					:panel="panel"
					:is="layout.panels[p].id"
					:is-active-panel="layout.activePanelIndex === p">
				</component>
			</div>
			<nav class="panel-nav">
				<transition-group name="fade" tag="div">
					<a v-show="canNavigateBack"
						v-on:click.prevent="navigateBack"
						href="#"
						key="back"
						tabIndex="-1">
					    <i class="mdi mdi-arrow-left-bold-circle-outline"></i>
					    <span>Back</span>
					</a>
					<a v-show="layout.activePanelIndex < (layout.panels.length - 1)"
						v-on:click.prevent="navigateForward"
						:disabled="!canNavigateForward"
						href="#"
						key="forward"
						tabIndex="-1">
					    <span>Continue</span>
					    <i class="mdi mdi-arrow-right-bold-circle-outline"></i>
					</a>
				</transition>
			</nav>
		</div>
	</div>

@endsection