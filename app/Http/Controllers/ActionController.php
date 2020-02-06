<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class ActionController extends Controller
{

    public function login(Request $request)
    {
        $formData = $request->input();
        //dd($formData);
        $loginData = [
            'username' => $formData['username'],
            'password' => $formData['password'],
        ];
        $domain = UtilityController::getDomain($formData['jiraUrl']);
        if (isset($formData['remember']) && $formData['remember'] == 1) {
            Cookie::queue('jiraUrl', $formData['jiraUrl'], env('COOKIE_LIFETIME'));
            Cookie::queue('username', $formData['username'], env('COOKIE_LIFETIME'));
            Cookie::queue('password', $formData['password'], env('COOKIE_LIFETIME'));
        }

        $authToken = base64_encode($loginData['username'] . ':' . $loginData['password']);
        $request->session()->put('authToken', $authToken);
        $request->session()->put('jiraUrl', $domain);

        // check username and password validation
        $apiEndPoint = $domain . '/rest/api/latest/search';
        $jql = 'sprint in openSprints () AND assignee = ' . $loginData['username'] . '  AND status != Closed ';
        $postData = [
            'jql' => $jql,
            "maxResults" => 1,
        ];
        $result = json_decode(UtilityController::getDataCurlPost($apiEndPoint, $postData, $request), true);
        //dd($result);
        if ($result) {
            $request->session()->put('jql', $jql);
            $request->session()->put('username', $loginData['username']);
            return redirect('/');
        } else {
            $request->session()->forget('authToken');
            $request->session()->forget('jiraUrl');
            return redirect('/login')->with('danger', 'Incorrect login credentials, please try again.');
        }
    }

    public function logout(Request $request)
    {
        $request->session()->forget('authToken');
        $request->session()->forget('username');
        return redirect('login')->with('success', 'Logout has been successfully!');
    }

    public function searchIssue(Request $request)
    {
        $formData = $request->input();
        $apiEndPoint = $request->session()->get('jiraUrl') . '/rest/api/latest/search';
        $postData = [
            'jql' => $formData['jql'],
            "maxResults" => 1,
        ];
        $result = json_decode(UtilityController::getDataCurlPost($apiEndPoint, $postData, $request), true);
        if (!isset($result['errors'])) {
            $request->session()->put('jql', $formData['jql']);
        }
        return redirect()->back();
    }

    public function logWork(Request $request)
    {
        $formData = $request->input();
        $logData = UtilityController::helpermultiArrayToSingleArray($formData['logData']);
        $timeSpent = UtilityController::minutes($logData['timeSpent']);
        if ($timeSpent <= 0) {
            $timeSpent = '1m';
        } else {
            $timeSpent = $timeSpent . 'm';
        }

        // add self comment
        if (isset($logData['comment']) && $logData['comment'] != '') {
            $apiEndPoint = $request->session()->get('jiraUrl') . '/rest/api/latest/issue/' . $logData['jiraIssue'] . '/comment';
            $postDataComment = [
                'body' => $logData['comment']
            ];
            UtilityController::getDataCurlPost($apiEndPoint, $postDataComment, $request);
        }
        // add comment on main issue
        if (isset($logData['commentMain']) && isset($logData['commentMainIssueId']) && $logData['commentMain'] != '' && $logData['commentMainIssueId'] != '') {
            $apiEndPoint = $request->session()->get('jiraUrl') . '/rest/api/latest/issue/' . $logData['commentMainIssueId'] . '/comment';
            $postDataComment = [
                'body' => $logData['commentMain']
            ];
            UtilityController::getDataCurlPost($apiEndPoint, $postDataComment, $request);
        }

        // work log add
        $postData = [
            'comment' => $logData['description'],
            'timeSpent' => $timeSpent
        ];
        $apiEndPoint = $request->session()->get('jiraUrl') . '/rest/api/latest/issue/' . $logData['jiraIssue'] . '/worklog';
        $result = json_decode(UtilityController::getDataCurlPost($apiEndPoint, $postData, $request), true);
        if (isset($result['errors'])) {
            $response = [
                'status' => false,
                'error' => $result
            ];
        } else {
            $response = [
                'status' => true,
                'data' => $result
            ];
        }
        return response()->json($response);
    }

    public function issueDetails(Request $request)
    {
        $formData = UtilityController::helpermultiArrayToSingleArray($request->input('formData'));
        $apiEndPoint = $request->session()->get('jiraUrl') . '/rest/api/latest/issue/' . $formData['issyeKey'] . '?fields=summary,status,parent,timeestimate';
        $result = json_decode(UtilityController::getDataCurlGet($apiEndPoint, $request), true);
        if (isset($result['errors'])) {
            $response = [
                'status' => false,
                'error' => $result
            ];
        } else {
            $response = [
                'status' => true,
                'data' => $result
            ];
        }
        return response()->json($response);
    }
}
