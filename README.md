# Pronto
## To pronto or not to pronto

[![Build Status](https://travis-ci.org/DieterVyncke/pronto.svg?branch=master)](https://travis-ci.org/DieterVyncke/pronto)

### CLI usage

```ssh
pronto (-i <inputfile> | -s <sourcecode> | -a) [-o <outputfile>] [-d <writedir>] [-r <runtimefile>]
```

#### Options

Option | Name | Description
------ | ---- | -----------
-i     | input file | Pronto entry point as a file
-s     | sourcecode | Pronto code to evaluate
-a     | interactive shell | Starts an interactive shell
-o     | output file | File to write the output to, if not set Pronto will output to the console
-d     | write directory | The directory Pronto will write files to
-r     | runtime file | The file to read and write the runtime from (currently only .json support)