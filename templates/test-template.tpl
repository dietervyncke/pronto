<p>
	Hallo welkom op de builder
</p>

{{ if ?=module_backend( 1, 2, 3 ) equals "y" }}
<p>
	Ik wil de backend module met alles er op en eraanananan;
</p>
{{ /if }}

{{ if ?=module_backend( 1 => 'optie 1', 2 => 'optie 2', 3 => 'optie 3' ) equals "y" }}
<p>
	Ik wil de backend module met alles er op en eraanananan;
</p>
{{ /if }}

{{ if ?=module_backend( [ 1 => 'optie 1', 2 => 'optie 2', 3 => 'optie 3' ] ) equals "y" }}
<p>
	Ik wil de backend module met alles er op en eraanananan;
</p>
{{ /if }}

{{ if ?=module_backend equals "y" }}
<p>
	Ik wil de backend module met alles er op en eraanananan;
</p>
{{ /if }}

<p>
	HOi hoi erna.
</p>

{{ ?=glob_var( "optie 1", "optie 2" ) }}

{{ if ?=second_var( 1,2,3 ) equals 2 }}
	<h1>Je antwoord op deze vraag was {{ ?=second_var }}</h1>
	<p>Correct antwoord</p>
{{ /if }}