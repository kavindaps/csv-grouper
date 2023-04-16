<?php

class ParserTest extends \PHPUnit\Framework\TestCase {
    public function testSomething(){
        $inputFilePath = 'input.csv';

        // Find the input file extention
        $inputFileNameParts = explode('.', $inputFilePath);
        $inputFileExtension = end($inputFileNameParts);

        // Output Logic Statement
        $outputLogic = '--unique-combinations';

        // Set the output file name
        $outputFilePath = 'output.csv';

        // Find the output file extention
        $outputFileNameParts = explode('.', $outputFilePath);
        $outputFileExtension = end($outputFileNameParts);

        // API logic here
        if($outputLogic == '--unique-combinations'){
            // Read input CSV file
            $inputFile;
            if (file_exists($inputFilePath)) {
                $inputFile = fopen($inputFilePath, 'r'); // Open the file in read mode.

                if (!$inputFile) {
                    die ("Failed to open $inputFilePath file."); // End the process in case file could not be opened.
                }
            } else {
                die ("File $inputFilePath does not exist."); // End the process if file not exists.
            }

            // Read CSV header from input file
            if($inputFileExtension == 'csv'){
                $csvHeader = fgetcsv($inputFile);
            } else if($inputFileExtension == 'tsv'){
                $csvHeader = fgetcsv($inputFile, 0, "\t");
            } else if($inputFileExtension == 'json'){
                die('File type is not supported yet!');
            } else if($inputFileExtension == 'xml'){
                die('File type is not supported yet!');
            }else {
                die('Invalid file type');
            }

            if ($csvHeader === false) {
                die('Failed to read CSV header');
            }

            // This code segment will check if the file has all the fields, if not it will stop running
            try{
                if(count($csvHeader) < 7){
                    throw new Exception("Required fields are missing in the file $inputFilePath");
                }
            } catch (Exception $e) {
                echo "Error: ".$e->getMessage();
                exit;
            }

            // Create output CSV file
            $outputFile = fopen($outputFilePath, 'w');
            if ($outputFile === false) {
                die('Failed to create output file');
            }

            // Add "Count" column to the header
            $csvHeader[] = 'Count';

            // Write headers to the output file
            if($outputFileExtension == 'csv'){ // executes if the output file extension is csv
                fputcsv($outputFile, $csvHeader);
            } else if($outputFileExtension == 'tsv'){ // executes if the output file extension is tsv
                fputcsv($outputFile, $csvHeader, "\t");
            } 

            // Initializing an array to keep track of grouped counts
            $groupedCounts = array();

            if($inputFileExtension == 'csv'){ // executes if the input file extension is csv
                // Loop through each row in the input file
                while (($csvRow = fgetcsv($inputFile)) !== false) {

                    echo implode(', ', $csvRow) . "\n";

                    // Create a unique combination key based on all columns
                    $groupKey = implode('--', $csvRow);
                    
                    // Check if the group key already exists in the array
                    if (isset($groupedCounts[$groupKey])) {
                        // If yes, increment the count for that group
                        $groupedCounts[$groupKey]['Count']++;
                    } else {
                        // If not, add a new group with count as 1
                        $groupedCounts[$groupKey] = array_combine($csvHeader, array_merge($csvRow, array('Count' => 1)));
                    }
                }
            }

            if($inputFileExtension == 'tsv'){ // executes if the input file extension is tsv
                while (($csvRow = fgetcsv($inputFile, 0, "\t")) !== false) {

                    echo implode("\t", $csvRow) . "\n";

                    // Create a unique combination key based on all columns
                    $groupKey = implode('--', $csvRow);
                    
                    // Check if the group key already exists in the array
                    if (isset($groupedCounts[$groupKey])) {
                        // If yes, increment the count for that group
                        $groupedCounts[$groupKey]['Count']++;
                    } else {
                        // If not, add a new group with count as 1
                        $groupedCounts[$groupKey] = array_combine($csvHeader, array_merge($csvRow, array('Count' => 1)));
                    }
                }
            }

            // Write grouped counts to output file
            if($outputFileExtension == 'csv'){ // executes if the output file extension is csv
                foreach ($groupedCounts as $group) {
                    fputcsv($outputFile, $group);
                }
            } else if($outputFileExtension == 'tsv'){ // executes if the output file extension is tsv
                foreach ($groupedCounts as $group) {
                    fputcsv($outputFile, $group, "\t");
                }
            } 

            // Close input and output files
            fclose($inputFile);
            fclose($outputFile);
        } else {
            echo "API logic is not valid \n";
            echo "Please try again \n";
        }
    }
}