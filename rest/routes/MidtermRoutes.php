<?php

Flight::route('GET /midterm/connection-check', function(){
    /** TODO
    * This endpoint prints the message from constructor within MidtermDao class
    * Goal is to check whether connection is successfully established or not
    * This endpoint does not have to return output in JSON format
    * Sample output is given in figure 2    
    */

    echo "Connected successfully";
});

Flight::route('POST /midterm/investor', function() {
    /** TODO
    * This endpoint is used to add a new record to the investors and cap-table database tables.
    * Investor contains: first_name, last_name, email, and company.
    * Cap table fields are share_class_id, investor_id, and diluted_shares.
    * RULE 1: The sum of diluted shares of all investors within a given class cannot be higher than the authorized assets field
    * for the share class given in the share_classes table.
    * RULE 2: Email addresses have to be unique, meaning that two investors cannot have the same email address.
    * If added successfully, the output should be the message that the investor has been created successfully.
    * If an error is detected, an appropriate error message should be given as output.
    * This endpoint should return output in JSON format.
    * Sample output is given in figure 2 (the message should be updated according to the result).
    */

    $data = Flight::request()->data;
    $firstName = $data['first_name'];
    $lastName = $data['last_name'];
    $email = $data['email'];
    $company = $data['company'];
    $shareClassId = $data['share_class_id'];
    $dilutedShares = $data['diluted_shares'];

    $service = Flight::midtermService();
    $response = $service->addInvestor($firstName, $lastName, $email, $company, $shareClassId, $dilutedShares);
    Flight::json($response);
});



Flight::route('GET /midterm/investor_email/@email', function($email){
    /** TODO
    * This endpoint is used to check if investor email is in valid format
    * and if it exists in investors table
    * If format is not valid, output should be 'Invalid email format' message
    * If format is valid, return either
    * 'Investor first_name last_name' uses this email address' (replace first_name and last_name with data from database)
    * or 'Investor with this email does not exists in database'
    * Output example is given in figure 2 (message should be updated according to the result)
    * This endpoint should return output in JSON format
    */

    $service = Flight::midtermService();
    $response = $service->investor_email($email);
    Flight::json($response);
    
});

Flight::route("GET /midterm/investor/@share_class_id", function($share_class_id){
    /** TODO
    * This endpoint is used to list all investors from give share_class
    * (meaning all investors occuring in cap table with given share_class_id)
    * It should return share class description, equiy main currency, price and authorized_assets,
    * investor first and last name, email, company and total diluted assets within cap table
    * Sample data within tables and expected output with given data is provided in figures 3, 4, 5 and 6
    * Output is given in figure 6
    * This endpoint should return output in JSON format
    */

    Flight::json(Flight::midtermService() -> investors($share_class_id));
});

?>
