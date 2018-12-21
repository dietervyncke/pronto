# Pronto
## To pronto or not to pronto

[![Build Status](https://travis-ci.org/DieterVyncke/pronto.svg?branch=master)](https://travis-ci.org/DieterVyncke/pronto)

### CLI usage

```ssh
pronto (-i <inputfile> | -s <sourcecode> | -a) [-o <outputfile>] [-d <writedir>]
```

#### Options

Option | Name | Description
------ | ---- | -----------
-i     | inputfile | Pronto entry point as a file
-s     | sourcecode | Pronto code to evaluate
-a     | interactive shell | Starts an interactive shell
-o     | outputfile | File to write the output to, if not set Pronto will output to the console
-d     | writedir | The directory Pronto will write files to