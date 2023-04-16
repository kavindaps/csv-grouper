# API for Generating Unique Combinations from CSV and TSV Files

This API works in Terminal for generating unique combinations from CSV (Comma Separated Values) and TSV (Tab Separated Values) files. The API is designed to work in a terminal environment and can be executed using PHP. (Successfully Tested in Mac Terminal)

#### Requirements
To use this API, you need to have the following software installed on your system:
- PHP (minimum version 7.4)

#### Usage
To run this API, use the following command in your terminal:

`php parser.php --file example_1.csv --unique-combinations=combination_count.csv`

- **`example_1.csv:`** This is the input CSV or TSV file from which unique combinations will be generated. You can replace this with the name of your own input file.
- **`combination_count.csv`** or **`combination_count.tsv`**: This is the output CSV or TSV file where the generated unique combinations will be stored. You can replace this with the name of your desired output file. The API supports both CSV and TSV formats as output files, and you can specify the desired format in the output file name.
The API will process the input file and generate unique combinations according to the specified rules. The generated combinations will be stored in the output file in the format specified in the output file name.
- Place the source file **`example_1.csv:`** in the same folder as the **`parser.php`** file

#### PHP Version Support
This API supports PHP version 7.4 and above.

#### Exception Handling
The API has built-in exception handling. If the **`--file`** command or **`--unique-combinations=`** command is not provided or if there are any other issues, the API will throw an exception with a relevant error message.

#### Support
If you need any help or have questions about using this API, please refer to the documentation or contact the author at kavindaps@gmail.com.
