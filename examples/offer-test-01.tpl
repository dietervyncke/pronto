<html>
	<head>
		<link rel="stylesheet" type="text/css" href="assets/reset.css">
		<link href="https://fonts.googleapis.com/css?family=Roboto:400,700" rel="stylesheet">
		<link rel="stylesheet" type="text/css" href="assets/style.css">
	</head>
	<body>
		<div id="wrapper">
			<div class="wrap">

				<h1 class="project-title">{{ ?=project_title }}</h1>

				{{ if ?=page_module( "yes", "no" ) equals "yes" }}

					<div class="module">
						<h2 class="title">Paginas <span class="timing">({{ ?-module_timing }})</span></h2>
						<p class="description">Lorem ipsum is simply dummy text of the printing's and typesetting industry.</p>

						<ul class="fields">
							{{ repeat( 'Add a field?' ) }}

								<li class="field">
									<p class="title">{{ ?-page_module_field( 'titel', 'tekstblok', 'photo' ) }} <span class="required">({{ ?-field_required( 'verplicht', 'optioneel' ) }})</span></p>

								{{ if ?-page_module_field equals 'titel' }}
									<p class="description">De tekst bij de titel</p>
								{{ /if }}

								{{ if ?-page_module_field equals 'tekstblok' }}
									<p class="description">De tekst bij de tekstblock</p>
								{{ /if }}

								{{ if ?-page_module_field equals 'photo' }}
									<p class="description">De tekst bij de photo</p>
								{{ /if }}
								</li>

							{{ /repeat }}

						{{ repeat( 'Add a custom field?' ) }}

							{{ if ?-page_module_field( 'custom' ) equals 'custom' }}
							<li class="field">
								<p class="title">{{ ?-custom_field_title }}<span class="required">({{ ?-field_required( 'verplicht', 'optioneel' ) }})</span></p>
								<p class="description">{{ ?-custom_field_description }}</p>
							</li>

						{{ /repeat }}
						</ul>

					</div>
				{{ /if }}

				{{ if ?-custom_module( 'yes', 'no' ) equals 'yes' }}

					{{ repeat( 'Add custom module' ) }}

						<div class="module">
							<h2 class="title">{{ ?-module_title }} <span class="timing">({{ ?-module_timing }})</span></h2>
							<p class="description">{{ ?-module_description }}</p>

							<ul class="fields">
								{{ repeat( 'Add a field?' ) }}

								<li class="field">
									<p class="title">{{ ?-page_module_field( 'titel', 'tekstblok', 'photo' ) }} <span class="required">({{ ?-field_required( 'verplicht', 'optioneel' ) }})</span></p>

									{{ if ?-page_module_field equals 'titel' }}
									<p class="description">De tekst bij de titel</p>
									{{ /if }}

									{{ if ?-page_module_field equals 'tekstblok' }}
									<p class="description">De tekst bij de tekstblock</p>
									{{ /if }}

									{{ if ?-page_module_field equals 'photo' }}
									<p class="description">De tekst bij de photo</p>
									{{ /if }}

								</li>

								{{ /repeat }}

								{{ repeat( 'Add a custom field?' ) }}

								{{ if ?-page_module_field( 'custom' ) equals 'custom' }}
								<li class="field">
									<p class="title">{{ ?-custom_field_title }}<span class="required">({{ ?-field_required( 'verplicht', 'optioneel' ) }})</span></p>
									<p class="description">{{ ?-custom_field_description }}</p>
								</li>

								{{ /repeat }}
							</ul>

						</div>

					{{ /repeat }}

				{{ /if }}

			</div>
		</div>

	</body>
</html>