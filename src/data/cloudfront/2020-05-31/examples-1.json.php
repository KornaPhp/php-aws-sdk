<?php
// This file was auto-generated from sdk-root/src/data/cloudfront/2020-05-31/examples-1.json
return [ 'version' => '1.0', 'examples' => [ 'CreateFunction' => [ [ 'input' => [ 'FunctionCode' => 'function-code.js', 'FunctionConfig' => [ 'Comment' => 'my-function-comment', 'KeyValueStoreAssociations' => [ 'Items' => [ [ 'KeyValueStoreARN' => 'arn:aws:cloudfront::123456789012:key-value-store/54947df8-0e9e-4471-a2f9-9af509fb5889', ], ], 'Quantity' => 1, ], 'Runtime' => 'cloudfront-js-2.0', ], 'Name' => 'my-function-name', ], 'output' => [ 'ETag' => 'ETVPDKIKX0DER', 'FunctionSummary' => [ 'FunctionConfig' => [ 'Comment' => 'my-function-comment', 'KeyValueStoreAssociations' => [ 'Items' => [ [ 'KeyValueStoreARN' => 'arn:aws:cloudfront::123456789012:key-value-store/54947df8-0e9e-4471-a2f9-9af509fb5889', ], ], 'Quantity' => 1, ], 'Runtime' => 'cloudfront-js-2.0', ], 'FunctionMetadata' => [ 'CreatedTime' => '2023-11-07T19:53:50.334Z', 'FunctionARN' => 'arn:aws:cloudfront::123456789012:function/my-function-name', 'LastModifiedTime' => '2023-11-07T19:53:50.334Z', 'Stage' => 'DEVELOPMENT', ], 'Name' => 'my-function-name', 'Status' => 'UNPUBLISHED', ], 'Location' => 'https://cloudfront.amazonaws.com/2020-05-31/function/arn:aws:cloudfront::123456789012:function/my-function-name', ], 'description' => 'Use the following command to create a function.', 'id' => 'example-1', 'title' => 'To create a function', ], ], 'CreateKeyValueStore' => [ [ 'input' => [ 'Comment' => 'my-key-valuestore-comment', 'ImportSource' => [ 'SourceARN' => 'arn:aws:s3:::my-bucket/validJSON.json', 'SourceType' => 'S3', ], 'Name' => 'my-keyvaluestore-name', ], 'output' => [ 'ETag' => 'ETVPDKIKX0DER', 'KeyValueStore' => [ 'ARN' => 'arn:aws:cloudfront::123456789012:key-value-store/54947df8-0e9e-4471-a2f9-9af509fb5889', 'Comment' => 'my-key-valuestore-comment', 'Id' => '54947df8-0e9e-4471-a2f9-9af509fb5889', 'LastModifiedTime' => '2023-11-07T18:15:52.042Z', 'Name' => 'my-keyvaluestore-name', 'Status' => 'PROVISIONING', ], 'Location' => 'https://cloudfront.amazonaws.com/2020-05-31/key-value-store/arn:aws:cloudfront::123456789012:key-value-store/54947df8-0e9e-4471-a2f9-9af509fb5889', ], 'description' => 'Use the following command to create a KeyValueStore.', 'id' => 'example-1', 'title' => 'To create a KeyValueStore', ], ], 'DeleteKeyValueStore' => [ [ 'input' => [ 'IfMatch' => 'ETVPDKIKX0DER', 'Name' => 'my-keyvaluestore-name', ], 'description' => 'Use the following command to delete a KeyValueStore.', 'id' => 'example-1', 'title' => 'To delete a KeyValueStore', ], ], 'DescribeKeyValueStore' => [ [ 'input' => [ 'Name' => 'my-keyvaluestore-name', ], 'output' => [ 'ETag' => 'ETVPDKIKX0DER', 'KeyValueStore' => [ 'ARN' => 'arn:aws:cloudfront::123456789012:key-value-store/54947df8-0e9e-4471-a2f9-9af509fb5889', 'Comment' => 'my-key-valuestore-comment', 'Id' => '54947df8-0e9e-4471-a2f9-9af509fb5889', 'LastModifiedTime' => '2023-11-07T18:20:33.056Z', 'Name' => 'my-keyvaluestore-name', 'Status' => 'READY', ], ], 'description' => 'Use the following command to describe a KeyValueStore.', 'id' => 'example-1', 'title' => 'To describe a KeyValueStore', ], ], 'ListKeyValueStores' => [ [ 'input' => [ 'Status' => 'READY', ], 'output' => [ 'KeyValueStoreList' => [ 'Items' => [ [ 'ARN' => 'arn:aws:cloudfront::123456789012:key-value-store/54947df8-0e9e-4471-a2f9-9af509fb5889', 'Comment' => '', 'Id' => '54947df8-0e9e-4471-a2f9-9af509fb5889', 'LastModifiedTime' => '2023-11-07T18:45:21.069Z', 'Name' => 'my-keyvaluestore-name', 'Status' => 'READY', ], ], 'MaxItems' => 100, 'NextMarker' => '', 'Quantity' => 1, ], ], 'description' => 'The following command retrieves a list of KeyValueStores with READY status.', 'id' => 'example-1', 'title' => 'To get a list of KeyValueStores', ], ], 'UpdateFunction' => [ [ 'input' => [ 'FunctionCode' => 'function-code-changed.js', 'FunctionConfig' => [ 'Comment' => 'my-changed-comment', 'KeyValueStoreAssociations' => [ 'Items' => [ [ 'KeyValueStoreARN' => 'arn:aws:cloudfront::123456789012:key-value-store/54947df8-0e9e-4471-a2f9-9af509fb5889', ], ], 'Quantity' => 1, ], 'Runtime' => 'cloudfront-js-2.0', ], 'IfMatch' => 'ETVPDKIKX0DER', 'Name' => 'my-function-name', ], 'output' => [ 'ETag' => 'E3UN6WX5RRO2AG', 'FunctionSummary' => [ 'FunctionConfig' => [ 'Comment' => 'my-changed-comment', 'KeyValueStoreAssociations' => [ 'Items' => [ [ 'KeyValueStoreARN' => 'arn:aws:cloudfront::123456789012:key-value-store/54947df8-0e9e-4471-a2f9-9af509fb5889', ], ], 'Quantity' => 1, ], 'Runtime' => 'cloudfront-js-2.0', ], 'FunctionMetadata' => [ 'CreatedTime' => '2023-11-07T19:53:50.334Z', 'FunctionARN' => 'arn:aws:cloudfront::123456789012:function/my-function-name', 'LastModifiedTime' => '2023-11-07T20:01:37.174Z', 'Stage' => 'DEVELOPMENT', ], 'Name' => 'my-function-name', 'Status' => 'UNPUBLISHED', ], ], 'description' => 'Use the following command to update a function.', 'id' => 'example-1', 'title' => 'To update a function', ], ], 'UpdateKeyValueStore' => [ [ 'input' => [ 'Comment' => 'my-changed-comment', 'IfMatch' => 'ETVPDKIKX0DER', 'Name' => 'my-keyvaluestore-name', ], 'output' => [ 'ETag' => 'E3UN6WX5RRO2AG', 'KeyValueStore' => [ 'ARN' => 'arn:aws:cloudfront::123456789012:key-value-store/54947df8-0e9e-4471-a2f9-9af509fb5889', 'Comment' => 'my-changed-comment', 'Id' => '54947df8-0e9e-4471-a2f9-9af509fb5889', 'LastModifiedTime' => '2023-11-07T18:45:21.069Z', 'Name' => 'my-keyvaluestore-name', 'Status' => 'READY', ], ], 'description' => 'Use the following command to update a KeyValueStore.', 'id' => 'example-1', 'title' => 'To update a KeyValueStore', ], ], ],];
