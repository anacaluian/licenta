<?php

namespace App\Providers;
require __DIR__ . './../../vendor/autoload.php';

use App\Comment;
use App\Email;
use App\File;
use App\Task;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class GmailServiceProvider
{
 protected $emailModel;
    public function __construct(Email $email)
    {
        $this->emailModel = $email;
    }

    function requestAuthLink(){

            try {
                $client = new \Google_Client();
                $client->setApplicationName('Gmail API PHP Quickstart');
//            $client->setScopes([\Google_Service_Gmail::GMAIL_READONLY,\Google_Service_Gmail::GMAIL_MODIFY, \Google_Service_Gmail::GMAIL_LABELS,\Google_Service_Gmail::GMAIL_DELETE]);
                $client->setScopes('https://mail.google.com/');
                $client->setAuthConfig('./../credentials.json');
                $client->setAccessType('offline');
                $client->setPrompt('select_account consent');
                $authUrl = $client->createAuthUrl();

                return [
                    'authLink' => $authUrl
                ];

              } catch (\Exception $e) {
                        echo 'Caught exception: ',  $e->getMessage(), "\n";
                    }
    }

    function generateToken($code){
        try {
            $client = new \Google_Client();
            $client->setApplicationName('Gmail API PHP Quickstart');
            $client->setScopes('https://mail.google.com/');
            $client->setAuthConfig('./../credentials.json');
            $client->setAccessType('offline');
            $client->setPrompt('select_account consent');

            $accessToken = $client->fetchAccessTokenWithAuthCode($code);

            $client->setAccessToken($accessToken);

            if (array_key_exists('error', $accessToken)) {
                return response()->json(new \Exception(join(', ', $accessToken),500));
            }

            $tokenPath = './../tokens/'. Str::random(16) . '.json';

            if (!file_exists(dirname($tokenPath))) {
                mkdir(dirname($tokenPath), 0700, true);
            }
            file_put_contents($tokenPath, json_encode($client->getAccessToken()));


        } catch (\Exception $e) {
            return response()->json( $e->getMessage(),500);
        }
    }

    function getClient($tokenPath)
    {

        try {
            $client = new \Google_Client();
            $client->setApplicationName('Gmail API PHP Quickstart');
//            $client->setScopes([\Google_Service_Gmail::GMAIL_READONLY,\Google_Service_Gmail::GMAIL_MODIFY, \Google_Service_Gmail::GMAIL_LABELS,\Google_Service_Gmail::GMAIL_DELETE]);
            $client->setScopes('https://mail.google.com/');
            $client->setAuthConfig('./../credentials.json');
            $client->setAccessType('offline');
            $client->setPrompt('select_account consent');

            // Load previously authorized token from a file, if it exists.
            // The file token.json stores the user's access and refresh tokens, and is
            // created automatically when the authorization flow completes for the first
            // time.

            if (file_exists($tokenPath)) {
                $accessToken = json_decode(file_get_contents($tokenPath), true);
                $client->setAccessToken($accessToken);
            }

            // If there is no previous token or it's expired.
            if ($client->isAccessTokenExpired()) {
                // Refresh the token if possible, else fetch a new one.
                if ($client->getRefreshToken()) {
                    $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
                } else {
                    // Request authorization from the user.
                    $authUrl = $client->createAuthUrl();
                    printf("Open the following link in your browser:\n%s\n", $authUrl);
                    print 'Enter verification code: ';
                    $authCode = trim(fgets(STDIN));

                    // Exchange authorization code for an access token.
                    $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
                    $client->setAccessToken($accessToken);

                    // Check to see if there was an error.
                    if (array_key_exists('error', $accessToken)) {
                        throw new \Exception(join(', ', $accessToken));
                    }
                }
                // Save the token to a file.
                if (!file_exists(dirname($tokenPath))) {
                    mkdir(dirname($tokenPath), 0700, true);
                }
                file_put_contents($tokenPath, json_encode($client->getAccessToken()));
            }
            return $client;
        } catch (\Exception $e) {
            return response()->json( $e->getMessage(),500);
        }

    }

    function getUnreadMails(){
        try {
            $tokensFolder = glob('./../tokens/*.{json}', GLOB_BRACE);
            foreach($tokensFolder as $token) {
                $client = $this->getClient($token);
                $service = new \Google_Service_Gmail($client);
                $user = 'me';
                $opt_param = ["q"=>"is:unread"];
                $messages = [];
                $messagesResponse = $service->users_messages->listUsersMessages($user, $opt_param);
                if ($messagesResponse->getMessages()) {
                    $messages = array_merge($messages, $messagesResponse->getMessages());
                }

                foreach ($messages as $message) {
                    $data = new Email();
                    $data->email_id =  $message->getId();
                    $item = $service->users_messages->get($user, $message->getId(),["format"=>"full"]);
                    $date = $item->getInternalDate() / 1000;
                    $data->received_at = date("Y-m-d H:i:s", $date);
                    $headers = $item->getPayload()->getHeaders();
                    $parts = $item->getPayload()->getParts();
                    $data->subject = $this->getHeader($headers, 'Subject');
                    $data->project_email = $service->users->getProfile($user)->emailAddress;
                    $data->from = $this->getHeader($headers, 'From');
                    $data->message = $this->getMail($parts);
                    $mods = new \Google_Service_Gmail_ModifyMessageRequest();
                    $mods->setRemoveLabelIds(['UNREAD']);
                    $mark = $service->users_messages->modify($user,  $message->getId(), $mods);
                    if ($mark){
                        $data->save();
                    }
                }
            }
            return response()->json( 'success',200);

        } catch (\Exception $e) {
            return response()->json( $e->getMessage(),500);
        }

    }


    function getHeader($headers, $name) {
        foreach($headers as $header) {
            if($header['name'] == $name) {
                return $header['value'];
            }
        }
    }

    function getMail($parts){
        $body = $parts[0]['body'];
        $rawData = $body->data;
        $sanitizedData = strtr($rawData,'-_', '+/');
        $message = base64_decode($sanitizedData);
        return $message;

    }

    function index(){
        $emails = $this->emailModel::has('project')->get();

        $collection = collect($emails);
        $grouped = $collection->groupBy('project_email');
        $grouped->toArray();

        return [
            'status' => 'success',
            'data' => $grouped
        ];

    }

    function task($id){
        $email = $this->emailModel->where('id',$id)->with('project')->first();
        if ($email){
            $task =  new Task();
            $task->name = $email->subject;
            $task->details = $email->message;
            $task->task_list = 'backlog';
            $task->project_id = $email->project->id;

            if ($task->save()){
                $this->emailModel->where('id',$id)->update([
                    'task' => $task->id
                ]);
                return response()->json( 'success',200);
            }
        }
        return response()->json( 'error',500);
    }

    function remove($id)
    {
        $email = $this->emailModel->where('id', $id)->first();
        $comments = Comment::where('task_id', $email->task)->get();

        if ($comments) {
            foreach ($comments as $comment) {
                File::where('comment_id', $comment->id)->delete();
                $comment->delete();
            }
        }

        Task::where('id', $email->task)->delete();
        $remove = $this->emailModel->where('id', $id)->update([
            'task' => 0
    ]);

        if ($remove){
            return response()->json( 'success',200);
        }
        return response()->json( 'error',500);
    }

    function deleteEmail($messageId){
        $tokensFolder = glob('./../tokens/*.{json}', GLOB_BRACE);
        foreach($tokensFolder as $token) {
            // Get the API client and construct the service object.
            $client = $this->getClient($token);
            $service = new \Google_Service_Gmail($client);
            $user = 'me';

            $messages = [];
            $opt_param = [];
            $messagesResponse = $service->users_messages->listUsersMessages($user, $opt_param);
            if ($messagesResponse->getMessages()) {
                $messages = array_merge($messages, $messagesResponse->getMessages());
            }

            foreach ($messages as $message) {
                if (  $message->getId() == $messageId){
                    $service->users_messages->delete($user, trim($messageId));
                    $this->emailModel->where('email_id',$messageId)->delete();
                    return response()->json( 'success',200);
                }
            }

        }

    }

}
