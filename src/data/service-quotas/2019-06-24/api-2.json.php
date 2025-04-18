<?php
// This file was auto-generated from sdk-root/src/data/service-quotas/2019-06-24/api-2.json
return [ 'version' => '2.0', 'metadata' => [ 'apiVersion' => '2019-06-24', 'endpointPrefix' => 'servicequotas', 'jsonVersion' => '1.1', 'protocol' => 'json', 'protocols' => [ 'json', ], 'serviceFullName' => 'Service Quotas', 'serviceId' => 'Service Quotas', 'signatureVersion' => 'v4', 'targetPrefix' => 'ServiceQuotasV20190624', 'uid' => 'service-quotas-2019-06-24', 'auth' => [ 'aws.auth#sigv4', ], ], 'operations' => [ 'AssociateServiceQuotaTemplate' => [ 'name' => 'AssociateServiceQuotaTemplate', 'http' => [ 'method' => 'POST', 'requestUri' => '/', ], 'input' => [ 'shape' => 'AssociateServiceQuotaTemplateRequest', ], 'output' => [ 'shape' => 'AssociateServiceQuotaTemplateResponse', ], 'errors' => [ [ 'shape' => 'DependencyAccessDeniedException', ], [ 'shape' => 'AccessDeniedException', ], [ 'shape' => 'ServiceException', ], [ 'shape' => 'TooManyRequestsException', ], [ 'shape' => 'AWSServiceAccessNotEnabledException', ], [ 'shape' => 'OrganizationNotInAllFeaturesModeException', ], [ 'shape' => 'TemplatesNotAvailableInRegionException', ], [ 'shape' => 'NoAvailableOrganizationException', ], ], ], 'DeleteServiceQuotaIncreaseRequestFromTemplate' => [ 'name' => 'DeleteServiceQuotaIncreaseRequestFromTemplate', 'http' => [ 'method' => 'POST', 'requestUri' => '/', ], 'input' => [ 'shape' => 'DeleteServiceQuotaIncreaseRequestFromTemplateRequest', ], 'output' => [ 'shape' => 'DeleteServiceQuotaIncreaseRequestFromTemplateResponse', ], 'errors' => [ [ 'shape' => 'AccessDeniedException', ], [ 'shape' => 'ServiceException', ], [ 'shape' => 'DependencyAccessDeniedException', ], [ 'shape' => 'TooManyRequestsException', ], [ 'shape' => 'NoSuchResourceException', ], [ 'shape' => 'IllegalArgumentException', ], [ 'shape' => 'AWSServiceAccessNotEnabledException', ], [ 'shape' => 'TemplatesNotAvailableInRegionException', ], [ 'shape' => 'NoAvailableOrganizationException', ], ], ], 'DisassociateServiceQuotaTemplate' => [ 'name' => 'DisassociateServiceQuotaTemplate', 'http' => [ 'method' => 'POST', 'requestUri' => '/', ], 'input' => [ 'shape' => 'DisassociateServiceQuotaTemplateRequest', ], 'output' => [ 'shape' => 'DisassociateServiceQuotaTemplateResponse', ], 'errors' => [ [ 'shape' => 'DependencyAccessDeniedException', ], [ 'shape' => 'ServiceQuotaTemplateNotInUseException', ], [ 'shape' => 'AccessDeniedException', ], [ 'shape' => 'ServiceException', ], [ 'shape' => 'TooManyRequestsException', ], [ 'shape' => 'AWSServiceAccessNotEnabledException', ], [ 'shape' => 'TemplatesNotAvailableInRegionException', ], [ 'shape' => 'NoAvailableOrganizationException', ], ], ], 'GetAWSDefaultServiceQuota' => [ 'name' => 'GetAWSDefaultServiceQuota', 'http' => [ 'method' => 'POST', 'requestUri' => '/', ], 'input' => [ 'shape' => 'GetAWSDefaultServiceQuotaRequest', ], 'output' => [ 'shape' => 'GetAWSDefaultServiceQuotaResponse', ], 'errors' => [ [ 'shape' => 'AccessDeniedException', ], [ 'shape' => 'NoSuchResourceException', ], [ 'shape' => 'IllegalArgumentException', ], [ 'shape' => 'ServiceException', ], [ 'shape' => 'TooManyRequestsException', ], ], ], 'GetAssociationForServiceQuotaTemplate' => [ 'name' => 'GetAssociationForServiceQuotaTemplate', 'http' => [ 'method' => 'POST', 'requestUri' => '/', ], 'input' => [ 'shape' => 'GetAssociationForServiceQuotaTemplateRequest', ], 'output' => [ 'shape' => 'GetAssociationForServiceQuotaTemplateResponse', ], 'errors' => [ [ 'shape' => 'DependencyAccessDeniedException', ], [ 'shape' => 'ServiceQuotaTemplateNotInUseException', ], [ 'shape' => 'AccessDeniedException', ], [ 'shape' => 'ServiceException', ], [ 'shape' => 'TooManyRequestsException', ], [ 'shape' => 'AWSServiceAccessNotEnabledException', ], [ 'shape' => 'TemplatesNotAvailableInRegionException', ], [ 'shape' => 'NoAvailableOrganizationException', ], ], ], 'GetRequestedServiceQuotaChange' => [ 'name' => 'GetRequestedServiceQuotaChange', 'http' => [ 'method' => 'POST', 'requestUri' => '/', ], 'input' => [ 'shape' => 'GetRequestedServiceQuotaChangeRequest', ], 'output' => [ 'shape' => 'GetRequestedServiceQuotaChangeResponse', ], 'errors' => [ [ 'shape' => 'AccessDeniedException', ], [ 'shape' => 'NoSuchResourceException', ], [ 'shape' => 'IllegalArgumentException', ], [ 'shape' => 'ServiceException', ], [ 'shape' => 'TooManyRequestsException', ], ], ], 'GetServiceQuota' => [ 'name' => 'GetServiceQuota', 'http' => [ 'method' => 'POST', 'requestUri' => '/', ], 'input' => [ 'shape' => 'GetServiceQuotaRequest', ], 'output' => [ 'shape' => 'GetServiceQuotaResponse', ], 'errors' => [ [ 'shape' => 'AccessDeniedException', ], [ 'shape' => 'NoSuchResourceException', ], [ 'shape' => 'IllegalArgumentException', ], [ 'shape' => 'ServiceException', ], [ 'shape' => 'TooManyRequestsException', ], ], ], 'GetServiceQuotaIncreaseRequestFromTemplate' => [ 'name' => 'GetServiceQuotaIncreaseRequestFromTemplate', 'http' => [ 'method' => 'POST', 'requestUri' => '/', ], 'input' => [ 'shape' => 'GetServiceQuotaIncreaseRequestFromTemplateRequest', ], 'output' => [ 'shape' => 'GetServiceQuotaIncreaseRequestFromTemplateResponse', ], 'errors' => [ [ 'shape' => 'AccessDeniedException', ], [ 'shape' => 'DependencyAccessDeniedException', ], [ 'shape' => 'ServiceException', ], [ 'shape' => 'TooManyRequestsException', ], [ 'shape' => 'NoSuchResourceException', ], [ 'shape' => 'IllegalArgumentException', ], [ 'shape' => 'AWSServiceAccessNotEnabledException', ], [ 'shape' => 'TemplatesNotAvailableInRegionException', ], [ 'shape' => 'NoAvailableOrganizationException', ], ], ], 'ListAWSDefaultServiceQuotas' => [ 'name' => 'ListAWSDefaultServiceQuotas', 'http' => [ 'method' => 'POST', 'requestUri' => '/', ], 'input' => [ 'shape' => 'ListAWSDefaultServiceQuotasRequest', ], 'output' => [ 'shape' => 'ListAWSDefaultServiceQuotasResponse', ], 'errors' => [ [ 'shape' => 'AccessDeniedException', ], [ 'shape' => 'NoSuchResourceException', ], [ 'shape' => 'IllegalArgumentException', ], [ 'shape' => 'InvalidPaginationTokenException', ], [ 'shape' => 'ServiceException', ], [ 'shape' => 'TooManyRequestsException', ], ], ], 'ListRequestedServiceQuotaChangeHistory' => [ 'name' => 'ListRequestedServiceQuotaChangeHistory', 'http' => [ 'method' => 'POST', 'requestUri' => '/', ], 'input' => [ 'shape' => 'ListRequestedServiceQuotaChangeHistoryRequest', ], 'output' => [ 'shape' => 'ListRequestedServiceQuotaChangeHistoryResponse', ], 'errors' => [ [ 'shape' => 'AccessDeniedException', ], [ 'shape' => 'NoSuchResourceException', ], [ 'shape' => 'IllegalArgumentException', ], [ 'shape' => 'InvalidPaginationTokenException', ], [ 'shape' => 'ServiceException', ], [ 'shape' => 'TooManyRequestsException', ], ], ], 'ListRequestedServiceQuotaChangeHistoryByQuota' => [ 'name' => 'ListRequestedServiceQuotaChangeHistoryByQuota', 'http' => [ 'method' => 'POST', 'requestUri' => '/', ], 'input' => [ 'shape' => 'ListRequestedServiceQuotaChangeHistoryByQuotaRequest', ], 'output' => [ 'shape' => 'ListRequestedServiceQuotaChangeHistoryByQuotaResponse', ], 'errors' => [ [ 'shape' => 'AccessDeniedException', ], [ 'shape' => 'NoSuchResourceException', ], [ 'shape' => 'IllegalArgumentException', ], [ 'shape' => 'InvalidPaginationTokenException', ], [ 'shape' => 'ServiceException', ], [ 'shape' => 'TooManyRequestsException', ], ], ], 'ListServiceQuotaIncreaseRequestsInTemplate' => [ 'name' => 'ListServiceQuotaIncreaseRequestsInTemplate', 'http' => [ 'method' => 'POST', 'requestUri' => '/', ], 'input' => [ 'shape' => 'ListServiceQuotaIncreaseRequestsInTemplateRequest', ], 'output' => [ 'shape' => 'ListServiceQuotaIncreaseRequestsInTemplateResponse', ], 'errors' => [ [ 'shape' => 'AccessDeniedException', ], [ 'shape' => 'DependencyAccessDeniedException', ], [ 'shape' => 'ServiceException', ], [ 'shape' => 'TooManyRequestsException', ], [ 'shape' => 'IllegalArgumentException', ], [ 'shape' => 'AWSServiceAccessNotEnabledException', ], [ 'shape' => 'TemplatesNotAvailableInRegionException', ], [ 'shape' => 'NoAvailableOrganizationException', ], ], ], 'ListServiceQuotas' => [ 'name' => 'ListServiceQuotas', 'http' => [ 'method' => 'POST', 'requestUri' => '/', ], 'input' => [ 'shape' => 'ListServiceQuotasRequest', ], 'output' => [ 'shape' => 'ListServiceQuotasResponse', ], 'errors' => [ [ 'shape' => 'AccessDeniedException', ], [ 'shape' => 'NoSuchResourceException', ], [ 'shape' => 'IllegalArgumentException', ], [ 'shape' => 'InvalidPaginationTokenException', ], [ 'shape' => 'ServiceException', ], [ 'shape' => 'TooManyRequestsException', ], ], ], 'ListServices' => [ 'name' => 'ListServices', 'http' => [ 'method' => 'POST', 'requestUri' => '/', ], 'input' => [ 'shape' => 'ListServicesRequest', ], 'output' => [ 'shape' => 'ListServicesResponse', ], 'errors' => [ [ 'shape' => 'AccessDeniedException', ], [ 'shape' => 'IllegalArgumentException', ], [ 'shape' => 'InvalidPaginationTokenException', ], [ 'shape' => 'ServiceException', ], [ 'shape' => 'TooManyRequestsException', ], ], ], 'ListTagsForResource' => [ 'name' => 'ListTagsForResource', 'http' => [ 'method' => 'POST', 'requestUri' => '/', ], 'input' => [ 'shape' => 'ListTagsForResourceRequest', ], 'output' => [ 'shape' => 'ListTagsForResourceResponse', ], 'errors' => [ [ 'shape' => 'TooManyRequestsException', ], [ 'shape' => 'NoSuchResourceException', ], [ 'shape' => 'IllegalArgumentException', ], [ 'shape' => 'AccessDeniedException', ], [ 'shape' => 'ServiceException', ], ], ], 'PutServiceQuotaIncreaseRequestIntoTemplate' => [ 'name' => 'PutServiceQuotaIncreaseRequestIntoTemplate', 'http' => [ 'method' => 'POST', 'requestUri' => '/', ], 'input' => [ 'shape' => 'PutServiceQuotaIncreaseRequestIntoTemplateRequest', ], 'output' => [ 'shape' => 'PutServiceQuotaIncreaseRequestIntoTemplateResponse', ], 'errors' => [ [ 'shape' => 'AccessDeniedException', ], [ 'shape' => 'DependencyAccessDeniedException', ], [ 'shape' => 'ServiceException', ], [ 'shape' => 'TooManyRequestsException', ], [ 'shape' => 'IllegalArgumentException', ], [ 'shape' => 'QuotaExceededException', ], [ 'shape' => 'NoSuchResourceException', ], [ 'shape' => 'AWSServiceAccessNotEnabledException', ], [ 'shape' => 'TemplatesNotAvailableInRegionException', ], [ 'shape' => 'NoAvailableOrganizationException', ], ], ], 'RequestServiceQuotaIncrease' => [ 'name' => 'RequestServiceQuotaIncrease', 'http' => [ 'method' => 'POST', 'requestUri' => '/', ], 'input' => [ 'shape' => 'RequestServiceQuotaIncreaseRequest', ], 'output' => [ 'shape' => 'RequestServiceQuotaIncreaseResponse', ], 'errors' => [ [ 'shape' => 'DependencyAccessDeniedException', ], [ 'shape' => 'QuotaExceededException', ], [ 'shape' => 'ResourceAlreadyExistsException', ], [ 'shape' => 'AccessDeniedException', ], [ 'shape' => 'NoSuchResourceException', ], [ 'shape' => 'IllegalArgumentException', ], [ 'shape' => 'InvalidResourceStateException', ], [ 'shape' => 'ServiceException', ], [ 'shape' => 'TooManyRequestsException', ], ], ], 'TagResource' => [ 'name' => 'TagResource', 'http' => [ 'method' => 'POST', 'requestUri' => '/', ], 'input' => [ 'shape' => 'TagResourceRequest', ], 'output' => [ 'shape' => 'TagResourceResponse', ], 'errors' => [ [ 'shape' => 'TooManyRequestsException', ], [ 'shape' => 'NoSuchResourceException', ], [ 'shape' => 'TooManyTagsException', ], [ 'shape' => 'TagPolicyViolationException', ], [ 'shape' => 'IllegalArgumentException', ], [ 'shape' => 'AccessDeniedException', ], [ 'shape' => 'ServiceException', ], ], ], 'UntagResource' => [ 'name' => 'UntagResource', 'http' => [ 'method' => 'POST', 'requestUri' => '/', ], 'input' => [ 'shape' => 'UntagResourceRequest', ], 'output' => [ 'shape' => 'UntagResourceResponse', ], 'errors' => [ [ 'shape' => 'TooManyRequestsException', ], [ 'shape' => 'NoSuchResourceException', ], [ 'shape' => 'IllegalArgumentException', ], [ 'shape' => 'AccessDeniedException', ], [ 'shape' => 'ServiceException', ], ], ], ], 'shapes' => [ 'AWSServiceAccessNotEnabledException' => [ 'type' => 'structure', 'members' => [ 'Message' => [ 'shape' => 'ExceptionMessage', ], ], 'exception' => true, ], 'AccessDeniedException' => [ 'type' => 'structure', 'members' => [ 'Message' => [ 'shape' => 'ExceptionMessage', ], ], 'exception' => true, ], 'AmazonResourceName' => [ 'type' => 'string', 'max' => 1011, 'min' => 1, 'pattern' => 'arn:aws(-[\\w]+)*:*:.+:[0-9]{12}:.+', ], 'AppliedLevelEnum' => [ 'type' => 'string', 'enum' => [ 'ACCOUNT', 'RESOURCE', 'ALL', ], ], 'AssociateServiceQuotaTemplateRequest' => [ 'type' => 'structure', 'members' => [], ], 'AssociateServiceQuotaTemplateResponse' => [ 'type' => 'structure', 'members' => [], ], 'AwsRegion' => [ 'type' => 'string', 'max' => 64, 'min' => 1, 'pattern' => '[a-zA-Z][a-zA-Z0-9-]{1,128}', ], 'CustomerServiceEngagementId' => [ 'type' => 'string', ], 'DateTime' => [ 'type' => 'timestamp', ], 'DeleteServiceQuotaIncreaseRequestFromTemplateRequest' => [ 'type' => 'structure', 'required' => [ 'ServiceCode', 'QuotaCode', 'AwsRegion', ], 'members' => [ 'ServiceCode' => [ 'shape' => 'ServiceCode', ], 'QuotaCode' => [ 'shape' => 'QuotaCode', ], 'AwsRegion' => [ 'shape' => 'AwsRegion', ], ], ], 'DeleteServiceQuotaIncreaseRequestFromTemplateResponse' => [ 'type' => 'structure', 'members' => [], ], 'DependencyAccessDeniedException' => [ 'type' => 'structure', 'members' => [ 'Message' => [ 'shape' => 'ExceptionMessage', ], ], 'exception' => true, ], 'DisassociateServiceQuotaTemplateRequest' => [ 'type' => 'structure', 'members' => [], ], 'DisassociateServiceQuotaTemplateResponse' => [ 'type' => 'structure', 'members' => [], ], 'ErrorCode' => [ 'type' => 'string', 'enum' => [ 'DEPENDENCY_ACCESS_DENIED_ERROR', 'DEPENDENCY_THROTTLING_ERROR', 'DEPENDENCY_SERVICE_ERROR', 'SERVICE_QUOTA_NOT_AVAILABLE_ERROR', ], ], 'ErrorMessage' => [ 'type' => 'string', ], 'ErrorReason' => [ 'type' => 'structure', 'members' => [ 'ErrorCode' => [ 'shape' => 'ErrorCode', ], 'ErrorMessage' => [ 'shape' => 'ErrorMessage', ], ], ], 'ExceptionMessage' => [ 'type' => 'string', ], 'GetAWSDefaultServiceQuotaRequest' => [ 'type' => 'structure', 'required' => [ 'ServiceCode', 'QuotaCode', ], 'members' => [ 'ServiceCode' => [ 'shape' => 'ServiceCode', ], 'QuotaCode' => [ 'shape' => 'QuotaCode', ], ], ], 'GetAWSDefaultServiceQuotaResponse' => [ 'type' => 'structure', 'members' => [ 'Quota' => [ 'shape' => 'ServiceQuota', ], ], ], 'GetAssociationForServiceQuotaTemplateRequest' => [ 'type' => 'structure', 'members' => [], ], 'GetAssociationForServiceQuotaTemplateResponse' => [ 'type' => 'structure', 'members' => [ 'ServiceQuotaTemplateAssociationStatus' => [ 'shape' => 'ServiceQuotaTemplateAssociationStatus', ], ], ], 'GetRequestedServiceQuotaChangeRequest' => [ 'type' => 'structure', 'required' => [ 'RequestId', ], 'members' => [ 'RequestId' => [ 'shape' => 'RequestId', ], ], ], 'GetRequestedServiceQuotaChangeResponse' => [ 'type' => 'structure', 'members' => [ 'RequestedQuota' => [ 'shape' => 'RequestedServiceQuotaChange', ], ], ], 'GetServiceQuotaIncreaseRequestFromTemplateRequest' => [ 'type' => 'structure', 'required' => [ 'ServiceCode', 'QuotaCode', 'AwsRegion', ], 'members' => [ 'ServiceCode' => [ 'shape' => 'ServiceCode', ], 'QuotaCode' => [ 'shape' => 'QuotaCode', ], 'AwsRegion' => [ 'shape' => 'AwsRegion', ], ], ], 'GetServiceQuotaIncreaseRequestFromTemplateResponse' => [ 'type' => 'structure', 'members' => [ 'ServiceQuotaIncreaseRequestInTemplate' => [ 'shape' => 'ServiceQuotaIncreaseRequestInTemplate', ], ], ], 'GetServiceQuotaRequest' => [ 'type' => 'structure', 'required' => [ 'ServiceCode', 'QuotaCode', ], 'members' => [ 'ServiceCode' => [ 'shape' => 'ServiceCode', ], 'QuotaCode' => [ 'shape' => 'QuotaCode', ], 'ContextId' => [ 'shape' => 'QuotaContextId', ], ], ], 'GetServiceQuotaResponse' => [ 'type' => 'structure', 'members' => [ 'Quota' => [ 'shape' => 'ServiceQuota', ], ], ], 'GlobalQuota' => [ 'type' => 'boolean', ], 'IllegalArgumentException' => [ 'type' => 'structure', 'members' => [ 'Message' => [ 'shape' => 'ExceptionMessage', ], ], 'exception' => true, ], 'InputTagKeys' => [ 'type' => 'list', 'member' => [ 'shape' => 'TagKey', ], ], 'InputTags' => [ 'type' => 'list', 'member' => [ 'shape' => 'Tag', ], 'min' => 1, ], 'InvalidPaginationTokenException' => [ 'type' => 'structure', 'members' => [ 'Message' => [ 'shape' => 'ExceptionMessage', ], ], 'exception' => true, ], 'InvalidResourceStateException' => [ 'type' => 'structure', 'members' => [ 'Message' => [ 'shape' => 'ExceptionMessage', ], ], 'exception' => true, ], 'ListAWSDefaultServiceQuotasRequest' => [ 'type' => 'structure', 'required' => [ 'ServiceCode', ], 'members' => [ 'ServiceCode' => [ 'shape' => 'ServiceCode', ], 'NextToken' => [ 'shape' => 'NextToken', ], 'MaxResults' => [ 'shape' => 'MaxResults', ], ], ], 'ListAWSDefaultServiceQuotasResponse' => [ 'type' => 'structure', 'members' => [ 'NextToken' => [ 'shape' => 'NextToken', ], 'Quotas' => [ 'shape' => 'ServiceQuotaListDefinition', ], ], ], 'ListRequestedServiceQuotaChangeHistoryByQuotaRequest' => [ 'type' => 'structure', 'required' => [ 'ServiceCode', 'QuotaCode', ], 'members' => [ 'ServiceCode' => [ 'shape' => 'ServiceCode', ], 'QuotaCode' => [ 'shape' => 'QuotaCode', ], 'Status' => [ 'shape' => 'RequestStatus', ], 'NextToken' => [ 'shape' => 'NextToken', ], 'MaxResults' => [ 'shape' => 'MaxResults', ], 'QuotaRequestedAtLevel' => [ 'shape' => 'AppliedLevelEnum', ], ], ], 'ListRequestedServiceQuotaChangeHistoryByQuotaResponse' => [ 'type' => 'structure', 'members' => [ 'NextToken' => [ 'shape' => 'NextToken', ], 'RequestedQuotas' => [ 'shape' => 'RequestedServiceQuotaChangeHistoryListDefinition', ], ], ], 'ListRequestedServiceQuotaChangeHistoryRequest' => [ 'type' => 'structure', 'members' => [ 'ServiceCode' => [ 'shape' => 'ServiceCode', ], 'Status' => [ 'shape' => 'RequestStatus', ], 'NextToken' => [ 'shape' => 'NextToken', ], 'MaxResults' => [ 'shape' => 'MaxResults', ], 'QuotaRequestedAtLevel' => [ 'shape' => 'AppliedLevelEnum', ], ], ], 'ListRequestedServiceQuotaChangeHistoryResponse' => [ 'type' => 'structure', 'members' => [ 'NextToken' => [ 'shape' => 'NextToken', ], 'RequestedQuotas' => [ 'shape' => 'RequestedServiceQuotaChangeHistoryListDefinition', ], ], ], 'ListServiceQuotaIncreaseRequestsInTemplateRequest' => [ 'type' => 'structure', 'members' => [ 'ServiceCode' => [ 'shape' => 'ServiceCode', ], 'AwsRegion' => [ 'shape' => 'AwsRegion', ], 'NextToken' => [ 'shape' => 'NextToken', ], 'MaxResults' => [ 'shape' => 'MaxResults', ], ], ], 'ListServiceQuotaIncreaseRequestsInTemplateResponse' => [ 'type' => 'structure', 'members' => [ 'ServiceQuotaIncreaseRequestInTemplateList' => [ 'shape' => 'ServiceQuotaIncreaseRequestInTemplateList', ], 'NextToken' => [ 'shape' => 'NextToken', ], ], ], 'ListServiceQuotasRequest' => [ 'type' => 'structure', 'required' => [ 'ServiceCode', ], 'members' => [ 'ServiceCode' => [ 'shape' => 'ServiceCode', ], 'NextToken' => [ 'shape' => 'NextToken', ], 'MaxResults' => [ 'shape' => 'MaxResults', ], 'QuotaCode' => [ 'shape' => 'QuotaCode', ], 'QuotaAppliedAtLevel' => [ 'shape' => 'AppliedLevelEnum', ], ], ], 'ListServiceQuotasResponse' => [ 'type' => 'structure', 'members' => [ 'NextToken' => [ 'shape' => 'NextToken', ], 'Quotas' => [ 'shape' => 'ServiceQuotaListDefinition', ], ], ], 'ListServicesRequest' => [ 'type' => 'structure', 'members' => [ 'NextToken' => [ 'shape' => 'NextToken', ], 'MaxResults' => [ 'shape' => 'MaxResults', ], ], ], 'ListServicesResponse' => [ 'type' => 'structure', 'members' => [ 'NextToken' => [ 'shape' => 'NextToken', ], 'Services' => [ 'shape' => 'ServiceInfoListDefinition', ], ], ], 'ListTagsForResourceRequest' => [ 'type' => 'structure', 'required' => [ 'ResourceARN', ], 'members' => [ 'ResourceARN' => [ 'shape' => 'AmazonResourceName', ], ], ], 'ListTagsForResourceResponse' => [ 'type' => 'structure', 'members' => [ 'Tags' => [ 'shape' => 'OutputTags', ], ], ], 'MaxResults' => [ 'type' => 'integer', 'max' => 100, 'min' => 1, ], 'MetricDimensionName' => [ 'type' => 'string', ], 'MetricDimensionValue' => [ 'type' => 'string', ], 'MetricDimensionsMapDefinition' => [ 'type' => 'map', 'key' => [ 'shape' => 'MetricDimensionName', ], 'value' => [ 'shape' => 'MetricDimensionValue', ], 'max' => 10, ], 'MetricInfo' => [ 'type' => 'structure', 'members' => [ 'MetricNamespace' => [ 'shape' => 'QuotaMetricNamespace', ], 'MetricName' => [ 'shape' => 'QuotaMetricName', ], 'MetricDimensions' => [ 'shape' => 'MetricDimensionsMapDefinition', ], 'MetricStatisticRecommendation' => [ 'shape' => 'Statistic', ], ], ], 'NextToken' => [ 'type' => 'string', 'max' => 2048, 'pattern' => '^[a-zA-Z0-9/+]*={0,2}$', ], 'NoAvailableOrganizationException' => [ 'type' => 'structure', 'members' => [ 'Message' => [ 'shape' => 'ExceptionMessage', ], ], 'exception' => true, ], 'NoSuchResourceException' => [ 'type' => 'structure', 'members' => [ 'Message' => [ 'shape' => 'ExceptionMessage', ], ], 'exception' => true, ], 'OrganizationNotInAllFeaturesModeException' => [ 'type' => 'structure', 'members' => [ 'Message' => [ 'shape' => 'ExceptionMessage', ], ], 'exception' => true, ], 'OutputTags' => [ 'type' => 'list', 'member' => [ 'shape' => 'Tag', ], 'max' => 200, ], 'PeriodUnit' => [ 'type' => 'string', 'enum' => [ 'MICROSECOND', 'MILLISECOND', 'SECOND', 'MINUTE', 'HOUR', 'DAY', 'WEEK', ], ], 'PeriodValue' => [ 'type' => 'integer', ], 'PutServiceQuotaIncreaseRequestIntoTemplateRequest' => [ 'type' => 'structure', 'required' => [ 'QuotaCode', 'ServiceCode', 'AwsRegion', 'DesiredValue', ], 'members' => [ 'QuotaCode' => [ 'shape' => 'QuotaCode', ], 'ServiceCode' => [ 'shape' => 'ServiceCode', ], 'AwsRegion' => [ 'shape' => 'AwsRegion', ], 'DesiredValue' => [ 'shape' => 'QuotaValue', ], ], ], 'PutServiceQuotaIncreaseRequestIntoTemplateResponse' => [ 'type' => 'structure', 'members' => [ 'ServiceQuotaIncreaseRequestInTemplate' => [ 'shape' => 'ServiceQuotaIncreaseRequestInTemplate', ], ], ], 'QuotaAdjustable' => [ 'type' => 'boolean', ], 'QuotaArn' => [ 'type' => 'string', ], 'QuotaCode' => [ 'type' => 'string', 'max' => 128, 'min' => 1, 'pattern' => '[a-zA-Z][a-zA-Z0-9-]{1,128}', ], 'QuotaContextId' => [ 'type' => 'string', ], 'QuotaContextInfo' => [ 'type' => 'structure', 'members' => [ 'ContextScope' => [ 'shape' => 'QuotaContextScope', ], 'ContextScopeType' => [ 'shape' => 'QuotaContextScopeType', ], 'ContextId' => [ 'shape' => 'QuotaContextId', ], ], ], 'QuotaContextScope' => [ 'type' => 'string', 'enum' => [ 'RESOURCE', 'ACCOUNT', ], ], 'QuotaContextScopeType' => [ 'type' => 'string', ], 'QuotaDescription' => [ 'type' => 'string', 'max' => 350, 'min' => 0, 'pattern' => '^.{0,350}$', ], 'QuotaExceededException' => [ 'type' => 'structure', 'members' => [ 'Message' => [ 'shape' => 'ExceptionMessage', ], ], 'exception' => true, ], 'QuotaMetricName' => [ 'type' => 'string', ], 'QuotaMetricNamespace' => [ 'type' => 'string', ], 'QuotaName' => [ 'type' => 'string', ], 'QuotaPeriod' => [ 'type' => 'structure', 'members' => [ 'PeriodValue' => [ 'shape' => 'PeriodValue', ], 'PeriodUnit' => [ 'shape' => 'PeriodUnit', ], ], ], 'QuotaUnit' => [ 'type' => 'string', ], 'QuotaValue' => [ 'type' => 'double', 'max' => 10000000000, 'min' => 0, ], 'RequestId' => [ 'type' => 'string', 'max' => 128, 'min' => 1, 'pattern' => '[0-9a-zA-Z][a-zA-Z0-9-]{1,128}', ], 'RequestServiceQuotaIncreaseRequest' => [ 'type' => 'structure', 'required' => [ 'ServiceCode', 'QuotaCode', 'DesiredValue', ], 'members' => [ 'ServiceCode' => [ 'shape' => 'ServiceCode', ], 'QuotaCode' => [ 'shape' => 'QuotaCode', ], 'DesiredValue' => [ 'shape' => 'QuotaValue', ], 'ContextId' => [ 'shape' => 'QuotaContextId', ], 'SupportCaseAllowed' => [ 'shape' => 'SupportCaseAllowed', ], ], ], 'RequestServiceQuotaIncreaseResponse' => [ 'type' => 'structure', 'members' => [ 'RequestedQuota' => [ 'shape' => 'RequestedServiceQuotaChange', ], ], ], 'RequestStatus' => [ 'type' => 'string', 'enum' => [ 'PENDING', 'CASE_OPENED', 'APPROVED', 'DENIED', 'CASE_CLOSED', 'NOT_APPROVED', 'INVALID_REQUEST', ], ], 'RequestedServiceQuotaChange' => [ 'type' => 'structure', 'members' => [ 'Id' => [ 'shape' => 'RequestId', ], 'CaseId' => [ 'shape' => 'CustomerServiceEngagementId', ], 'ServiceCode' => [ 'shape' => 'ServiceCode', ], 'ServiceName' => [ 'shape' => 'ServiceName', ], 'QuotaCode' => [ 'shape' => 'QuotaCode', ], 'QuotaName' => [ 'shape' => 'QuotaName', ], 'DesiredValue' => [ 'shape' => 'QuotaValue', ], 'Status' => [ 'shape' => 'RequestStatus', ], 'Created' => [ 'shape' => 'DateTime', ], 'LastUpdated' => [ 'shape' => 'DateTime', ], 'Requester' => [ 'shape' => 'Requester', ], 'QuotaArn' => [ 'shape' => 'QuotaArn', ], 'GlobalQuota' => [ 'shape' => 'GlobalQuota', ], 'Unit' => [ 'shape' => 'QuotaUnit', ], 'QuotaRequestedAtLevel' => [ 'shape' => 'AppliedLevelEnum', ], 'QuotaContext' => [ 'shape' => 'QuotaContextInfo', ], ], ], 'RequestedServiceQuotaChangeHistoryListDefinition' => [ 'type' => 'list', 'member' => [ 'shape' => 'RequestedServiceQuotaChange', ], ], 'Requester' => [ 'type' => 'string', ], 'ResourceAlreadyExistsException' => [ 'type' => 'structure', 'members' => [ 'Message' => [ 'shape' => 'ExceptionMessage', ], ], 'exception' => true, ], 'ServiceCode' => [ 'type' => 'string', 'max' => 63, 'min' => 1, 'pattern' => '[a-zA-Z][a-zA-Z0-9-]{1,63}', ], 'ServiceException' => [ 'type' => 'structure', 'members' => [ 'Message' => [ 'shape' => 'ExceptionMessage', ], ], 'exception' => true, 'fault' => true, ], 'ServiceInfo' => [ 'type' => 'structure', 'members' => [ 'ServiceCode' => [ 'shape' => 'ServiceCode', ], 'ServiceName' => [ 'shape' => 'ServiceName', ], ], ], 'ServiceInfoListDefinition' => [ 'type' => 'list', 'member' => [ 'shape' => 'ServiceInfo', ], ], 'ServiceName' => [ 'type' => 'string', ], 'ServiceQuota' => [ 'type' => 'structure', 'members' => [ 'ServiceCode' => [ 'shape' => 'ServiceCode', ], 'ServiceName' => [ 'shape' => 'ServiceName', ], 'QuotaArn' => [ 'shape' => 'QuotaArn', ], 'QuotaCode' => [ 'shape' => 'QuotaCode', ], 'QuotaName' => [ 'shape' => 'QuotaName', ], 'Value' => [ 'shape' => 'QuotaValue', ], 'Unit' => [ 'shape' => 'QuotaUnit', ], 'Adjustable' => [ 'shape' => 'QuotaAdjustable', ], 'GlobalQuota' => [ 'shape' => 'GlobalQuota', ], 'UsageMetric' => [ 'shape' => 'MetricInfo', ], 'Period' => [ 'shape' => 'QuotaPeriod', ], 'ErrorReason' => [ 'shape' => 'ErrorReason', ], 'QuotaAppliedAtLevel' => [ 'shape' => 'AppliedLevelEnum', ], 'QuotaContext' => [ 'shape' => 'QuotaContextInfo', ], 'Description' => [ 'shape' => 'QuotaDescription', ], ], ], 'ServiceQuotaIncreaseRequestInTemplate' => [ 'type' => 'structure', 'members' => [ 'ServiceCode' => [ 'shape' => 'ServiceCode', ], 'ServiceName' => [ 'shape' => 'ServiceName', ], 'QuotaCode' => [ 'shape' => 'QuotaCode', ], 'QuotaName' => [ 'shape' => 'QuotaName', ], 'DesiredValue' => [ 'shape' => 'QuotaValue', ], 'AwsRegion' => [ 'shape' => 'AwsRegion', ], 'Unit' => [ 'shape' => 'QuotaUnit', ], 'GlobalQuota' => [ 'shape' => 'GlobalQuota', ], ], ], 'ServiceQuotaIncreaseRequestInTemplateList' => [ 'type' => 'list', 'member' => [ 'shape' => 'ServiceQuotaIncreaseRequestInTemplate', ], ], 'ServiceQuotaListDefinition' => [ 'type' => 'list', 'member' => [ 'shape' => 'ServiceQuota', ], ], 'ServiceQuotaTemplateAssociationStatus' => [ 'type' => 'string', 'enum' => [ 'ASSOCIATED', 'DISASSOCIATED', ], ], 'ServiceQuotaTemplateNotInUseException' => [ 'type' => 'structure', 'members' => [ 'Message' => [ 'shape' => 'ExceptionMessage', ], ], 'exception' => true, ], 'Statistic' => [ 'type' => 'string', 'max' => 256, 'min' => 1, 'pattern' => '(Sum|Maximum)', ], 'SupportCaseAllowed' => [ 'type' => 'boolean', ], 'Tag' => [ 'type' => 'structure', 'required' => [ 'Key', 'Value', ], 'members' => [ 'Key' => [ 'shape' => 'TagKey', ], 'Value' => [ 'shape' => 'TagValue', ], ], ], 'TagKey' => [ 'type' => 'string', 'max' => 128, 'min' => 1, 'pattern' => '^([\\p{L}\\p{Z}\\p{N}_.:/=+\\-@]*)$', ], 'TagPolicyViolationException' => [ 'type' => 'structure', 'members' => [ 'Message' => [ 'shape' => 'ExceptionMessage', ], ], 'exception' => true, ], 'TagResourceRequest' => [ 'type' => 'structure', 'required' => [ 'ResourceARN', 'Tags', ], 'members' => [ 'ResourceARN' => [ 'shape' => 'AmazonResourceName', ], 'Tags' => [ 'shape' => 'InputTags', ], ], ], 'TagResourceResponse' => [ 'type' => 'structure', 'members' => [], ], 'TagValue' => [ 'type' => 'string', 'max' => 256, 'min' => 0, 'pattern' => '^([\\p{L}\\p{Z}\\p{N}_.:/=+\\-@]*)$', ], 'TemplatesNotAvailableInRegionException' => [ 'type' => 'structure', 'members' => [ 'Message' => [ 'shape' => 'ExceptionMessage', ], ], 'exception' => true, ], 'TooManyRequestsException' => [ 'type' => 'structure', 'members' => [ 'Message' => [ 'shape' => 'ExceptionMessage', ], ], 'exception' => true, ], 'TooManyTagsException' => [ 'type' => 'structure', 'members' => [ 'Message' => [ 'shape' => 'ExceptionMessage', ], ], 'exception' => true, ], 'UntagResourceRequest' => [ 'type' => 'structure', 'required' => [ 'ResourceARN', 'TagKeys', ], 'members' => [ 'ResourceARN' => [ 'shape' => 'AmazonResourceName', ], 'TagKeys' => [ 'shape' => 'InputTagKeys', ], ], ], 'UntagResourceResponse' => [ 'type' => 'structure', 'members' => [], ], ],];
