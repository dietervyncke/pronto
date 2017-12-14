<h1>{{ ?=project_title }}</h1>

{{ if ?=backend_module( "yes", "no" ) equals "yes" }}
	<div class="module">
		<h2>Backend<span class="timing">({{ ?-module_timing }})</span></h2>
		<p>psum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s,
			when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap
			into electronic typesetting, remaining essentially un
		</p>
	</div>
{{ /if }}

{{ if ?=page_module( "yes", "no" ) equals "yes" }}
	<div class="module">
		<h2>Paginas<span class="timing">({{ ?-module_timing }})</span></h2>
		<p>psum is simply dummy text of the printing and typesetting industry.</p>

		{{ repeat }}

			{{ ?-page_module_field( 'titel', 'tekstblok', 'photo' ) }}

			{{ if ?-page_module_field equals "titel" }}
			<div class="field">
				<p class="title">Een titel</p>
				<p class="body">De tekst bij de titel</p>
			</div>
			{{ /if }}

			{{ if ?-page_module_field equals "tekstblok" }}
			<div class="field">
				<p class="title">Een tekstblock</p>
				<p class="body">De tekst bij de tekstblock</p>
			</div>
			{{ /if }}

			{{ if ?-page_module_field equals "photo" }}
			<div class="field">
				<p class="title">Een photo</p>
				<p class="body">De tekst bij de photo</p>
			</div>
			{{ /if }}

		{{ /repeat }}

	</div>
{{ /if }}