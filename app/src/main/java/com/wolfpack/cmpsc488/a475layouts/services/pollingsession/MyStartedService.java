package com.wolfpack.cmpsc488.a475layouts.services.pollingsession;

import android.app.Service;
import android.content.Intent;
import android.os.AsyncTask;
import android.os.Binder;
import android.os.IBinder;
import android.support.v4.content.LocalBroadcastManager;
import android.util.Log;

import com.google.gson.Gson;
import com.wolfpack.cmpsc488.a475layouts.services.WolfpackClient;
import com.wolfpack.cmpsc488.a475layouts.services.data_retrieval.BasicWolfpackResponse;
import com.wolfpack.cmpsc488.a475layouts.services.pollingsession.models.ActiveQuestionInfo;
import com.wolfpack.cmpsc488.a475layouts.services.pollingsession.models.ActiveSessionInfo;
import com.wolfpack.cmpsc488.a475layouts.services.pollingsession.models.PollingResults;
import com.wolfpack.cmpsc488.a475layouts.services.pollingsession.models.QuestionInformation;
import com.wolfpack.cmpsc488.a475layouts.services.pollingsession.models.ValidateQuestionInfo;

import java.util.ArrayList;
import java.util.Random;

import javax.security.auth.login.LoginException;

import okhttp3.Response;
import okhttp3.ResponseBody;
import retrofit2.Call;
import retrofit2.http.Field;

public class MyStartedService extends Service {

    private final String TAG = "MyStartedService";
    public static final String MY_SERVICE_ACTIVE_SESSION = "MY_SERVICE_ACTIVE_SESSION";
    public static final String MY_SERVICE_ACTIVE_QUESTION = "MY_SERVICE_ACTIVE_QUESTION";
    public static final String MY_SERVICE_QUESTION_INFO = "MY_SERVICE_QUESTION_INFO";
    public static final String MY_SERVICE_SUBMIT_ANSWER = "MY_SERVICE_SUBMIT_ANSWER";
    public static final String MY_SERVICE_QUESTION_SET_ID = "MY_SERVICE_QUESTION_SET_ID";
    public static final String MY_SERVICE_QUESTION_SESSION_ID = "MY_SERVICE_QUESTION_SESSION_ID";
    public static final String MY_SERVICE_QUESTION_ID= "MY_SERVICE_QUESTION_ID";
    public static final String MY_SERVICE_QUESTION_HISTORY_ID = "MY_SERVICE_QUESTION_HISTORY_ID";
    public static final String MY_SERVICE_QUESTION_INFO_JSON = "MY_SERVICE_QUESTION_INFO_JSON";
    public static final String MY_SERVICE_ANSWER_STATUS = "MY_SERVICE_ANSWER_STATUS";
    public static final String MY_SERVICE_ANSWER_MESSAGE = "MY_SERVICE_ANSWER_MESSAGE";
    public static final String MY_SERVICE_QUESTION_SET_NAME = "MY_SERVICE_QUESTION_SET_NAME";
    public static final String MY_SERVICE_VALIDATE_ANSWER = "MY_SERVICE_VALIDATE_ANSWER";

    public synchronized boolean isRunning() {
        return isRunning;
    }

    public synchronized void setRunning(boolean running) {
        isRunning = running;
    }

    //service variables
    private boolean isRunning = false;

    private final Binder mBinder = new MyServiceBinder();

    private ServiceJobAsyncTask ourTask;

    public MyStartedService() {
        Log.i(TAG, "MyStartedService: ");
    }

    @Override
    public IBinder onBind(Intent intent) {
        Log.i(TAG, "onBind: ");
        return mBinder;
    }

    public class MyServiceBinder extends Binder {

        public MyStartedService getService(){
            Log.i(TAG, "getService: ");
            return MyStartedService.this;
        }
    }

    @Override
    public void onCreate(){
        Log.i(TAG, "MY_SERVICE CREATED");
        setRunning(false);
        super.onCreate();
    }

    @Override
    public void onDestroy(){
        Log.i(TAG, "MY_SERVICE DESTROYED");
        setRunning(false);
        super.onDestroy();
    }

    @Override
    public boolean onUnbind(Intent intent){
        Log.i(TAG, "onUnbind: MyStartedService");
        return super.onUnbind(intent);
    }

    // methods to use in order to invoke our background task

     public synchronized void searchActiveSession(String inputClassId, String firstTime){
         Log.i(TAG, "searchActiveSession: " + "searching for active session for classId: " + inputClassId);
         if(!isRunning()){
             ourTask = new ServiceJobAsyncTask();
             ourTask.execute("searchActiveSession", inputClassId, firstTime);
         }
     }

