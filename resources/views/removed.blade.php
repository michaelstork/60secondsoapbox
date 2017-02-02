@extends('layouts.app')

@section('content')

	<div class="container">
		<div class="panel-container">
			<div v-bind:style="panelStyle" class="panel-track">
				<section class="content-panel">
					<div class="panel-content">
						<h3>You've been removed from the list, have a nice day!</h3>
					</div>
				</section>
			</div>
		</div>
	</div>

@endsection