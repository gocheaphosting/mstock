<? include("../function/db.php");?>
<?php
/*
 * Copyright 2010 Amazon.com, Inc. or its affiliates. All Rights Reserved.
 *
 * Licensed under the Apache License, Version 2.0 (the "License").
 * You may not use this file except in compliance with the License.
 * A copy of the License is located at
 *
 *  http://aws.amazon.com/apache2.0
 *
 * or in the "license" file accompanying this file. This file is distributed
 * on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either
 * express or implied. See the License for the specific language governing
 * permissions and limitations under the License.
 */

/*
	PREREQUISITES:
	In order to run this sample, I'll assume a few things:

	* You already have a valid Amazon Web Services developer account, and are
	  signed up to use Amazon S3 <http://aws.amazon.com/s3>.

	* You already understand the fundamentals of object-oriented PHP.

	* You've verified that your PHP environment passes the SDK Compatibility Test.

	* You've already added your credentials to your config.inc.php file, as per the
	  instructions in the Getting Started Guide.

	TO RUN:
	* Run this file on your web server by loading it in your browser, OR...
	* Run this file from the command line with `php cli-s3_get_urls_for_uploads.php`.
*/


/*%******************************************************************************************%*/
// SETUP

//Check access
admin_panel_access("settings_storage");


	// Enable full-blown error reporting. http://twitter.com/rasmus/status/7448448829
	error_reporting(-1);

	// Set plain text headers
	header("Content-type: text/plain; charset=utf-8");

	// Include the SDK
	require_once '../amazon/sdk.class.php';


/*%******************************************************************************************%*/
// UPLOAD FILES TO S3

	// Instantiate the AmazonS3 class
	$s3 = new AmazonS3();

	// Determine a completely unique bucket name (all lowercase)
	$bucket = $global_settings["amazon_prefix"]. "-test";
	$bucket_files = $global_settings["amazon_prefix"]. "-files";
	$bucket_previews = $global_settings["amazon_prefix"]. "-previews";
	

	// Create our new bucket in the US-West region.
	if ($global_settings["amazon_region"] == "REGION_US_E1") {$region = AmazonS3::REGION_US_E1;}
	if ($global_settings["amazon_region"] == "REGION_US_W1") {$region = AmazonS3::REGION_US_W1;}
    if ($global_settings["amazon_region"] == "REGION_EU_W1") {$region = AmazonS3::REGION_EU_W1;}
    if ($global_settings["amazon_region"] == "REGION_APAC_SE1") {$region = AmazonS3::REGION_APAC_SE1;}
    if ($global_settings["amazon_region"] == "REGION_APAC_NE1") {$region = AmazonS3::REGION_APAC_NE1;}
    
    if ($global_settings["amazon_region"] == "REGION_US_W2") {$region = AmazonS3::REGION_US_W2;}
    if ($global_settings["amazon_region"] == "REGION_EU_W2") {$region = AmazonS3::REGION_EU_W2;}
    if ($global_settings["amazon_region"] == "REGION_APAC_SE2") {$region = AmazonS3::REGION_APAC_SE2;}
    if ($global_settings["amazon_region"] == "REGION_SA_E1") {$region = AmazonS3::REGION_SA_E1;}
	
	
	
	$create_bucket_response = $s3->create_bucket($bucket,$region);

	// Provided that the bucket was created successfully...
	if ($create_bucket_response->isOK())
	{
		/* Since AWS follows an "eventual consistency" model, sleep and poll
		   until the bucket is available. */
		$exists = $s3->if_bucket_exists($bucket);
		while (!$exists)
		{
			// Not yet? Sleep for 1 second, then check again
			sleep(1);
			$exists = $s3->if_bucket_exists($bucket);
		}

			$filename="test.jpg";
			$filename_path=$_SERVER["DOCUMENT_ROOT"].site_root."/admin/storage/test.jpg";



			$s3->batch()->create_object($bucket,$filename , array(
                'fileUpload' => $filename_path,
                'acl' => AmazonS3::ACL_PUBLIC,
            ));
		

		$file_upload_response = $s3->batch()->send();
		
		if ($file_upload_response->areOK())
        {
			$url=$s3->get_object_url($bucket, $filename);
			
			echo("The file has been uploaded successfully: ".$url. " into the bucket '".$bucket."'\n");
			
			
		}
		else
		{
			echo("Error. The script cannot upload the file '".$filename."' into the bucket '".$bucket."'.\n");
		}
	}
	else
	{
		echo("Error. The script cannot create the bucket: '".$bucket."'. Probably the bucket already exists or the name is incorrect.\n");
	}

	$create_bucket_response = $s3->create_bucket($bucket_files,$region);
	if ($create_bucket_response->isOK())
	{
		echo("The script created the bucket: '".$bucket_files."' successfully.\n");
	}
	else
	{
		echo("Error. The script cannot create the bucket: '".$bucket_files."'. Probably the bucket already exists or the name is incorrect.\n");
	}
	
	
	$create_bucket_response = $s3->create_bucket($bucket_previews,$region);
	if ($create_bucket_response->isOK())
	{
		echo("The script created the bucket: '".$bucket_previews."' successfully.\n");
	}
	else
	{
		echo("Error. The script cannot create the bucket: '".$bucket_previews."'. Probably the bucket already exists or the name is incorrect.\n");
	}
	
	$db->close();
?>