     public synchronized void searchActiveQuestion(String inputQuestionSetId, String firstTime){
         if(!isRunning()){
             ourTask = new ServiceJobAsyncTask();
             ourTask.execute("searchActiveQuestion", inputQuestionSetId, firstTime);
         }
     }

     public synchronized void searchLiveQuestionInfo(String inputQuestionId, String firstTime){
         if(!isRunning()){
             ourTask = new ServiceJobAsyncTask();
             ourTask.execute("searchLiveQuestionInfo", inputQuestionId, firstTime);
         }
     }

    //@Field("inputQuestionSetId") String  inputQuestionSetId,
    //@Field("inputQuestionId") String  inputQuestionId,
    //@Field("inputQuestionSessionId") String inputQuestionSessionId,
    //@Field("inputQuestionHistoryId") String inputQuestionHistoryId,

    public synchronized void validateSameQuestion(String inputQuestionSetId,
                                                  String inputQuestionId,
                                                  String inputQuestionSessionId,
                                                  String inputQuestionHistoryId,
                                                  String firstTime){
        if(!isRunning()){
            ourTask = new ServiceJobAsyncTask();
            ourTask.execute("validateSameQuestion",
                    inputQuestionSetId,
                    inputQuestionId,
                    inputQuestionSessionId,
                    inputQuestionHistoryId,
                    firstTime);
        }
    }

     public synchronized void submitAnswer(String inputStudentId,
                              String inputSessionId,
                              String inputQuestionHistoryId,
                              String inputAnswerType,
                              String inputAnswer,
                              String isFinal){
         if(!isRunning() && isFinal.equals("false")){
             ourTask = new ServiceJobAsyncTask();
             ourTask.execute("submitAnswer",
                     inputStudentId,
                     inputSessionId,
                     inputQuestionHistoryId,
                     inputAnswerType,
                     inputAnswer,
                     isFinal);
         }
         else{
             if(isRunning()){
                 if(ourTask != null){
                     ourTask.cancel(true);
                 }
                 ourTask = null;
             }
             ourTask = new ServiceJobAsyncTask();
             ourTask.execute("submitAnswer",
                         inputStudentId,
                         inputSessionId,
                         inputQuestionHistoryId,
                         inputAnswerType,
                         inputAnswer,
                         isFinal);

         }
     }



    class ServiceJobAsyncTask extends AsyncTask<String, Void, Object> {

        private final String TAG = "ServiceJobAT";
        private int id = 0;

        @Override
        protected Object doInBackground(String... params) {
            setRunning(true);
            Object response = null;

            try {
                //TODO: set this interval in preferences
                long sleepTimeRequestedByUser = 0L;
                //any request will wait for at least 5 seconds before retrying

                //determine which method wants to run on the background thread
                //set that 'id' to use in onPostExecute
                //TODO: implement interval logic appropriately



                WolfpackClient client = WolfpackClient.retrofit.create(WolfpackClient.class);
                WolfpackClient debugClient = WolfpackClient.debugRetrofit.create(WolfpackClient.class);

                switch (params[0]){
                    case "searchActiveSession":{
                        if(!params[2].equals("true")){
                            Thread.sleep(2000);

                        }

                        id = 1;

                        Call<PollingResults<ActiveSessionInfo>> call = client.searchActiveSession(
                                params[1],
                                "searchActiveSession"
                        );

                        //PollingResults<ActiveSessionInfo>
                        response = call.execute().body();
                    }

                        break;
                    case "searchActiveQuestion":{
                        if(!params[2].equals("true")){
                            Thread.sleep(2000);

                        }
                        id = 2;
                        Call<PollingResults<ActiveQuestionInfo>> call = client.searchActiveQuestion(
                                params[1],
                                "searchActiveQuestion"
                        );

                        //PollingResults<ActiveQuestionInfo>
                        response = call.execute().body();
                    }
                        break;
                    case "searchLiveQuestionInfo": {
                        if(!params[2].equals("true")){
                            Thread.sleep(2000);

                        }
                        id = 3;
                        Call<PollingResults<QuestionInformation>> call = client.searchLiveQuestionInfo(
                                params[1],
                                "searchLiveQuestionInfo"
                        );

                        //PollingResults<QuestionInformation>
                        response = call.execute().body();
                    } break;

                    case "submitAnswer": {
                        id = 4;
                        if(!params[6].equals("true")){
                            Log.i(TAG, "doInBackground: sleeping in submitAnswer");
                            Thread.sleep(3000);
                        }
                        Log.i(TAG, "doInBackground: about to submit an answer");

                        Call<BasicWolfpackResponse> call = client.submitAnswer(
                                params[1],
                                params[2],
                                params[3],
                                params[4],
                                params[5],
                                "submitAnswer"
                        );

                        //BasicWolfpackResponse
                        response = call.execute().body();

                    }
                        break;
                    case "validateSameQuestion": {
                        if(!params[5].equals("true")){
                            Log.i(TAG, "doInBackground: sleeping in validateSameQuestion");
                            Thread.sleep(2000);
                        }

                        id = 5;
                        //@Field("inputQuestionSetId") String  inputQuestionSetId,
                        //@Field("inputQuestionId") String  inputQuestionId,
                        //@Field("inputQuestionSessionId") String inputQuestionSessionId,
                        //@Field("inputQuestionHistoryId") String inputQuestionHistoryId,

                        Call<PollingResults<ValidateQuestionInfo>> call = client.
                                validateSameQuestion(params[1],
                                        params[2],
                                        params[3],
                                        params[4],
                                        "validateSameQuestion");

                        response = call.execute().body();

                    } break;
                }

            } catch (InterruptedException e){
                setRunning(false);
                Log.e(TAG, e.getMessage());
                return null;
            }
            catch(java.net.ConnectException e){
                setRunning(false);
                Log.e(TAG, e.getMessage());
                return null;
            }
            catch (IllegalStateException e){
                setRunning(false);
                Log.e(TAG, e.getMessage());
                return null;

            } catch (Exception e){
                setRunning(false);
                Log.e(TAG, e.getClass().toString() + e.getMessage());
                return null;
            }

            return response;
        }

