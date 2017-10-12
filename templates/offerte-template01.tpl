<h1>Familie {{ ?=last_name }}</h1>

<h2>Kinderen:</h2>

<ul>
	{{ repeat }}
		<li>
			Nice {{ ?=last_name }}<br />
			Kinderen van Nice {{ ?=last_name }}:
			<ul>
				{{ repeat }}
					<li>Een itemke</li>
				{{ /repeat }}
			</ul>
		</li>
	{{ /repeat }}
</ul>