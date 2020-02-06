<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller {

    public function login(Request $request) {
        $response = [];
        return view('login', ['response' => $response]);
    }

    public function home(Request $request) {
        $authToken = $request->session()->get('authToken');
        if ($authToken == NULL) {
            return redirect('login')->with('danger', 'Please login with your JIRA credentials to continue.');
        }
        // get inprogress issue
        $jql = $request->session()->get('jql');
        $apiEndPoint = $request->session()->get('jiraUrl') . '/rest/api/latest/search';
        $postData = [
            'jql' => $jql,
            'fields' => ["subtasks", "summary", "timeestimate", "status", "fixVersions", "parent"],
        ];
        $result = json_decode(UtilityController::getDataCurlPost($apiEndPoint, $postData, $request), true);
        //dd($result);
        $response = [
            'jiraIssues' => $result,
            'postData' => $postData,
            'authToken' => $request->session()->get('authToken')
        ];
        //dd($response);
        return view('home', ['response' => $response]);
    }

}
