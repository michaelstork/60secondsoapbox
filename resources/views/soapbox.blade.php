@extends('layouts.app')

@section('content')

	<div class="container vue-mount">
		<div class="panel-container">
			<div v-bind:style="panelStyle" class="panel-track">
				<component v-for="(panel, p) in layout.panels"
					:panel="panel"
					:is="layout.panels[p].id"
					:is-active-panel="layout.activePanelIndex === p">
					@foreach ($panelContent as $content)
						<div slot="{{ $content->name }}-content">{!! nl2br($content->content) !!}</div>
					@endforeach
				</component>
			</div>
		</div>
	</div>

@endsection