        @Override
        protected void onPostExecute(final Object result){
            setRunning(false);

            switch (id){
                case 1:{
                    Intent intent = new Intent(MY_SERVICE_ACTIVE_SESSION);

                    try{

                        if(result != null)
                        {
                            @SuppressWarnings("unchecked")
                            String questionSetId = ((PollingResults<ActiveSessionInfo>) result).getResults()
                                    .get(0).getQuestionSetId();

                            @SuppressWarnings("unchecked")
                            String questionSessionId = ((PollingResults<ActiveSessionInfo>) result).getResults()
                                .get(0).getQuestionSessionId();

                            @SuppressWarnings("unchecked")
                            String questionSetName = ((PollingResults<ActiveSessionInfo>) result).getResults()
                                    .get(0).getQuestionSetName();

                            intent.putExtra(MY_SERVICE_QUESTION_SET_ID, questionSetId);
                            intent.putExtra(MY_SERVICE_QUESTION_SESSION_ID, questionSessionId);
                            intent.putExtra(MY_SERVICE_QUESTION_SET_NAME, questionSetName);

                        }
                        else{
                            Log.e(TAG, "onPostExecute: " + "result was null" );
                        }

                    } catch(NullPointerException e){
                        Log.e(TAG, "onPostExecute: " + e.getMessage() );

                    } catch (ClassCastException e){
                        Log.e(TAG, "onPostExecute: " + e.getMessage() );

                    } catch (Exception e){
                        Log.e(TAG, "onPostExecute: " + e.getMessage() );

                    }
                    finally {
                        //send our response back
                        LocalBroadcastManager.getInstance(
                                getApplicationContext())
                                .sendBroadcast(intent);

                    }

                } break;

                case 2:{
                    Intent intent = new Intent(MY_SERVICE_ACTIVE_QUESTION);
                    try{

                        if(result != null)
                        {
                            @SuppressWarnings("unchecked")
                            String questionId = ((PollingResults<ActiveQuestionInfo>) result).getResults()
                                    .get(0).getQuestionId();

                            @SuppressWarnings("unchecked")
                            String getQuestionHistoryId = ((PollingResults<ActiveQuestionInfo>) result).getResults()
                                    .get(0).getQuestionHistoryId();
                            intent.putExtra(MY_SERVICE_QUESTION_ID, questionId);
                            intent.putExtra(MY_SERVICE_QUESTION_HISTORY_ID, getQuestionHistoryId);

                        }
                        else{
                            Log.e(TAG, "onPostExecute: " + "result was null" );
                        }

                    } catch(NullPointerException e){
                        Log.e(TAG, "onPostExecute: " + e.getMessage() );

                    } catch (ClassCastException e){
                        Log.e(TAG, "onPostExecute: " + e.getMessage() );

                    } catch (Exception e){
                        Log.e(TAG, "onPostExecute: " + e.getMessage() );

                    }
                    finally {
                        //send our response back
                        LocalBroadcastManager.getInstance(
                                getApplicationContext())
                                .sendBroadcast(intent);

                    }

                } break;

                case 3:{
                    Intent intent = new Intent(MY_SERVICE_QUESTION_INFO);
                    try{

                        if(result != null)
                        {
                            String questionJSON;

                            @SuppressWarnings("unchecked")
                            QuestionInformation questionInformation =
                                    ((PollingResults<QuestionInformation>) result).getResults()
                                    .get(0);

                            questionJSON = new Gson().toJson(questionInformation,
                                    QuestionInformation.class);

                            //Log.i(TAG, "QUESTION INFO JSON = " + questionJSON);
                            //perform conversation later on
                            intent.putExtra(MY_SERVICE_QUESTION_INFO_JSON, questionJSON);

                        }
                        else{
                            Log.e(TAG, "onPostExecute: " + "result was null" );
                        }

                    } catch(NullPointerException e){
                        Log.e(TAG, "onPostExecute: " + e.getMessage() );

                    } catch (ClassCastException e){
                        Log.e(TAG, "onPostExecute: " + e.getMessage() );

                    } catch (Exception e){
                        Log.e(TAG, "onPostExecute: " + e.getMessage() );

                    } finally {
                        //send our response back
                        LocalBroadcastManager.getInstance(
                                getApplicationContext())
                                .sendBroadcast(intent);

                    }

                }



                    break;
                case 4:{
                    Intent intent = new Intent(MY_SERVICE_SUBMIT_ANSWER);
                    try{

                        if(result != null)
                        {
                            @SuppressWarnings("unchecked")
                            String message = ((BasicWolfpackResponse) result).getMessage();
                            int sukmoonChang = ((BasicWolfpackResponse) result).getStatus();

                            Log.i(TAG, "SUBMISSION OF ANSWER = " + message);
                            //perform conversation later on
                            intent.putExtra(MY_SERVICE_ANSWER_MESSAGE, message);
                            intent.putExtra(MY_SERVICE_ANSWER_STATUS, sukmoonChang);
                        }
                        else{
                            Log.e(TAG, "onPostExecute: " + "result was null" );
                        }

                    } catch(NullPointerException e){
                        Log.e(TAG, "onPostExecute: " + e.getMessage() );

                    } catch (ClassCastException e){
                        Log.e(TAG, "onPostExecute: " + e.getMessage() );

                    } catch (Exception e){
                        Log.e(TAG, "onPostExecute: " + e.getMessage() );

                    }finally {
                        //send our response back
                        LocalBroadcastManager.getInstance(
                                getApplicationContext())
                                .sendBroadcast(intent);
                    }

                } break;

                case 5:{
                    Intent intent = new Intent(MY_SERVICE_VALIDATE_ANSWER);
                    try{

                        if(result != null)
                        {
                            Log.i(TAG, "onPostExecute: result was not null: ");



                            @SuppressWarnings("unchecked")
                            String getQuestionId = ((PollingResults<ValidateQuestionInfo>) result).getResults()
                                    .get(0).getQuestionId();

                            @SuppressWarnings("unchecked")
                            String getQuestionHistoryId = ((PollingResults<ValidateQuestionInfo>) result).getResults()
                                    .get(0).getQuestionHistoryId();

                            @SuppressWarnings("unchecked")
                            String getQuesitonSessionId = ((PollingResults<ValidateQuestionInfo>) result).getResults()
                                    .get(0).getQuestionSessionId();

                            @SuppressWarnings("unchecked")
                            String getQuestionSetId = ((PollingResults<ValidateQuestionInfo>) result).getResults()
                                    .get(0).getQuestionSetId();


                            intent.putExtra(MY_SERVICE_QUESTION_ID, getQuestionId);
                            intent.putExtra(MY_SERVICE_QUESTION_HISTORY_ID, getQuestionHistoryId);
                            intent.putExtra(MY_SERVICE_QUESTION_SESSION_ID, getQuesitonSessionId);
                            intent.putExtra(MY_SERVICE_QUESTION_SET_ID,getQuestionSetId);
                        }
                        else{
                            Log.e(TAG, "onPostExecute: " + "result was null" );
                        }

                    } catch(NullPointerException e){
                        Log.e(TAG, "onPostExecute: " + e.getMessage() );

                    } catch (ClassCastException e){
                        Log.e(TAG, "onPostExecute: " + e.getMessage() );

                    } catch (Exception e){
                        Log.e(TAG, "onPostExecute: " + e.getMessage() );

                    }finally {
                        //send our response back
                        LocalBroadcastManager.getInstance(
                                getApplicationContext())
                                .sendBroadcast(intent);
                    }

                } break;

            }

        }
    }
}
