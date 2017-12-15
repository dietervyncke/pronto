{{ if ?=module_backend( "yes", "no" ) equals "yes" }}
{{ include '/templates/modules/backend.md' }}
{{ /if }}

{{ if ?=module_pages( "yes", "no" ) equals "yes" }}
{{ include '/templates/modules/pages.md' }}
{{ /if }}

{{ if ?=module_news( "yes", "no" ) equals "yes" }}
{{ include '/templates/modules/news.md' }}
{{ /if }}