<?php

namespace App\Http\Controllers\API\Route53;

use App\Http\Controllers\Controller;
use Aws\Route53\Route53Client;
use Aws\Exception\AwsException;
use Illuminate\Http\Request;

class Route53Controller extends Controller
{
    public function createSubdomain(Request $request)
    {
        $client = new Route53Client([
            'version' => 'latest',
            'region' => env('AWS_DEFAULT_REGION'), // Replace with your AWS region
            'credentials' => [
                'key' => env('AWS_ACCESS_KEY_ID'), // Replace with your AWS access key ID
                'secret' => env('AWS_SECRET_ACCESS_KEY') // Replace with your AWS secret access key
            ]
        ]);

        $domainName = 'figure4life.com'; // Replace with your domain name
        $subdomainName = $request->subdomain; // Replace with your desired subdomain name
        $hostedZoneId = env('AWS_hosted_zone_id'); // Replace with your Route 53 hosted zone ID

        try {
            $result = $client->changeResourceRecordSets([
                'HostedZoneId' => $hostedZoneId,
                'ChangeBatch' => [
                    'Changes' => [
                        [
                            'Action' => 'UPSERT',
                            'ResourceRecordSet' => [
                                'Name' => $subdomainName . '.' . $domainName,
                                'Type' => 'CNAME',
                                'TTL' => 300,
                                'ResourceRecords' => [
                                    [
                                        'Value' =>$domainName // Replace with the target of the subdomain
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ]);

            return response()->json($result);
        } catch (AwsException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}

