<?php
/*********************************************
* Author: Kavinda Sooriyarathna
* CSV & TSV Unique combination counter
* Version: 1.0
* Supported File Types: CSV & TSV
* Any questions: kavindaps@gmail.com
*********************************************/

// Starting point to accept arguments to the API script. Checks if arguments are exists.
// $argc -> The number of arguments passed to script.
// $argv[1] -> Checks if the --file command is exists.
// $argv[2] -> Input file.
// $argv[3] -> --unique-combinations command and output file name.
if ( ($argc > 1) && ($argv[1] == '--file') && (isset($argv[2])) && (isset($argv[3])) ) {
    
    // Set the input file name
    $inputFilePath = $argv[2];

    // Find the input file extention
    $inputFileNameParts = explode('.', $inputFilePath);
    $inputFileExtension = end($inputFileNameParts);
    
    //Seperate output logic and the output file name
    $pieces = explode('=', $argv[3]);

    try{
        if(count($pieces) !== 2){
            throw new Exception("Arguments combination is not valid");
        }
    } catch (Exception $e) {
        echo "Error: ".$e->getMessage();
        exit;
    }
    // Output Logic Statement
    $outputLogic = $pieces[0];

    // Set the output file name
    $outputFilePath = $pieces[1];

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

                // Create a unique combination key based on all columns
                $groupKey = implode('--', $csvRow);
                
                // Check if the group key already exists in the array
                if (isset($groupedCounts[$groupKey])) {
                    // If yes, increment the count for that group
                    $groupedCounts[$groupKey]['Count']++;
                } else {
                    // If not, add a new group with count as 1
                    $newFeilds = array_merge($csvRow, array('Count' => 1));
                    try{
                        if(count($newFeilds) == count($csvHeader)){
                            $groupedCounts[$groupKey] = array_combine($csvHeader, $newFeilds);
                        } else {
                            throw new Exception("Headers count do not match with the values count");
                        }
                    } catch (Exception $e) {
                        echo "Error: ".$e->getMessage();
                        exit;
                    }   
                }
                // Show each product object 
                echo implode(', ', $csvRow) . "\n";
            }
        }

        if($inputFileExtension == 'tsv'){ // executes if the input file extension is tsv
            while (($csvRow = fgetcsv($inputFile, 0, "\t")) !== false) {

                // Create a unique combination key based on all columns
                $groupKey = implode('--', $csvRow);
                
                // Check if the group key already exists in the array
                if (isset($groupedCounts[$groupKey])) {
                    // If yes, increment the count for that group
                    $groupedCounts[$groupKey]['Count']++;
                } else {
                    // If not, add a new group with count as 1
                    $newFeilds = array_merge($csvRow, array('Count' => 1));
                    try{
                        if(count($newFeilds) == count($csvHeader)){
                            $groupedCounts[$groupKey] = array_combine($csvHeader, $newFeilds);
                        } else {
                            throw new Exception("Headers count do not match with the values count");
                        }
                    } catch (Exception $e) {
                        echo "Error: ".$e->getMessage();
                        exit;
                    }
                }
                // Show each product object
                echo implode("\t", $csvRow) . "\n";
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
} else {
    echo "Program didn't work \n";
    echo "One or more arguments are missing \n";
}
?>